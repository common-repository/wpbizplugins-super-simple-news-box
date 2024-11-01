<?php
/*  WPBizPlugins Super Simple Help Box
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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  021101301  USA
*/

/**
 * Add shortcode for outputting a directions box
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_ssnb_newsbox_shortcode ( $atts ) {

    global $wpbizplugins_ssnb_options;

    // Extract the shortcode options
    extract( shortcode_atts( 
        array(
        
        'show_updated'              => 'yes',           // Show when the box was last updated?
        'title'                     => ''               // The title

        ), $atts ) 
    );

    // Do not output anything if the news box content is empty
    $content = stripslashes( get_option( 'wpbizplugins_ssnb_newsbox_content' ) );

    if( $content == '' ) return null;

    ob_start();
    
    // Full container for the entire output
    echo '<div class="wpbizplugins-ssnb-full-container">';

    echo '<h' . $wpbizplugins_ssnb_options['headline_size'] . ' id="wpbizplugins-ssnb-title">' . $title . '</h' . $wpbizplugins_ssnb_options['headline_size'] . '>';

    echo '<div class="wpbizplugins-ssnb-content">';
    echo wpautop( $content );
    echo '</div>';

    if( $show_updated == 'yes' ) { 

        echo '<div class="wpbizplugins-ssnb-updated-date">';
        echo '<em>' . __( 'Last updated', 'wpbizplugins-ssnb' ) . '</em> ' . get_option( 'wpbizplugins_ssnb_newsbox_updated_date' );
        echo '</div>';

    }

    echo '</div>';
    
    $output_string = ob_get_contents();
    ob_end_clean();

    unset( $wpbizplugins_ssnb_options );

    return $output_string;

}

add_shortcode( 'wpbizplugins_ssnb', 'wpbizplugins_ssnb_newsbox_shortcode' );
