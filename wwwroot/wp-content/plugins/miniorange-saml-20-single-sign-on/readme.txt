=== SAML Single Sign On - SSO ===
Contributors: miniOrange
Donate link: http://miniorange.com
Tags: single sign on,sso,saml, SSO Saml, login, sso integration WordPress, SSO using SAML, SAML 2.0 Service Provider, Wordpress SAML, SAML Single Sign-On, SSO using SAML, SAML 2.0, SAML 20, Wordpress Single Sign On,ad,ADFS,Active directory,Azure AD,adfs single sign-on, ad sso,Okta, Google Apps, Google for Work, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2, NetIQ, Novell Access Manager, login with adfs, login with azure ad, login with okta, auth0, AuthAnvil, cognito, Atlassian, CA Identity Manager, Tivoli Identity Manage, Forgerock OpenIDM, SAP Cloud Identity Provider
Requires at least: 3.5
Tested up to: 4.9.4
Stable tag: 4.8.42
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SAML 2.0 Single Sign On provides SSO/Login to your Wordpress site with any SAML compliant Identity Provider. (ACTIVE SUPPORT for IdP config)

== Description ==

miniOrange SAML 2.0 Single Sign On (SSO) allows users residing at SAML 2.0 capable Identity Provider to login to your WordPress website. We support all known IdPs - Google Apps, ADFS, Azure AD, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2, NetIQ etc.

miniOrange SAML Single Sign on (SSO) Plugin acts as a SAML 2.0 Service Provider which can be configured to establish the trust between the plugin and a SAML 2.0 capable Identity Providers to securely authenticate the user to the Wordpress site. 

WordPress Multi-Site Environment and ability to configure Multiple IDP's against wordpress as service provider is also supported in premium plugin.

This plugin is also compatible with WordPress OAuth Server plugin. You can now make your WordPress site an OAuth Server and have the users authenticate themselves with your SAML-compliant IDPs like ADFS, Azure AD, OneLogin instead of their WordPress credentials.

If you require any Single Sign On (SSO) application or need any help with installing this plugin, please feel free to email us at info@miniorange.com or <a href="http://miniorange.com/contact">Contact us</a>.

= Free Version Features =
*   Unlimited authentication with your SAML 2.0 compliant Identity Providers like ADFS, Azure AD, Okta, Salesforce, Shibboleth, SimpleSAMLphp, OpenAM, Centrify, Ping, RSA, IBM, Oracle, OneLogin, Bitium, WSO2, NetIQ etc.
*   Automatic user registration after login if the user is not already registered with your site.
*   Use Widgets to easily integrate the login link with your Wordpress site.
*   Use Basic Attribute Mapping feature to map wordpress user profile attributes like First Name, Last Name to the attributes provided by your IDP.
*   Select default role to assign to users on auto registration.
*   Force authentication with your IDP on each login attempt.

= Premium Version Features =
*   All the Free version features.
*   **SAML Single Logout [Premium]** - Support for SAML Single Logout (Works only if your IDP supports SLO).
*   **Auto-redirect to IDP [Premium]** - Auto-redirect to your IDP for authentication without showing them your WordPress site's login page.
*   **Protect Site [Premium]** - Protect your complete site. Have users authenticate themselves before they could access your site content.
*   **Advanced Attribute Mapping [Premium]** - Use this feature to map your IDP attributes to your WordPress site attributes like Username, Email, First Name, Last Name, Group/Role, Display Name.
*   **Advanced Role Mapping [Premium]** - Use this feature to assign WordPress roles your users based on the group/role sent by your IDP.
*   **Short Code [Premium]** - Use Short Code (PHP or HTML) to place the login link wherever you want on the site.
*   **Reverse-proxy Support [Premium]** - Support for sites behind a reverse-proxy.
*   **Select Binding Type [Premium]** - Select HTTP-Post or HTTP-Redirect binding type to use for sending SAML Requests.
*   **Integrated Windows Authentication [Premium]** - Support for Integrated Windows Authentication (IWA)
*   **Step-by-step Guides [Premium]** - Use step-by-step guide to configure your Identity Provider like ADFS, Centrify, Google Apps, Okta, OneLogin, Salesforce, SimpleSAMLphp, Shibboleth, WSO2, JBoss Keycloak, Oracle. 
*   **WordPress Multi-site Support [Premium]** - Multi-Site environment is one which allows multiple subdomains / subdirectories to share a single installation. With multisite premium plugin, you can configure the IDP in minutes for all your sites in a network. While, if you have basic premium plugin, you have to do plugin configuration on each site individually as well as multiple service provider configuration's in the IDP.

    For Example - If you have 1 main site with 3 subsites. Then, you have to configure the plugin 3 times on each site as well as 3 service provider configurations in your IDP. Instead, with multisite premium plugin. You have to configure the plugin only once on main network site as well as only 1 service provider configuration in the IDP.


