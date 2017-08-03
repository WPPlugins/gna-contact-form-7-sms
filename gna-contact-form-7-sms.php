<?php
/*
Plugin Name: GNA Contact Form 7 SMS
Version: 1.0.5
Plugin URI: http://wordpress.org/plugins/gna-contact-form-7-sms/
Author: Chris Dev
Author URI: http://webgna.com/
Description: Send SMS from your existing Contact Form 7 plugin using SMS Global.
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gna-contact-form-7-sms
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('gna-contact-form-7-sms-core.php');

register_activation_hook(__FILE__, array('GNA_ContactForm7SMS', 'activate_handler'));		//activation hook
register_deactivation_hook(__FILE__, array('GNA_ContactForm7SMS', 'deactivate_handler'));	//deactivation hook
