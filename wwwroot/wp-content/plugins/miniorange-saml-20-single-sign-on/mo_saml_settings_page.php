<?php
include 'Import-export.php';
if ( session_id() == '' ) {
    session_start();
}
function mo_register_saml_sso() {
    if ( isset( $_GET['tab'] ) ) {
        $active_tab = $_GET['tab'];
    } else if ( mo_saml_is_customer_registered_saml() && mo_saml_is_sp_configured() ) {
        $active_tab = 'general';
    } else if ( mo_saml_is_customer_registered_saml() ) {
        $active_tab = 'config';
    } else {
        $active_tab = 'login';
    }
    ?>
    <?php
    if ( ! mo_saml_is_curl_installed() ) {
        ?>
        <p><font color="#FF0000">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP
                    cURL extension</a> is not installed or disabled)</font></p>
        <?php
    }

    if ( ! mo_saml_is_openssl_installed() ) {
        ?>
        <p><font color="#FF0000">(Warning: <a href="http://php.net/manual/en/openssl.installation.php" target="_blank">PHP
                    openssl extension</a> is not installed or disabled)</font></p>
        <?php
    }

	if ( ! mo_saml_is_dom_installed() ) {
		?>
        <p><font color="#FF0000">(Warning: PHP
                    dom extension is not installed or disabled)</font></p>
		<?php
	}

    ?>
    <div id="mo_saml_settings">
        <?php
        if ( $active_tab == 'licensing' || ! get_option( 'mo_saml_show_mo_idp_message' ) ) {
            ?>
            <form name="f" method="post" action="" id="mo_saml_mo_idp_form">
                <input type="hidden" name="option" value="mo_saml_mo_idp_message"/>
                <div class="notice notice-info" style="padding-right: 38px;position: relative;">
                    <h4>If you are looking for an Identity Provider,you can try out <a href="https://idp.miniorange.com"
                                                                                       target="_blank">miniOrange
                            On-Premise IdP</a>.</h4>
                    <button type="button" class="notice-dismiss" id="mo_saml_mo_idp"><span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            </form>
            <script>
                jQuery("#mo_saml_mo_idp").click(function () {
                    jQuery("#mo_saml_mo_idp_form").submit();
                });
            </script>
            <?php
        }
        ?>
        <div class="miniorange_container">
            <table style="width:100%;">
                <tr>
                    <h2 class="nav-tab-wrapper">
                        <?php if ( ! mo_saml_is_customer_registered_saml() ) { ?>
                            <a class="nav-tab <?php echo $active_tab == 'login' ? 'nav-tab-active' : ''; ?>"
                               href="<?php echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Account
                                Setup</a>
                        <?php } ?>
                        <a class="nav-tab <?php echo $active_tab == 'config' ? 'nav-tab-active' : ''; ?>"
                           href="<?php echo add_query_arg( array( 'tab' => 'config' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Identity
                            Provider</a>
                        <a class="nav-tab <?php echo $active_tab == 'save' ? 'nav-tab-active' : ''; ?>"
                           href="<?php echo add_query_arg( array( 'tab' => 'save' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Service
                            Provider</a>
                        <?php if ( mo_saml_is_customer_registered_saml() ) { ?>
                            <a class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"
                               href="<?php echo add_query_arg( array( 'tab' => 'general' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Sign
                                in Settings</a>
                        <?php } ?>
                        <a class="nav-tab <?php echo $active_tab == 'opt' ? 'nav-tab-active' : ''; ?>"
                           href="<?php echo add_query_arg( array( 'tab' => 'opt' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Attribute/Role
                            Mapping</a>
                        <a class="nav-tab <?php echo $active_tab == 'impexp' ? 'nav-tab-active' : ''; ?>"
                           href="<?php echo add_query_arg( array( 'tab' => 'impexp' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Import/Export
                            Configuration</a>
                        <a class="nav-tab <?php echo $active_tab == 'proxy' ? 'nav-tab-active' : ''; ?>"
                           href="<?php echo add_query_arg( array( 'tab' => 'proxy' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Proxy
                            Setup</a>

                        <a class="nav-tab <?php echo $active_tab == 'licensing' ? 'nav-tab-active' : ''; ?>"
                           href="<?php echo add_query_arg( array( 'tab' => 'licensing' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Licensing
                            Plans</a>
                        <a class="nav-tab <?php echo $active_tab == 'help' ? 'nav-tab-active' : ''; ?>"
                           href="<?php echo add_query_arg( array( 'tab' => 'help' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Help
                            & FAQ</a>
                    </h2>
                    <td style="vertical-align:top;width:65%;">
                        <?php
                        if ( $active_tab == 'save' ) {
                            mo_saml_apps_config_saml();
                        } else if ( $active_tab == 'opt' ) {
                            mo_saml_save_optional_config();
                        } else if ( $active_tab == 'proxy' ) {
                            mo_saml_proxy();
                        } else if ( $active_tab == 'impexp' ) {
                            miniorange_import_configuration_saml();
                        } else if ( $active_tab == 'help' ) {
                            mo_saml_save_plugin_config();
                        } else if ( $active_tab == 'config' ) {
                            mo_saml_configuration_steps();
                        } else if ( $active_tab == 'general' ) {
                            mo_saml_general_login_page();
                        } else if ( $active_tab == 'licensing' ) {
                            mo_saml_show_pricing_page();
                            echo '<style>#support-form{ display:none;}</style>';
                        } else {
                            if ( get_option( 'mo_saml_verify_customer' ) == 'true' ) {
                                mo_saml_show_verify_password_page_saml();
                            } else if ( trim( get_option( 'mo_saml_admin_email' ) ) != '' && trim( get_option( 'mo_saml_admin_api_key' ) ) == '' && get_option( 'mo_saml_new_registration' ) != 'true' ) {
                                mo_saml_show_verify_password_page_saml();
                            } else if ( get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_PHONE' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_PHONE' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_FAILURE' ) {
                                mo_saml_show_otp_verification();
                            } else if ( ! mo_saml_is_customer_registered_saml() ) {
                                delete_option( 'password_mismatch' );
                                mo_saml_show_new_registration_page_saml();

                            } else {
                                mo_saml_general_login_page();
                            }
                        }
                        ?>
                    </td>
                    <td style="vertical-align:top;padding-left:1%;" id="support-form">
                        <?php echo miniorange_support_saml(); ?>

                    </td>
                </tr>
            </table>
        </div>

        <?php
        }

        function mo_saml_is_curl_installed() {
            if ( in_array( 'curl', get_loaded_extensions() ) ) {
                return 1;
            } else {
                return 0;
            }
        }

        function mo_saml_is_openssl_installed() {
            if ( in_array( 'openssl', get_loaded_extensions() ) ) {
                return 1;
            } else {
                return 0;
            }
        }

        function mo_saml_is_dom_installed(){

	        if ( in_array( 'dom', get_loaded_extensions() ) ) {
		        return 1;
	        } else {
		        return 0;
	        }
        }

        function mo_saml_is_iconv_installed(){

            if ( in_array( 'iconv', get_loaded_extensions() ) ) {
                return 1;
            } else {
                return 0;
            }
        }

        function mo_saml_show_new_registration_page_saml() {
            update_option( 'mo_saml_new_registration', 'true' );
            $user = wp_get_current_user();
            ?>
            <!--Register with miniOrange-->
            <form name="f" method="post" action="">
                <input type="hidden" name="option" value="mo_saml_register_customer"/>
                <div class="mo_saml_table_layout">
                    <div id="toggle1" class="panel_toggle">
                        <h3>Register with miniOrange</h3>
                    </div>
                    <div id="panel1">
                        <p><a href="#" id="help_register_link">[ Why should I register? ]</a></p>
                        <div hidden id="help_register_desc" class="mo_saml_help_desc">
                            You should register so that in case you need help, we can help you with step by step
                            instructions. We support all known IdPs - <b>ADFS, Okta, Salesforce, Shibboleth,
                                SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2 etc</b>.
                        </div>
                        </p>
                        <table class="mo_saml_settings_table">
                            <tr>
                                <td><b><font color="#FF0000">*</font>Email:</b></td>
                                <td><input class="mo_saml_table_textbox" type="email" name="email"
                                           required placeholder="person@example.com"
                                           value="<?php echo ( get_option( 'mo_saml_admin_email' ) == '' ) ? get_option( 'admin_email' ) : get_option( 'mo_saml_admin_email' ); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><b><font color="#FF0000">*</font>Company/Organisation:</b></td>
                                <td><input class="mo_saml_table_textbox" type="text" name="company"
                                           required placeholder="Your company name"
                                           value="<?php echo ( get_option( 'mo_saml_admin_company' ) == '' ) ? site_url() : get_option( 'mo_saml_admin_company' ); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><b>First Name:</b></td>
                                <td><input class="mo_saml_table_textbox" type="text" name="first_name"
                                           placeholder="First Name"
                                           value="<?php echo ( get_option( 'mo_saml_admin_first_name' ) == '' ) ? $user->first_name : get_option( 'mo_saml_admin_first_name' ); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Last Name:</b></td>
                                <td><input class="mo_saml_table_textbox" type="text" name="last_name"
                                           placeholder="Last Name"
                                           value="<?php echo ( get_option( 'mo_saml_admin_last_name' ) == '' ) ? $user->last_name : get_option( 'mo_saml_admin_last_name' ); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Phone number:</b></td>
                                <td><input class="mo_saml_table_textbox" type="tel" id="phone_contact"
                                           style="width:80%;"
                                           pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})"
                                           class="mo_saml_table_textbox" name="phone"
                                           title="Phone with country code eg. +1xxxxxxxxxx"
                                           placeholder="Phone with country code eg. +1xxxxxxxxxx"
                                           value="<?php echo get_option( 'mo_saml_admin_phone' ); ?>"/></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>We will call only if you need support.</td>
                            </tr>
                            <tr>
                                <td><b><font color="#FF0000">*</font>Password:</b></td>
                                <td><input class="mo_saml_table_textbox" required type="password"
                                           name="password" placeholder="Choose your password (Min. length 6)"/></td>
                            </tr>
                            <tr>
                                <td><b><font color="#FF0000">*</font>Confirm Password:</b></td>
                                <td><input class="mo_saml_table_textbox" required type="password"
                                           name="confirmPassword" placeholder="Confirm your password"/></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><br><input type="submit" name="submit" value="Register"
                                               class="button button-primary button-large"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="button" name="mo_saml_goto_login" id="mo_saml_goto_login"
                                           value="Already have an account?" class="button button-primary button-large"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
            <form name="f1" method="post" action="" id="mo_saml_goto_login_form">
                <input type="hidden" name="option" value="mo_saml_goto_login"/>
            </form>
            <script>
                jQuery('#mo_saml_goto_login').click(function () {
                    jQuery('#mo_saml_goto_login_form').submit();
                });
            </script>
            <?php
        }


        function mo_saml_show_verify_password_page_saml() {
            ?>
            <!--Verify password with miniOrange-->
            <form name="f" method="post" action="">
                <input type="hidden" name="option" value="mo_saml_verify_customer"/>
                <div class="mo_saml_table_layout">
                    <div id="toggle1" class="panel_toggle">
                        <h3>Login with miniOrange</h3>
                    </div>
                    <div id="panel1">
                        <p><b>It seems you already have an account with miniOrange. Please enter your miniOrange email
                                and password.<br/> <a target="_blank"
                                                      href="https://auth.miniorange.com/moas/idp/resetpassword">Click
                                    here if you forgot your password?</a></b></p>
                        <br/>
                        <table class="mo_saml_settings_table">
                            <tr>
                                <td><b><font color="#FF0000">*</font>Email:</b></td>
                                <td><input class="mo_saml_table_textbox" type="email" name="email"
                                           required placeholder="person@example.com"
                                           value="<?php echo get_option( 'mo_saml_admin_email' ); ?>"/></td>
                            </tr>
                            <tr>
                                <td><b><font color="#FF0000">*</font>Password:</b></td>
                                <td><input class="mo_saml_table_textbox" required type="password"
                                           name="password" placeholder="Enter your password"/></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="submit" name="submit" value="Login"
                                           class="button button-primary button-large"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="button" name="mo_saml_goback" id="mo_saml_goback" value="Back"
                                           class="button button-primary button-large"/>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
            <form name="f" method="post" action="" id="mo_saml_goback_form">
                <input type="hidden" name="option" value="mo_saml_go_back"/>
            </form>
            <form name="f" method="post" action="" id="mo_saml_forgotpassword_form">
                <input type="hidden" name="option" value="mo_saml_forgot_password_form_option"/>
            </form>
            <script>
                jQuery('#mo_saml_goback').click(function () {
                    jQuery('#mo_saml_goback_form').submit();
                });
                jQuery("a[href=\"#mo_saml_forgot_password_link\"]").click(function () {
                    jQuery('#mo_saml_forgotpassword_form').submit();
                });
            </script>
            <?php
        }

        function mo_saml_show_otp_verification(){
        ?>
        <!-- Enter otp -->
        <form name="f" method="post" id="otp_form" action="">
            <input type="hidden" name="option" value="mo_saml_validate_otp"/>
            <div class="mo_saml_table_layout">
                <table class="mo_saml_settings_table">
                    <h3>Verify Your Email</h3>
                    <tr>
                        <td><b><font color="#FF0000">*</font>Enter OTP:</b></td>
                        <td colspan="2"><input class="mo_saml_table_textbox" autofocus="true" type="text"
                                               name="otp_token" required placeholder="Enter OTP" style="width:61%;"
                                               pattern="[0-9]{6,8}"/>
                            &nbsp;&nbsp;<a style="cursor:pointer;"
                                           onclick="document.getElementById('resend_otp_form').submit();">Resend OTP</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                    <tr>

                        <td>&nbsp;</td>
                        <td style="width:17%">
                            <input type="submit" name="submit" value="Validate OTP"
                                   class="button button-primary button-large"/></td>

        </form>
        <form name="f" method="post">
            <td style="width:18%">
                <input type="hidden" name="option" value="mo_saml_go_back"/>
                <input type="submit" name="submit" value="Back" class="button button-primary button-large"/>
            </td>
        </form>
        <form name="f" id="resend_otp_form" method="post" action="">
            <td>
                <?php if ( get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_EMAIL' ) { ?>
                    <input type="hidden" name="option" value="mo_saml_resend_otp_email"/>
                <?php } else { ?>
                    <input type="hidden" name="option" value="mo_saml_resend_otp_phone"/>
                <?php } ?>
            </td>

        </form>
        </tr>
        </table>
        <?php if ( get_option( 'mo_saml_registration_status' ) == 'MO_OTP_DELIVERED_SUCCESS_EMAIL' || get_option( 'mo_saml_registration_status' ) == 'MO_OTP_VALIDATION_FAILURE_EMAIL' ) { ?>
            <hr>

            <h3>I did not recieve any email with OTP . What should I do ?</h3>
            <form id="mo_saml_register_with_phone_form" method="post" action="">
                <input type="hidden" name="option" value="mo_saml_register_with_phone_option"/>
                If you can't see the email from miniOrange in your mails, please check your <b>SPAM</b> folder. If you
                don't see an email even in the SPAM folder, verify your identity with our alternate method.
                <br><br>
                <b>Enter your valid phone number here and verify your identity using one time passcode sent to your
                    phone.</b><br><br>
                <input class="mo_saml_table_textbox" type="tel" id="phone_contact" style="width:40%;"
                       pattern="[\+]\d{11,14}|[\+]\d{1,4}([\s]{0,1})(\d{0}|\d{9,10})" class="mo_saml_table_textbox"
                       name="phone"
                       title="Phone with country code eg. +1xxxxxxxxxx" required
                       placeholder="Phone with country code eg. +1xxxxxxxxxx"
                       value="<?php echo get_option( 'mo_saml_admin_phone' ); ?>"/>
                <br/><br/><input type="submit" value="Send OTP" class="button button-primary button-large"/>

            </form>
        <?php } ?>
    </div>

    <?php
}

