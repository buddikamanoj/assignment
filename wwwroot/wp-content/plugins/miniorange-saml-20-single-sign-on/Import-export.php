<?php
require_once dirname( __FILE__ ) . '/includes/lib/mo-options-enum.php';
add_action( 'admin_init', 'miniorange_import_export' );
define( "Tab_Class_Names", serialize( array(
	"SSO_Login"         => 'mo_options_enum_sso_login',
	"Identity_Provider" => 'mo_options_enum_identity_provider',
	"Service_Provider"  => 'mo_options_enum_service_provider',
	"Attribute_Mapping" => 'mo_options_enum_attribute_mapping',
	"Role_Mapping"      => 'mo_options_enum_role_mapping',
	"Proxy_Setup"       => 'mo_options_enum_proxy_setup'
) ) );

/**
 *Function to display block of UI for export Import
 */
function miniorange_import_configuration_saml() {

	echo '<div class="mo_saml_support_layout">
		<div>
			<h3>Import /Export Configuration</h3>
			<form method="post" action="" name="mo_export">
				<input type="hidden" name="option" value="mo_saml_export" />
				<table>

				<hr class="header"/>
        <p>This tab will help you to transfer your plugin configurations when you change your Wordpress instance</p>
        <p>Example: When you switch from test environment to production. Follow these 3 simple steps to do that:
            <ol>
                <li>Download plugin configuration file by clicking on the link given below.</li>
                <li>Install the plugin on new Wordpress instance.</li>
                <li>Upload the configuration file in Import Plugin Configurations section.</li>
            </ol></p>
        <p> And just like that, all your plugin configurations will be transferred! </p>
        <p>You can also send us this file along with your support query.</p>

        <hr class="header"/>
        
       				<tr><td><h3>Export Configurations</h3></td></tr>
				<tr><td>';
	if(mo_saml_is_sp_configured())
	    echo '<a href="#" onclick="document.forms[\'mo_export\'].submit();">Click Here</a> to download the plugin configurations</td></tr>';
    else
        echo '<h4>Please configure the plugin to export the configurations.</h4>';
	echo '</table>
				<div>
				</div>
			</form>
		<hr class="header">
			<form method="post" action="' . admin_url() . 'admin.php?page=mo_saml_settings&tab=save' . '" enctype="multipart/form-data">
				<input type="hidden" name="option" value="mo_saml_import" />
				<table>
				<tr><td><h3><span style="color:red;">*</span>Import Configurations</h3></td></tr>
				<tr><td><input type="file" name="configuration_file" disabled="disabled"></td>
				<td><input type="submit" disabled="disabled" name="submit" style="width: auto" class="button button-primary button-large" value="Import"/></td></tr>
				
				</table>
				<br><br>
				<div>
				</div>';
				if ( get_option( 'mo_saml_free_version' ) ) {?>
                <span style="color:red;">*</span>These options are configurable in the <a
                        href="<?php echo admin_url( 'admin.php?page=mo_saml_settings&tab=licensing' ); ?>"><b>standard,
                        premium and enterprise</b></a> version of the plugin.</h3>
                <br/><br/>
			<?php } ?>
			</form>
		</div>
	</div>
<?php
}

/**
 *Function iterates through the enum to create array of values and converts to JSON and lets user download the file
 */
function miniorange_import_export() {

	if ( array_key_exists( "option", $_POST ) && $_POST['option'] == 'mo_saml_export' ) {

		$tab_class_name      = unserialize( Tab_Class_Names );
		$configuration_array = array();
		foreach ( $tab_class_name as $key => $value ) {
			$configuration_array[ $key ] = mo_get_configuration_array( $value );
		}
		$configuration_array["Version_dependencies"]=mo_get_version_informations();

		header( "Content-Disposition: attachment; filename=miniorange-saml-config.json" );
		echo( json_encode( $configuration_array, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) );
		exit;
	}



}

function mo_get_configuration_array( $class_name ) {
	$class_object = call_user_func( $class_name . '::getConstants' );
	$mo_array = array();
	foreach ( $class_object as $key => $value ) {
		$mo_option_exists=get_option($value);

		if($mo_option_exists){
			if(@unserialize($mo_option_exists)!==false){
				$mo_option_exists = unserialize($mo_option_exists);
			}
			$mo_array[ $key ] = $mo_option_exists;

		}

	}

	return $mo_array;
}

function mo_update_configuration_array( $configuration_array ) {
	$tab_class_name = unserialize( Tab_Class_Names );
	foreach ( $tab_class_name as $tab_name => $class_name ) {
		foreach ( $configuration_array[ $tab_name ] as $key => $value ) {
			$option_string = constant( "$class_name::$key" );
			$mo_option_exists = get_option($option_string);
			if ( $mo_option_exists) {
				if(is_array($value))
					$value = serialize($value);
				update_option( $option_string, $value );
			}
		}
	}

}

function mo_get_version_informations(){
	$array_version = array();
	$array_version["PHP_version"] = phpversion();
	$array_version["Wordpress_version"] = get_bloginfo('version');
	$array_version["OPEN_SSL"] = mo_saml_is_openssl_installed();
	$array_version["CURL"] = mo_saml_is_curl_installed();
    $array_version["ICONV"] = mo_saml_is_dom_installed();
    $array_version["DOM"] = mo_saml_is_dom_installed();

	return $array_version;

}