*   **Multiple SAML IDPs Support [Premium]** - We now support configuration of Multiple IDPs in the plugin to authenticate the different group of users with different IDP's. You can give access to users by users to IDP mapping (which IDP to use to authenticate a user) is done based on the domain name in the user's email. (This is a **PREMIUM** feature with separate licensing. Contact us at info@miniorange.com to get licensing plans for this feature.)

* If you are looking for an Identity Provider,you can try out <a href="https://idp.miniorange.com">miniOrange On-Premise IdP</a>.

= Website - =
Check out our website for other plugins <a href="http://miniorange.com/plugins" >http://miniorange.com/plugins</a> or <a href="https://wordpress.org/plugins/search.php?q=miniorange" >click here</a> to see all our listed WordPress plugins.
For more support or info email us at info@miniorange.com or <a href="http://miniorange.com/contact" >Contact us</a>. You can also submit your query from plugin's configuration page.

== Installation ==

= From your WordPress dashboard =
1. Visit `Plugins > Add New`.
2. Search for `miniOrange SAML 2.0 Single Sign On`. Find and Install `miniOrange SAML 2.0 Single Sign On`.
3. Activate the plugin from your Plugins page.

= From WordPress.org =
1. Download miniOrange SAML 2.0 Single Sign On plugin.
2. Unzip and upload the `miniorange-saml-20-single-sign-on` directory to your `/wp-content/plugins/` directory.
3. Activate miniOrange SAML 2.0 Single Sign-On from your Plugins page.

== Frequently Asked Questions ==

= I am not able to configure the Identity Provider with the provided settings =
Please email us at info@miniorange.com or <a href="http://miniorange.com/contact" >Contact us</a>. You can also submit your app request from plugin's configuration page.

= For any query/problem/request =
Visit Help & FAQ section in the plugin OR email us at info@miniorange.com or <a href="http://miniorange.com/contact">Contact us</a>. You can also submit your query from plugin's configuration page.

== Screenshots ==

1. General settings like auto redirect user to your IdP.
2. Guide to configure your Wordpress site as Service Provider to your IdP.
3. Configure your IdP in your Wordpress site.

== Changelog ==

= 4.8.42 =
* Input validation fixes.

= 4.8.41 =
* Support for PHP version 5.3 and above.

= 4.8.40 =
* Added feature for Exporting plugin configuration.
* Added View SAML Request and Response

= 4.8.35 =
* Fixed the Entity ID/Issuer issues for AzureAD Idp.

= 4.8.34 =
* Introducing miniOrange on-premise IDP

= 4.8.33 =
* Compatibilty with Wordpress 4.9

= 4.8.32 =
* Introducing Standard version of SAML Plugin for small sized customers.
* Fixed Registration issue.

= 4.8.31 =
* Introducing Standard version of SAML Plugin for small sized customers.Check Licensing Plans for more details.

= 4.8.30 =
* Fix the CSS and JS inclusion only in the plugin pages, removed it from frontend and backend of wordpress.

= 4.8.28 =
* Bug fix for PHP 5.4.x version.

= 4.8.27 =
* Support for Multiple Certificate via Metadata

= 4.8.26 =
* SAML Configuration by Uploading metadata.
* Descriptive Error Messages. 
* Use miniorange as IdP feature. 
* More verbose instructions.
* Compatibility with WordPress 4.8

= 4.8.25 =
Updating Licensing Plans

= 4.8.24 =
Updating Plugin Title

= 3.8.14 =
Pattern change on OTP Verification page

= 3.8.12 =
Major security fixes. It is recommended to update to this version.

= 3.8.7 =
Fixes for WordPress 4.5

= 3.8.6 =
Added features: Support for WordPress Multi-site (Network setup), support for Multiple IDPs and some fixes for WordPress 4.5