function mo_saml_general_login_page() {

    ?>
    <?php if ( mo_saml_is_customer_registered_saml() ) { ?>
        <div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 2% 0px 2%;">
            <h3>Sign in options</h3>
            <input type="checkbox" style="background: #DCDAD1;" disabled/> <span style="color: red;">*</span>Redirect to
            IdP if user not logged in.
            <a href="#" id="registered_only_access">[What does this mean?]</a>
            <br>
            <div hidden id="registered_only_access_desc" class="mo_saml_help_desc">
                <span>Select this option if you want to restrict your site to only logged in users. Selecting this option will redirect the users to your IdP if logged in session is not found.</span>
            </div>
            <br/>
            <form id="mo_saml_force_authentication_form" method="post" action="">
                <input type="hidden" name="option" value="mo_saml_force_authentication_option"/>
                <input type="checkbox" name="mo_saml_force_authentication"
                       value="true" <?php if ( ! mo_saml_is_sp_configured() )
                    echo 'disabled title="Disabled. Configure your Service Provider"' ?> <?php checked( get_option( 'mo_saml_force_authentication' ) == 'true' ); ?>
                       onchange="document.getElementById('mo_saml_force_authentication_form').submit();"/> Force
                authentication with your IdP on each login attempt.
                <a href="#" id="force_authentication_with_idp">[What does this mean?]</a>
                <br>
                <div hidden id="force_authentication_with_idp_desc" class="mo_saml_help_desc">
                    <span>It will force user to provide credentials on your IdP on each login attempt even if the user is already logged in to IdP. This option may require some additional setting in your IdP to force it depending on your Identity Provider.</span>
                </div>
            </form>
            <br/>
            Choose how you want users to log into your WordPress website. You can choose any or all of the three options
            below.<br/><br/>
            <span style="font-size:15px;"><b>Option 1: Use Default WordPress LogIn</b></span>
            <div style="margin-left:17px;margin-top:2%;">
                <form id="mo_saml_enable_redirect_form" method="post" action="">
                    <input type="hidden" name="option" value="mo_saml_enable_login_redirect_option"/>
                    <?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                        <input type="checkbox" name="mo_saml_enable_login_redirect"
                               value="true" <?php if ( ! mo_saml_is_sp_configured() )
                            echo 'disabled title="Disabled. Configure your Service Provider"' ?> <?php checked( get_option( 'mo_saml_enable_login_redirect' ) == 'true' ); ?>
                               onchange="document.getElementById('mo_saml_enable_redirect_form').submit();"/> Check this option if you want to
                        <b>auto redirect the user to IdP</b>.
                        <a href="#" id="redirect_to_idp">[What does this mean?]</a>
                        <br>
                        <div hidden id="redirect_to_idp_desc" class="mo_saml_help_desc">
                            <span>Users visiting any of the following URLs will get redirected to your configured IdP for authentication:</span>
                            <br/><code><b><?php echo wp_login_url(); ?></b></code> or
                            <code><b><?php echo admin_url(); ?></b></code>
                        </div>
                    <?php } else { ?>
                        <input type="checkbox"
                               style="background: #DCDAD1;" <?php checked( get_option( 'mo_saml_allow_wp_signin' ) == 'true' ); ?>
                               disabled/> <span style="color: red;">*</span>Check this option if you want to <b>auto
                            redirect the user to IdP</b>.
                        <a href="#" id="redirect_to_idp">[What does this mean?]</a>
                        <br>
                        <div hidden id="redirect_to_idp_desc" class="mo_saml_help_desc">
                            <span>Users visiting any of the following URLs will get redirected to your configured IdP for authentication:</span>
                            <br/><code><b><?php echo wp_login_url(); ?></b></code> or
                            <code><b><?php echo admin_url(); ?></b></code>
                        </div>
                    <?php } ?>
                </form>
                <form id="mo_saml_allow_wp_signin_form" method="post" action="">
                    <input type="hidden" name="option" value="mo_saml_allow_wp_signin_option"/>
                    <p>
                        <?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                        <input type="checkbox" name="mo_saml_allow_wp_signin"
                               value="true" <?php checked( get_option( 'mo_saml_allow_wp_signin' ) == 'true' ); ?>
                               onchange="document.getElementById('mo_saml_allow_wp_signin_form').submit();"/> Checking
                        this option creates a backdoor to login to your Website using WordPress credentials incase you
                        get locked out of your IdP.
                        <i>(Note down this URL: <code><b><?php echo site_url(); ?>
                                    /wp-login.php?saml_sso=false</b></code> )</i>
                    <div style="background-color:#CBCBCB;padding:1%;">
                        <span style="color:#FF0000;">WARNING:</span> Checking the above option will <b>enable a security
                            hole</b>. Anybody knowing the above URL will be able to login to your website using
                        WordPress Credentials. <b>Please do not share this URL.</b>
                    </div>
                    <?php } else { ?>
                        <input type="checkbox"
                               style="background: #DCDAD1;" <?php checked( get_option( 'mo_saml_allow_wp_signin' ) == 'true' ); ?>
                               disabled/> <span
                                style="color: red;">*</span>Checking this option creates a backdoor to login to your Website using WordPress credentials incase you get locked out of your IdP.
                        <i>(Note down this URL: <code><b><?php echo site_url(); ?>
                                    /wp-login.php?saml_sso=false</b></code> )</i>
                    <?php } ?>
                    </p>
                </form>
            </div>
            <span style="font-size:15px;"><b>Option 2: Use a Widget</b></span>
            <div style="margin:2% 0 2% 17px;">
                <input type="checkbox" name="mo_saml_add_widget"
                       id="mo_saml_add_widget" <?php if ( ! mo_saml_is_sp_configured() )
                    echo 'disabled title="Disabled. Configure your Service Provider"' ?> value="true"> Check this option
                if you want to add a Widget to your page.
                <div id="mo_saml_add_widget_steps" hidden>
                    <ol>
                        <li>Go to Appearances > Widgets.</li>
                        <li>Select "Login with <?php echo get_option( 'saml_identity_name' ); ?>". Drag and drop to your
                            favourite location and save.
                        </li>
                    </ol>
                </div>
            </div>

            <span style="font-size:15px;"><b>Option 3: Use a ShortCode</b></span>
            <div style="margin:2% 0 2% 17px;">
                <?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                    <input type="checkbox" name="mo_saml_add_shortcode"
                           id="mo_saml_add_shortcode" <?php if ( ! mo_saml_is_sp_configured() )
                        echo 'disabled title="Disabled. Configure your Service Provider"' ?>
                           value="true"> Check this option if you want to add a shortcode to your page.
                    <div id="mo_saml_add_shortcode_steps" hidden>
                        <table>
                            <tr>
                                <td>For PHP page:</td>
                                <td><code>&lt;?php echo do_shortcode('[MO_SAML_FORM]'); ?&gt;</code></td>
                            </tr>
                            <tr>
                                <td style="display:block;width:100px">
                                    For HTML page:
                                </td>
                                <td><code><?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' ) { ?>
                                            &lt;a href="<?php echo site_url() . '/?option=saml_user_login' ?>"&gt;Login with IdP&lt;/a&gt;
                                        <?php } else { ?>
                                            &lt;a href="<?php echo get_option( 'mo_saml_host_name' ) . "/moas/rest/saml/request?id=" . get_option( 'mo_saml_admin_customer_key' ) . "&returnurl=" . urlencode( site_url() . '/?option=readsamllogin' ) ?>"/&gt;Login with IdP&lt;/a&gt;
                                        <?php } ?></code></td>
                            </tr>
                        </table>
                    </div>
                <?php } else { ?>
                    <input type="checkbox" style="background: #DCDAD1;"
                           disabled <?php if ( ! mo_saml_is_sp_configured() )
                        echo 'disabled title="Disabled. Configure your Service Provider"' ?> value="true"> <span
                            style="color: red">*</span>Check this option if you want to add a shortcode to your page.
                    <br/>
                <?php } ?>
            </div>
            <div style="display:block;text-align:center;margin:2%;">
                <input type="button"
                       onclick="window.location.href='<?php echo wp_logout_url( site_url() ); ?>'" <?php if ( ! mo_saml_is_sp_configured() )
                    echo 'disabled title="Disabled. Configure your Service Provider"' ?>
                       class="button button-primary button-large" value="Log Out and Test">
            </div>
            <?php if ( get_option( 'mo_saml_free_version' ) ) { ?>
                <span style="color:red;">*</span>These options are configurable in the <a
                        href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>standard,
                        premium and enterprise</b></a> version of the plugin.</h3>
                <br/><br/>
            <?php } ?>
        </div>
        <br/>
        <div style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
            <h3>Your Profile</h3>
            <table border="1"
                   style="background-color:#FFFFFF; border:1px solid #CCCCCC; border-collapse: collapse; padding:0px 0px 0px 10px; margin:2px; width:85%">
                <tr>
                    <td style="width:45%; padding: 10px;">miniOrange Account Email</td>
                    <td style="width:55%; padding: 10px;"><?php echo get_option( 'mo_saml_admin_email' ) ?></td>
                </tr>
                <tr>
                    <td style="width:45%; padding: 10px;">Customer ID</td>
                    <td style="width:55%; padding: 10px;"><?php echo get_option( 'mo_saml_admin_customer_key' ) ?></td>
                </tr>
            </table>
            <br/>
        </div>
    <?php }
}

