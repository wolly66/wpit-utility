<?php
class Wpit_Utility_Options
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Utility', 
            'Utility Options', 
            'manage_options', 
            'wpit-utility-options', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'wpit-options' );
        ?>
        <div class="wrap">
            <h2>Impostazione Utility</h2>        
            
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'wpit_options_utility_settings_group' );   
                do_settings_sections( 'wpit-utility-options' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'wpit_options_utility_settings_group', // Option group
            'wpit-options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'wpit_ga_page', // ID
            'Home page GA', // Title
            array( $this, 'print_section_ga' ), // Callback
            'wpit-utility-options' // Page
        );  

        add_settings_field(
            'ga_ua', // ID
            'GA code', // Title 
            array( $this, 'ga_callback' ), // Callback
            'wpit-utility-options', // Page
            'wpit_ga_page' // Section           
        );      

                 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['ga_ua'] ) )
            $new_input['ga_ua'] =  sanitize_text_field( $input['ga_ua'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_ga()
    {
        print 'Inserisci il codice GA:';
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function ga_callback()
    {
        printf(
            '<input type="text" size="100" id="ga_ua" name="wpit-options[ga_ua]" value="%s" />',
            isset( $this->options['ga_ua'] ) ? sanitize_text_field( $this->options['ga_ua'] ) : ''
        );
    }
}

if( is_admin() )
    $wpit_utility_options = new Wpit_Utility_Options();