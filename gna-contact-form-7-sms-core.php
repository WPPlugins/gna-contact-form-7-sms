<?php
if (!class_exists('GNA_ContactForm7SMS')) {
	class GNA_ContactForm7SMS {
		var $plugin_url;
		var $admin_init;
		var $configs;

		public function init() {
			$class = __CLASS__;
			new $class;
		}

		public function __construct() {
			$this->load_configs();
			$this->define_constants();
			$this->define_variables();
			$this->includes();
			$this->loads();

			add_action( 'init', array(&$this, 'plugin_init'), 0 );
			add_filter( 'plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2 );
			add_action( 'plugins_loaded', array(&$this, 'gna_plugin_load_textdomain') );
		}

		/**
		 * Load plugin textdomain.
		 *
		 * @since 1.0.0
		 */
		function gna_plugin_load_textdomain() {
			load_plugin_textdomain( 'gna-contact-form-7-sms', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}

		public function load_configs() {
			include_once('inc/gna-contact-form-7-sms-config.php');
			$this->configs = GNA_ContactForm7SMS_Config::get_instance();
		}

		public function define_constants() {
			define('GNA_CONTACT_FROM_7_SMS_VERSION', '1.0.5');

			define('GNA_CONTACT_FROM_7_SMS_BASENAME', plugin_basename(__FILE__));
			define('GNA_CONTACT_FROM_7_SMS_URL', $this->plugin_url());
			define('GNA_CONTACT_FROM_7_SMS_MENU_SLUG_PREFIX', 'gna-cfs');

			define('GNA_CONTACT_FROM_7_SMS_WSDL_LINK', 'http://www.smsglobal.com/mobileworks/soapserver.php?wsdl');
		}

		public function define_variables() {
		}

		public function includes() {
			if(is_admin()) {
				include_once('admin/gna-contact-form-7-sms-admin-init.php');
			}

			add_action( 'wpcf7_mail_sent', array(&$this, 'gna_wpcf7_mail_sent_function') );
			add_filter( 'wpcf7_editor_panels' , array(&$this, 'sms_template_panel') );
			add_action( 'wpcf7_after_save', array( &$this, 'save_form' ) );
		}

		public function loads() {
			if(is_admin()){
				$this->admin_init = new GNA_ContactForm7SMS_Admin_Init();
			}
		}

		public function plugin_init() {
			load_plugin_textdomain('gna-contact-form-7-sms', false, dirname(plugin_basename(__FILE__ )) . '/languages/');
		}

		public function plugin_url() {
			if ($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		}

		public function filter_plugin_meta($links, $file) {
			if( strpos( GNA_CONTACT_FROM_7_SMS_BASENAME, str_replace('.php', '', $file) ) !== false ) { /* After other links */
				$links[] = '<a target="_blank" href="https://profiles.wordpress.org/chris_dev/" rel="external">' . __('Developer\'s Profile', 'gna-contact-form-7-sms') . '</a>';
			}

			return $links;
		}

		public function install() {
		}

		public function uninstall() {
		}

		public function activate_handler() {
		}

		public function deactivate_handler() {
		}

		public function sms_template_panel($panels) {
			$panels['gna-sms-panel'] = array(
					'title' => 'SMS Template',
					'callback' => array(&$this, 'display_panel')
			);
			return $panels;
		}

		public function display_panel($contact_form) {
			if ( wpcf7_admin_has_edit_cap() ) {
				global $g_contactform7sms;
				$options['receive_number'] = $g_contactform7sms->configs->get_value('g_cfs_form_rn_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id));
				$options['message'] = $g_contactform7sms->configs->get_value('g_cfs_form_mb_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id));

				$options['form'] = $contact_form;
				$this->render_template( 'default', $options );
			}
		}

		public function save_form( $contact_form ) {
			//print_r($_POST);die();
			global $g_contactform7sms;

			$g_contactform7sms->configs->set_value('g_cfs_form_rn_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id), $_POST['g_cfs_receive_number']);
			$g_contactform7sms->configs->set_value('g_cfs_form_mb_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id), $_POST['g_cfs_mail_body']);
			$g_contactform7sms->configs->save_config();
		}

		public function gna_wpcf7_mail_sent_function( $contact_form ) {
			global $g_contactform7sms;

			if ( $g_contactform7sms->configs->get_value('g_cfs_enabled') == '1' ) {

				//Contact Form 7 >= 3.9
				if(function_exists('wpcf7_mail_replace_tags')) {
					$message = wpcf7_mail_replace_tags($g_contactform7sms->configs->get_value('g_cfs_form_mb_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id)), array());
					$phone = wpcf7_mail_replace_tags($g_contactform7sms->configs->get_value('g_cfs_form_rn_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id)), array());
				} elseif(method_exists($form, 'replace_mail_tags')) {
					$message = $form->replace_mail_tags($g_contactform7sms->configs->get_value('g_cfs_form_mb_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id)));
					$phone = $form->replace_mail_tags($g_contactform7sms->configs->get_value('g_cfs_form_rn_'.(method_exists($contact_form, 'id') ? $contact_form->id() : $contact_form->id)));
				} else {
					return;
				}

				$client = new SoapClient(GNA_CONTACT_FROM_7_SMS_WSDL_LINK);
				$validation_login = $client->apiValidateLogin($g_contactform7sms->configs->get_value('g_cfs_username'), $g_contactform7sms->configs->get_value('g_cfs_userpw'));

				//Sending SMS.
				$xml_praser = xml_parser_create();
				xml_parse_into_struct($xml_praser, $validation_login, $xml_data, $xml_index);
				xml_parser_free($xml_praser);
				$ticket_id = $xml_data[$xml_index['TICKET'][0]]['value'];

				$result = $client->apiSendLongSms($ticket_id, $g_contactform7sms->configs->get_value('g_cfs_sender_number'), $phone, $message, 'text', '0', '0');
			}
		}

		public function render_template( $name, $data = array() ) {
			include_once( 'templates/' . $name . '.php' );
		}
	}
}

$GLOBALS['g_contactform7sms'] = new GNA_ContactForm7SMS();