function mo_saml_configuration_steps() {
    $sp_base_url = site_url();
    ?>
    <!-- <form  name="saml_form_am" method="post" action="" id="mo_saml_idp_config">-->
    <input type="hidden" name="option" value="mo_saml_idp_config"/>
    <div id="instructions_idp"></div>
    <table width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:2%;">
        <tr>
            <?php if ( ! mo_saml_is_customer_registered_saml() ) { ?>
                <td colspan="2">
                    <div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                        Please <a
                                href="<?php echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Register
                            or Login with miniOrange</a> to configure the miniOrange SAML Plugin.
                    </div>
                </td>
            <?php } ?>
        </tr>

        <tr>
            <td colspan="2">
                <div>
                    <?php if ( mo_saml_is_customer_registered_saml() ) { ?>
                        <form id="mo_saml_cloud_broker" method="post" action="">
                        <input type="hidden" name="option" value="mo_saml_enable_cloud_broker"/>
                        <p>

                            <!-- Edited In this Block. Adding the third option -->
                            <input type="radio" <?php checked( get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ); ?>
                                   id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker"
                                   value="miniorange"
                                   onchange="document.getElementById('mo_saml_cloud_broker').submit();">Use miniOrange
                            as an Identity Provider (IDP) ( <a href="#" id="help_working_title3">Click Here<a></a> to
                                know how the plugin works for this case. )<br/>

                                <input type="radio" <?php checked( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' ); ?>
                                       id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker" value="true"
                                       onchange="document.getElementById('mo_saml_cloud_broker').submit();">Use
                                miniOrange Identity broker service. ( <a href="#" id="help_working_title1">Click
                                    Here<a></a> to know how the plugin works for this case. )<br/>

                                    <input type="radio" <?php checked( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' || get_option( 'mo_saml_enable_cloud_broker' ) == '' ); ?>
                                           id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker"
                                           value="false"
                                           onchange="document.getElementById('mo_saml_cloud_broker').submit();">Use Your
                                    own Identity Provider ( <a href="#" id="help_working_title2">Click Here<a></a> to
                                        know how the plugin works for this case. )<br/>


                                        <!--    <input type="checkbox" id="mo_saml_enable_cloud_broker" name="mo_saml_enable_cloud_broker" value="true"  onchange="document.getElementById('mo_saml_cloud_broker').submit();" style="-webkit-appearance: radio; -moz-appearance: radio; -ms-appearance: radio;" /> Use <b>miniOrange Single Sign on service.</b>-->

                                        <!--OPTION-1-->

                                        <div hidden id="help_working_desc3" class="mo_saml_help_desc">
                                            <h3>Option 1 :Use miniOrange as an Identity Provider (IDP) :</h3>
                                            <div style="display:block;text-align:center;">
                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/saml_working_womo1.png' ?>"
                                                     alt="Working of miniOrange SAML plugin" style="width: 55%;"/>
                                            </div>
                                            <ol>
                                                <li>miniOrange SAML SSO plugin sends a login request to MiniOrange for
                                                    authentication.
                                                </li>
                                                <li>Upon successful authentication, MiniOrange sends a SAML Response
                                                    back to miniOrange SAML SSO plugin. Plugin then reads the response
                                                    and login the user.
                                                </li>
                                            </ol>
                                            <div>
                                                <b>Advantages:</b>
                                                <ol>
                                                    <li>The number of messages required for Single Sign On is less as
                                                        your website is directly interacting with the Identity Provider.
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>

                                        <!--OPTION-2-->

                                        <div hidden id="help_working_desc1" class="mo_saml_help_desc">
                                            <h3>Option 2: Use miniOrange Identity broker service:</h3>
                                            <div style="display:block;text-align:center;">
                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/saml_working.png' ?>"
                                                     alt="Working of miniOrange SAML plugin" style="width: 85%;"/>
                                            </div>
                                            <ol>
                                                <li>miniOrange SAML SSO plugin sends a login request to miniOrange SSO
                                                    Service.
                                                </li>
                                                <li>miniOrange SSO Service creates a SAML Request and redirects the user
                                                    to your Identity Provider for authentication.
                                                </li>
                                                <li>Upon successful authentication, your Identity Provider sends a SAML
                                                    Response back to miniOrange SSO Service.
                                                </li>
                                                <li>miniOrange SSO Service verifies the SAML Response and sends a
                                                    response status (along with the logged in user's information) back
                                                    to miniOrange SAML SSO plugin. Plugin then reads the response and
                                                    logins the user.
                                                </li>
                                            </ol>
                                            <div>
                                                <b>Advantages:</b>
                                                <ol>
                                                    <li>If you are an enterprise or business user then on using this
                                                        service you will be able to take full advantage of all of
                                                        miniOrange SSO features. ( For a complete list of these features
                                                        <a href="http://miniorange.com/single-sign-on-sso"
                                                           target="_blank">Click Here</a>)
                                                    </li>
                                                    <li>You can use Non-SAML Identity Providers for Single Sign On.</li>
                                                    <li>If you have multiple websites then you can use the same IdP
                                                        configuration for all of them. You don't have to make seperate
                                                        configurations in your IdP.
                                                    </li>
                                                    <li>Some Identity Providers like ADFS do not support HTTP endpoints
                                                        ( i.e. your wordpress site needs to be on HTTPS ). So, if your
                                                        wordpress site is not on HTTPS then you can use this service for
                                                        such IdPs.
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>

                                        <!--OPTION-3-->


                                        <div hidden id="help_working_desc2" class="mo_saml_help_desc">
                                            <h3>Option 3 : Use Your own Identity Provider:</h3>
                                            <div style="display:block;text-align:center;">
                                                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/saml_working_womo.png' ?>"
                                                     alt="Working of miniOrange SAML plugin" style="width: 55%;"/>
                                            </div>
                                            <ol>
                                                <li>miniOrange SAML SSO plugin sends a login request to your Identity
                                                    Provider for authentication.
                                                </li>
                                                <li>Upon successful authentication, your Identity Provider sends a SAML
                                                    Response back to miniOrange SAML SSO plugin. Plugin then reads the
                                                    response and login the user.
                                                </li>
                                            </ol>

                                            <div>
                                                <b>Advantages:</b>
                                                <ol>
                                                    <li>The number of messages required for Single Sign On is less as
                                                        your website is directly interacting with the Identity Provider.
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>


                                        <!-- Edited In this Block. Adding the third option -->
                        </p>

                        <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' && get_option( 'saml_x509_certificate' ) ){ ?>
                        <div style="background-color:#CBCBCB;padding:1%;">
                            You will have to follow the following steps after you change the option above:
                            <ol>
                                <li><b>ReConfigure your IdP settings</b>. Please refer the table below in <b>Step 1</b>
                                    for updated URLs that you would need.
                                </li>
                                <li>After configuring your IdP go to <a
                                            href="<?php echo admin_url() ?>admin.php?page=mo_saml_settings&tab=save">Service
                                        Provider Tab</a> and click on the <b>save button</b> to save your configuration.
                                </li>


                            </ol>
                            <?php }

                            /* edited here */
                            else if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ){ ?>
                            <?php }
                            else if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' ){ ?>
                            <div style="background-color:#CBCBCB;padding:1%;">
                                <?php if ( ! get_option( 'saml_x509_certificate' ) ) { ?>
                                    Please follow the following steps:
                                    <ol>
                                        <li>Configure your IdP. Refer the table below in <b>Step 1</b> and keep the
                                            information handy for <b>Step 2</b>.
                                        </li>
                                        <li>After configuring your IdP go to <a
                                                    href="<?php echo admin_url() ?>admin.php?page=mo_saml_settings&tab=save">Service
                                                Provider Tab</a> to configure and save your configuration.
                                        </li>
                                    </ol>
                                <?php } else { ?>
                                    You will have to follow the following steps after you change the above option:
                                    <ol>
                                        <li><b>ReConfigure your IdP settings</b>. Please refer the table below in <b>Step
                                                1</b> for updated URLs that you would need.
                                        </li>
                                        <li>After configuring your IdP go to <a
                                                    href="<?php echo admin_url() ?>admin.php?page=mo_saml_settings&tab=save">Service
                                                Provider Tab</a> and click on the <b>save button</b> to save your
                                            configuration.
                                        </li>


                                    </ol>
                                <?php } ?>
                                <?php } ?>
                            </div>

                        </form><?php } ?></div>
                <!--xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->

                <!--STEP-1-->
                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ){ ?>

                    <!-- miniOrange IdP Radio Key -->
                    <p>
                        Step by Step to Configure miniOrange IdP:</br>
                        <a href="http://miniorange.com/miniorange_as_idp_wordpress" target='_blank'>Click Here to see
                            the Guide for Configuring <b>miniOrange</b> as IdP.</a>
                    </p>

                    <h4>You will need the following information to configure your IdP. Copy it and keep it handy:</h4>
                    <table border="1"
                           style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px; margin:2px; border-collapse: collapse; width:98%">


                        <tr>
                            <td style="width:40%; padding: 15px;"><b>SP-EntityID / Issuer</b></td>
                            <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                <td style="width:60%; padding: 15px;">https://auth.miniorange.com/moas</td>
                            <?php } else { ?>
                                <td style="width:60%; padding: 15px;"><?php echo site_url() . '/wp-content/plugins/miniorange-saml-20-single-sign-on/'; ?></td>
                            <?php } ?>
                        </tr>


                        <tr>
                            <td style="width:40%; padding: 15px;"><b>ACS (AssertionConsumerService) URL</b></td>
                            <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ){ ?>
                                <td style="width:60%;  padding: 15px;">https://auth.miniorange.com/moas/rest/saml/acs
                                </td>
                            <?php }else{ ?>
                            <td style="width:60%;  padding: 15px;"><?php echo site_url() . '/' ?></td>
                        </tr>
                        <?php } ?>
                    </table>


                    <!--
                        <h3><b>Step 2:</b></h3><h4> Assuming that you are now done with Step 1, please note down the "X.509 certificate" from your IdP admin screen and keep it handy to configure your Service provider.</h4>


                        <h4>Link to Configure the Plug in:
                        <a href="http://miniorange.com/wordpress-single-sign-on-(sso)" target='_blank'>Click Here to see the Guide for Configuring the plugin</b></a></h4> -->


                <?php } else{ ?>

                <h3><b>Step 1</b>:
                    <h4>Link to Configure the Plug in:
                        <a href="https://miniorange.com/wordpress-single-sign-on-(sso)" target='_blank'>Click Here to
                            see the Guide for Configuring the plugin</b></a></b><h4>You will need the following
                            information to configure your IdP. Copy it and keep it handy:</h4>
                        <table border="1"
                               style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px; margin:2px; border-collapse: collapse; width:98%">


                            <tr>
                                <td style="width:40%; padding: 15px;"><b>SP-EntityID / Issuer</b></td>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                    <td style="width:60%; padding: 15px;">https://auth.miniorange.com/moas</td>
                                <?php } else { ?>
                                    <td style="width:60%; padding: 15px;"><?php echo site_url() . '/wp-content/plugins/miniorange-saml-20-single-sign-on/'; ?></td>
                                <?php } ?>
                            </tr>


                            <tr>
                                <td style="width:40%; padding: 15px;"><b>ACS (AssertionConsumerService) URL</b></td>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                    <td style="width:60%;  padding: 15px;">
                                        https://auth.miniorange.com/moas/rest/saml/acs
                                    </td>
                                <?php } else { ?>
                                    <td style="width:60%;  padding: 15px;"><?php echo site_url() . '/' ?></td>
                                <?php } ?>
                            </tr>


                            <tr>
                                <td style="width:40%; padding: 15px;"><b>Audience URI</b></td>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                    <td style="width:60%; padding: 15px;">https://auth.miniorange.com/moas</td>
                                <?php } else { ?>
                                    <td style="width:60%; padding: 15px;"><?php echo site_url() . '/wp-content/plugins/miniorange-saml-20-single-sign-on/'; ?></td>
                                <?php } ?>
                            </tr>


                            <tr>
                                <td style="width:40%; padding: 15px;"><b>NameID format</b></td>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                    <td style="width:60%; padding: 15px;">
                                        urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress
                                    </td>
                                <?php } else { ?>
                                    <td style="width:60%; padding: 15px;">
                                        urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress
                                    </td>
                                <?php } ?>
                            </tr>


                            <tr>
                                <td style="width:40%; padding: 15px;"><b>Recipient URL</b></td>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                    <td style="width:60%;  padding: 15px;">
                                        https://auth.miniorange.com/moas/rest/saml/acs
                                    </td>
                                <?php } else { ?>
                                    <td style="width:60%;  padding: 15px;"><?php echo site_url() . '/' ?></td>
                                <?php } ?>
                            </tr>


                            <tr>
                                <td style="width:40%; padding: 15px;"><b>Destination URL</b></td>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                    <td style="width:60%;  padding: 15px;">
                                        https://auth.miniorange.com/moas/rest/saml/acs
                                    </td>
                                <?php } else { ?>
                                    <td style="width:60%;  padding: 15px;"><?php echo site_url() . '/' ?></td>
                                <?php } ?>
                            </tr>


                            <?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                                <tr>
                                    <td style="width:40%; padding: 15px;"><b>Default Relay State (Optional)</b></td>
                                    <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'true' && mo_saml_is_customer_registered_saml() ) { ?>
                                        <td style="width:60%;  padding: 15px;"><?php echo site_url(); ?>
                                            ?option=readsamllogin&mId=<?php echo get_option( 'mo_saml_admin_customer_key' ) ?></td>
                                    <?php } else { ?>
                                        <td style="width:60%;  padding: 15px;"><?php echo site_url() . '/' ?></td>
                                    <?php } ?>
                                </tr>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) != 'true' ) { ?>
                                    <tr>
                                        <td style="width:40%; padding: 15px;"><b>Certificate (Optional)</b></td>
                                        <?php if ( ! mo_saml_is_customer_registered_saml() ) { ?>
                                            <td style="width:60%;  padding: 15px;">Download <i>(Register to download the
                                                    certificate)</i></td>
                                        <?php } else { ?>
                                            <td style="width:60%;  padding: 15px;"><a
                                                        href="<?php echo plugins_url( 'resources/sp-certificate.crt', __FILE__ ); ?>">Download</a>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td style="width:40%; padding: 15px;"><b>Default Relay State (Optional)</b></td>
                                    <td style="width:60%;  padding: 15px;">Available in the <a
                                                href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>standard,
                                                premium and enterprise</b></a> plans of the plugin.
                                    </td>
                                </tr>
                                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) != 'true' ) { ?>
                                    <tr>
                                        <td style="width:40%; padding: 15px;"><b>Certificate (Optional)</b></td>
                                        <td style="width:60%;  padding: 15px;">Available in the <a
                                                    href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>standard,
                                                    premium and enterprise</b></a> plans of the plugin.
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </table>


                        <?php
                        echo '<p style="text-align: center;font-size: 13pt;font-weight: bold;">OR</p>
                <p>Provide this metadata URL to your Identity Provider or open it and save as .xml file and upload it in your idp:</p>';
                        if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' ) {
                            echo '
                <code><b><a target="_blank" href="' . $sp_base_url . '/?option=mosaml_metadata">' . $sp_base_url . '/?option=mosaml_metadata</a></b></code>';
                        } else { ?>
                            <code><b><a target="_blank"
                                        href="https://auth.miniorange.com/moas/metadata/sp-metadata.xml">https://auth.miniorange.com/moas/metadata/sp-metadata.xml</a></b></code>
                        <?php }

                        ?>

            </td>
        </tr>

        <!--STEP-2-->

        <tr>
            <td colspan="2">
                <p>
                <h3><b>Step 2:</b></h3>
                With the help of information given in <b>Step 1</b>, configure your IdP. Then come back to <b>Step 3</b>.
                <br/>
                <h4>If you are looking for an Identity Provider,you can try out <a href="https://idp.miniorange.com"
                                                                                   target="_blank">miniOrange On-Premise
                        IdP</a></h4>
                <a href="http://miniorange.com/miniorange_as_idp_wordpress" target='_blank'>Click Here to see the Guide
                    for Configuring <b>miniOrange</b> as Cloud IdP.</a>
                </p>
                <div style="background-color:#CBCBCB;padding:5px;">We also have step by step <b>do-it-yourself
                        guides</b> available for all known IdPs like <b>ADFS, Centrify, Okta, OneLogin, OpenAM, Oracle
                        Identity Manager, JBoss Keycloak, Salesforce, Shibboleth, SimpleSAML, WSO2</b> etc. <a
                            href="<?php echo admin_url() . 'admin.php?page=mo_saml_settings&tab=licensing'; ?>">(
                        Supported in standard, premium and enterprise versions of the plugin. )</a></div>
                <br>
                If you can't find your Idp in this list, write us the name of your Idp via <b>support form</b> on right.

                <p><b>For more help, checkout the <a
                                href="<?php echo add_query_arg( array( 'tab' => 'help' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Help
                            section</a>.</b></p>
            </td>
        </tr>


        <!--STEP-3-->


        <tr>
            <td colspan="2">
                <h3><b>Step 3:</b></h3><h4> Assuming that you are now done with Step 2, please note down the following
                    information from your IdP admin screen and keep it handy to configure your Service provider.</h4>
                <ol>
                    <li><b>X.509 certificate</b></li>
                    <li><b>SAML Login URL (Single Sign On URL)</b></li>
                    <li><b>IdP Entity ID (IdP Issuer)</b></li>
                    <li><b>Is Response signed</b> by your IdP?</li>
                    <li><b>Is Assertion signed</b> by your IdP?</li>
                    </li>
                </ol>
                <a href="#" id="idp_details_link">[ Cannot find the above information? ]</a>
                <div hidden id="idp_details_desc" class="mo_saml_help_desc">
                    <ol>
                        <li>X.509 certificate is enclosed in <code>X509Certificate</code> tag in IdP-Metadata XML file.
                            (parent tag: <code>KeyDescriptor use="signing"</code>)
                        </li>
                        <li>SAML Login URL is enclosed in <code>SingleSignOnService</code> tag (Binding type:
                            HTTP-Redirect) in IdP-Metadata XML file.
                        </li>
                        <li>EntityID is the value of the <code>entityID</code> attribute of
                            <code>EntityDescriptor</code> tag in IdP-Metadata XML file.
                        </li>
                    </ol>
                    Still Cannot find the above information?<br/> You can contact us using the support form on the right
                    and we will help you.
                </div>
                <br/><br/>
                <input type="checkbox" <?php if ( ! mo_saml_is_customer_registered_saml() )
                    echo 'disabled' ?>
                       onchange="window.location='<?php echo admin_url(); ?>/admin.php?page=mo_saml_settings&tab=save'"/>
                Check this option if you have the above information. You will be redirected to configure the Service
                Provider.
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php
}

