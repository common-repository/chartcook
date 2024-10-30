<?php
/*
Plugin Name:  Chartcook Analytics
Plugin URI:   https://developer.wordpress.org/plugins/chartcook/
Description:  Easily integrate the Chartcook.com tracking code into your Wordpress site.
Version:      1.0
Author:       Chartcook.com
Author URI:   https://chartcook.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  chartcook


Chartcook for Wordpress is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Chartcook for Wordpress is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Chartcook for Wordpress. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/





function chartcookwp_activation(){
	
}


function chartcookwp_deactivation(){
	
}


function chartcookwp_uninstall(){
	$option_name = 'chartcookwp_pid';
	delete_option($option_name);
}




function chartcookwp_options_page()
{
    add_submenu_page(
        'tools.php',
        'Chartcook Analytics',
        'Chartcook Analytics',
        'manage_options',
        'chartcookwp',
        'chartcookwp_options_page_html'
    );
}
add_action('admin_menu', 'chartcookwp_options_page');




function chartcookwp_settings_init() {
	register_setting( 'chartcookwp', 'chartcookwp_pid' );
 
	add_settings_section(
		'chartcookwp_section_settings',
		'Settings',
		'chartcookwp_section_settings_cb',
		'chartcookwp'
	);
 
	add_settings_field(
		'chartcookwp_settings_field',
		__( 'Tracking ID', 'chartcookwp' ),
		'chartcookwp_settings_field_cb',
		'chartcookwp',
		'chartcookwp_section_settings'
	);
}
add_action( 'admin_init', 'chartcookwp_settings_init' );
 
 
function chartcookwp_section_settings_cb( $args ) {
	?><p style="max-width:500px;">Create a <a href="https://chartcook.com" target="_blank">Chartcook.com</a> account and insert your personal tracking ID below. This will automatically integrate the tracking code in your Wordpress site.<br /><br />You can find your  tracking ID after logging in on app.chartcook.com and clicking "Get tracking code" on the Dashboard.</p><?php	
}
 
function chartcookwp_settings_field_cb( $args ) {

	$options = get_option( 'chartcookwp_pid' ); ?>
    <input type="text" name="chartcookwp_pid" value="<?= isset($options) ? esc_attr($options) : ''; ?>">
	
 
 
 
 <?php
}
 

add_action( 'admin_menu', 'chartcookwp_options_page' );
 
function chartcookwp_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages
 
	// check if the user have submitted the settings
	// wordpress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'chartcookwp_messages', 'chartcookwp_message', __( 'Settings Saved', 'chartcookwp' ), 'updated' );
 	}
 
 	// show error/update messages
 	settings_errors( 'chartcookwp_messages' );
 	?>
    <div class="wrap">
        <h1>Chartcook Analytics</h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg_options"
            settings_fields('chartcookwp');
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections('chartcookwp');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function chartcookwp_hook_js() {
	$options = get_option( 'chartcookwp_pid' );
	if($options!=""){ ?>
		<script type="text/javascript">
!function(e,t,r){var s,n;window.chartcook=t;var c=t;for(t._pid=r,reg=["reset","optOut"],i=0;i<reg.length;i++)!function(e,t){e[t]=function(){e.push([t].concat(Array.prototype.slice.call(arguments,0)))}}(c,reg[i]);
s=e.createElement("script"),n=e.getElementsByTagName("script")[0],s.type="text/javascript",s.async=!0,s.src="https://cdn.chartcook.com/static/cc.min.js",n.parentNode.insertBefore(s,n)
}(document,window.chartcook||[],"<?php echo intval(esc_attr($options)); ?>");
</script>
	<?php }
}
add_action('wp_head', 'chartcookwp_hook_js');


//plugin activation
register_activation_hook( __FILE__, 'chartcookwp_activation' );

//plugin deactivation --not uninstall
register_deactivation_hook( __FILE__, 'chartcookwp_deactivation' );

//plugin uninstall
register_uninstall_hook(__FILE__, 'chartcookwp_uninstall');