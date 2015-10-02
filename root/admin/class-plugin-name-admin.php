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
		$this->plugin_slug = $this->plugin->get( 'slug' );
		$this->plugin_name = $this->plugin->get( 'name' );
		$this->version = $this->plugin->get( 'version' );

	}

	/**
	 * Register the stylesheets for the admin.
	 *
	 * @since    {%= version %}
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'css/{%= slug %}-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the scripts for the admin.
	 *
	 * @since    {%= version %}
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug, plugin_dir_url( __FILE__ ) . 'js/{%= slug %}-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add settings page.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_page() {

		$this->settings_page = add_options_page(
			__( '{%= title %}', '{%= slug %}' ), // Page title
			__( '{%= title %}', '{%= slug %}' ), // Menu title
			'manage_options', // Capability
			$this->plugin_slug, // Page ID
			array( $this, 'do_settings_page' ) // Callback
		);

	}

	/**
	 * Output contents of settings page.
	 *
	 * @since 1.0.0
	 */
	public function do_settings_page() {

		?>
		<div class="wrap <?php echo $this->plugin_slug; ?>-settings">
	        <h1><?php echo $this->plugin_name; ?></h1>
	        <?php

			// Set up tab/settings.
			$tab_base_url = "?page={$this->plugin_slug}";
			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : null;

			?>
	        <h2 class="nav-tab-wrapper">
	        	<a href="<?php echo $tab_base_url; ?>&tab=tab-1" class="nav-tab <?php echo ( ! $active_tab || 'tab-1' == $active_tab ) ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Tab 1', '{%= slug %}' ); ?></a>
	        	<a href="<?php echo $tab_base_url; ?>&tab=tab-2" class="nav-tab <?php echo ( 'tab-2' == $active_tab ) ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Tab 2', '{%= slug %}' ); ?></a>
	        </h2>
			<form action='options.php' method='post'>
				<?php
				settings_fields( $this->plugin_slug );

				if ( ( ! $active_tab || 'tab-1' == $active_tab ) ) {
					do_settings_sections( "{$this->plugin_slug}-tab-1" );
				} elseif ( 'tab-2' == $active_tab ) {
					do_settings_sections( "{$this->plugin_slug}-tab-2" );
				}

				submit_button();
				?>
			</form>
		</div>
		<?php

	}

	/**
	 * Add settings fields to the settings page.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_fields() {

		register_setting(
			$this->plugin_slug, // Option group
			$this->plugin_slug, // Option name
			array( $this, 'validate_settings' ) // Sanitization
		);

		// Tab 1 settings section
		add_settings_section(
			'tab-1', // Section ID
			null, // Title
			null, // Callback
			"{$this->plugin_slug}-tab-1" // Page
		);

		// Tab 2 settings section
		add_settings_section(
			'tab-2', // Section ID
			null, // Title
			null, // Callback
			"{$this->plugin_slug}-tab-2" // Page
		);

		$id = 'field_1';
		add_settings_field(
			$id, // ID
			__( 'Field Title', {%= slug %} ), // Title
			array( $this, 'render_post_type_settings' ), // Callback
			"{$this->plugin_slug}-post_types_settings", // Page
			'post_types', // Section
			array( // Args
				'id' => $id,
			)
		);

	}

	/*===========================================
	 * Field rendering functions.
	===========================================*/

	/**
	 * Render checkbox input for settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Args from add_settings_field().
	 */
	public function render_checkbox( $args ) {

		// Set up option name and value.
		if ( isset( $args['secondary_id'] ) ) {
			$option_name = $this->get_option_name( $args['id'], $args['secondary_id'] );
			$option_value = $this->get_option_value( $args['id'], $args['secondary_id'] );
		} else {
			$option_name = $this->get_option_name( $args['id'] );
			$option_value = $this->get_option_value( $args['id'] );
		}

		$checked = isset( $option_value ) ? $option_value : null;

		// Get post type REST info.
		if ( isset ( $args['post_type_object'] ) ) {

			$post_type_object = $args['post_type_object'];
			$init_rest_base = isset( $post_type_object->rest_base ) ? $post_type_object->rest_base : '';

			// Get checked value based on saved value, or existing value if option doesn't exist.
			if ( isset( $option_value ) ) {
				$checked = $option_value;
			} elseif ( $init_rest_base ) {
				$checked = true;
			}

		}

		// Render hidden input set to 0 to save unchecked value as non-null.
		if ( empty( $args['save_null'] ) ) {

			printf(
				'<input type="hidden" value="0" id="%s" name="%s"/>',
				$option_name,
				$option_name
			);

		}

		printf(
			'<label for="%s"><input type="checkbox" value="1" id="%s" name="%s" %s/>&nbsp;%s</label>',
			$option_name,
			$option_name,
			$option_name,
			checked( 1, $checked, false ),
			! empty( $args['description'] ) ? '<span class="rae-description">' . $args['description'] . '</span>': ''
		);

	}

	/**
	 * Render text input for settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Args from add_settings_field().
	 */
	public function render_text_input( $args ) {

		// Set up option name and value.
		if ( isset( $args['secondary_id'] ) ) {
			$option_name = $this->get_option_name( $args['id'], $args['secondary_id'] );
			$option_value = $this->get_option_value( $args['id'], $args['secondary_id'] );
		} else {
			$option_name = $this->get_option_name( $args['id'] );
			$option_value = $this->get_option_value( $args['id'] );
		}

		$value = $option_value;

		// Get post type REST info.
		if ( ! $value && isset ( $args['post_type_object'] ) ) {

			$post_type_object = $args['post_type_object'];
			$rest_base = isset( $post_type_object->rest_base ) ? $post_type_object->rest_base : '';

			// Auto-generate initial rest_base if not already set.
			if ( ! $rest_base ) {
				$rest_base = sanitize_title_with_dashes( $args['post_type_object']->labels->name );
			}

			$value = $rest_base;

		}

		printf(
			'%s<input type="text" value="%s" id="%s" name="%s" class="regular-text %s"/>%s',
			! empty( $args['sub_heading'] ) ? '<b>' . $args['sub_heading'] . '</b><br />' : '',
			$value,
			$option_name,
			$option_name,
			! empty( $args['class'] ) ? $args['class'] : '',
			! empty( $args['description'] ) ? sprintf( '<br /><p class="description" for="%s">%s</p>',
				$option_name, $args['description'] ) : ''
		);

	}

	/**
	 * Render select for settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Args from add_settings_field().
	 */
	public function render_select( $args ) {

		if ( ! isset( $args['options'] ) ) {
			return;
		}

		// Set up option name and value.
		if ( isset( $args['secondary_id'] ) ) {
			$option_name = $this->get_option_name( $args['id'], $args['secondary_id'] );
			$option_value = $this->get_option_value( $args['id'], $args['secondary_id'] );
		} else {
			$option_name = $this->get_option_name( $args['id'] );
			$option_value = $this->get_option_value( $args['id'] );
		}

		printf(
			'<select id="%s" name="%s" %s"/>',
			$option_name,
			$option_name,
			! empty( $args['class'] ) ? $args['class'] : ''
		);

		// Output each option.
		foreach ( $args['options'] as $option_slug => $option_name ) {

			printf(
				'<option %s value="%s"/>%s</option>',
				selected( $option_value, $option_slug, false ),
				$option_slug,
				$option_name
			);

		}

		echo '</select>';

	}

}