function mo_saml_apps_config_saml() {
    $sync_interval                   = get_option( 'saml_metadata_sync_interval' );
    $sync_url                        = get_option( 'saml_metadata_url_for_sync' );
    $sync_selected                   = ! empty( $sync_url ) ? 'checked' : '';
    $hidden                          = empty( $sync_url ) ? 'hidden' : '';
    $saml_identity_metadata_provider = '';
    if ( isset( $_GET['action'] ) && $_GET['action'] == 'upload_metadata' ) {
        echo '<div border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
        <table style="width:100%;">
            <tr>
                <td colspan="3">
                    <h3>Upload IDP Metadata
                        <span style="float:right;margin-right:25px;">
                            <a href="' . admin_url() . 'admin.php?page=mo_saml_settings&tab=save' . '"><input type="button" class="button" value="Cancel"/></a>
                        </span>
                    </h3>
                </td>
            </tr><tr><td colspan="4"><hr></td></tr>
            <tr>';

        echo '
            <form name="saml_form" method="post" action="' . admin_url() . 'admin.php?page=mo_saml_settings&tab=save' . '" enctype="multipart/form-data">
            
                <tr>
                <td width="30%"><strong>Identity Provider Name<span style="color:red;">*</span>:</strong></td>
                <td><input type="text" name="saml_identity_metadata_provider" placeholder="Identity Provider name like ADFS, SimpleSAML" style="width: 100%;" value="" required /></td>
                </tr>';
        echo '
                
                <tr>';

        echo '
                <input type="hidden" name="option" value="saml_upload_metadata" />
                <input type="hidden" name="action" value="upload_metadata" />
            
                    <td>Upload Metadata  :</td>
                    <td colspan="2"><input type="file" name="metadata_file" />
                    <input type="submit" class="button button-primary button-large" value="Upload"/></td>
                    </tr>';
        echo '<tr>
                <td colspan="2"><p style="font-size:13pt;text-align:center;"><b>OR</b></p></td>
            </tr>';
        echo '
            
            <tr>
                <input type="hidden" name="option" value="saml_upload_metadata" />
                <input type="hidden" name="action" value="fetch_metadata" />
                <td width="20%">Enter metadata URL:</td>
                <td><input type="url" name="metadata_url" placeholder="Enter metadata URL of your IdP." style="width:100%" value="' . $sync_url . '"/></td>
                <td width="20%">&nbsp;&nbsp;<input type="submit" class="button button-primary button-large" value="Fetch Metadata"/></td>
            </tr>
            </form>';
        echo '</table><br /></div>';


    } else {
        global $wpdb;
        $entity_id = get_option( 'entity_id' );
        if ( ! $entity_id ) {
            $entity_id = 'https://auth.miniorange.com/moas';
        }
        $sso_url = get_option( 'sso_url' );
        $cert_fp = get_option( 'cert_fp' );

        //Broker Service
        $saml_identity_name    = get_option( 'saml_identity_name' );
        $saml_login_url        = get_option( 'saml_login_url' );
        $saml_issuer           = get_option( 'saml_issuer' );
        $saml_x509_certificate = maybe_unserialize( get_option( 'saml_x509_certificate' ) );
        $saml_x509_certificate = ! is_array( $saml_x509_certificate ) ? array( 0 => $saml_x509_certificate ) : $saml_x509_certificate;
        $saml_response_signed  = get_option( 'saml_response_signed' );
        if ( $saml_response_signed == null ) {
            $saml_response_signed = 'checked';
        }
        $saml_assertion_signed = get_option( 'saml_assertion_signed' );
        if ( $saml_assertion_signed == null ) {
            $saml_assertion_signed = 'Yes';
        }

        $idp_config = get_option( 'mo_saml_idp_config_complete' );
        ?>
        <form width="98%" border="0"
              style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;" name="saml_form"
              method="post" action="">
            <input type="hidden" name="option" value="login_widget_saml_save_settings"/>
            <table style="width:100%;">
                <tr>
                    <td colspan="2">
                        <h3>Configure Service Provider
                            <span style="float:right;margin-right:30px;"><a
                                        href="<?php echo admin_url() ?>admin.php?page=mo_saml_settings&tab=save&action=upload_metadata"><input
                                            type="button" class="button button-primary button-large"
                                            value="Upload IDP Metadata"
                                        <?php
                                        if ( ! mo_saml_is_customer_registered_saml() ) {
                                            echo "disabled";
                                        }
                                        ?>

                                    /></a>&nbsp &nbsp
                </span></h3>
                </tr>
                <tr>
                    <td colspan="4">
                        <hr>
                    </td>
                </tr>
                <?php if ( ! mo_saml_is_customer_registered_saml() ) { ?>
                    <tr>
                        <td colspan="2">
                            <div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                                Please <a
                                        href="<?php echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Register
                                    or Login with miniOrange</a> to configure the miniOrange SAML Plugin.
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ( ! $idp_config && mo_saml_is_customer_registered_saml() ) { ?>
                    <!--<tr>
                    <td colspan="2"><div style="display:block;color:red;background-color:rgba(251, 251, 0, 0.43);padding:5px;border:solid 1px yellow;">You skipped a step. Please complete your Identity Provider configuration before you can enter the fields given below. If you have already completed your IdP configuration, please confirm on <a href="<?php //echo add_query_arg( array('tab' => 'config'), $_SERVER['REQUEST_URI'] ); ?>">Configure Identity Provider</a> page to remove this warning.</div></td>
                </tr>-->
                <?php } ?>
                <tr>
                    <td colspan="2">Enter the information gathered from your Identity Provider<br/><br/></td>
                </tr>
                <?php if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ) { ?>
                    <tr>
                        <td colspan="2"><b>Note</b> : On miniOrange IdP, Go to <code>Apps</code> -> <code>Manage
                                Apps</code> and click on <code> metadata</code> link given beside your WordPress SAML
                            App to download IdP-Metadata XML file
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="width:200px;"><strong>Identity Provider Name <span style="color:red;">*</span>:</strong>
                    </td>
                    <td><input type="text" name="saml_identity_name"
                               placeholder="Identity Provider name like ADFS, SimpleSAML, Salesforce"
                               style="width: 95%;" value="<?php echo $saml_identity_name; ?>"
                               required <?php if ( ! mo_saml_is_customer_registered_saml() )
                            echo 'disabled' ?> title="Only alphabets, numbers and underscore is allowed"/></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>IdP Entity ID or Issuer <span style="color:red;">*</span>:</strong></td>
                    <td><input type="text" name="saml_issuer" placeholder="Identity Provider Entity ID or Issuer"
                               style="width: 95%;" value="<?php echo $saml_issuer; ?>"
                               required <?php if ( ! mo_saml_is_customer_registered_saml() )
                            echo 'disabled' ?>/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><b>Note</b> : You can find the <b>EntityID</b> in Your IdP-Metadata XML file enclosed in <code>EntityDescriptor</code>
                        tag having attribute as <code>entityID</code></td>

                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td><strong>SAML Login URL <span style="color:red;">*</span>:</strong></td>
                    <td><input type="url" name="saml_login_url"
                               placeholder="Single Sign On Service URL (HTTP-Redirect binding) of your IdP"
                               style="width: 95%;" value="<?php echo $saml_login_url; ?>"
                               required <?php if ( ! mo_saml_is_customer_registered_saml() )
                            echo 'disabled' ?>/></td>
                </tr>

                <tr>
                    <td></td>

                    <td><b>Note</b> : You can find the <b>SAML Login URL</b> in Your IdP-Metadata XML file enclosed in
                        <code>SingleSignOnService</code> tag (Binding type: HTTP-Redirect)


                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>
                <?php
                foreach ( $saml_x509_certificate as $key => $value ) {
                    echo '<tr>
                <td><strong>X.509 Certificate <span style="color:red;">*</span>:</strong></td>
                <td><textarea rows="6" cols="5" name="saml_x509_certificate[' . $key . ']" placeholder="Copy and Paste the content from the downloaded certificate or copy the content enclosed in X509Certificate tag (has parent tag KeyDescriptor use=signing) in IdP-Metadata XML file" style="width: 95%;"';
                    echo ' >' . $value . '</textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><b>NOTE:</b> Format of the certificate:<br/><b>-----BEGIN CERTIFICATE-----<br/>XXXXXXXXXXXXXXXXXXXXXXXXXXX<br/>-----END CERTIFICATE-----</b></i><br/>
                </tr>';
                }


                ?>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><br/><input type="submit" name="submit" style="width:150px;margin-right: 3%;" value="Save"
                                    class="button button-primary button-large"<?php if ( ! mo_saml_is_customer_registered_saml() )
                            echo 'disabled' ?>/>
                        <input type="button" name="test"
                               title="You can only test your Configuration after saving your Service Provider Settings."
                               onclick="showTestWindow();" <?php if ( ! mo_saml_is_sp_configured() || ! get_option( 'saml_x509_certificate' ) )
                            echo 'disabled' ?> value="Test configuration" class="button button-primary button-large"
                               style="margin-right: 3%;width: 150px;"/>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><br/>
                        <input type="button" name="saml_request"
                               title="You can only view your SAML request after saving your Service Provider Settings."
                               onclick="showSAMLRequest()"';
                        <?php if ( ! mo_saml_is_sp_configured() || ! get_option( 'saml_x509_certificate' ) ) {
                            echo 'disabled';
                        } ?> value="Show SAML Request" class="button button-primary button-large" style="margin-right:
                        3%;width:150px"/>

                        <input type="button" name="saml_response"
                               title="You can only view your SAML response after saving your Service Provider Settings."
                               onclick="showSAMLResponse();"';
                        <?php if ( ! mo_saml_is_sp_configured() || ! get_option( 'saml_x509_certificate' ) ) {
                            echo 'disabled';
                        } ?> value="Show SAML Response" class="button button-primary button-large" style="margin-right:
                        3%;width:150px"/>
                    </td>
                </tr>
            </table>
            <br/>
        </form>
        <script>
            function showTestWindow() {
                var myWindow = window.open("<?php echo mo_saml_get_test_url(); ?>", "TEST SAML IDP", "scrollbars=1 width=800, height=600");
            }

            function showSAMLRequest() {
                var myWindow = window.open("<?php echo mo_saml_get_saml_request_url();?>", "VIEW SAML REQUEST", "scrollbars=1 width=800, height=600");
            }

            function showSAMLResponse() {
                var myWindow = window.open("<?php echo mo_saml_get_saml_response_url();?>", "VIEW SAML RESPONSE", "scrollbars=1 width=800, height=600");
            }
        </script>
        <?php
    }
}


function mo_saml_save_optional_config() {
    global $wpdb;
    $entity_id = get_option( 'entity_id' );
    if ( ! $entity_id ) {
        $entity_id = 'https://auth.miniorange.com/moas';
    }
    $sso_url = get_option( 'sso_url' );
    $cert_fp = get_option( 'cert_fp' );

    $saml_identity_name = get_option( 'saml_identity_name' );

    //Attribute mapping
    $saml_am_username = get_option( 'saml_am_username' );
    if ( $saml_am_username == null ) {
        $saml_am_username = 'NameID';
    }
    $saml_am_email = get_option( 'saml_am_email' );
    if ( $saml_am_email == null ) {
        $saml_am_email = 'NameID';
    }
    $saml_am_first_name = get_option( 'saml_am_first_name' );
    $saml_am_last_name  = get_option( 'saml_am_last_name' );
    $saml_am_group_name = get_option( 'saml_am_group_name' );
    ?>
    <form name="saml_form_am" method="post" action="">
        <input type="hidden" name="option" value="login_widget_saml_attribute_mapping"/>
        <table width="98%" border="0"
               style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
            <tr>
                <td colspan="2">
                    <h3>Attribute Mapping (Optional)</h3>
                </td>
            </tr>
            <?php if ( ! mo_saml_is_customer_registered_saml() ) { ?>
                <tr>
                    <td colspan="2">
                        <div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                            Please <a
                                    href="<?php echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Register
                                or Login with miniOrange</a> to configure the miniOrange SAML Plugin.
                        </div>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2">[ <a href="#" id="attribute_mapping">Click Here</a> to know how this is useful. ]
                    <div hidden id="attribute_mapping_desc" class="mo_saml_help_desc">
                        <ol>
                            <li>Attributes are user details that are stored in your Identity Provider.</li>
                            <li>Attribute Mapping helps you to get user attributes from your IdP and map them to
                                WordPress user attributes like firstname, lastname etc.
                            </li>
                            <li>While auto registering the users in your WordPress site these attributes will
                                automatically get mapped to your WordPress user details.
                            </li>
                        </ol>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br/><b>NOTE: </b>Use attribute name <code>NameID</code> if Identity is in the <i>NameIdentifier</i>
                    element of the subject statement in SAML Response.<br/><br/></td>
            </tr>

            <tr>
                <td style="width:200px;"><strong>Login/Create Wordpress account by: </strong></td>
                <td><select name="saml_am_account_matcher"
                            id="saml_am_account_matcher" <?php if ( ! mo_saml_is_customer_registered_saml() )
                        echo 'disabled' ?>>
                        <option value="email"<?php if ( get_option( 'saml_am_account_matcher' ) == 'email' ) {
                            echo 'selected="selected"';
                        } ?> >Email
                        </option>
                        <option value="username"<?php if ( get_option( 'saml_am_account_matcher' ) == 'username' ) {
                            echo 'selected="selected"';
                        } ?> >Username
                        </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><i>Users in Wordpress will be searched (existing wordpress users) or created (new users) based on
                        this attribute. Use Email by default.</i></td>
            </tr>
            <?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                <tr>
                    <td style="width:150px;"><strong>Username <span style="color:red;">*</span>:</strong></td>
                    <td><input type="text" name="saml_am_username" placeholder="Enter attribute name for Username"
                               style="width: 350px;" value="<?php echo $saml_am_username; ?>"
                               required <?php if ( ! mo_saml_is_customer_registered_saml() )
                            echo 'disabled' ?>/></td>
                </tr>
                <tr>
                    <td><strong>Email <span style="color:red;">*</span>:</strong></td>
                    <td><input type="text" name="saml_am_email" placeholder="Enter attribute name for Email"
                               style="width: 350px;" value="<?php echo $saml_am_email; ?>"
                               required <?php if ( ! mo_saml_is_customer_registered_saml() )
                            echo 'disabled' ?>/></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td style="width:150px;"><span style="color:red;">*</span><strong>Username (required):</strong></td>
                    <td><b>NameID</b></td>
                </tr>
                <tr>
                    <td><span style="color:red;">*</span><strong>Email (required):</strong></td>
                    <td><b>NameID</b></td>
                </tr>
            <?php } ?>
            <tr>
                <td><strong>First Name:</strong></td>
                <td><input type="text" name="saml_am_first_name" placeholder="Enter attribute name for First Name"
                           style="width: 350px;"
                           value="<?php echo $saml_am_first_name; ?>" <?php if ( ! mo_saml_is_customer_registered_saml() )
                        echo 'disabled' ?>/></td>
            </tr>
            <tr>
                <td><strong>Last Name:</strong></td>
                <td><input type="text" name="saml_am_last_name" placeholder="Enter attribute name for Last Name"
                           style="width: 350px;"
                           value="<?php echo $saml_am_last_name; ?>" <?php if ( ! mo_saml_is_customer_registered_saml() )
                        echo 'disabled' ?>/></td>
            </tr>
            <?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                <tr>
                    <td><strong>Group/Role:</strong></td>
                    <td><input type="text" name="saml_am_group_name" placeholder="Enter attribute name for Group/Role"
                               style="width: 350px;"
                               value="<?php echo $saml_am_group_name; ?>" <?php if ( ! mo_saml_is_customer_registered_saml() )
                            echo 'disabled' ?>/></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td><span style="color:red;">*</span><strong>Group/Role:</strong></td>
                    <td><input type="text" disabled placeholder="Enter attribute name for Group/Role"
                               style="width: 350px;background: #DCDAD1;"/></td>
                </tr>
                <tr>
                    <td colspan="2"><br/><span style="color:red;">*</span>Username and Email are configurable in <a
                                href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>standard,
                                premium and enterprise</b></a> versions of the plugin.<br/>Group/Role is configurable in
                        <a href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>premium
                                and enterprise</b></a> versions of the plugin.<br/>Customized Attribute Mapping is
                        configurable in the <a
                                href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>premium
                                and enterprise</b></a> versions of the plugin. Customized Attribute Mapping means you
                        can map any attribute of the IDP to the attributes of <b>user-meta</b> table of your database.
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td><br/><input type="submit" style="width:100px;" name="submit" value="Save"
                                class="button button-primary button-large" <?php if ( ! mo_saml_is_customer_registered_saml() )
                        echo 'disabled' ?>/> &nbsp;
                    <br/><br/>
                </td>
            </tr>
        </table>
    </form>
    <br/>
    <form name="saml_form_am_role_mapping" method="post" action="">
        <input type="hidden" name="option" value="login_widget_saml_role_mapping"/>
        <table width="98%" border="0"
               style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
            <tr>
                <td colspan="2">
                    <h3>Role Mapping (Optional)</h3>
                </td>
            </tr>
            <tr>
                <td colspan="2">[ <a href="#" id="role_mapping">Click Here</a> to know how this is useful. ]
                    <div hidden id="role_mapping_desc" class="mo_saml_help_desc">
                        <ol>
                            <li>WordPress uses a concept of Roles, designed to give the site owner the ability to
                                control what users can and cannot do within the site.
                            </li>
                            <li>WordPress has six pre-defined roles: Super Admin, Administrator, Editor, Author,
                                Contributor and Subscriber.
                            </li>
                            <li>Role mapping helps you to assign specific roles to users of a certain group in your
                                IdP.
                            </li>
                            <li>While auto registering, the users are assigned roles based on the group they are mapped
                                to.
                            </li>
                        </ol>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br/><b>NOTE: </b>Role will be assigned only to new users. Existing Wordpress users'
                    role remains same.<br/><br/></td>
            </tr>
            <tr>
                <td colspan="2"><input type="checkbox" disabled style="background: #DCDAD1;"/>&nbsp;&nbsp;<span
                            style="color:red;">*</span>Do not auto create users if roles are not mapped here.<br/></td>
            </tr>
            <?php if ( ! get_option( 'mo_saml_free_version' ) ) { ?>
                <tr>
                    <td colspan="2"><input type="checkbox" id="dont_allow_unlisted_user_role"
                                           name="saml_am_dont_allow_unlisted_user_role"
                                           value="checked" <?php echo get_option( 'saml_am_dont_allow_unlisted_user_role' ); ?> <?php if ( ! mo_saml_is_customer_registered_saml() ) {
                            echo "disabled";
                        } ?> />&nbsp;&nbsp;Do not assign role to unlisted users.<br/><br/></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <td colspan="2"><input type="checkbox" style="background: #DCDAD1;" disabled/>&nbsp;&nbsp;<span
                                style="color:red;">*</span>Do not assign role to unlisted users.<br/><br/></td>
                </tr>
            <?php } ?>
            <tr>
                <td><strong>Default Role:</strong></td>
                <td>
                    <?php
                    $disabled = '';
                    if ( ! mo_saml_is_customer_registered_saml() ) {
                        $disabled = 'disabled';
                    }
                    ?>
                    <select id="saml_am_default_user_role" name="saml_am_default_user_role" <?php echo $disabled ?>
                            style="width:150px;">
                        <?php
                        $default_role = get_option( 'saml_am_default_user_role' );
                        if ( empty( $default_role ) ) {
                            $default_role = get_option( 'default_role' );
                        }
                        echo wp_dropdown_roles( $default_role );
                        ?>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;<i>Select the default role to assign to Users.</i>
                </td>
            </tr>
            <?php
            $is_disabled = "";
            if ( ! mo_saml_is_customer_registered_saml() ) {
                $is_disabled = "disabled";
            }
            $wp_roles         = new WP_Roles();
            $roles            = $wp_roles->get_names();
            $roles_configured = get_option( 'saml_am_role_mapping' );
            foreach ( $roles as $role_value => $role_name ) {
                if ( ! get_option( 'mo_saml_free_version' ) ) {
                    echo '<tr><td><b>' . $role_name . '</b></td><td><input type="text" name="saml_am_group_attr_values_' . $role_value . '" value="' . $roles_configured[ $role_value ] . '" placeholder="Semi-colon(;) separated Group/Role value for ' . $role_name . '" style="width: 400px;"' . $is_disabled . ' /></td></tr>';
                } else {
                    echo '<tr><td><span style="color:red;">*</span><b>' . $role_name . '</b></td><td><input type="text" placeholder="Semi-colon(;) separated Group/Role value for ' . $role_name . '" style="width: 400px;background: #DCDAD1" disabled /></td></tr>';
                }
            }
            ?>
            <?php if ( get_option( 'mo_saml_free_version' ) ) { ?>
                <tr>
                    <td colspan="2"><br/><span style="color:red;">*</span>Customized Role Mapping options are
                        configurable in the <a
                                href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>premium
                                and enterprise</b></a> versions of the plugin. In the <a
                                href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>standard</b></a>
                        version, you can only assign the default role to the user.
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>&nbsp;</td>
                <td><br/><input type="submit" style="width:100px;" name="submit" value="Save"
                                class="button button-primary button-large" <?php if ( ! mo_saml_is_customer_registered_saml() )
                        echo 'disabled' ?>/> &nbsp;
                    <br/><br/>
                </td>
            </tr>
        </table>
    </form>
    <?php
}

function mo_saml_proxy() {
    ?>
    <form width="98%" border="0" style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;"
          name="saml_form" method="post" action="">
        <input type="hidden" name="option" value="mo_saml_save_proxy_setting"/>
        <table style="width:100%;">
            <tr>
                <td colspan="2">
                    <h3>Configure Proxy Server
                    </h3>
                </td>
            </tr>
            <?php if ( ! mo_saml_is_customer_registered_saml() ) { ?>
                <tr>
                    <td colspan="2">
                        <div style="display:block;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                            Please <a
                                    href="<?php echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Register
                                or Login with miniOrange</a> to configure the miniOrange SAML Plugin.
                        </div>
                    </td>
                </tr>
            <?php } ?>

            <tr>
                <td colspan="2">Enter the information to setup the proxy server.<br/><br/></td>
            </tr>
            <tr>
                <td style="width:200px;"><strong>Proxy Host Name:</strong></td>
                <td><input type="text" name="mo_proxy_host" placeholder="Enter the host name" style="width: 95%;"
                           value="<?php echo get_option( "mo_proxy_host" ); ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Port Number:</strong></td>
                <td><input type="text" name="mo_proxy_port" placeholder="Enter the port number of the proxy"
                           style="width: 95%;" value="<?php echo get_option( "mo_proxy_port" ); ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Username:</strong></td>
                <td><input type="text" name="mo_proxy_username" placeholder="Enter the username of proxy server"
                           style="width: 95%;" value="<?php echo get_option( "mo_proxy_username" ); ?>"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Password:</strong></td>
                <td><input type="password" name="mo_proxy_password" placeholder="Enter the password of proxy server"
                           style="width: 95%;" value="<?php echo get_option( "mo_proxy_password" ); ?>"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><br/><input type="submit" name="submit" style="width:100px;" value="Save"
                                class="button button-primary button-large"/> &nbsp;
                </td>
            </tr>
        </table>
        <br/>
        <br/><br/>
    </form>
    <?php
}

function mo_saml_save_plugin_config() {
    ?>
    <form>
        <div id="instructions_idp"></div>
        <table width="98%" border="0"
               style="background-color:#FFFFFF; border:1px solid #CCCCCC; padding:0px 0px 0px 10px;">
            <tr>
                <?php if ( ! mo_saml_is_customer_registered_saml() ) { ?>
                    <td colspan="2">
                        <div style="display:block;margin-top:10px;color:red;background-color:rgba(251, 232, 0, 0.15);padding:5px;border:solid 1px rgba(255, 0, 9, 0.36);">
                            Please <a
                                    href="<?php echo add_query_arg( array( 'tab' => 'login' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Register
                                or Login with miniOrange</a> to configure the miniOrange SAML Plugin.
                        </div>
                    </td>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="2">
                    <br/>
                    <p style="font-size:13px;">miniOrange SAML SSO Plugin enables login to WordPress through your
                        Identity Provider. <br/>We support all known IdPs - <b>ADFS, Okta, Salesforce, Shibboleth,
                            SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium etc</b>. If you
                        need detailed instructions on setting up these IdPs, we can give you step by step instructions.
                        Contact us using the support form on the right.</p>
                    <h3>Frequently Asked Questions</h3>
                    <table class="mo_saml_help">
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_steps_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">Instructions to use miniOrange SAML plugin</div>
                                </div>
                                <div hidden id="help_steps_desc" class="mo_saml_help_desc">
                                    <ul>
                                        <li>Step 1:&nbsp;&nbsp;&nbsp;&nbsp;Configure your Identity Provider by following
                                            <a href="<?php echo add_query_arg( array( 'tab' => 'config' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">these
                                                steps</a>.
                                        </li>
                                        <li>Step 2:&nbsp;&nbsp;&nbsp;&nbsp;Download X.509 certificate from your Identity
                                            Provider.
                                        </li>
                                        <li>Step 3:&nbsp;&nbsp;&nbsp;&nbsp;Enter appropriate values in the fields in <a
                                                    href="<?php echo add_query_arg( array( 'tab' => 'save' ), htmlentities( $_SERVER['REQUEST_URI'] ) ); ?>">Configure
                                                Service Provider</a>.
                                        </li>
                                        <li>Step 4:&nbsp;&nbsp;&nbsp;&nbsp;After saving your configuration, you will be
                                            able to test your configuration using the <b>Test &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Configuration</b>
                                            button on the top of the page.
                                        </li>
                                        <li>Step 5:&nbsp;&nbsp;&nbsp;&nbsp;Add "Login to &lt;IdP&gt;" widget to your
                                            WordPress page.
                                        </li>
                                    </ul>
                                    For any further queries, please contact us.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_widget_steps_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">Add login Link to post/page/blog</div>
                                </div>
                                <div hidden id="help_widget_steps_desc" class="mo_saml_help_desc">
                                    <ol>
                                        <li>Go to Appearances > Widgets.</li>
                                        <li>Select "Login with &lt;Identity Provider&gt;". Drag and drop to your
                                            favourite location and save.
                                        </li>
                                    </ol>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_faq_idp_config_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">I logged in to my Identity Provider and it
                                        redirected to WordPress, but I'm not logged in. There was an error - "We could
                                        not sign you in.".
                                    </div>
                                </div>
                                <div hidden id="help_faq_idp_config_desc" class="mo_saml_help_desc">
                                    To know what actually went wrong,
                                    <ol>
                                        <li>Login to you Wordpress administrator account. And go miniOrange SAML SSO
                                            plugin's Configure Service Provider tab.
                                        </li>
                                        <li>Click on <b>Test Configuration</b>. A popup window will open (make sure you
                                            popup enabled in your browser).
                                        </li>
                                        <li>Click on <b>Login</b> button. You will be redirected to your IdP for
                                            authentication.
                                        </li>
                                        <li>On successful authentication, You will be redirect back with the actual
                                            error message.
                                        </li>
                                        <li>Here are the some frequent errors:
                                            <ul><br/>
                                                <li><b>INVALID_ISSUER</b>: This means that you have NOT entered the
                                                    correct Issuer or Entity ID value provided by your Identity
                                                    Provider. You'll see in the error message what was the expected
                                                    value (that you have configured) and what actually found in the SAML
                                                    Response.
                                                </li>
                                                <li><b>INVALID_AUDIENCE</b>: This means that you have NOT configured
                                                    Audience URL in your Identity Provider correctly. It must be set to
                                                    <b>https://auth.miniorange.com/moas/rest/saml/acs</b> in your
                                                    Identity Provider.
                                                </li>
                                                <li><b>INVALID_DESTINATION</b>: This means that you have NOT configured
                                                    Destination URL in your Identity Provider correctly. It must be set
                                                    to <b>https://auth.miniorange.com/moas/rest/saml/acs</b> in your
                                                    Identity Provider.
                                                </li>
                                                <li><b>INVALID_SIGNATURE</b>: This means that the certificate you
                                                    provided did NOT match the certificate found in the SAML Response.
                                                    Make sure you provide the same certificate that you downloaded from
                                                    your IdP. If you have your IdP's Metadata XML file then make sure
                                                    you provide certificate enclosed in X509Certificate tag which has an
                                                    attribute <b>use="signing"</b>.
                                                </li>
                                                <li><b>INVALID_CERTIFICATE</b>: This means that the certificate you
                                                    provided is NOT in proper format. Make sure you have copied the
                                                    entire certificate provided by your IdP. If coiped from IdP's
                                                    Metadata XML file, make sure that you copied the entire value.
                                                </li>
                                            </ul>
                                    </ol>
                                    If you need help resolving the issue, please contact us using the support form and
                                    we will get back to you shortly.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_faq_idp_redirect_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">I clicked on login link but I cannot see the login
                                        page of my Identity Provider.
                                    </div>
                                </div>
                                <div hidden id="help_faq_idp_redirect_desc" class="mo_saml_help_desc">
                                    This could mean that the <b>SAML Login URL</b> you have entered is not correct.
                                    Please enter the correct <b>SAML Login URL</b> (with HTTP-Redirect binding) provided
                                    by your Identity Provider. <br/><br/>If the problem persists, please contact us
                                    using the support form. It would be helpful if you could share your Identity
                                    Provider details with us.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_faq_404_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">I'm getting a 404 error page when I try to login.
                                    </div>
                                </div>
                                <div hidden id="help_faq_404_desc" class="mo_saml_help_desc">
                                    This could mean that you have not entered the correct <b>SAML Login URL</b>. Please
                                    enter the correct <b>SAML Login URL</b> (with HTTP-Redirect binding) provided by
                                    your Identity Provider and try again.<br/><br/>If the problem persists, please
                                    contact us using the support form. It would be helpful if you could share your
                                    Identity Provider details with us.
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_curl_enable_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">How to enable PHP cURL extension?</div>
                                </div>
                                <div hidden id="help_curl_enable_desc" class="mo_saml_help_desc">
                                    <ol>
                                        <li>Open php.ini file located under php installation folder.</li>
                                        <li>Search for extension=php_curl.dll.</li>
                                        <li>Uncomment it by removing the semi-colon(;) in front of it.</li>
                                        <li>Restart the Apache Server.</li>
                                    </ol>
                                    For any further queries, please contact us.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_openssl_enable_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">How to enable PHP openssl extension?</div>
                                </div>
                                <div hidden id="help_openssl_enable_desc" class="mo_saml_help_desc">
                                    <ol>
                                        <li>Open php.ini file located under php installation folder.</li>
                                        <li>Search for extension=php_openssl.dll.</li>
                                        <li>Uncomment it by removing the semi-colon(;) in front of it.</li>
                                        <li>Restart the Apache Server.</li>
                                    </ol>
                                    For any further queries, please contact us.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_saml_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">What is SAML?</div>
                                </div>
                                <div hidden id="help_saml_desc" class="mo_saml_help_desc">
                                    Security Assertion Markup Language(SAML) is an XML-based, open-standard data format
                                    for exchanging authentication and authorization data between parties, in particular,
                                    between an Identity Provider and a Service Provider. In our case, miniOrange is the
                                    Service Provider and the application which manages credentials is the Identity
                                    provider.
                                    <br/><br/>
                                    The SAML specification defines three roles: the Principal (in this case, your
                                    Wordpress user), the Identity provider (IdP), and the Service Provider (SP). The
                                    Service Provider requests and obtains an identity assertion from the Identity
                                    Provider. On the basis of this assertion, the service provider can make an access
                                    control decision - in other words it can decide whether to allow user to login to
                                    WordPress.
                                    <br/><br/>
                                    For more details please refer to this <a
                                            href="https://en.wikipedia.org/wiki/Security_Assertion_Markup_Language"
                                            target="_blank">SAML document</a>.
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mo_saml_help_cell">
                                <div id="help_saml_flow_title" class="mo_saml_title_panel">
                                    <div class="mo_saml_help_title">SP-Initiated Login vs. IdP-Initiated Login</div>
                                </div>
                                <div hidden id="help_saml_flow_desc" class="mo_saml_help_desc">
                                    The user's identity(user profile and credentials) is managed by an Identity
                                    Provider(IdP) and the user wants to login to your WordPress site.
                                    <br/><br/>
                                    <b>SP-Initiated Login</b>
                                    <br/>
                                    <ol>
                                        <li>The request to login is initiated through the WordPress site.</li>
                                        <li>Using the miniOrange SAML Plugin, the user is redirected to IdP login
                                            page.
                                        </li>
                                        <li>The user authenticates with the IdP.</li>
                                        <li>With the help of response from IdP, miniOrange SAML Plugin logs in the user
                                            to WordPress site.
                                        </li>
                                    </ol>
                                    <b>IdP-Initiated Login</b>
                                    <br/>
                                    <ol>
                                        <li>The user initiates login through IdP.</li>
                                        <li>With the help of response from IdP, miniOrange SAML Plugin logs in the user
                                            to WordPress site.
                                        </li>
                                    </ol>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <br/>

                    <br/><br/>
                </td>
            </tr>
        </table>
    </form>


    </div>
    <?php
}

function mo_saml_get_test_url() {
    if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' || get_option( 'mo_saml_enable_cloud_broker' ) == 'miniorange' ) {
        $url = site_url() . '/?option=testConfig';
    } else {
        $url = get_option( 'mo_saml_host_name' ) . '/idptest/?id=' . get_option( 'mo_saml_admin_customer_key' ) . '&key=' . get_option( 'mo_saml_customer_token' );
    }

    return $url;
}

function mo_saml_is_customer_registered_saml() {
    $email       = get_option( 'mo_saml_admin_email' );
    $customerKey = get_option( 'mo_saml_admin_customer_key' );
    if ( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) ) {
        return 0;
    } else {
        return 1;
    }
}

function Multisite_enabled(){
    if( is_multisite()){
        return "<b><font color='green'> enabled </font></b>";
    }
    return "<b><font color='red'> disabled </font></b>";
}

function mo_saml_is_sp_configured() {
    $saml_login_url = get_option( 'saml_login_url' );

    // $saml_x509_certificate=get_option('saml_x509_certificate');
    // $saml_issuer=get_option('saml_issuer');
    if ( empty( $saml_login_url ) ) {
        return 0;
    } else {
        return 1;
    }
}

function mo_saml_download_logs($error_msg,$cause_msg) {

    echo '<div style="font-family:Calibri;padding:0 3%;">';
    echo '<hr class="header"/>';
    echo '          <p>Please try the solution given above.If the problem persists,please download SAML Request, Response under Show SAML 
                        Request and Response.Attach the screenshot of this window along with SAML Request and Response with 
                        your query and mail us at <a href="mailto:info@miniorange.com">info@miniorange.com</a>.</p>
                    <p>We will get back to you soon!<p>
                    </div>
                    <div style="margin:3%;display:block;text-align:center;">
                    <div style="margin:3%;display:block;text-align:center;"><input style="padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button" value="Close" onclick="self.close()">';
    //<form method="get" action="mo_logs.json">
    echo '&nbsp;&nbsp;';
//

    $samlResponse = $_POST['SAMLResponse'];
    $samlResponse = base64_decode( $samlResponse );
    $samlResponse = mo_document_xml( $samlResponse );

    $ssoUrl       = get_option( "saml_login_url" );
    $force_authn  = get_option( 'mo_saml_force_authentication' );
    $acsUrl       = site_url() . "/";
    $issuer       = site_url() . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
    $sp_entity_id = get_option( 'mo_saml_sp_entity_id' );
    $samlRequest  = Utilities::createSAMLRequest( $acsUrl, $sp_entity_id, $ssoUrl, $force_authn );
    $samlRequest  = mo_document_xml( $samlRequest );
    $error_array  = array("Error"=>$error_msg,"Cause"=>$cause_msg);
    $saml_logs    = array( "SAMLRequest" => $samlRequest, "SAMLResponse" => $samlResponse,"Error"=>$error_array);
    $saml_logs    = json_encode( $saml_logs );
    ?>
    <script type="text/javascript">
        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/json;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }

        document.getElementById("dwn-btn").addEventListener("click", function () {
            // Generate download of hello.txt file with some content

            var filename = "mo-saml-report.json";
            var text = '<?php echo $saml_logs?>';
            var data = JSON.parse(text);
            download(filename, JSON.stringify(data, undefined, 2));
        }, false);
    </script>
    <?php

    exit();


}

