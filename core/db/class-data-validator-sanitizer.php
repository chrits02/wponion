<?php
/**
 *
 * Initial version created 14-05-2018 / 03:10 PM
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @since 1.0
 * @package
 * @link
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */

namespace WPOnion\DB;

use WPOnion\Bridge;
use WPOnion\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\WPOnion\DB\Data_Validator_Sanitizer' ) ) {
	/**
	 * Class Data_Validator_Sanitizer
	 *
	 * @package WPOnion\DB
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class Data_Validator_Sanitizer extends Bridge {
		/**
		 * @var
		 * @access
		 */
		protected $unique;

		/**
		 * @var \WPO\Builder
		 * @access
		 */
		protected $fields;

		/**
		 * @var
		 * @access
		 */
		protected $retain_values;

		/**
		 * @var
		 * @access
		 */
		protected $db_values;

		/**
		 * @var
		 * @access
		 */
		protected $posted_values;

		/**
		 * @var
		 * @access
		 */
		protected $return_values;

		/**
		 * Stores All Field Errors.
		 *
		 * @var array
		 */
		protected $errors = array();

		/**
		 * Data_Validator_Sanitizer constructor.
		 *
		 * @param $args
		 */
		public function __construct( $args ) {
			$args                = $this->parse_args( $args, array(
				'module'        => false,
				'unique'        => false,
				'fields'        => false,
				'db_values'     => false,
				'retain_value'  => false,
				'posted_values' => false,
			) );
			$this->module        = $args['module'];
			$this->unique        = $args['unique'];
			$this->fields        = $args['fields'];
			$this->retain_values = $args['retain_value'];
			$this->posted_values = $args['posted_values'];
			$this->db_values     = $args['db_values'];

			if ( false === $this->posted_values ) {
				$this->posted_values = ( isset( $_POST[ $this->unique ] ) ) ? $_POST[ $this->unique ] : array();
			}
		}

		/**
		 * @param \WPO\Container|\WPO\Builder $data
		 */
		protected function field_loop( $data ) {
			if ( $data->has_fields() ) {
				foreach ( $data->fields() as $field ) {
					if ( false === wponion_valid_user_input_field( $field ) ) {
						continue;
					}

					if ( ! wponion_valid_field( $field ) ) {
						continue;
					}

					$field['error_id'] = $this->unique . '/' . wponion_field_id( $field );
					$user_val          = $this->user_options( $field );
					$db_val            = $this->db_options( $field );
					$this->save_value( $this->handle_field( $field, $user_val, $db_val ), $field );

					if ( ! in_array( $field['type'], array( 'group' ), true ) ) {
						if ( isset( $field['fields'] ) ) {
							$this->nested_field_loop( $field );
						}
					}
				}
			}
		}

		/**
		 * This function is used for nested field loops.
		 *
		 * @param $field
		 */
		protected function nested_field_loop( $field ) {
			$parent_field = $field;
			if ( wponion_is_array( $field['fields'] ) ) {
				foreach ( $field['fields'] as $_field ) {
					if ( false === wponion_valid_user_input_field( $field ) ) {
						continue;
					}

					if ( ! wponion_valid_field( $field ) ) {
						continue;
					}

					if ( wponion_is_unarrayed( $field ) ) {
						$parent_field = $_field;
					}
					$_field['error_id']        = $field['error_id'] . '/' . $_field['id'];
					$db_val                    = $this->db_options( $parent_field );
					$user_val                  = $this->user_options( $parent_field );
					$_user_val                 = ( isset( $user_val[ $_field['id'] ] ) ) ? $user_val[ $_field['id'] ] : $user_val;
					$_db_val                   = ( isset( $db_val[ $_field['id'] ] ) ) ? $db_val[ $_field['id'] ] : $db_val;
					$value                     = $this->handle_field( $_field, $_user_val, $_db_val );
					$user_val[ $_field['id'] ] = $value;
					$this->save_value( $user_val, $parent_field );
					if ( ! in_array( $_field['type'], array( 'group' ), true ) ) {
						if ( isset( $_field['fields'] ) ) {
							$this->nested_field_loop( $_field );
						}
					}
				}
			}
		}

		/**
		 * Runs It.
		 */
		public function run() {
			/**
			 * @var \WPO\Container $container
			 * @var \WPO\Container $sub_container
			 */
			if ( $this->fields->has_fields() ) {
				/* Below Foreach Runs Just the main level fields. */
				$this->field_loop( $this->fields );
			} elseif ( false === $this->fields->has_fields() && $this->fields->has_containers() ) {
				foreach ( $this->fields->get() as $container ) {
					if ( $container->has_containers() ) {
						/* This if condition handles all the subcontainers inside a container. */
						foreach ( $container->containers() as $sub_container ) {
							if ( $sub_container->has_fields() && ! $sub_container->has_callback() && ! $sub_container->has_href() ) {
								$this->field_loop( $sub_container );
							}
						}
					} elseif ( $container->has_fields() && ! $container->has_callback() && ! $container->has_href() ) {
						/* This if condition handles all the fields that are directly inside a container */
						$this->field_loop( $container );
					}
				}
			}
		}

		/**
		 * Handles Single Field.
		 *
		 * @param array $field
		 * @param bool  $value
		 * @param bool  $database
		 *
		 * @return array|bool|mixed
		 */
		protected function handle_field( $field = array(), $value = false, $database = false ) {
			$value = $this->sanitize( $field, $value );
			$value = $this->validate( $field, $value, $database );
			return $value;
		}

		/**
		 * Saves A Value.
		 *
		 * @param $value
		 * @param $field
		 *
		 * @return bool|mixed
		 */
		protected function save_value( $value, $field ) {
			if ( wponion_is_unarrayed( $field ) ) {
				$this->return_values = array_merge( $this->return_values, $value );
			} else {
				$this->return_values[ $field['id'] ] = $value;
			}
			return true;
		}

		/**
		 * @param $field
		 * @param $value
		 *
		 * @return bool|false|mixed|string|void
		 */
		protected function sanitize( $field, $value ) {
			$functions = false;
			if ( isset( $field['sanitize'] ) && true === $field['sanitize'] || false === isset( $field['sanitize'] ) ) {
				$functions = 'wponion_field_' . $field['type'] . '_sanitize';
			} elseif ( isset( $field['sanitize'] ) && true !== $field['sanitize'] ) {
				$functions = $field['sanitize'];
			}
			if ( wponion_is_array( $functions ) ) {
				foreach ( $functions as $function ) {
					if ( wponion_is_callable( $function ) ) {
						$value = wponion_callback( $function, array( $value, $field, $this->module() ) );
					} elseif ( is_string( $value ) && has_filter( $functions ) ) {
						$value = apply_filters( $functions, $value, $field, $this->module() );
					}
				}
			} elseif ( wponion_is_callable( $functions ) ) {
				$value = wponion_callback( $functions, array( $value, $field, $this->module() ) );
			} elseif ( has_filter( $functions ) ) {
				$value = apply_filters( $functions, $value, $field, $this->module() );
			}
			return $value;
		}

		/**
		 * Handles Field Validate Callbacks.
		 *
		 * @param $field
		 * @param $value
		 * @param $db_value
		 *
		 * @return mixed
		 */
		protected function validate( $field, $value, $db_value ) {
			$functions = false;
			$errors    = array();
			if ( isset( $field['validate'] ) && ( ! empty( $field['validate'] ) && true !== $field['validate'] ) ) {
				$functions = $field['validate'];
			}

			if ( false === $functions ) {
				return $value;
			}

			if ( wponion_is_array( $functions ) ) {
				foreach ( $functions as $key => $function ) {
					$custom_message = ( ! is_numeric( $key ) ) ? $function : false;
					$function       = ( ! is_numeric( $key ) ) ? $key : $function;
					if ( wponion_is_callable( $function ) ) {
						$_is_valid = $this->_validate( $function, $field, $value );
						if ( ! empty( $_is_valid ) && true !== $_is_valid ) {
							$errors[] = ( ! empty( $custom_message ) ) ? $custom_message : $_is_valid;
						}
					}
				}
			} elseif ( wponion_is_callable( $functions ) ) {
				$_is_valid = $this->_validate( $functions, $field, $value );
				if ( ! empty( $_is_valid ) && true !== $_is_valid ) {
					$errors[] = $_is_valid;
				}
			}
			if ( ! empty( $errors ) ) {
				$this->error( $errors, 'error', $field['error_id'] );
				return $db_value;
			}
			return $value;
		}

		/**
		 * Triggers a Validation Function.
		 *
		 * @param $function
		 * @param $field
		 * @param $value
		 *
		 * @return mixed
		 */
		protected function _validate( $function, $field, $value ) {
			return wponion_callback( $function, array( $value, $field, $this->module() ) );
		}

		/**
		 * Returns Final Value.
		 *
		 * @return array
		 */
		public function get_values() {
			return ( true === $this->retain_values ) ? $this->parse_args( $this->return_values, $this->db_values ) : $this->return_values;
		}

		/**
		 * Returns Errors Array.
		 *
		 * @return array
		 */
		public function get_errors() {
			return $this->errors;
		}

		/**
		 * Stores Field's Error Information.
		 *
		 * @param        $message
		 * @param string $type
		 * @param string $ids
		 *
		 * @return $this
		 */
		public function error( $message, $type = 'error', $ids = 'global' ) {
			$this->errors = Helper::array_key_set( $ids, array(
				'setting' => 'wponion-errors',
				'message' => $message,
				'type'    => $type,
			), $this->errors, '/' );
			return $this;
		}

		/**
		 * This returns value from db array.
		 *
		 * @param string $field
		 * @param bool   $value_arr
		 * @param bool   $default
		 *
		 * @return bool|mixed
		 */
		protected function db_options( $field = '', $value_arr = false, $default = false ) {
			return $this->_value_options( $field, $value_arr, $default, 'db_values' );
		}

		/**
		 * This returns value from User array.
		 *
		 * @param string $field
		 * @param bool   $value_arr
		 * @param bool   $default
		 *
		 * @return bool|mixed
		 */
		protected function user_options( $field = '', $value_arr = false, $default = false ) {
			return $this->_value_options( $field, $value_arr, $default, 'posted_values' );
		}

		/**
		 * @param $field
		 * @param $value_arr
		 * @param $default
		 * @param $variable
		 *
		 * @return bool|mixed
		 */
		protected function _value_options( $field, $value_arr, $default, $variable ) {
			$value_arr = ( false === $value_arr ) ? $this->{$variable} : $value_arr;
			$_value    = wponion_get_field_value( $field, $value_arr );
			return ( $_value ) ? $_value : $default;
		}
	}
}