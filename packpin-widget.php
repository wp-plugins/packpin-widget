<?php
/**
 * Packpin Widget
 *
 * Eases addition of Packpin E-Commerce Widget to your Wordpress project
 *
 * @package   Packpin_Widget
 * @author    Packpin <info@packpin.com>
 * @license   GPL-2.0+
 * @link      http://packpin.com
 * @copyright 2015 Packpin B.V.
 *
 * @wordpress-plugin
 * Plugin Name: 	  Packpin Widget
 * Plugin URI:        http://wordpress.org/extend/plugins/packpin-widget
 * Description:       Eases addition of Packpin E-Commerce Widget to your Wordpress project
 * Version:           1.0.0
 * Author:            Packpin <info@packpin.com>
 * Author URI:        http://packpin.com
 * Text Domain:       packpin-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /lang
 * GitHub Plugin URI: https://github.com/packpin/packpin-wordpress-widget
 */
 
 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

class Packpin_Widget extends WP_Widget {

    /**
     * Unique identifier.
     *
     * @var string
     */
    protected $widget_slug = 'packpin-widget';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	public function __construct() {
		// load plugin text domain
		add_action( 'init', array( $this, 'widget_textdomain' ) );

		parent::__construct(
			$this->get_widget_slug(),
			__( 'Packpin Widget', $this->get_widget_slug() ),
			array(
				'classname'  => $this->get_widget_slug().'-class',
				'description' => __( 'Eases addition of Packpin E-Commerce Widget to your Wordpress project.', $this->get_widget_slug() )
			)
		);

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	} // end constructor

    /**
     * Return the widget slug.
     *
     * @return Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 * @return int
	 */
	public function widget( $args, $instance ) {
		// Check if there is a cached output
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];

		// Just so the IDE would not scream with errors
		$before_widget = "";
		$before_title = "";
		$after_title = "";
		$after_widget = "";

		$return_attributes = Packpin_Widget_Static::return_attributes($instance);

		extract( $args, EXTR_OVERWRITE );

		$widget_string = $before_widget;
		if($instance['title'])
			$widget_string .= $before_title.$instance['title'].$after_title;

		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		$widget_string .= sprintf('<div class="widget-content">%s</div>', ob_get_clean());
		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;
	} // end widget

	/**
	* Clearing WP cache
	*/
	public function flush_widget_cache() 
	{
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}

	/**
	 * Processes the widget's options to be saved.
	 * @TODO Add stronger validation
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		/**
		 * Default arguments.
		 *
		 * @var array $defaults
		 */
		$defaults = Packpin_Widget_Static::widget_defaults();

		/**
		 * Update logic.
		 *
		 * @var array $instance
		 */
		$instance = $old_instance;
		foreach ( $defaults as $key => $val ) {
			$instance[$key] = ($new_instance[$key]) ?
				trim(strip_tags( $new_instance[$key] )) :
				$val
			;
		}

		$this->flush_widget_cache();

		return $instance;
	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {
		
		$defaults = Packpin_Widget_Static::widget_defaults();
		
		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		/**
		 * Position of the widget
		 */
		$position = array(
			"right" 	=> __("Right", $this->get_widget_slug()),
			"bottom" 	=> __("Bottom", $this->get_widget_slug()),
		);

		/**
		 * Button height
		 */
		$height = array(
			"default"	=> __("Default", $this->get_widget_slug()),
			"small" 	=> __("Small", $this->get_widget_slug())
		);

		// Display the admin form
		include( plugin_dir_path(__FILE__) . 'views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {
		load_plugin_textdomain( $this->get_widget_slug(), false, plugin_dir_path( __FILE__ ) . 'lang/' );
	} // end widget_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles( $hook_suffix = false ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts( $hook_suffix = false )  {
		if ( 'widgets.php' !== $hook_suffix ) { return; }

		wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'underscore' );
		add_action( 'admin_footer-widgets.php', array( $this, 'print_admin_scripts' ), 9999 );
		//@TODO wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery', 'wp-color-picker'), false, true  );
	}

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {
		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );
	}

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

		wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url( 'js/widget.js', __FILE__ ), array() );

	}

	/**
	 * Prints Wordpress admin scripts
	 */
	public function print_admin_scripts(){ ?>
		<script>
            ( function( $ ){
                function initColorPicker( widget ) {
                    widget.find( 'input.packpinColorPicker' ).wpColorPicker( {
                        change: _.throttle( function() { // For Customizer
                                $(this).trigger( 'change' );
                        }, 3000 )
                    });
                }
                function onFormUpdate( event, widget ) {
                    initColorPicker( widget );
                }
                $( document ).on( 'widget-added widget-updated', onFormUpdate );
                $( document ).ready( function() {
                    $( '.packpin_widget_admin:has(input.packpinColorPicker)' ).each( function () {
                        initColorPicker( $( this ) );
                    } );
                } );
            }( jQuery ) );
        </script><?php
	}
}

/**
 * Class Packpin_Widget_Static
 *
 * We need to store some values in a different class, for now static,
 * to be able to access it in some other areas (etc. shortcode).
 */
class Packpin_Widget_Static{
	/**
	 * Set up defaults form values in an array.
	 *
	 * @return array
	 */
	static public function widget_defaults( $type = 'default' ) {

		// HTML attributes have "data-button-*name*" format

		$attributes = array(
			'position'         	=> 'bottom',
			'width'           	=> '',
			'height'         	=> 'default',
			'color'         	=> '#ffffff',
			'background'        => '#e51c23',
		);

		$nonEditables = array(
			'carriers'        	=> '[]',
			'language'			=> '',
			'number'			=> '',
			'hide_number'		=> '0',
			'domain'			=> 'button.packpin.com',
		);

		$nonPackpin = array(
			'before_text'		=> '',
			'after_text'		=> '',
			'title'				=> ''
		);

		$return = array();
		switch($type){
			case 'attributes':
				$return = array_merge($attributes, $nonEditables);
				break;
			case 'default':
			default:
				$return = array_merge($attributes, $nonEditables, $nonPackpin);
				break;
		}

		return $return;
	}

	/**
	 * Set up attributes for HTML code
	 *
	 * @param $instance
	 * @return string
	 */
	static public function return_attributes($instance){
		$ready = array();

		$attribute_prefix = "data-button-";
		$domain_prefix = "data-";
		$attributes = array();

		$defaults = self::widget_defaults('attributes');

		foreach($defaults as $attr => $val){
			$key = str_replace('_','-', $attr);
			$key = ($attr == "domain") ? $domain_prefix.$key : $attribute_prefix.$key;
			$attributes[$key] = ($instance[$attr]) ?
				$instance[$attr] :
				$val
			;
		}

		foreach($attributes as $attr => $val){
			$ready[] = sprintf('%s="%s"', $attr, $val);
		}

		return implode(" ", $ready);
	}
}

/**
* Creates a [packpin] shortcode.
* Arguments are in Packpin_Widget_Static::widget_defaults('attributes')
* @TODO Actual documentation, live insert
*
* @return string
*/
function packpin_wordpress_shortcode( $instance ) {
	if($instance['id']){
		$widgetId = $instance['id'];
		unset($instance['id']);
	}

	$return_attributes = Packpin_Widget_Static::return_attributes($instance);

	ob_start();
	include( plugin_dir_path( __FILE__ ) . 'views/shortcode.php' );
	return ob_get_clean();
}
add_shortcode( 'packpin', 'packpin_wordpress_shortcode' );

add_action( 'widgets_init', create_function( '', 'register_widget("Packpin_Widget");' ) );