function mo_document_xml( $saml ) {
    $doc = new DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = true;
    $doc->loadXML($saml);
    $out   = $doc->saveXML();
    $xml   = simplexml_load_string( $out );
    $json  = json_encode( $xml );
    $array = json_decode( $json );

    return $array;

}



function miniorange_support_saml() {
    ?>
    <div class="mo_saml_support_layout">
        <div>
            <h3>Support</h3>
            <p>Need any help? We can help you with configuring your Identity Provider. Just send us a query and we will
                get back to you soon.</p>
            <form method="post" action="">
                <input type="hidden" name="option" value="mo_saml_contact_us_query_option"/>
                <table class="mo_saml_settings_table">
                    <tr>
                        <td><input style="width:95%" type="email" class="mo_saml_table_textbox" required
                                   name="mo_saml_contact_us_email"
                                   value="<?php echo get_option( "mo_saml_admin_email" ); ?>"
                                   placeholder="Enter your email"></td>
                    </tr>
                    <tr>
                        <td><input type="tel" style="width:95%" id="contact_us_phone"
                                   pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" class="mo_saml_table_textbox"
                                   name="mo_saml_contact_us_phone"
                                   value="<?php echo get_option( 'mo_saml_admin_phone' ); ?>"
                                   placeholder="Enter your phone"></td>
                    </tr>
                    <tr>
                        <td><textarea class="mo_saml_table_textbox" style="width:95%"
                                      onkeypress="mo_saml_valid_query(this)" onkeyup="mo_saml_valid_query(this)"
                                      onblur="mo_saml_valid_query(this)" required name="mo_saml_contact_us_query"
                                      rows="4" style="resize: vertical;" placeholder="Write your query here"></textarea>
                        </td>
                    </tr>
                </table>
                <div style="text-align:center;">
                    <input type="submit" name="submit" style="margin:15px; width:120px;"
                           class="button button-primary button-large"/>
                </div>
            </form>
        </div>
    </div>
    <script>
        jQuery("#contact_us_phone").intlTelInput();
        jQuery("#phone_contact").intlTelInput();

        function mo_saml_valid_query(f) {
            !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
                /[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
        }


    </script>
<?php }

function mo_saml_show_pricing_page() { ?>
    <div class="mo_saml_table_layout">

        <h2>Licensing Plans
            <span style="float:right;margin-right:15px;"><input type="button" name="ok_btn"
                                                                class="button button-primary button-large"
                                                                value="OK, Got It"
                                                                onclick="window.location.href='admin.php?page=mo_saml_settings'"/></span>
        </h2>
        <hr>

        <h3>
            What is a MultiSite?
        </h3>
        <p>WordPress Multisite is a feature which allows users to create a “Network” of subsites within a single install of WordPress. This allows you to use subdomains (or subdirectories) of the same root domain (site1.domain.com as a subsite of domain.com for example), within the same Network.
            <b style="font-size: 16px">Currently you have Multisite <?php echo Multisite_enabled()?> on this instance.</b><p>

        <h3>
            What is Multiple IDP?
        </h3>
        <p>
            miniOrange SAML plugin provides support for more than one Identity Providers simultaneously.Choose Enterprise version if you have multiple Identity Providers.
        </p>
        <table class="table mo_table-bordered mo_table-striped">

            <thead>
            <tr style="background-color:#93ca3a;">
                <th width="25%"><br><br><br><br>
                    <h3>Features \ Plans</h3></th>
                <th class="text-center" width="25%"><h3>Standard <br><br><br></h3><p class="mo_plan-desc"></p><h3><b class="tooltip">$249 - One Time Payment *<span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>

                <input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now for Single Site Environment"
                       onclick="upgradeform('wp_saml_sso_standard_plan')"/>
                </span></h3></th>
                <th class="text-center" width="25%"><h3>Premium <br><br><br></h3><p class="mo_plan-desc"></p><h3><b class="tooltip">$449 - One Time Payment *<span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>

                <input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now for Single Site Environment"
                       onclick="upgradeform('wp_saml_sso_basic_plan')"/><br /><br />
				<input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now for Multsite Network Environment"
                       onclick="upgradeform('wp_saml_sso_multisite_basic_plan')" title="Use it if you have subdomain or sub-directory of WordPress site."/>


                </span></h3></th>

                <th class="text-center" width="25%"><h3>Enterprise <br></h3><p>(Multiple IDP and MultiNetwork Support)</p><p class="mo_plan-desc"></p><h3><b class="tooltip">$499 - One Time Payment *<span class="tooltiptext">Cost applicable for one instance only.</span></b><br><br><br><span>
				<input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now for Single Site Environment"
                       onclick="upgradeform('wp_saml_sso_enterprise_plan')"/><br /><br />
				<input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now for Multisite Network Environment"
                       onclick="upgradeform('wp_saml_sso_multisite_enterprise_plan')"/ title="Use it if you have subdomain or sub-directory of WordPress site."><br /><br />
				<input type="button" name="upgrade_btn" class="button button-default button-large" value="Upgrade Now for Single Site Environment And Multiple IdP"
                       onclick="upgradeform('wp_saml_sso_multiple_idp_plan')" title="Use it if you want the users of more than 1 IDP to login into single WordPress site."/>
					   
				</span></h3></th>

            </tr>
            </thead>
            <tbody class="mo_align-center mo-fa-icon">
            <tr>
                <td>Unlimited Authentications</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Basic Attribute Mapping (Username, Email, First Name, Last Name,Display Name)</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Widget,Shortcode to add IDP Login Link on your site</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Step-by-step guide to setup IDP</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Auto-Redirect to IDP from login page</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Protect your complete site (Auto-Redirect to IDP from any page)</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Change SP base Url and SP Entity ID</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Options to select SAML Request binding type</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>SAML Single Logout</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Integrated Windows Authentication (supported with AD FS)</td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Customized Role Mapping</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Auto-sync IdP Configuration from metadata</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Custom Attribute Mapping (Any attribute which is stored in user-meta table)</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Store Multiple IdP Certificates</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Custom SP Certificate</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Multi-Site Support **</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Sub-site specific SSO for Multisite</td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Multiple IDP's Supported ***</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Multi-Network SSO Support ***</td>
                <td></td>
                <td></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td><b>Add-Ons</b></td>
                <td>Purchase Separately</td>
                <td>Purchase Separately</td>
                <td>Included</td>
            </tr>
            <tr>
                <td>Buddypress Attribute Mapping Add-On</td>
                <td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
                <td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
                <td><i class="fa fa-check"></i></td>
            </tr>
            <tr>
                <td>Page Restriction Add-On</td>
                <td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
                <td><a target="_blank" href="https://www.miniorange.com/contact">Contact Us</a></td>
                <td><i class="fa fa-check"></i></td>

        </table>

        <form style="display:none;" id="loginform"
              action="<?php echo get_option( 'mo_saml_host_name' ) . '/moas/login'; ?>"
              target="_blank" method="post">
            <input type="email" name="username" value="<?php echo get_option( 'mo_saml_admin_email' ); ?>"/>
            <input type="text" name="redirectUrl"
                   value="<?php echo get_option( 'mo_saml_host_name' ) . '/moas/initializepayment'; ?>"/>
            <input type="text" name="requestOrigin" id="requestOrigin"/>
        </form>
        <script>
            function upgradeform(planType) {

                jQuery('#requestOrigin').val(planType);
                jQuery('#loginform').submit();
            }
        </script>
        <br>
        <h3>Steps to Upgrade to Premium Plugin -</h3>
        <p>1. You will be redirected to miniOrange Login Console. Enter your password with which you created an account
            with us. After that you will be redirected to payment page.</p>
        <p>2. Enter you card details and complete the payment. On successful payment completion, you will see the link
            to download the premium plugin.</p>
        <p>3. When you download the premium plugin, just unzip it and replace the folder with existing plugin. Do not
            delete and upload again from wordpress admin panel as your already saved settings will get lost.<br><br>
            <b>Note: If you are downloading the Multi-Site Plugin, then first delete existing plugin and then re-install
                the Multi-Site plugin.</b></p>
        <p>4. From this point on, do not update the premium plugin from the Wordpress store.</p>

        <h3>* Cost applicable for one instance only.</h3>
        <p>You can Upgrade the number of instances by clicking on Upgrade now.</p>
        <h3>** Multi-Site Support - </h3>
        <p>This feature has a separate premium plugin and licensing is also based on number of subsites. Please select the Multisite option on the payment page while upgrading.</p>

        <h3>*** Multiple IDP\'s Supported - </h3>
        <p>This feature has a separate premium plugin and licensing is also based on number of IDPs. Contact us at <b>info@miniorange.com</b> to get quote for these.</p>

        <h3>*** Multi-Network Supported - </h3>
        <p>This feature has a separate premium plugin and licensing is also based on number of networks and subsites. Contact us at <b>info@miniorange.com</b> to get quote for these.</p>

        <!--<h3>*** End to End Identity Provider Integration - </h3>
        <p>We will setup a Conference Call / Gotomeeting and do end to end configuration for you for IDP as well as plugin. We provide services to do the configuration on your behalf. </p> -->
        <h3>10 Days Return Policy -</h3>
        At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium plugin you purchased is
        not working as advertised and you've attempted to resolve any issues with our support team, which couldn't get
        resolved. We will refund the whole amount within 10 days of the purchase. Please email us at info@miniorange.com
        for any queries regarding the return policy.

        Please email us at <b>info@miniorange.com</b> for any queries regarding the return policy.
        <h2>Licensing Plans (Cloud Service)</h2>
        <hr>
        <p>If you want to use miniOrange Cloud Single Sign on service. <a style="cursor:pointer;"
                                                                          id="help_working_title1">Click Here</a> to
            know how the plugin works for this case. Contact us at <b>info@miniorange.com</b> to get its licensing plans
            info.</p>
        <br>

        <div hidden id="help_working_desc1" class="mo_saml_help_desc">
            <h3>Using miniOrange Single Sign On service:</h3>
            <div style="display:block;text-align:center;">
                <img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/saml_working.png' ?>"
                     alt="Working of miniOrange SAML plugin" style="width: 85%;"/>
            </div>
            <ol>
                <li>miniOrange SAML SSO plugin sends a login request to miniOrange SSO Service.</li>
                <li>miniOrange SSO Service creates a SAML Request and redirects the user to your Identity Provider for
                    authentication.
                </li>
                <li>Upon successful authentication, your Identity Provider sends a SAML Response back to miniOrange SSO
                    Service.
                </li>
                <li>miniOrange SSO Service verifies the SAML Response and sends a response status (along with the logged
                    in user's information) back to miniOrange SAML SSO plugin. Plugin then reads the response and logins
                    the user.
                </li>
            </ol>
            <div>
                <b>Advantages:</b>
                <ol>
                    <li>If you are an enterprise or business user then on using this service you will be able to take
                        full advantage of all of miniOrange SSO features. ( For a complete list of these features <a
                                href="http://miniorange.com/single-sign-on-sso" target="_blank">Click Here</a>)
                    </li>
                    <li>You can use Non-SAML Identity Providers for Single Sign On.</li>
                    <li>If you have multiple websites then you can use the same IdP configuration for all of them. You
                        don't have to make seperate configurations in your IdP.
                    </li>
                    <li>Some Identity Providers like ADFS do not support HTTP endpoints ( i.e. your wordpress site needs
                        to be on HTTPS ). So, if your wordpress site is not on HTTPS then you can use this service for
                        such IdPs.
                    </li>
                </ol>
            </div>
        </div>
        If you have any doubts regarding the licensing plans, you can mail us at <a
                href="mailto:info@miniorange.com"><i>info@miniorange.com</i></a> or submit a query using the <b>support
            form</b> on right.
        <br>

        <br>

    </div>
<?php }

function mo_saml_get_saml_request_url() {

    if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' ) {
        $url = home_url() . '/?option=getsamlrequest';
    } else {
        $url = get_option( 'mo_saml_host_name' ) . '/getsamlrequest/?id=' . get_option( 'mo_saml_admin_customer_key' ) . '&key=' . get_option( 'mo_saml_customer_token' );
    }

    return $url;
}

function mo_saml_get_saml_response_url() {
    if ( get_option( 'mo_saml_enable_cloud_broker' ) == 'false' ) {
        $url = home_url() . '/?option=getsamlresponse';
    } else {
        $url = get_option( 'mo_saml_host_name' ) . '/getsamlresponse/?id=' . get_option( 'mo_saml_admin_customer_key' ) . '&key=' . get_option( 'mo_saml_customer_token' );
    }

    return $url;
}

function miniorange_generate_metadata() {

    $sp_base_url = get_option( 'mo_saml_sp_base_url' );
    if ( empty( $sp_base_url ) ) {
        $sp_base_url = site_url();
    }
    if ( substr( $sp_base_url, - 1 ) == '/' ) {
        $sp_base_url = substr( $sp_base_url, 0, - 1 );
    }
    $sp_entity_id = get_option( 'mo_saml_sp_entity_id' );
    if ( empty( $sp_entity_id ) ) {
        $sp_entity_id = $sp_base_url . '/wp-content/plugins/miniorange-saml-20-single-sign-on/';
    }

    $entity_id   = $sp_entity_id;
    $acs_url     = $sp_base_url . '/';
    $certificate = file_get_contents( plugin_dir_path( __FILE__ ) . 'resources' . DIRECTORY_SEPARATOR . 'sp-certificate.crt' );
    $certificate = Utilities::desanitize_certificate( $certificate );
    ob_clean();
    header( 'Content-Type: text/xml' );
    echo '<?xml version="1.0"?>
<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2020-10-28T23:59:59Z" cacheDuration="PT1446808792S" entityID="' . $entity_id . '">
  <md:SPSSODescriptor AuthnRequestsSigned="false" WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
    <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress</md:NameIDFormat>
    <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="' . $acs_url . '" index="1"/>
  </md:SPSSODescriptor>
  <md:Organization>
    <md:OrganizationName xml:lang="en-US">miniOrange</md:OrganizationName>
    <md:OrganizationDisplayName xml:lang="en-US">miniOrange</md:OrganizationDisplayName>
    <md:OrganizationURL xml:lang="en-US">http://miniorange.com</md:OrganizationURL>
  </md:Organization>
  <md:ContactPerson contactType="technical">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@miniorange.com</md:EmailAddress>
  </md:ContactPerson>
  <md:ContactPerson contactType="support">
    <md:GivenName>miniOrange</md:GivenName>
    <md:EmailAddress>info@miniorange.com</md:EmailAddress>
  </md:ContactPerson>
</md:EntityDescriptor>';
    exit;

}

?>