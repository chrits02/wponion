<?php
/**
 *
 * Project : wponion
 * Date : 03-11-2018
 * Time : 09:10 AM
 * File : admin-page.php
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
 * @version 1.0
 * @package wponion
 * @copyright 2018 Varun Sridharan
 * @license GPLV3 Or Greater (https://www.gnu.org/licenses/gpl-3.0.txt)
 */


namespace WPOnion\Modules;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\WPOnion\Modules\Admin_Page' ) ) {
	/**
	 * Class metabox
	 *
	 * @package WPOnion\Modules
	 * @author Varun Sridharan <varunsridharan23@gmail.com>
	 * @since 1.0
	 */
	class Admin_Page extends \WPOnion\Bridge {
		/**
		 * option
		 *
		 * @var array
		 */
		protected $option = array();
		protected $module = 'admin_page';

		protected $page_slug = null;

		/**
		 * Admin_Page constructor.
		 *
		 * @param array $options
		 */
		public function __construct( $options = array() ) {
			if ( false === $this->is_multiple( $options ) ) {
				foreach ( $options as $option ) {
					new \WPOnion\Modules\Admin_Page( $option );
				}
			} else {
				$this->settings = $this->parse_args( $options, $this->defaults() );
				$this->init();
			}
		}

		/**
		 * Checks if given menu args is multiple or not.
		 *
		 * @param $args
		 *
		 * @return bool
		 */
		protected function is_multiple( $args ) {
			foreach ( $this->defaults() as $key => $val ) {
				if ( isset( $args[ $key ] ) ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * @return array
		 */
		protected function defaults() {
			return array(
				'submenu'       => false,
				'menu_title'    => false,
				'page_title'    => false,
				'capability'    => 'manage_options',
				'menu_slug'     => false,
				'icon'          => false,
				'position'      => null,
				'on_load'       => false,
				'assets'        => false,
				'hook_priority' => 10,
				'callback'      => false,
				'render'        => false,
			);
		}

		/**
		 * @param null $submenu
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function submenu( $submenu = null ) {
			if ( ! is_null( $submenu ) ) {
				return $this->set_option( 'submenu', $submenu );
			}

			if ( is_object( $this->option( 'submenu', false ) ) ) {
				if ( $this->option( 'submenu', false ) instanceof Admin_Page ) {
					return $this->option( 'submenu', false )
						->menu_slug();
				}
			}

			return $this->option( 'submenu' );
		}

		/**
		 * @param null $menu_title
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function menu_title( $menu_title = null ) {
			return ( ! is_null( $menu_title ) ) ? $this->set_option( 'menu_title', $menu_title ) : $this->option( 'menu_title', false );
		}

		/**
		 * @param null $page_title
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function page_title( $page_title = null ) {
			return ( ! is_null( $page_title ) ) ? $this->set_option( 'page_title', $page_title ) : $this->option( 'page_title', false );
		}

		/**
		 * @param null $capability
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function capability( $capability = null ) {
			return ( ! is_null( $capability ) ) ? $this->set_option( 'capability', $capability ) : $this->option( 'capability', false );
		}

		/**
		 * @param null $menu_slug
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function menu_slug( $menu_slug = null ) {
			return ( ! is_null( $menu_slug ) ) ? $this->set_option( 'menu_slug', $menu_slug ) : $this->option( 'menu_slug', false );
		}

		/**
		 * @param null $icon
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function icon( $icon = null ) {
			return ( ! is_null( $icon ) ) ? $this->set_option( 'icon', $icon ) : $this->option( 'icon', false );
		}

		/**
		 * @param null $position
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function position( $position = null ) {
			return ( ! is_null( $position ) ) ? $this->set_option( 'position', $position ) : $this->option( 'position', null );
		}

		/**
		 * @param null $hook_priority
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function hook_priority( $hook_priority = null ) {
			return ( ! is_null( $hook_priority ) ) ? $this->set_option( 'hook_priority', $hook_priority ) : $this->option( 'hook_priority', false );
		}

		/**
		 * @param null $on_load
		 *
		 * @return bool|mixed
		 */
		public function on_load( $on_load = null ) {
			if ( ! is_null( $on_load ) ) {
				if ( ! is_array( $this->option( 'on_load' ) ) && false !== $this->option( 'on_load' ) ) {
					$this->set_option( 'on_load', array( $this->option( 'on_load' ), $on_load ) );
				} elseif ( is_array( $this->option( 'on_load' ) ) ) {
					$_on_load   = $this->option( 'on_load' );
					$_on_load[] = $on_load;
					$this->set_option( 'on_load', $_on_load );
				} else {
					$this->set_option( 'on_load', array( $on_load ) );
				}
			}
			return $this->option( 'on_load' );
		}

		/**
		 * @param null $assets
		 *
		 * @return bool|mixed
		 */
		public function assets( $assets = null ) {
			if ( ! is_null( $assets ) ) {
				if ( ! is_array( $this->option( 'assets' ) ) && false !== $this->option( 'assets' ) ) {
					$this->set_option( 'assets', array( $this->option( 'assets' ), $assets ) );
				} elseif ( is_array( $this->option( 'assets' ) ) ) {
					$_assets   = $this->option( 'assets' );
					$_assets[] = $assets;
					$this->set_option( 'on_load', $_assets );
				} else {
					$this->set_option( 'assets', array( $assets ) );
				}
			}
			return $this->option( 'assets' );
		}

		/**
		 * Inits Works.
		 */
		public function init() {
			if ( ! empty( $this->option( 'menu_title' ) ) ) {
				if ( ! did_action( 'admin_menu' ) ) {
					$this->add_action( 'admin_menu', 'add_menu', $this->hook_priority() );
				} else {
					$this->add_menu();
				}
			}
		}

		/**
		 * Checks and returns a valid title.
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function get_menu_title() {
			return $this->menu_title();
		}

		/**
		 * Checks and returns a valid title
		 *
		 * @return bool|mixed|\WPOnion\Modules\Admin_Page
		 */
		public function get_page_title() {
			return ( empty( $this->page_title() ) ) ? $this->menu_title() : $this->page_title();
		}

		/**
		 * Returns A Valid Slug.
		 *
		 * @return bool|mixed|string|\WPOnion\Modules\Admin_Page
		 */
		public function get_slug() {
			if ( empty( $this->menu_slug() ) ) {
				$title = sanitize_title( $this->get_page_title() );
				if ( empty( $title ) ) {
					return 'wponion-' . md5( json_encode( $this->settings ) );
				}
				return $title;
			}
			return $this->menu_slug();
		}

		public function get_page_slug() {
			return $this->page_slug;
		}

		/**
		 * Registers Menu With WP.
		 */
		public function add_menu() {
			$_slug = $this->get_slug();
			$this->menu_slug( $_slug );
			$menu_title = $this->get_menu_title();
			$page_title = $this->get_page_title();

			if ( false === $this->submenu() || is_array( $this->submenu() ) ) {
				$this->page_slug = add_menu_page( $page_title, $menu_title, $this->capability(), $_slug, array(
					&$this,
					'render',
				), $this->icon(), $this->position() );
			} else {
				switch ( $this->submenu() ) {
					case 'management':
					case 'dashboard':
					case 'options':
					case 'plugins':
					case 'theme':
						if ( function_exists( 'add_' . $this->submenu() . '_page' ) ) {
							$this->page_slug = wponion_callback( 'add_' . $this->submenu() . '_page', array(
								$page_title,
								$menu_title,
								$this->capability(),
								$_slug,
								array(
									&$this,
									'render',
								),
							) );
						}
						break;
					default:
						$this->page_slug = add_submenu_page( $this->submenu(), $page_title, $menu_title, $this->capability(), $_slug, array(
							&$this,
							'render',
						) );
						break;
				}
			}
			$this->add_action( 'load-' . $this->page_slug, 'on_page_load', 1 );

			if ( is_array( $this->submenu() ) ) {
				$subemnus = array();
				if ( true === $this->is_multiple( $this->submenu() ) ) {
					$subemnus[] = $this->submenu();
				} else {
					$subemnus = $this->submenu();
				}

				foreach ( $subemnus as $sub_menu ) {
					if ( ! isset( $sub_menu['submenu'] ) ) {
						$sub_menu['submenu'] = $this;
					}
					new self( $sub_menu );
				}
			}

		}

		/**
		 * Renders.
		 */
		public function render() {
			echo '<div class="wrap">';
			echo '<h1>' . get_admin_page_title() . '</h1>';
			if ( false !== $this->option( 'callback' ) ) {
				echo wponion_callback( $this->option( 'callback' ), $this );
			} elseif ( false !== $this->option( 'render' ) ) {
				echo wponion_callback( $this->option( 'render' ), $this );
			}
			echo '</div>';
		}

		/**
		 * Triggers On Page Load.
		 */
		public function on_page_load() {
			$this->add_action( 'admin_enqueue_scripts', 'handle_assets' );
			if ( is_array( $this->on_load() ) ) {
				$is_called = wponion_callback( $this->on_load(), $this );
				if ( false === $is_called ) {
					foreach ( $this->on_load() as $call ) {
						echo wponion_callback( $call, $this );
					}
				} else {
					echo $is_called;
				}
			} elseif ( false !== $this->on_load() ) {
				wponion_callback( $this->on_load(), $this );
			}
		}

		/**
		 * Handles Page Assets.
		 */
		public function handle_assets() {
			if ( is_array( $this->assets() ) ) {
				foreach ( $this->assets() as $call ) {
					if ( is_string( $call ) ) {
						if ( wp_script_is( $call, 'registered' ) || wp_style_is( $call, 'registered' ) ) {
							wp_enqueue_script( $call );
							wp_enqueue_style( $call );
						} else {
							wponion_callback( $call, $this );
						}
					} else {
						echo wponion_callback( $call, $this );
					}
				}
			} elseif ( false !== $this->assets() ) {
				$status = wponion_callback( $this->assets(), $this );
				if ( false === $status ) {
					if ( wp_script_is( $this->assets(), 'registered' ) || wp_style_is( $this->assets(), 'registered' ) ) {
						wp_enqueue_script( $this->assets() );
						wp_enqueue_style( $this->assets() );
					}
				}
			}
		}
	}
}
