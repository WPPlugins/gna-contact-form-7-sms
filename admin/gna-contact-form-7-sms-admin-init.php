<?php
/*
 * Inits the admin dashboard side of things.
 * Main admin file which loads all settings panels and sets up admin menus.
 */
if (!class_exists('GNA_ContactForm7SMS_Admin_Init')) {
	class GNA_ContactForm7SMS_Admin_Init {
		var $main_menu_page;
		//var $dashboard_menu;
		var $settings_menu;

		public function __construct() {
			//This class is only initialized if is_admin() is true
			$this->admin_includes();
			add_action('admin_menu', array(&$this, 'create_admin_menus'));

			//make sure we are on our plugin's menu pages
			if (isset($_GET['page']) && (strpos($_GET['page'], GNA_CONTACT_FROM_7_SMS_MENU_SLUG_PREFIX ) !== false || strpos($_GET['page'], 'wpcf7' ) !== false) ) {
				add_action('admin_print_scripts', array(&$this, 'admin_menu_page_scripts'));
				add_action('admin_print_styles', array(&$this, 'admin_menu_page_styles'));
			}
		}

		public function admin_includes() {
			include_once('gna-contact-form-7-sms-admin-menu.php');
		}

		public function admin_menu_page_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('postbox');
			wp_enqueue_script('dashboard');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-upload');
		}

		function admin_menu_page_styles() {
			wp_enqueue_style('dashboard');
			wp_enqueue_style('thickbox');
			wp_enqueue_style('global');
			wp_enqueue_style('wp-admin');
			wp_enqueue_style('gna-contact-form-7-sms-admin-css', GNA_CONTACT_FROM_7_SMS_URL. '/assets/css/gna-contact-form-7-sms-admin-styles.css');
		}

		public function create_admin_menus() {
			$this->main_menu_page = add_menu_page( __('GNA Contact Form 7 SMS', 'gna-contact-form-7-sms'), __('GNA Contact Form 7 SMS', 'gna-contact-form-7-sms'), 'manage_options', 'gna-cfs-settings-menu', array(&$this, 'handle_settings_menu_rendering'), GNA_CONTACT_FROM_7_SMS_URL . '/assets/images/gna_20x20.png' );

			add_submenu_page('gna-cfs-settings-menu', __('Settings', 'gna-contact-form-7-sms'),  __('Settings', 'gna-contact-form-7-sms'), 'manage_options', 'gna-cfs-settings-menu', array(&$this, 'handle_settings_menu_rendering'));

			add_action( 'admin_init', array(&$this, 'register_gna_contact_form_7_sms_settings') );
		}

		public function register_gna_contact_form_7_sms_settings() {
			register_setting( 'gna-contact-form-7-sms-setting-group', 'g_contactform7sms_configs' );
		}

		public function handle_settings_menu_rendering() {
			include_once('gna-contact-form-7-sms-admin-settings-menu.php');
			$this->settings_menu = new GNA_ContactForm7SMS_Settings_Menu();
		}
	}
}
