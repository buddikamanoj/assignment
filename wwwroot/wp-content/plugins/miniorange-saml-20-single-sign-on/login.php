<?php
/*
Plugin Name: miniOrange SSO using SAML 2.0
Plugin URI: http://miniorange.com/
Description: miniOrange SAML 2.0 SSO enables user to perform Single Sign On with any SAML 2.0 enabled Identity Provider.
Version: 4.8.42
Author: miniOrange
Author URI: http://miniorange.com/
*/


include_once dirname( __FILE__ ) . '/mo_login_saml_sso_widget.php';
require('mo-saml-class-customer.php');
require('mo_saml_settings_page.php');
require('MetadataReader.php');
class saml_mo_login {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'miniorange_sso_menu' ) );
		add_action( 'admin_init', array( $this, 'miniorange_login_widget_saml_save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_style' ) );
		register_deactivation_hook(__FILE__, array( $this, 'mo_sso_saml_deactivate'));
		//register_uninstall_hook(__FILE__, array( 'saml_mo_login', 'mo_sso_saml_uninstall'));
		add_action( 'admin_enqueue_scripts', array( $this, 'plugin_settings_script' ) );
		//add_action( 'plugins_loaded', array( $this, 'mo_login_widget_text_domain' ) );
		remove_action( 'admin_notices', array( $this, 'mo_saml_success_message') );
		remove_action( 'admin_notices', array( $this, 'mo_saml_error_message') );
		add_action('wp_authenticate', array( $this, 'mo_saml_authenticate' ) );
		add_action('wp', array( $this, 'mo_saml_auto_redirect' ) );
		add_action('login_form', array( $this, 'mo_saml_modify_login_form' ) );
		add_shortcode( 'MO_SAML_FORM', array($this, 'mo_get_saml_shortcode') );
	}

	function  mo_login_widget_saml_options () {
		global $wpdb;
		update_option( 'mo_saml_host_name', 'https://auth.miniorange.com' );
		$host_name = get_option('mo_saml_host_name');

		$brokerService = get_option('mo_saml_enable_cloud_broker');
		$token = get_option('saml_x509_certificate');

		if(empty($brokerService) && !empty($token)){
			update_option('mo_saml_enable_cloud_broker', 'true');
		}else{
			if(empty($brokerService))
			update_option('mo_saml_enable_cloud_broker', 'false');
		}
		mo_register_saml_sso();
	}


	function mo_saml_success_message() {
		$class = "error";
		$message = get_option('mo_saml_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	function mo_saml_error_message() {
		$class = "updated";
		$message = get_option('mo_saml_message');
		echo "<div class='" . $class . "'> <p>" . $message . "</p></div>";
	}

	public function mo_sso_saml_deactivate() {
		if(!is_multisite()) {
			//delete all customer related key-value pairs
			delete_option('mo_saml_host_name');
			delete_option('mo_saml_new_registration');
			delete_option('mo_saml_admin_phone');
			delete_option('mo_saml_admin_password');
			delete_option('mo_saml_verify_customer');
			delete_option('mo_saml_admin_customer_key');
			delete_option('mo_saml_admin_api_key');
			delete_option('mo_saml_customer_token');

			delete_option('mo_saml_message');
			delete_option('mo_saml_registration_status');
			delete_option('mo_saml_idp_config_complete');
			delete_option('mo_saml_transactionId');
			delete_option('mo_proxy_host');
			delete_option('mo_proxy_username');
			delete_option('mo_proxy_port');
			delete_option('mo_proxy_password');
			delete_option('mo_saml_show_mo_idp_message');

		} else {
			global $wpdb;
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id )
			{
				switch_to_blog( $blog_id );
				//delete all your options
				//E.g: delete_option( {option name} );
				delete_option('mo_saml_host_name');
				delete_option('mo_saml_new_registration');
				delete_option('mo_saml_admin_phone');
				delete_option('mo_saml_admin_password');
				delete_option('mo_saml_verify_customer');
				delete_option('mo_saml_admin_customer_key');
				delete_option('mo_saml_admin_api_key');
				delete_option('mo_saml_customer_token');
				delete_option('mo_saml_message');
				delete_option('mo_saml_registration_status');
				delete_option('mo_saml_idp_config_complete');
				delete_option('mo_saml_transactionId');
				delete_option('mo_saml_show_mo_idp_message');
			}
			switch_to_blog( $original_blog_id );
		}
	}

	private function mo_saml_show_success_message() {
		remove_action( 'admin_notices', array( $this, 'mo_saml_success_message') );
		add_action( 'admin_notices', array( $this, 'mo_saml_error_message') );
	}
	function mo_saml_show_error_message() {
		remove_action( 'admin_notices', array( $this, 'mo_saml_error_message') );
		add_action( 'admin_notices', array( $this, 'mo_saml_success_message') );
	}
	function plugin_settings_style($page) {
		if($page != 'toplevel_page_mo_saml_settings'){
			return;
		}
		wp_enqueue_style( 'mo_saml_admin_settings_jquery_style', plugins_url( 'includes/css/jquery.ui.css', __FILE__ ) );
		wp_enqueue_style( 'mo_saml_admin_settings_style', plugins_url( 'includes/css/style_settings.css?ver=4.8.40', __FILE__ ) );
		wp_enqueue_style( 'mo_saml_admin_settings_phone_style', plugins_url( 'includes/css/phone.css', __FILE__ ) );
		wp_enqueue_style( 'mo_saml_wpb-fa', plugins_url( 'includes/css/font-awesome.min.css', __FILE__ ) );
	}
	function plugin_settings_script($page) {
		if($page != 'toplevel_page_mo_saml_settings'){
			return;
		}
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'mo_saml_admin_settings_script', plugins_url( 'includes/js/settings.js', __FILE__ ) );
		wp_enqueue_script( 'mo_saml_admin_settings_phone_script', plugins_url('includes/js/phone.js', __FILE__ ) );
	}
	function miniorange_login_widget_saml_save_settings(){
		if ( current_user_can( 'manage_options' )){
			
		if(isset($_POST['option']) and $_POST['option'] == "mo_saml_mo_idp_message"){
			update_option('mo_saml_show_mo_idp_message', 1);
			return;
		}

		if(isset($_POST['option']) and $_POST['option'] == "login_widget_saml_save_settings"){
			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Save Identity Provider Configuration failed.');
				$this->mo_saml_show_error_message();
				return;
			}


			//validation and sanitization
			$saml_identity_name = '';
			$saml_login_url = '';
			$saml_issuer = '';
			$saml_x509_certificate = '';
			if( $this->mo_saml_check_empty_or_null( $_POST['saml_identity_name'] ) || $this->mo_saml_check_empty_or_null( $_POST['saml_login_url'] ) || $this->mo_saml_check_empty_or_null( $_POST['saml_issuer'] )  ) {
				update_option( 'mo_saml_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_saml_show_error_message();
				return;
			} else if(!preg_match("/^\w*$/", $_POST['saml_identity_name'])) {
				update_option( 'mo_saml_message', 'Please match the requested format for Identity Provider Name. Only alphabets, numbers and underscore is allowed.');
				$this->mo_saml_show_error_message();
				return;
			} else{
				$saml_identity_name = trim( $_POST['saml_identity_name'] );
				$saml_login_url = trim( $_POST['saml_login_url'] );
				$saml_issuer = trim( $_POST['saml_issuer'] );
				$saml_x509_certificate = ( $_POST['saml_x509_certificate'] );
			}

			update_option('saml_identity_name', $saml_identity_name);
			update_option('saml_login_url', $saml_login_url);
			update_option('saml_issuer', $saml_issuer);
			//update_option('saml_x509_certificate', $saml_x509_certificate);

			if(isset($_POST['saml_response_signed']))
				{
				update_option('saml_response_signed' , 'checked');
				}
			else
				{
				update_option('saml_response_signed' , 'Yes');
				}


			foreach ($saml_x509_certificate as $key => $value) {
				if(empty($value))
					unset($saml_x509_certificate[$key]);
				else
					{
						$saml_x509_certificate[$key] = Utilities::sanitize_certificate( $value );

					if(!@openssl_x509_read($saml_x509_certificate[$key])){
						update_option('mo_saml_message', 'Invalid certificate: Please provide a valid certificate.');
						$this->mo_saml_show_error_message();
						delete_option('saml_x509_certificate');
						return;
					}
									}
			}
			if(empty($saml_x509_certificate)){
				update_option("mo_saml_message",'Invalid Certificate: Please provide a certificate');
				$this->mo_saml_show_error_message();

				return;
			}
			update_option('saml_x509_certificate', maybe_serialize( $saml_x509_certificate ) );
			if(isset($_POST['saml_assertion_signed']))
				{
				update_option('saml_assertion_signed' , 'checked');
				}
			else
				{
				update_option('saml_assertion_signed' , 'Yes');
				}

			if(get_option('mo_saml_enable_cloud_broker') =='true'){
				$saveSaml = new Customersaml();
				$outputSaml = json_decode( $saveSaml->save_external_idp_config(), true );
				if(!@openssl_x509_read($outputSaml['samlX509Certificate'])){
					update_option('mo_saml_message', 'Invalid certificate: Please provide a valid certificate.');
					$this->mo_saml_show_error_message();
					delete_option('saml_x509_certificate');
					return;
				}
				if(isset($outputSaml['customerId'])) {
					update_option('saml_x509_certificate', $outputSaml['samlX509Certificate']);
					update_option('mo_saml_message', 'Identity Provider details saved successfully.');
					$this->mo_saml_show_success_message();
				}
				else {
					update_option('mo_saml_message', 'Identity Provider details could not be saved. Please try again.');
					$this->mo_saml_show_error_message();
				}
			}else{


				update_option('mo_saml_message', 'Identity Provider details saved successfully.');
				$this->mo_saml_show_success_message();
			}
		}
		//Save Attribute Mapping
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_saml_attribute_mapping"){

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Save Attribute Mapping failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			if (!get_option('mo_saml_free_version')) {
				update_option('saml_am_username', stripslashes($_POST['saml_am_username']));
				update_option('saml_am_email', stripslashes($_POST['saml_am_email']));
				update_option('saml_am_group_name', stripslashes($_POST['saml_am_group_name']));
			}
			update_option('saml_am_first_name', stripslashes($_POST['saml_am_first_name']));
			update_option('saml_am_last_name', stripslashes($_POST['saml_am_last_name']));
			update_option('saml_am_account_matcher', stripslashes($_POST['saml_am_account_matcher']));
			update_option('mo_saml_message', 'Attribute Mapping details saved successfully');
			$this->mo_saml_show_success_message();

		}
		//Save Role Mapping
		if(isset($_POST['option']) and $_POST['option'] == "login_widget_saml_role_mapping"){

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Save Role Mapping failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			if (!get_option('mo_saml_free_version')) {
				if(isset($_POST['saml_am_dont_allow_unlisted_user_role'])) {
					update_option('saml_am_default_user_role', false);
					update_option('saml_am_dont_allow_unlisted_user_role', 'checked');
				} else {
					update_option('saml_am_default_user_role', $_POST['saml_am_default_user_role']);
					update_option('saml_am_dont_allow_unlisted_user_role', 'unchecked');
				}
				$wp_roles = new WP_Roles();
				$roles = $wp_roles->get_names();
				$role_mapping;
				foreach ($roles as $role_value => $role_name) {
					$attr = 'saml_am_group_attr_values_' . $role_value;
					$role_mapping[$role_value] = stripslashes($_POST[$attr]);
				}
				update_option('saml_am_role_mapping', $role_mapping);
			} else {
				update_option('saml_am_default_user_role', $_POST['saml_am_default_user_role']);
			}
			update_option('mo_saml_message', 'Role Mapping details saved successfully.');
			$this->mo_saml_show_success_message();
		}

		if(isset($_POST['option']) and $_POST['option'] == "saml_upload_metadata"){
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$this->_handle_upload_metadata();
		}

		if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_register_customer" ) {	//register the admin to miniOrange

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Registration failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			//validation and sanitization
			$email = '';
			$company = '';
			$first_name = '';
			$last_name = '';
			$phone = '';
			$password = '';
			$confirmPassword = '';

			if( $this->mo_saml_check_empty_or_null( $_POST['email'] ) || $this->mo_saml_check_empty_or_null( $_POST['password'] ) || $this->mo_saml_check_empty_or_null( $_POST['confirmPassword'] ) || $this->mo_saml_check_empty_or_null( $_POST['company'] )) {

				update_option( 'mo_saml_message', 'Please enter the required fields.');
				$this->mo_saml_show_error_message();
				return;
			} else if( strlen( $_POST['password'] ) < 6 || strlen( $_POST['confirmPassword'] ) < 6){
				update_option( 'mo_saml_message', 'Choose a password with minimum length 6.');
				$this->mo_saml_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				$company = sanitize_text_field( $_POST['company'] );
				$first_name = sanitize_text_field( $_POST['first_name'] );
				$last_name = sanitize_text_field( $_POST['last_name'] );
				$phone = sanitize_text_field( $_POST['phone'] );
				$password = stripslashes( $_POST['password'] );
				$confirmPassword = stripslashes( $_POST['confirmPassword'] );
			}
			update_option( 'mo_saml_admin_email', $email );
			update_option( 'mo_saml_admin_phone', $phone );
			update_option( 'mo_saml_admin_company', $company );
			update_option( 'mo_saml_admin_first_name', $first_name );
			update_option( 'mo_saml_admin_last_name', $last_name );
			if( strcmp( $password, $confirmPassword) == 0 ) {
				update_option( 'mo_saml_admin_password', $password );
				$email = get_option('mo_saml_admin_email');
				$customer = new CustomerSaml();
				$content = json_decode($customer->check_customer(), true);
				if( strcasecmp( $content['status'], 'CUSTOMER_NOT_FOUND') == 0 ){
					$content = json_decode($customer->send_otp_token($email, ''), true);
					if(strcasecmp($content['status'], 'SUCCESS') == 0) {
						update_option( 'mo_saml_message', ' A one time passcode is sent to ' . get_option('mo_saml_admin_email') . '. Please enter the otp here to verify your email.');
						update_option('mo_saml_transactionId',$content['txId']);
						update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_EMAIL');
						$this->mo_saml_show_success_message();
					}else{
						update_option('mo_saml_message','There was an error in sending email. Please verify your email and try again.');
						update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_EMAIL');
						$this->mo_saml_show_error_message();
					}
				}else{
					$this->get_current_customer();
				}

			} else {
				update_option( 'mo_saml_message', 'Passwords do not match.');
				delete_option('mo_saml_verify_customer');
				$this->mo_saml_show_error_message();
			}

			//new starts here

		}


		if(isset($_POST['option']) and $_POST['option'] == "mo_saml_validate_otp"){

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Validate OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			//validation and sanitization
			$otp_token = '';
			if( $this->mo_saml_check_empty_or_null( $_POST['otp_token'] ) ) {
				update_option( 'mo_saml_message', 'Please enter a value in otp field.');
				//update_option('mo_saml_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_saml_show_error_message();
				return;
			} else{
				$otp_token = sanitize_text_field( $_POST['otp_token'] );
			}

			$customer = new CustomerSaml();
			$content = json_decode($customer->validate_otp_token(get_option('mo_saml_transactionId'), $otp_token ),true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {

					$this->create_customer();
			}else{
				update_option( 'mo_saml_message','Invalid one time passcode. Please enter a valid otp.');
				//update_option('mo_saml_registration_status','MO_OTP_VALIDATION_FAILURE');
				$this->mo_saml_show_error_message();
			}
		}
		if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_verify_customer" ) {	//register the admin to miniOrange

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Login failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			//validation and sanitization
			$email = '';
			$password = '';
			if( $this->mo_saml_check_empty_or_null( $_POST['email'] ) || $this->mo_saml_check_empty_or_null( $_POST['password'] ) ) {
				update_option( 'mo_saml_message', 'All the fields are required. Please enter valid entries.');
				$this->mo_saml_show_error_message();
				return;
			} else{
				$email = sanitize_email( $_POST['email'] );
				$password = stripslashes( $_POST['password'] );
			}

			update_option( 'mo_saml_admin_email', $email );
			update_option( 'mo_saml_admin_password', $password );
			$customer = new Customersaml();
			$content = $customer->get_customer_key();
			$customerKey = json_decode( $content, true );
			if( json_last_error() == JSON_ERROR_NONE ) {
				update_option( 'mo_saml_admin_customer_key', $customerKey['id'] );
				update_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
				update_option( 'mo_saml_customer_token', $customerKey['token'] );
				update_option( 'mo_saml_admin_phone', $customerKey['phone'] );
				$certificate = get_option('saml_x509_certificate');
				if(empty($certificate)) {
					update_option( 'mo_saml_free_version', 1 );
				}
				update_option('mo_saml_admin_password', '');
				update_option( 'mo_saml_message', 'Customer retrieved successfully');
				update_option('mo_saml_registration_status' , 'Existing User');
				delete_option('mo_saml_verify_customer');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Invalid username or password. Please try again.');
				$this->mo_saml_show_error_message();
			}
			update_option('mo_saml_admin_password', '');
		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_contact_us_query_option" ) {

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Query submit failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			// Contact Us query
			$email = $_POST['mo_saml_contact_us_email'];
			$phone = $_POST['mo_saml_contact_us_phone'];
			$query = $_POST['mo_saml_contact_us_query'];
			$customer = new CustomerSaml();
			if ( $this->mo_saml_check_empty_or_null( $email ) || $this->mo_saml_check_empty_or_null( $query ) ) {
				update_option('mo_saml_message', 'Please fill up Email and Query fields to submit your query.');
				$this->mo_saml_show_error_message();
			} else {
				$submited = $customer->submit_contact_us( $email, $phone, $query );
				if ( $submited == false ) {
					update_option('mo_saml_message', 'Your query could not be submitted. Please try again.');
					$this->mo_saml_show_error_message();
				} else {
					update_option('mo_saml_message', 'Thanks for getting in touch! We shall get back to you shortly.');
					$this->mo_saml_show_success_message();
				}
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_resend_otp_email" ) {

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}
			$email = get_option ( 'mo_saml_admin_email' );
		    $customer = new CustomerSaml();
			$content = json_decode($customer->send_otp_token($email, ''), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
					update_option( 'mo_saml_message', ' A one time passcode is sent to ' . get_option('mo_saml_admin_email') . ' again. Please check if you got the otp and enter it here.');
					update_option('mo_saml_transactionId',$content['txId']);
					update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_EMAIL');
					$this->mo_saml_show_success_message();
			}else{
					update_option('mo_saml_message','There was an error in sending email. Please click on Resend OTP to try again.');
					update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_EMAIL');
					$this->mo_saml_show_error_message();
			}
		} else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_resend_otp_phone" ) {

			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}
			$phone = get_option('mo_saml_admin_phone');
		    $customer = new CustomerSaml();
			$content = json_decode($customer->send_otp_token('', $phone, FALSE, TRUE), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
					update_option( 'mo_saml_message', ' A one time passcode is sent to ' . $phone . ' again. Please check if you got the otp and enter it here.');
					update_option('mo_saml_transactionId',$content['txId']);
					update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_PHONE');
					$this->mo_saml_show_success_message();
			}else{
					update_option('mo_saml_message','There was an error in sending email. Please click on Resend OTP to try again.');
					update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_PHONE');
					$this->mo_saml_show_error_message();
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_go_back" ){
				update_option('mo_saml_registration_status','');
				update_option('mo_saml_verify_customer', '');
				delete_option('mo_saml_new_registration');
				delete_option('mo_saml_admin_email');
				delete_option('mo_saml_admin_phone');
		} else if(isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_goto_login"){
				delete_option('mo_saml_new_registration');
				update_option('mo_saml_verify_customer','true');
		}else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_register_with_phone_option" ) {
			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}
			$phone = sanitize_text_field($_POST['phone']);
			$phone = str_replace(' ', '', $phone);
			$phone = str_replace('-', '', $phone);
			update_option('mo_saml_admin_phone', $phone);
			$customer = new CustomerSaml();
			$content = json_decode($customer->send_otp_token('', $phone, FALSE, TRUE), true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0) {
				update_option( 'mo_saml_message', ' A one time passcode is sent to ' . get_option('mo_saml_admin_phone') . '. Please enter the otp here to verify your email.');
				update_option('mo_saml_transactionId',$content['txId']);
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_SUCCESS_PHONE');
				$this->mo_saml_show_success_message();
			}else{
				update_option('mo_saml_message','There was an error in sending SMS. Please click on Resend OTP to try again.');
				update_option('mo_saml_registration_status','MO_OTP_DELIVERED_FAILURE_PHONE');
				$this->mo_saml_show_error_message();
			}
		}
		else if( isset( $_POST['option']) and $_POST['option'] == "mo_saml_force_authentication_option") {
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_force_authentication', $_POST)) {
					$enable_redirect = $_POST['mo_saml_force_authentication'];
				} else {
					$enable_redirect = 'false';
				}
				if($enable_redirect == 'true') {
					update_option('mo_saml_force_authentication', 'true');
				} else {
					update_option('mo_saml_force_authentication', '');
				}
				update_option( 'mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete <a href="' . add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] ) . '" />Service Provider</a> configuration first.');
				$this->mo_saml_show_error_message();
			}
		} else if( isset( $_POST['option']) and $_POST['option'] == "mo_saml_enable_login_redirect_option") {
			if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_enable_login_redirect', $_POST)) {
					$enable_redirect = $_POST['mo_saml_enable_login_redirect'];
				} else {
					$enable_redirect = 'false';
				}
				if($enable_redirect == 'true') {
					update_option('mo_saml_enable_login_redirect', 'true');
					if (!get_option('mo_saml_free_version')) {
						update_option('mo_saml_allow_wp_signin', 'true');
					}
				} else {
					update_option('mo_saml_enable_login_redirect', '');
					update_option('mo_saml_allow_wp_signin', '');
				}
				update_option( 'mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();
			} else {
				update_option( 'mo_saml_message', 'Please complete <a href="' . add_query_arg( array('tab' => 'save'), $_SERVER['REQUEST_URI'] ) . '" />Service Provider</a> configuration first.');
				$this->mo_saml_show_error_message();
			}
		}else if( isset( $_POST['option']) and $_POST['option'] == "mo_saml_enable_cloud_broker") {//checking the form


			//if(mo_saml_is_sp_configured()) {
				if(array_key_exists('mo_saml_enable_cloud_broker', $_POST)) {//checking the button
					$enable_redirect = $_POST['mo_saml_enable_cloud_broker'];
				} else {
					//$enable_redirect = 'false';

				}

				if($enable_redirect == 'true') {
					update_option('mo_saml_enable_cloud_broker', 'true');

				}
				else if($enable_redirect == 'miniorange') {
					update_option('mo_saml_enable_cloud_broker', 'miniorange');
					//edited here
					update_option('saml_identity_name', 'miniOrange');
					delete_option('saml_login_url');
					delete_option('saml_issuer');
					delete_option('saml_x509_certificate');

						if(!mo_saml_is_curl_installed()) {
							update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Registration failed.');
							$this->mo_saml_show_error_message();
							return;
						}

						$customer = new Customersaml();
						/*$content = $customer->set_customer_app_policy();
						$customerKey = json_decode( $content, true );
						if( json_last_error() == JSON_ERROR_NONE ) {
							update_option( 'mo_saml_admin_customer_key', $customerKey['id'] );
							update_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
							update_option( 'mo_saml_customer_token', $customerKey['token'] );
							update_option( 'mo_saml_admin_phone', $customerKey['phone'] );
							$certificate = get_option('saml_x509_certificate');
							if(empty($certificate)) {
								update_option( 'mo_saml_free_version', 1 );
							}
							update_option('mo_saml_admin_password', '');
							update_option( 'mo_saml_message', 'Customer retrieved successfully');
							update_option('mo_saml_registration_status' , 'Existing User');
							delete_option('mo_saml_verify_customer');
							$this->mo_saml_show_success_message();
						}*/

				}else{
					update_option('mo_saml_enable_cloud_broker', 'false');

				}

				update_option( 'mo_saml_message', 'Sign in options updated.');
				$this->mo_saml_show_success_message();

		} else if( isset( $_POST['option']) and $_POST['option'] == "mo_saml_allow_wp_signin_option") {
			if (!get_option('mo_saml_free_version')) {
				if(array_key_exists('mo_saml_allow_wp_signin', $_POST)) {
					$allow_wp_signin = $_POST['mo_saml_allow_wp_signin'];
				} else {
					$allow_wp_signin = 'false';
				}
				if($allow_wp_signin == 'true') {
					update_option('mo_saml_allow_wp_signin', 'true');
				} else {
					update_option('mo_saml_allow_wp_signin', '');
				}
				update_option( 'mo_saml_message', 'Sign In settings updated.');
				$this->mo_saml_show_success_message();
			}
		} else if(isset($_POST['option']) && $_POST['option'] == 'mo_saml_forgot_password_form_option'){
			if(!mo_saml_is_curl_installed()) {
				update_option( 'mo_saml_message', 'ERROR: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP cURL extension</a> is not installed or disabled. Resend OTP failed.');
				$this->mo_saml_show_error_message();
				return;
			}

			$email = get_option('mo_saml_admin_email');

			$customer = new Customersaml();
			$content = json_decode($customer->mo_saml_forgot_password($email),true);
			if(strcasecmp($content['status'], 'SUCCESS') == 0){
				update_option( 'mo_saml_message','Your password has been reset successfully. Please enter the new password sent to ' . $email . '.');
				$this->mo_saml_show_success_message();
			}else{
				update_option( 'mo_saml_message','An error occured while processing your request. Please Try again.');
				$this->mo_saml_show_error_message();
			}
		}
		else if( isset( $_POST['option'] ) and $_POST['option'] == "mo_saml_save_proxy_setting" ) {
			update_option('mo_proxy_host',$_POST['mo_proxy_host']);
			update_option('mo_proxy_port',$_POST['mo_proxy_port']);
			update_option('mo_proxy_username',$_POST['mo_proxy_username']);
			update_option('mo_proxy_password',$_POST['mo_proxy_password']);
			update_option('mo_saml_message','Proxy settings saved successfully');
			$this->mo_saml_show_success_message();
		}
		}
	}

	function create_customer(){
		$customer = new CustomerSaml();
		$customerKey = json_decode( $customer->create_customer(), true );
		if( strcasecmp( $customerKey['status'], 'CUSTOMER_USERNAME_ALREADY_EXISTS') == 0 ) {
					$this->get_current_customer();
		} else if( strcasecmp( $customerKey['status'], 'SUCCESS' ) == 0 ) {
			update_option( 'mo_saml_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_saml_customer_token', $customerKey['token'] );
			update_option( 'mo_saml_free_version', 1 );
			update_option('mo_saml_admin_password', '');
			update_option( 'mo_saml_message', 'Thank you for registering with miniorange.');
			update_option('mo_saml_registration_status','');
			delete_option('mo_saml_verify_customer');
			delete_option('mo_saml_new_registration');
			$this->mo_saml_show_success_message();
			wp_redirect(admin_url().'admin.php?page=mo_saml_settings&tab=licensing');
		}
		update_option('mo_saml_admin_password', '');
	}

	function get_current_customer(){
		$customer = new CustomerSaml();
		$content = $customer->get_customer_key();
		$customerKey = json_decode( $content, true );
		if( json_last_error() == JSON_ERROR_NONE ) {
			update_option( 'mo_saml_admin_customer_key', $customerKey['id'] );
			update_option( 'mo_saml_admin_api_key', $customerKey['apiKey'] );
			update_option( 'mo_saml_customer_token', $customerKey['token'] );
			update_option('mo_saml_admin_password', '' );
			$certificate = get_option('saml_x509_certificate');
			if(empty($certificate)) {
				update_option( 'mo_saml_free_version', 1 );
			}
			update_option( 'mo_saml_message', 'Your account has been retrieved successfully.' );
			delete_option('mo_saml_verify_customer');
			delete_option('mo_saml_new_registration');
			$this->mo_saml_show_success_message();
			wp_redirect(admin_url().'admin.php?page=mo_saml_settings&tab=licensing');
		} else {
			update_option( 'mo_saml_message', 'You already have an account with miniOrange. Please enter a valid password.');
			update_option('mo_saml_verify_customer', 'true');
			delete_option('mo_saml_new_registration');
			$this->mo_saml_show_error_message();
		}
	}

	public function mo_saml_check_empty_or_null( $value ) {
		if( ! isset( $value ) || empty( $value ) ) {
			return true;
		}
		return false;
	}

	function miniorange_sso_menu() {
		//Add miniOrange SAML SSO
		$page = add_menu_page( 'MO SAML Settings ' . __( 'Configure SAML Identity Provider for SSO', 'mo_saml_settings' ), 'miniOrange SAML 2.0 SSO', 'administrator', 'mo_saml_settings', array( $this, 'mo_login_widget_saml_options' ), plugin_dir_url(__FILE__) . 'images/miniorange.png' );
	}


	function mo_saml_redirect_for_authentication( $relay_state ) {
		if(get_option('mo_saml_enable_cloud_broker') =='false'){
			if(mo_saml_is_sp_configured() && !is_user_logged_in()) {
				$sendRelayState = $relay_state;
				$ssoUrl = get_option("saml_login_url");
				$force_authn = get_option('mo_saml_force_authentication');
				$acsUrl = site_url()."/";
				$issuer = site_url().'/wp-content/plugins/miniorange-saml-20-single-sign-on/';
				$samlRequest = Utilities::createAuthnRequest($acsUrl, $issuer, $force_authn);
				$redirect = $ssoUrl;
				if (strpos($ssoUrl,'?') !== false) {
					$redirect .= '&';
				} else {
					$redirect .= '?';
				}
				$redirect .= 'SAMLRequest=' . $samlRequest . '&RelayState=' . urlencode($sendRelayState);
				header('Location: '.$redirect);
				exit();
			}
		}else{
			$mo_redirect_url = get_option('mo_saml_host_name') . "/moas/rest/saml/request?id=" . get_option('mo_saml_admin_customer_key') . "&returnurl=" . urlencode( site_url() . "/?option=readsamllogin&redirect_to=" . urlencode ($relay_state) );
			header('Location: ' . $mo_redirect_url);
			exit();
		}
	}
