<?php
/*
Plugin Name: WPBizPlugins Super Simple News Box
Plugin URI: http://www.wpbizplugins.com
Description: Add a super simple news box editable from the dashboard.
Version: 1.0.0
Author: Gabriel Nordeborn
Author URI: http://www.wpbizplugins.com
Text Domain: wpbizplugins-ssnb
*/

/*  WPBizPlugins Super Simple News Box
    Copyright 2014  Gabriel Nordeborn  (email : gabriel@wpbizplugins.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


//error_reporting(E_ALL);
//ini_set('display_errors', 1);

/**
 *
 * START BY INCLUDING THE VARIOUS EMBEDDED PLUGINS. CURRENTLY:
 *  - ReduxFramework for options
 *
 */

// Include Redux if plugin isn't available
if ( ! class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' ) ) {

    require_once( dirname( __FILE__ ) . '/assets/redux/ReduxCore/framework.php' );

}

require_once( dirname( __FILE__ ) . '/inc/redux-config.php' );              // Import Redux
require_once( dirname( __FILE__ ) . '/inc/custom-functions.php' );          // Import our custom functions
require_once( dirname( __FILE__ ) . '/inc/widgets.php' );                   // Import our widgets
require_once( dirname( __FILE__ ) . '/inc/shortcodes.php' );                // Import our shortcodes

// Load localization
function wpbizplugins_ssnb_init_plugin() {

    load_plugin_textdomain( 'wpbizplugins-ssnb', false, dirname(plugin_basename(__FILE__)) . '/lang' );

}

add_action( 'init', 'wpbizplugins_ssnb_init_plugin' );

/**
 * Adds our custom CSS.
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_ssnb_output_custom_css() {

    global $wpbizplugins_ssnb_options;

    echo '<style type="text/css">';
    echo '.wpbizplugins-ssnb-updated-date {
              color: darkgrey;
          }';

    if( $wpbizplugins_ssnb_options['custom_css'] != '' ) {

	echo $wpbizplugins_ssnb_options['custom_css'];

    }

    echo '</style>';

    unset( $wpbizplugins_ssnb_options );

}

if( ! is_admin() ) add_action( 'wp_head', 'wpbizplugins_ssnb_output_custom_css', 999 );

/**
 * Register settings group.
 *
 * @since 1.0.0
 *
 */


function wpbizplugins_ssnb_setting_section_callback_function( $arg ) {
  
}


/**
 * Adds options page if warranted.
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_ssnb_register_options_page() {

    global $wpbizplugins_ssnb_options;

    // Exit if this isn't set to be used
    if( $wpbizplugins_ssnb_options[ 'use_options_page' ] == false ) return null;

    if( $wpbizplugins_ssnb_options[ 'menu_icon' ] != '' ) {

        $icon_url = $wpbizplugins_ssnb_options[ 'menu_icon' ];

    } else {

        $icon_url = plugins_url( 'assets/img/wpbizplugins-ssnb-menuicon.png', __FILE__ );

    }

    add_settings_section(

      'wpbizplugins_ssnb_settings_section',
      'Super Simple News Box settings',
      'wpbizplugins_ssnb_setting_section_callback_function',
      'ssnb-options'
      
    );

    register_setting( 'wpbizplugins_ssnb_settings_section', 'wpbizplugins_ssnb_newsbox_content' );
    register_setting( 'wpbizplugins_ssnb_settings_section', 'wpbizplugins_ssnb_newsbox_updated_date' );

    add_menu_page( 

        $wpbizplugins_ssnb_options[ 'menu_name' ], 
        $wpbizplugins_ssnb_options[ 'menu_name' ], 
        $wpbizplugins_ssnb_options[ 'menu_capability' ], 
        'wpbizplugins-ssnb-menu', 
        'wpbizplugins_ssnb_options_page', 
        $icon_url 

        );

    unset( $wpbizplugins_ssnb_options );

}

add_action('admin_menu', 'wpbizplugins_ssnb_register_options_page');

function wpbizplugins_ssnb_options_page() {

    global $wpbizplugins_ssnb_options;

    $content = stripslashes( get_option( 'wpbizplugins_ssnb_newsbox_content' ) ); 

    ?>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo __( 'Newsbox', 'wpbizplugins-ssnb' ); ?></h2>
    <form method="post" action="options.php"> 
        <?php settings_fields( 'wpbizplugins_ssnb_settings_section' ); ?>

            <p>
                <?php echo wpautop( $wpbizplugins_ssnb_options[ 'intro_text' ] ); ?>
            </p>

            <div id="wpbizplugins-ssnb-editor">
                        <?php wp_editor( $content, 'wpbizplugins_ssnb_newsbox_content', array(

                            'wpautop'       => true,
                            'textarea_name' => 'wpbizplugins_ssnb_newsbox_content',
                            'textarea_rows' => 10,
                            ) 

                        );
            ?>
            <input type="hidden" id="wpbizplugins_ssnb_newsbox_updated_date" name="wpbizplugins_ssnb_newsbox_updated_date" value="<?php echo strftime( $wpbizplugins_ssnb_options[ 'date_format' ], current_time( 'timestamp' ) ); ?>">
            <?php
        submit_button(); 

        ?>
    </form>
</div>
<?php

    unset( $wpbizplugins_ssnb_options );
}
