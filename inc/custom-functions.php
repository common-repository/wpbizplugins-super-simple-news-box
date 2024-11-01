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
 *
 * This file contains custom functions.
 *
 */

/**
 * Simple function for checking whether or not we're on a certain post type.
 *
 * @return Returns the current post type.
 * @since 1.0.0
 *
 */

function wpbizplugins_ssnb_return_post_type() {

    global $post, $typenow, $current_screen;

    //we have a post so we can just get the post type from that
    if ( $post && $post->post_type )
        return $post->post_type;

    //check the global $typenow - set in admin.php
    elseif( $typenow )
        return $typenow;

    //check the global $current_screen object - set in sceen.php
    elseif( $current_screen && $current_screen->post_type )
        return $current_screen->post_type;

    //lastly check the post_type querystring
    elseif( isset( $_REQUEST['post_type'] ) )
        return sanitize_key( $_REQUEST['post_type'] );

    elseif ( ( isset( $_REQUEST['post_type'] ) ) && ( get_post_type( $_REQUEST['post'] ) ) )
            return get_post_type($_REQUEST['post']);
    //we do not know the post type!
    return null;

}

/**
 * Return array of capabilities for use with restricting access to editing the plugin contents.
 *
 * @return array Returns an array of all available capabilities.
 * @since 1.0.0
 *
 */

function wpbizplugins_ssnb_return_capabilities_array() {

    $capabilities_array = array(
        'activate_plugins',
        'add_users',
        'create_users',
        'delete_others_pages',
        'delete_others_posts',
        'delete_pages',
        'delete_plugins',
        'delete_posts',
        'delete_private_pages',
        'delete_private_posts',
        'delete_published_pages',
        'delete_published_posts',
        'delete_themes',
        'delete_users',
        'edit_dashboard',
        'edit_others_pages',
        'edit_others_posts',
        'edit_pages',
        'edit_plugins',
        'edit_posts',
        'edit_private_pages',
        'edit_private_posts',
        'edit_published_pages',
        'edit_published_posts',
        'edit_theme_options',
        'edit_themes',
        'edit_users',
        'export',
        'import',
        'install_plugins',
        'install_themes',
        'list_users',
        'manage_categories',
        'manage_links',
        'manage_options',
        'moderate_comments',
        'promote_users',
        'publish_pages',
        'publish_posts',
        'read',
        'read_private_pages',
        'read_private_posts',
        'remove_users',
        'switch_themes',
        'unfiltered_html',
        'unfiltered_upload',
        'update_core',
        'update_plugins',
        'update_themes',
        'upload_files'
    );

    $capabilities_array_keypair = array();

    foreach( $capabilities_array as $capability ) {

        $capabilities_array_keypair[$capability] = $capability;

    }

    return $capabilities_array_keypair;
}
