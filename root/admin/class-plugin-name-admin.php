<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       {%= homepage %}
 * @since      {%= version %}
 *
 * @package    {%= safe_name %}
 * @subpackage {%= safe_name %}/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    {%= safe_name %}
 * @subpackage {%= safe_name %}/admin
 * @author     {%= author_name %} {%= author_email %}
 */
class {%= safe_name %}_Admin {

	/**
	 * The main plugin instance.
	 *
	 * @since    {%= version %}
	 * @access   private
	 * @var      {%= safe_name %}    $plugin    The main plugin instance.
	 */
	private $plugin;

	/**
	 * The slug of this plugin.
	 *
	 * @since    {%= version %}
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The display name of this plugin.
	 *
	 * @since    {%= version %}
	 * @access   protected
	 * @var      string    $plugin_name    The plugin display name.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    {%= version %}
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The instance of this class.
	 *
	 * @since    {%= version %}
	 * @access   protected
	 * @var      {%= safe_name %}_Admin    $instance    The instance of this class.
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @return    {%= safe_name %}_Admin    A single instance of this class.
     */
    public static function get_instance( $plugin ) {
 
        if ( null == self::$instance ) {
            self::$instance = new self( $plugin );
        }
 
        return self::$instance;
 
    }

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    {%= version %}
	 * @var      string    $plugin_slug       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->plugin_slug = $this->plugin->get_plugin_slug();
		$this->plugin_name = $this->plugin->get_plugin_name();
		$this->version = $this->plugin->get_plugin_version();

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    {%= version %}
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in {%= safe_name %}_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The {%= safe_name %}_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/{%= slug %}-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    {%= version %}
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in {%= safe_name %}_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The {%= safe_name %}_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/{%= slug %}-admin.js', array( 'jquery' ), $this->version, false );

	}

}