function mo_saml_authenticate() {
		$redirect_to = '';
		if ( isset( $_REQUEST['redirect_to']) ) {
			$redirect_to = htmlentities($_REQUEST['redirect_to']);
		}
		if( is_user_logged_in() ) {
			if(!empty($redirect_to)) {
				header('Location: ' . $redirect_to);
			} else {
				header('Location: ' . site_url());
			}
			exit();
		}
		if( get_option('mo_saml_enable_login_redirect') == 'true' ) {
			if( isset($_GET['loggedout']) && $_GET['loggedout'] == 'true' ) {
				header('Location: ' . site_url());
				exit();
			} elseif ( get_option('mo_saml_allow_wp_signin') == 'true' ) {
				if( ( isset($_GET['saml_sso']) && $_GET['saml_sso'] == 'false' ) || ( isset($_POST['saml_sso']) && $_POST['saml_sso'] == 'false' ) ) {
					return;
				}
				elseif ( isset( $_REQUEST['redirect_to']) ) {
					$redirect_to = $_REQUEST['redirect_to'];
					if( strpos( $redirect_to, 'wp-admin') !== false && strpos( $redirect_to, 'saml_sso=false') !== false) {
						return;
					}
				}
			}
			$this->mo_saml_redirect_for_authentication( $redirect_to );
		}
	}
		function mo_saml_auto_redirect() {

		if( get_option('mo_saml_registered_only_access') == 'true' ) {

			if( get_option('mo_saml_enable_rss_access') == 'true' && is_feed())
				return;

			$relay_state = saml_get_current_page_url();

			$this->mo_saml_redirect_for_authentication( $relay_state );
		}
	}
	function mo_saml_modify_login_form() {
		echo '<input type="hidden" name="saml_sso" value="false">'."\n";
	}

		function mo_get_saml_shortcode(){
		if(!is_user_logged_in()){
			if(mo_saml_is_sp_configured()){

				if(get_option('mo_saml_enable_cloud_broker') =='true'){
					$html =	"<a href=".get_option('mo_saml_host_name')."/moas/rest/saml/request?id=".get_option('mo_saml_admin_customer_key')."&returnurl=".urlencode( site_url() . '/?option=readsamllogin' )." />Login with ".get_option('saml_identity_name')."</a>";
				}else{
					$html="<a href='".site_url()."/?option=saml_user_login' >Login with ".get_option('saml_identity_name').".</a>";

				}
			}else
				$html = 'SP is not configured.';
		}
		else
			$html = 'Hello, '.wp_get_current_user()->display_name.' | <a href='.wp_logout_url(site_url()).'>Logout</a>';
		return $html;
	}

		function _handle_upload_metadata(){
		if ( isset($_FILES['metadata_file']) || isset($_POST['metadata_url'])) {
			if(!empty($_FILES['metadata_file']['tmp_name'])) {
				$file = @file_get_contents( $_FILES['metadata_file']['tmp_name']);
			} else {
				$url=filter_var($_POST['metadata_url'],FILTER_SANITIZE_URL);
					$ch=curl_init();

    				curl_setopt($ch, CURLOPT_URL,$url);
    				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
   					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   					curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
   					curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    				$file = curl_exec($ch);

    				curl_close($ch);
			}
			$this->upload_metadata($file);
		}
	}

	function upload_metadata($file)
	{

		$old_error_handler = set_error_handler(array($this, 'handleXmlError'));
		$document = new DOMDocument();
		$document->loadXML($file);
		restore_error_handler();
		$first_child = $document->firstChild;
		if(!empty($first_child)) {
			$metadata = new IDPMetadataReader($document);
			$identity_providers = $metadata->getIdentityProviders();
			if(empty($identity_providers)) {
				update_option('mo_saml_message', 'Please provide a valid metadata file.');
				$this->mo_saml_show_error_message();
				return;
			}
			foreach($identity_providers as $key => $idp){
				//$saml_identity_name = preg_match("/^[a-zA-Z0-9-\._ ]+/", $idp->getIdpName()) ? $idp->getIdpName() : "";
				$saml_identity_name=$_POST['saml_identity_metadata_provider'];

				$saml_login_url = $idp->getLoginURL('HTTP-Redirect');

				$saml_issuer = $idp->getEntityID();
				$saml_x509_certificate = $idp->getSigningCertificate();

				update_option('saml_identity_name', $saml_identity_name);

				update_option('saml_login_url', $saml_login_url);


				update_option('saml_issuer', $saml_issuer);
				//certs already sanitized in Metadata Reader
				update_option('saml_x509_certificate', maybe_serialize($saml_x509_certificate));
				break;
			}
			update_option('mo_saml_message', 'Identity Provider details saved successfully.');
			$this->mo_saml_show_success_message();
		} else {
			update_option('mo_saml_message', 'Please provide a valid metadata file.');
			$this->mo_saml_show_error_message();
		}
	}

	function handleXmlError($errno, $errstr, $errfile, $errline) {
	    if ($errno==E_WARNING && (substr_count($errstr,"DOMDocument::loadXML()")>0)) {
	        return;
	    } else {
	        return false;
		}
	}
}
new saml_mo_login;