= 3.8.4 =
Introducing Free Trial for the premium version.

= 3.8.2 =
Security fix for preventing non-admin users from changing the settings.

= 3.8 =
Security fix for IDPs that signs only Assertion and not the complete SAML Response XML.

= 3.7 =
Support for Integrated Windows Authentication - contact info@miniorange.com if interested

= 3.5 =
Decrypt assertion bug fix

= 3.4 =
Added some requested features and some bug fixes.

= 3.3 =
Added support for Google Apps as an Identity Provider.

= 3.2 =
Some bug fixes in role mapping.

= 3.1 =
Some bug fixes in auto registration.

= 3.0 =
Added option to use miniOrange Single Sign On Service
Made it simple to setup SAML authentication with your IdP.

= 2.3 =
Fixed forgot password bug for some users.

= 2.2 =
Added guides for configuring common Identity Providers like ADFS, SimpleSAMLphp, Salesforce, Okta and some bug fixes.

= 2.1 =
Removed unwanted JS files.

= 2.0 =
Added new feature like role mapping and auto redirect user to your IdP.

= 1.7.0 =
Resolved UI issues for some users

= 1.6.0 =
Added help and troubleshooting guide.

= 1.5.0 =
Added error messaging.

= 1.4.0 =
Added fixes.

= 1.3.0 =
Added validations and fixes.
UI Improvements.

= 1.2.0 =
* this is the third release.

= 1.1.0 =
* this is the second release.

= 1.0.0 =
* this is the first release.

== Upgrade Notice ==

= 4.8.42 =
* Input validation fixes.

= 4.8.41 =
* Support for PHP version 5.3 and above.

= 4.8.40 =
* Added feature for Exporting plugin configuration.
* Added View SAML Request and Response

= 4.8.35 =
* Fixed the Entity ID/Issuer issues for AzureAD Idp.

= 4.8.34 =
* Introducing miniOrange on-premise IDP

= 4.8.33 =
* Compatibilty with Wordpress 4.9

= 4.8.32 =
* Introducing Standard version of SAML Plugin for small sized customers.
* Fixed Registration issue.

= 4.8.31 =
* Introducing Standard version of SAML Plugin for small sized customers.Check Licensing Plans for more details.

= 4.8.30 =
* Fix the CSS and JS inclusion only in the plugin pages, removed it from frontend and backend of wordpress.

= 4.8.28 =
* Bug fix for PHP 5.4.x version.

= 4.8.27 =
* Support for Multiple Certificate via Metadata

= 4.8.26 =
* SAML Configuration by Uploading metadata.
* Descriptive Error Messages. 
* Use miniorange as IdP feature. 
* More verbose instructions.
* Compatibility with WordPress 4.8

= 4.8.25 =
Updating Licensing Plans


= 4.8.24 =
Updating Plugin Title

= 3.8.14 =
Pattern change on OTP Verification page

= 3.8.12 =
Major security fixes. It is recommended to update to this version.

= 3.8.7 =
Fixes for WordPress 4.5

= 3.8.6 =
Added features: Support for WordPress Multi-site (Network setup), support for Multiple IDPs and some fixes for WordPress 4.5

= 3.8.4 =
Introducing Free Trial for the premium version.

= 3.8.2 =
Security fix for preventing non-admin users from changing the settings.

= 3.8 =
Security fix for IDPs that signs only Assertion and not the complete SAML Response XML.

= 3.7 =
Support for Integrated Windows Authentication - contact info@miniorange.com if interested

= 3.5 =
Decrypt assertion bug fix

= 3.4 =
Added some requested features and some bug fixes.

= 3.0 =
Major Update. We have taken ut-most care to make sure that your existing login flow doesn't break. If you have issues after this update then please contact us. We will get back to you very soon. 
 
= 2.1 =
Removed unwanted JS files.

= 2.0 =
Added new feature like role mapping and auto redirect user to your IdP.

= 1.7 =
Resolved UI issues for some users

= 1.6 =
Added help and troubleshooting guide.

= 1.5 =
Added error messaging.

= 1.4 =
Added fixes.

= 1.3 =
Added validations and fixes.
UI Improvements.

= 1.2 =
Some UI improvements.

= 1.1 =
Added Attribute mapping / Role mapping and test application.

= 1.0 =
I will update this plugin when ever it is required.