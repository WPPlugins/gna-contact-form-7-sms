<?php
if (!class_exists('GNA_ContactForm7SMS_Settings_Menu')) {
	class GNA_ContactForm7SMS_Settings_Menu extends GNA_ContactForm7SMS_Admin_Menu {
		var $menu_page_slug = 'gna-cfs-settings-menu';

		/* Specify all the tabs of this menu in the following array */
		var $menu_tabs;

		var $menu_tabs_handler = array(
			'tab1' => 'render_tab1',
			'tab2' => 'render_tab2',
			);

		public function __construct() {
			$this->render_menu_page();
		}

		public function set_menu_tabs() {
			$this->menu_tabs = array(
				'tab1' => __('General Settings', 'gna-contact-form-7-sms'),
				'tab2' => __('SMS Global', 'gna-contact-form-7-sms'),
			);
		}

		public function get_current_tab() {
			$tab_keys = array_keys($this->menu_tabs);
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $tab_keys[0];
			return $tab;
		}

		/*
		 * Renders our tabs of this menu as nav items
		 */
		public function render_menu_tabs() {
			$current_tab = $this->get_current_tab();

			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $this->menu_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->menu_page_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		/*
		 * The menu rendering goes here
		 */
		public function render_menu_page() {
			echo '<div class="wrap">';
			$this->set_menu_tabs();
			$tab = $this->get_current_tab();
			$this->render_menu_tabs();
			?>
			<div id="poststuff"><div id="post-body">
			<?php
				//$tab_keys = array_keys($this->menu_tabs);
				call_user_func(array(&$this, $this->menu_tabs_handler[$tab]));
			?>
			</div></div>
			</div><!-- end of wrap -->
			<?php
		}

		public function render_tab1() {
			global $g_contactform7sms;
			if(isset($_POST['gna_cfs_save_enabled_settings'])) {
				$nonce = $_REQUEST['_wpnonce'];
				if(!wp_verify_nonce($nonce, 'n_gna-cfs-save-settings')) {
					die("Nonce check failed on save settings!");
				}

				$g_contactform7sms->configs->set_value('g_cfs_enabled', isset($_POST["g_cfs_enabled"]) ? $_POST["g_cfs_enabled"] : '');
				$g_contactform7sms->configs->save_config();
				$this->show_msg_settings_updated();
			}
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Contact Form 7 SMS', 'gna-contact-form-7-sms'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Contact Form 7 SMS plugin.', 'gna-contact-form-7-sms'); ?></p>
				</div>
			</div> <!-- end postbox-->

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('Enable Settings', 'gna-contact-form-7-sms'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-cfs-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Enable this feature', 'gna-contact-form-7-sms')?>:</th>
								<td>
									<input name="g_cfs_enabled" type="checkbox" value="1" <?php echo ($g_contactform7sms->configs->get_value('g_cfs_enabled') ? "checked=\"checked\"" : ""); ?> />
								</td>
							</tr>
						</table>
						<div class="gna_blue_box">
							<?php
								echo '<p>'.__('If you enable it, GNA Contact Form 7 SMS plugin will send SMS when someone submits Contact Form 7 according to you have set it up.', 'gna-contact-form-7-sms').'</p>';
							?>
						</div>
						<input type="submit" name="gna_cfs_save_enabled_settings" value="<?php _e('Save Settings', 'gna-contact-form-7-sms')?>" class="button" />
					</form>
				</div>
			</div> <!-- end postbox-->
			<?php
		}

		public function render_tab2() {
			global $g_contactform7sms;
			if(isset($_POST['gna_cfs_smsglobal_save_settings'])) {
				$nonce = $_REQUEST['_wpnonce'];
				if(!wp_verify_nonce($nonce, 'n_gna-cfs-save-settings')) {
					die("Nonce check failed on save settings!");
				}

				$g_contactform7sms->configs->set_value('g_cfs_username', isset($_POST["g_cfs_username"]) ? $_POST["g_cfs_username"] : '');
				$g_contactform7sms->configs->set_value('g_cfs_userpw', isset($_POST["g_cfs_userpw"]) ? $_POST["g_cfs_userpw"] : '');
				$g_contactform7sms->configs->set_value('g_cfs_sender_number', isset($_POST["g_cfs_sender_number"]) ? $_POST["g_cfs_sender_number"] : '');
				$g_contactform7sms->configs->save_config();
				$this->show_msg_settings_updated();
			}
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Contact Form 7 SMS', 'gna-contact-form-7-sms'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Contact Form 7 SMS plugin.', 'gna-contact-form-7-sms'); ?></p>
				</div>
			</div> <!-- end postbox-->

			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('HTTP/SMPP API Key', 'gna-contact-form-7-sms'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-cfs-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Username', 'gna-contact-form-7-sms')?>:</th>
								<td>
									<input name="g_cfs_username" type="text" value="<?php echo $g_contactform7sms->configs->get_value('g_cfs_username'); ?>" autocomplete="off" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Password', 'gna-contact-form-7-sms')?>:</th>
								<td>
									<input name="g_cfs_userpw" type="password" value="<?php echo $g_contactform7sms->configs->get_value('g_cfs_userpw'); ?>" autocomplete="off" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Sender Number', 'gna-contact-form-7-sms')?>:</th>
								<td>
									<input name="g_cfs_sender_number" type="text" value="<?php echo $g_contactform7sms->configs->get_value('g_cfs_sender_number'); ?>" autocomplete="off" />
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Status', 'gna-contact-form-7-sms')?>:</th>
								<td>
									<span><?php print_r( $this->getStatus() ); ?></span>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row"><?php _e('Credit', 'gna-contact-form-7-sms')?>:</th>
								<td>
									<span><?php echo $this->getCredit(); ?></span>
								</td>
							</tr>
						</table>
						<div class="gna_blue_box">
							<?php
								//echo '<p>'.__('Please put your contact number with country code which you can access to check SMS when you have got an email from Contact form 7', 'gna-contact-form-7-sms').'</p>';
								//echo '<p>'.__('For instance, +614xxxxxxxx', 'gna-contact-form-7-sms').'</p>';
							?>
							<ol>
								<li>Log into SMS Global (<a href="http://www.smsglobal.com/" target="_blank">http://www.smsglobal.com/</a>)</li>
								<li>Go to Tools menu on the left side.</li>
								<li>Go to API Keys Sub Menu</li>
								<li>Add New API Key if you haven't create yet</li>
							</ol>
							<img src="<?php echo GNA_CONTACT_FROM_7_SMS_URL . '/assets/images/screenshot-2.png'; ?>" />
						</div>
						<input type="submit" name="gna_cfs_smsglobal_save_settings" value="<?php _e('Save Settings', 'gna-contact-form-7-sms')?>" class="button" />
					</form>
				</div>
			</div> <!-- end postbox-->
			<?php
		}

		public function getStatus() {
			global $g_contactform7sms;

			$client = new SoapClient( GNA_CONTACT_FROM_7_SMS_WSDL_LINK );
			$validation_login = $client->apiValidateLogin( $g_contactform7sms->configs->get_value('g_cfs_username'), $g_contactform7sms->configs->get_value('g_cfs_userpw') );
			$xml_praser = xml_parser_create();
			xml_parse_into_struct($xml_praser, $validation_login, $xml_data, $xml_index);
			xml_parser_free($xml_praser);
			$err_code = $xml_data[$xml_index['RESP'][0]]['attributes']['ERR'];

			return ($err_code == '0' ? 'Active' : 'Inactive');
		}

		public function getCredit() {
			global $g_contactform7sms;

			$client = new SoapClient( GNA_CONTACT_FROM_7_SMS_WSDL_LINK );
			$validation_login = $client->apiValidateLogin( $g_contactform7sms->configs->get_value('g_cfs_username'), $g_contactform7sms->configs->get_value('g_cfs_userpw') );
			$xml_praser = xml_parser_create();
			xml_parse_into_struct($xml_praser, $validation_login, $xml_data, $xml_index);
			xml_parser_free($xml_praser);
			$ticket_id = $xml_data[$xml_index['TICKET'][0]]['value'];
			$credit = $client->apiBalanceCheck($ticket_id, 'AU');
			$xml_credit = simplexml_load_string($credit);

			return (string) $xml_credit->credit;
		}
	} //end class
}