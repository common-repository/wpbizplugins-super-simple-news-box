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
 * Adds the dashboard widget that handles just about everything.
 *
 * @since 1.0.0
 *
 */

function wpbizplugins_ssnb_dashboard_widget() {

    global $wpbizplugins_ssnb_options;

    if( isset( $_POST[ 'submit_newsbox' ] ) ) {

        $content = $_POST[ 'content' ];
    
        // Sanitize and add data.
        if( ( update_option( 'wpbizplugins_ssnb_newsbox_content', $content ) ) && ( update_option( 'wpbizplugins_ssnb_newsbox_updated_date', strftime( $wpbizplugins_ssnb_options['date_format'], current_time( 'timestamp' ) ) ) ) ) {

            $success = true;

        } else {

            $success = false;
        }
        
    }
    
    // URL of current page
    $post_url = get_site_url() . "/wp-admin/index.php";
    $content = stripslashes( get_option( 'wpbizplugins_ssnb_newsbox_content' ) ); 

    echo '<div id="super-simple-news-box">';

    ?>
            <p><?php echo wpautop( $wpbizplugins_ssnb_options[ 'intro_text' ] ); ?></p>
            <p><?php echo __( 'Press the button below to show the editor.', 'wpbizplugins-ssnb' ); ?></p>
            <p><button id="show-editor-button" class="button-primary"><?php echo __( 'Show editor', 'wpbizplugins-ssnb' ); ?></button></p>
            <?php if( ( isset( $success) ) && ( $success == true ) ) {

                echo '<div class="updated"><p>' . __( 'Succeeded in updating the news box!', 'wpbizplugins-ssnb' ) . '</p></div>'; 

            }

            ?>
            <div>
            <form method="post" class="form" action="<?php echo $post_url; ?>">
                    <div id="wpbizplugins-ssnb-editor" style="padding: 5px 5px 5px 5px; margin-top: 10px; margin-bottom: 10px;">
                        <?php wp_editor( $content, 'content', array(
                            'wpautop'       => true,
                            'textarea_name' => 'content',
                            'textarea_rows' => 10,
                            ) 

                        ); ?>
                    
                        <p>
                            <button type="submit" name="submit_newsbox" class="button-primary"><i class="icon-envelope"></i> <?php echo __( 'Update newsbox', 'wpbizplugins-ssnb' ); ?></button>
                        </p>
                    </div>
            </form>
            </div>

        </div>

            <script type="text/javascript">
            jQuery( document ).ready( function() {
                jQuery( "#wpbizplugins-ssnb-editor" ).hide();
                jQuery( "#show-editor-button" ).click(function() {
                    jQuery( "#wpbizplugins-ssnb-editor" ).show( "fold", 1000 );
                });
            });
            </script>
             
    
    <?php

    unset( $wpbizplugins_ssnb_options );

}

function wpbizplugins_ssnb_add_dashboard_widget() {

    global $wpbizplugins_ssnb_options;

    // Exit if this isn't set to be used
    if( $wpbizplugins_ssnb_options[ 'use_dashboard_widget' ] == false ) return null;

    wp_add_dashboard_widget( 'widget_super_simple_news_box', __('Newsbox', 'wpbizplugins-ssnb'), 'wpbizplugins_ssnb_dashboard_widget' );    

}

if( is_admin() ) add_action( 'wp_dashboard_setup', 'wpbizplugins_ssnb_add_dashboard_widget' );

/**
 * Adds wpbizplugins_ssnb_widget widget.
 *
 * @since 1.0.0
 *
 */
class wpbizplugins_ssnb_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wpbizplugins_ssnb_widget', // Base ID
            __( 'Super Simple News Box', 'wpbizplugins-ssnb' ), // Name
            array( 'description' => __( 'Add our super simple news box.', 'wpbizplugins-ssnb' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */

    public function widget( $args, $instance ) {

        global $wpbizplugins_ssnb_options;

        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];
        echo $instance[ 'html_pre' ];         

        // Output the actual shortcode
        $shortcode = '[wpbizplugins_ssnb';
        $shortcode .= ' show_updated="' . $instance[ 'show_updated' ] . '"';
        $shortcode .= ' title="' . $title . '"';
        $shortcode .= ']';

        echo do_shortcode($shortcode);

        echo $instance[ 'html_post' ];

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = '';
        }

        if ( isset( $instance[ 'show_updated' ] ) ) {
            $show_updated = $instance[ 'show_updated' ];
        } else {
            $show_updated = 'yes';
        }

        if ( isset( $instance[ 'html_pre' ] ) ) {
            $html_pre = $instance[ 'html_pre' ];
        } else {
            $html_pre = '';
        }

        if ( isset( $instance[ 'html_post' ] ) ) {
            $html_post = $instance[ 'html_post' ];
        } else {
            $html_post = '';
        }
        
        
        ?>
        <p>
            <h3><?php echo __( 'Title of widget', 'wpbizplugins-ssnb' ); ?></h3>
            <p><?php echo __( 'Set the title of the widget.' , 'wpbizplugins-ssnb' ); ?></p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

            <h3><?php echo __( 'Show when the box was last updated?', 'wpbizplugins-ssnb' ); ?></h3>
            <p><?php echo __( 'Do you want to show when the box was last updated?', 'wpbizplugins-ssnb' ); ?></p>
            <select id="<?php echo $this->get_field_id( 'show_updated' ); ?>" name ="<?php echo $this->get_field_name( 'show_updated' ); ?>">
                <option value="yes"<?php if( $show_updated == 'yes' ) echo ' selected'; ?>><?php echo __( 'Yes', 'wpbizplugins-ssnb' ); ?></option>
                <option value="no"<?php if( $show_updated == 'no' ) echo ' selected'; ?>><?php echo __( 'No', 'wpbizplugins-ssnb' ); ?></option>
            </select>

            <h3><?php echo __( 'HTML before content', 'wpbizplugins-ssnb' ); ?></h3>
            <p><?php echo __( 'Add HTML you want <em>before</em> the content here.' , 'wpbizplugins-ssnb' ); ?></p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'html_pre' ); ?>" name="<?php echo $this->get_field_name( 'html_pre' ); ?>" type="text" value="<?php echo esc_attr( $html_pre ); ?>">

            <h3><?php echo __( 'HTML after content', 'wpbizplugins-ssnb' ); ?></h3>
            <p><?php echo __( 'Add HTML you want <em>after</em> the content here.' , 'wpbizplugins-ssnb' ); ?></p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'html_post' ); ?>" name="<?php echo $this->get_field_name( 'html_post' ); ?>" type="text" value="<?php echo esc_attr( $html_post ); ?>">

        </p>
        <?php 
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        
        
        foreach( $new_instance as $option_name => $option_value ) {
            if( $option_name == 'title' ) continue;
            $instance[$option_name] = $option_value;
        }


        return $instance;
    }

} // class wpbizplugins_ssnb_widget

// register wpbizplugins_ssnb_widget widget
function wpbizplugins_ssnb_widget_register() {
    register_widget( 'wpbizplugins_ssnb_widget' );
}
add_action( 'widgets_init', 'wpbizplugins_ssnb_widget_register' );
