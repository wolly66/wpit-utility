<?php
/*
Plugin Name: WPIT Utility
Description: Here goes all custom utility
Author: Wolly, xlthlx, flodolo
Version: 1.0
*/

define ( 'WPIT_UTILITY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define ( 'WPIT_UTILITY_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
define ( 'WPIT_UTILITY_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );
define ( 'WPIT_UTILITY_PLUGIN_VERSION', '1.0' );
define ( 'WPIT_UTILITY_PLUGIN_VERSION_NAME', 'wpit_utility_version' );

class Wpit_Utility {

	//A static member variable representing the class instance
	private static $_instance = null;

	/**
	 * Wpit_Utility::__construct()
	 * Locked down the constructor, therefore the class cannot be externally instantiated
	 *
	 * @param array $args various params some overidden by default
	 *
	 * @return
	 */

	private function __construct() {
		
		//check for plugin update 
		add_action( 'init', array( $this, 'update_check' ) );

		//Add GA script in footer
		add_action( 'wp_footer', array( $this, 'add_googleanalytics' ) );
		
		//transform youtube link in nocookies
		add_filter( 'the_content', array( $this, 'youtube_nocookies' ) );
		add_filter( 'content_save_pre', array( $this, 'youtube_nocookies', 10, 1 ) );
		
		//remove WordPress capitalization
		remove_filter( 'the_title', 'capital_P_dangit', 11 );
		remove_filter( 'the_content', 'capital_P_dangit', 11 );
		remove_filter( 'comment_text', 'capital_P_dangit', 31 );

	}

	/**
	 * Wpit_Utility::__clone()
	 * Prevent any object or instance of that class to be cloned
	 *
	 * @return
	 */
	public function __clone() {
		trigger_error( "Cannot clone instance of Singleton pattern ...", E_USER_ERROR );
	}

	/**
	 * Wpit_Utility::__wakeup()
	 * Prevent any object or instance to be deserialized
	 *
	 * @return
	 */
	public function __wakeup() {
		trigger_error( 'Cannot deserialize instance of Singleton pattern ...', E_USER_ERROR );
	}

	/**
	 * Wpit_Utility::getInstance()
	 * Have a single globally accessible static method
	 *
	 * @param mixed $args
	 *
	 * @return
	 */
	public static function getInstance( $args = array() ) {
		if ( ! is_object( self::$_instance ) )
			self::$_instance = new self( $args );

		return self::$_instance;
	}

	/**
	 * update_UTILITY_check function.
	 *
	 * @access public
	 * @return void
	 */
	private function update_check() {
		// Do checks only in backend
		if ( is_admin() ) {
	
			if ( version_compare( get_site_option( WPIT_UTILITY_VERSION_NAME ), WPIT_UTILITY_VERSION ) != 0  ) {
	
			$this->do_update();
	
			}
	
		} //end if only in the admin
	}
	
	/**
	 * do_update function.
	 *
	 * @access private
	 *
	 */
	private function do_update(){
	
		//DO NOTHING, BY NOW, MAYBE IN THE FUTURE
	
		//Update option
	
		update_option( WPIT_UTILITY_VERSION_NAME , WPIT_UTILITY_VERSION_NUMBER_VERSION );
	
	}
	
	/**
	 * add_googleanalytics function.
	 *
	 * insert the GA script
	 *
	 * @access public
	 *
	 */
	public function add_googleanalytics(){ ?>

		<!-- GA script added by Wolly's Utility plugin-->
		<script>

		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-xxxxxx-x', 'auto');
		ga('set', 'anonymizeIp', true);
		ga('send', 'pageview');

		</script>
	<?php
	}
	
	
	/**
	 * youtube_nocookies function.
	 * 
	 * @access public
	 * @param mixed $content
	 * @return $content
	 */
	public function youtube_nocookies( $content ) {
		
		$search = '/youtube\.com/';
		$replace = 'youtube-nocookie.com';
		
		return preg_replace( $search, $replace, $content );
	}
}// end class

//Class instance

$wpit_utility = Wpit_Utility::getInstance();
