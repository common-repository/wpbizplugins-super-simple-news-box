<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('WPBizPlugins_SuperSimpleNewsBox_Config')) {

    class WPBizPlugins_SuperSimpleNewsBox_Config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs. ;)
            if ( true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            // add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {

            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            
            //$sections = array();
            $sections[] = array(
                'title' => 'Section via hook',
                'desc' => '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>',
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            // ACTUAL DECLARATION OF SECTIONS

            $this->sections[] = array(
                'title' => __('Main configuration', 'wpbizplugins-ssnb'),
                'desc' => __('Configure the Super Simple News Box. Please contact <a href="mailto:support@wpbizplugins.com">support@wpbizplugins.com</a> if you need further assistance.', 'wpbizplugins-ssnb'),
                'icon' => 'el-icon-info-sign',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields' => array(
                
                    /**
                     * Super Simple News Box configuration
                     *
                     */

                    array(
                        'id'               => 'intro_text',
                        'type'             => 'editor',
                        'title'            => __('Introductory text', 'wpbizplugins-ssnb'), 
                        'subtitle'         => __('Explanatory and introductory text in the dashboard widget, shown together with the editor for the newsbox.', 'wpbizplugins-ssnb'),
                        'default'          =>  __('You can edit the contents of your news box here. <strong>The news box will be hidden if you leave the content empty</strong>.', 'wpbizplugins-ssnb' ),
                        'args'   => array(
                            'teeny'            => false,
                            'textarea_rows'    => 10
                        )
                    ),

                    array(
                        'id'        => 'headline_size',
                        'type'      => 'slider',
                        'title'     => __('Headline level', 'wpbizplugins-ssnb'),
                        'subtitle'  => __('Control the level of the headline for the news box. Smaller means bigger headline.', 'wpbizplugins-ssnb'),
                        'desc'      => __('Default:', 'wpbizplugins-ssnb') . '3',
                        "default"   => 3,
                        "min"       => 1,
                        "step"      => 1,
                        "max"       => 6,
                        'display_value' => 'text'
                    ),

                    array(
                        'id'       => 'date_format',
                        'type'     => 'text',
                        'title'    => __('Format for the date and time', 'wpbizplugins-ssnb'),
                        'subtitle' => __('Set the format for the date and time of last update of the box', 'wpbizplugins-ssnb'),
                        'desc'     => __('Read more about formatting date at:', 'wpbizplugins-ssnb') . ' <a target="_blank" href="https://php.net/strftime">https://php.net/strftime</a>. ' .__('Default:', 'wpbizplugins-ssnb') . ' <em>%H:%M, %d-%m-%Y</em>',
                        'default'  => '%H:%M, %d-%m-%Y'
                    ),

                    array(
                        'id'    => 'ssnb_advanced_section',
                        'type'  => 'info',
                        'title' => __('Advanced options', 'wpbizplugins-ssnb'),
                        'style' => 'warning',
                        'desc'  => __('You\'ll find advanced options below.', 'wpbizplugins-ssnb'),
                    ),

                    array(
                        'id'       => 'use_dashboard_widget',
                        'type'     => 'switch',
                        'desc'     => __( 'If this is turned on, a dashboard widget allowing you to edit the news box will be created.', 'wpbizplugins-ssnb' ),
                        'title'    => __( 'Show dashboard widget?', 'wpbizplugins-ssnb'),
                        'on'       => __( 'On', 'wpbizplugins-ssnb'),
                        'off'      => __( 'Off', 'wpbizplugins-ssnb'),
                        'default'  => true,
                    ),

                    array(
                        'id'       => 'use_options_page',
                        'type'     => 'switch',
                        'desc'     => __( 'If this is turned on, a separate page with the editor for the news box will be created. Look for it under the menu entry for the Super Simple News Box.<br />Useful if you want to link to editing the news box rather than edit it in the dashboard.', 'wpbizplugins-ssnb' ),
                        'title'    => __( 'Use separate page for editing?', 'wpbizplugins-ssnb'),
                        'on'       => __( 'On', 'wpbizplugins-ssnb'),
                        'off'      => __( 'Off', 'wpbizplugins-ssnb'),
                        'default'  => true,
                    ),

                    array(
                        'id'            => 'menu_name',
                        'type'          => 'text',
                        'title'         => __('Name of menu entry', 'wpbizplugins-ssnb'),
                        'subtitle'      => __('The name of the entry in the WordPress menu', 'wpbizplugins-ssnb'),
                        'description'   => __('This can be useful to edit if you want to clarify what the menu entry does for your users or clients.', 'wpbizplugins-ssnb' ),
                        'default'       => 'Super Simple News Box',
                        'required'      => array( 'use_options_page', 'equals', true )
                    ),  

                    array(
                        'id'            => 'menu_icon',
                        'type'          => 'text',
                        'title'         => __('Dashicon for menu', 'wpbizplugins-ssnb'),
                        'subtitle'      => __('Use this if you want a special dashicon for the menu entry', 'wpbizplugins-ssnb'),
                        'description'   => __('If you want a native WordPress dashicon instead of the Super Simple News Box icon, just enter the slug of that dashicon here, like "dashicons-iconname".<br />You can find all available dashicons and their slug at:', 'wpbizplugins-ssnb' ) . ' <a target="_blank" href="http://melchoyce.github.io/dashicons/">http://melchoyce.github.io/dashicons/</a>.',
                        'default'       => '',
                        'required'      => array( 'use_options_page', 'equals', true )
                    ),  


                    array(
                        'id'       => 'menu_capability',
                        'type'     => 'select',
                        'title'    => __('Capability required', 'wpbizplugins-ssnb'),
                        'subtitle' => __('The capability required to edit the news box contents', 'wpbizplugins-ssnb'),
                        'desc'     => __('Set the capability required for editing the news box contents here. Use to restrict access for your clients.', 'wpbizplugins-ssnb'),
                        // Must provide key => value pairs for select options
                        'options'  => wpbizplugins_ssnb_return_capabilities_array(),
                        'default'  => 'edit_pages',
                    ),

                    array(
                        'id'       => 'custom_css',
                        'type'     => 'ace_editor',
                        'title'    => __('Custom CSS Code', 'wpbizplugins-ssnb'),
                        'subtitle' => __('Put your custom CSS code here.', 'wpbizplugins-ssnb'),
                        'mode'     => 'css',
                        'theme'    => 'monokai',
                        'desc'     => '
                            <strong>' . __('Available CSS selectors', 'wpbizplugins-ssnb') . '</strong>' .
                            '<p><code>.wpbizplugins-ssnb-full-container</code> - ' . __('The full container of the newsbox.', 'wpbizplugins-ssnb') . '</p>' .
                            '<p><code>#wpbizplugins-ssnb-title</code> - ' . __('The title of the newsbox.', 'wpbizplugins-ssnb') . '</p>' .
                            '<p><code>.wpbizplugins-ssnb-content</code> - ' . __('The content of the newsbox.', 'wpbizplugins-ssnb') . '</p>' .
                            '<p><code>.wpbizplugins-ssnb-updated-date</code> - ' . __('The date/time the box was last updated.', 'wpbizplugins-ssnb') . '</p>' .
                            ''

                        ,
                        'default'  => ''
                    )
                )
            );

            $this->sections[] = array(
                'title'     => __('Import / Export', 'wpbizplugins-ssnb'),
                'desc'      => __('Import and Export the menu settings from file, text or URL.', 'wpbizplugins-ssnb'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your menu options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
           

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            /*$this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'wpbizplugins-ssnb'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'wpbizplugins-ssnb')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'wpbizplugins-ssnb'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'wpbizplugins-ssnb')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'wpbizplugins-ssnb');
        */
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            //$theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'wpbizplugins_ssnb_options',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => 'Configuration',            // Name that appears at the top of your panel
                'display_version'   => '1.0.0',  // Version that appears at the top of your panel
                'menu_type'         => 'submenu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title' => __('Super Simple News Box', 'wpbizplugins-ssnb'),
                'page_title' => __('Super Simple News Box Options', 'wpbizplugins-ssnb'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => false,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => false,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'options-general.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'delete_plugins',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                       // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => 'wpbizplugins_ssnb_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => false,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/wpbizplugins',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/wpbizplugins',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );

            // Panel Intro text -> before the form
            $this->args['intro_text'] = '<img src="' . plugins_url( '../assets/img/wpbizplugins-ssnb-logo.png', __FILE__ ) . '">
            <p>' . __('Welcome to the Super Simple News Box configuration. Configure everything needed for the newsbox here.', 'wpbizplugins-ssnb') . '</p>
            <p>' . __('Display the news box through using the native WordPress widget available in <em>Widgets</em>, or the following shortcode:', 'wpbizplugins-ssnb' ) . ' <code>[wpbizplugins_ssnb]</code></p>
            <p>' . __('The shortcode takes two options:', 'wpbizplugins-ssnb') . 
            ' <code>title="Your title here"</code> - ' . __('Sets the title of the widget.', 'wpbizplugins-ssnb') . 
            ' <code>show_updated="yes"</code> - ' . __('Yes or no. Shows or hides when the box was last updated.', 'wpbizplugins-ssnb') . 
            '</p>';

            // Add content after the form.
            $this->args['footer_text'] = '<a href="http://www.wpbizplugins.com?utm_source=ssnb&utm_medium=plugin&utm_campaign=ssnb" target="_blank"><img style="margin-top: 20px; margin-bottom: 20px;" src="' . plugins_url( '../assets/img/wpbizplugins-footer-img.png', __FILE__ ) . '"></a>';
        }

    }
    
    global $wpbizplugins_ssnb_options_config;
    $wpbizplugins_ssnb_options_config = new WPBizPlugins_SuperSimpleNewsBox_Config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
