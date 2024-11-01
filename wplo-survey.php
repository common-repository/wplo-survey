<?php
/*
Plugin Name: WPLO Survey
Plugin URI: https://edgewebapps.com/wplo-survey/
Version: 2.5.1
Author: Edge Web Apps
Description: Connect Luminate Online surveys to WordPress without having to code- just enter the survey id and the plugin takes care of the rest.
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Author URI: https://edgewebapps.com/
*/
namespace WPLOSURVEY_NS;
define( 'WPLO_SURVEY_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPLO_SURVEY_URL', plugins_url( '/', __FILE__ ) );

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//Page templater code adapted from wpexplorer's work at: https://github.com/wpexplorer/page-templater/blob/master/pagetemplater.php

class WPLOSURVEY_Templater {

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Returns an instance of this class.
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new WPLOSURVEY_Templater();
        }

        return self::$instance;

    }

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();


        // Add a filter to the attributes metabox to inject template into the cache.
        if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

            // 4.6 and older
            add_filter(
                'page_attributes_dropdown_pages_args',
                array( $this, 'register_project_templates' )
            );

        } else {

            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                'theme_page_templates', array( $this, 'add_new_template' )
            );

        }

        // Add a filter to the save post to inject out template into the page cache
        add_filter(
            'wp_insert_post_data',
            array( $this, 'register_project_templates' )
        );


        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter(
            'template_include',
            array( $this, 'view_project_template')
        );


        // Add your templates to this array.
//        $this->templates = array(
//            'survey-process.php' => 'Luminate Survey Check',
//        );

    }

    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public function add_new_template( $posts_templates ) {
        $posts_templates = array_merge( $posts_templates, $this->templates );
        return $posts_templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public function register_project_templates( $atts ) {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }
        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');
        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );
        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );
        return $atts;
    }


    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template( $template ) {

        // Get global post
        global $post;

        // Return template if post is empty
        if ( ! $post ) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        if ( ! isset( $this->templates[get_post_meta(
                $post->ID, '_wp_page_template', true
            )] ) ) {
            return $template;
        }

        $file = plugin_dir_path( __FILE__ ). get_post_meta(
                $post->ID, '_wp_page_template', true
            );

        // Just to be safe, we check if the file exist first
        if ( file_exists( $file ) ) {
            return $file;
        } else {
            echo  esc_html($file);
        }

        // Return template
        return $template;

    }

}

add_action( 'plugins_loaded', array( 'WPLOSURVEY_NS\\WPLOSURVEY_Templater', 'get_instance' ) );


function WPLOSURVEY_enqueue_styles_and_scripts() {

    //Only loads styles/scripts when it's a survey post
    if ( is_singular( 'survey' ) ) {

        $dir = plugin_dir_url(__FILE__);
        $dir2 = plugin_dir_path(__FILE__);

        //Only enqueue these if WPLO Donate is not installed
        if (!class_exists('WPLODONATIONS_Templater')){
            wp_enqueue_script( 'wplodf-luminateExtendjs', $dir . 'assets/js/luminateExtend.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/luminateExtend.min.js' ));
        }

        //Always load these styles
        wp_enqueue_style( 'wplo-svy-bootstrap4css', $dir . 'assets/css/bootstrapcustom.css', array(), filemtime( $dir2 . 'assets/css/bootstrapcustom.css' ));
        wp_enqueue_style( 'wplo_style1_survey', $dir . 'assets/css/wp-lo-survey-style.css', array(), filemtime( $dir2 . 'assets/css/wp-lo-survey-style.css' ));
        wp_enqueue_style( 'jquery_ui_css', $dir . 'assets/css/jquery-ui.css', array(), filemtime( $dir2 . 'assets/css/jquery-ui.css' ));

        //Always load these scripts
        wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery' ) );
        wp_enqueue_script( 'luminateSurveyjs', $dir . 'assets/js/wp-lo-survey-scripts.js', array('jquery'), filemtime( $dir2 . 'assets/js/wp-lo-survey-scripts.js' ));
        wp_enqueue_script( 'jquery-validate', $dir . 'assets/js/jquery.validate.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/jquery.validate.min.js' ));
        wp_enqueue_script( 'wplodf-cleave', $dir . 'assets/js/cleave.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/cleave.min.js' ));

        //Excludes running shared scripts when WPLO Donate (might ignore shortcode wplo-donate though)
        if ( !is_singular( 'donations' ) ) {
            wp_enqueue_style( 'wplodf-font-awesome-free', $dir . 'assets/fonts/css/all.min.css', array(), filemtime( $dir2 . 'assets/fonts/css/all.min.css' ));
            wp_enqueue_script( 'wplodf-popperjs', $dir . 'assets/js/popper.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/popper.min.js' ));
            wp_enqueue_script( 'wplodf-bootstrap4jsbundlemin', $dir . 'assets/js/bootstrap.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/bootstrap.min.js' ));
        }

    }
}


add_action( 'wp_enqueue_scripts', 'WPLOSURVEY_NS\\WPLOSURVEY_enqueue_styles_and_scripts' );

/**
 * Register scripts for enqeueuing later if a shortcode is present on a page
 */

function WPLOSURVEY_register_scripts() {

    $dir = plugin_dir_url(__FILE__);
    $dir2 = plugin_dir_path(__FILE__);

    //Always load these scripts
    wp_register_script( 'jquery-ui-datepicker', array( 'jquery' ) );
    wp_register_script( 'luminateSurveyjs', $dir . 'assets/js/wp-lo-survey-scripts.js', array('jquery'), filemtime( $dir2 . 'assets/js/wp-lo-survey-scripts.js' ));
    wp_register_script( 'jquery-validate', $dir . 'assets/js/jquery.validate.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/jquery.validate.min.js' ));
    wp_register_script( 'wplodf-cleave', $dir . 'assets/js/cleave.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/cleave.min.js' ));

    //Excludes running shared scripts when WPLO Donate (might ignore shortcode wplo-donate though)
    if ( !is_singular( 'donations' ) ) {
        wp_register_script( 'wplodf-popperjs', $dir . 'assets/js/popper.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/popper.min.js' ));
        wp_register_script( 'wplodf-bootstrap4jsbundlemin', $dir . 'assets/js/bootstrap.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/bootstrap.min.js' ));
    }

    //Only enqueue these if WPLO Donate is not installed
    if (!class_exists('WPLODONATIONS_Templater')){
        wp_register_script( 'wplodf-luminateExtendjs', $dir . 'assets/js/luminateExtend.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/luminateExtend.min.js' ));
    }


}

add_action( 'wp_enqueue_scripts', 'WPLOSURVEY_NS\\WPLOSURVEY_register_scripts' );


/**
 * Admin area only- Adds the meta box stylesheet and js script when appropriate
 */
function WPLOSURVEY_admin_styles(){
    global $typenow;
    if( $typenow == 'survey' ) {

        $dir = plugin_dir_url(__FILE__);
        $dir2 = plugin_dir_path(__FILE__);

        wp_enqueue_style( 'WPLOSURVEY_meta_box_styles', $dir . 'assets/css/meta-survey-box-styles.css', array(), filemtime( $dir2 . 'assets/css/meta-survey-box-styles.css' ));

        wp_enqueue_script( 'wplodf-luminateExtendjs', $dir . 'assets/js/luminateExtend.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/luminateExtend.min.js' ));
        wp_enqueue_script( 'meta-survey-box-tabs-js', $dir . 'assets/js/meta-survey-box-tabs.js', array('jquery'), filemtime( $dir2 . 'assets/js/meta-survey-box-tabs.js' ));
        wp_enqueue_script( 'meta-survey-box-general-js', $dir . 'assets/js/meta-survey-box-general-scripts.js', array('jquery'), filemtime( $dir2 . 'assets/js/meta-survey-box-general-scripts.js' ));

    }
}
add_action( 'admin_print_styles', 'WPLOSURVEY_NS\\WPLOSURVEY_admin_styles' );

/**
 * Admin area only- Loads the color picker javascript
 */
function WPLOSURVEY_color_enqueue() {
    global $typenow;
    if( $typenow == 'survey' ) {
        $dir = plugin_dir_url(__FILE__);
        $dir2 = plugin_dir_path(__FILE__);

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'meta-survey-box-color-js', $dir . 'assets/js/meta-survey-box-color.js', array('wp-color-picker'), filemtime( $dir2 . 'assets/js/meta-survey-box-color.js' ));
        wp_enqueue_script( 'wp-color-picker-alpha-js', $dir . 'assets/js/wp-color-picker-alpha.min.js', array('wp-color-picker'), filemtime( $dir2 . 'assets/js/wp-color-picker-alpha.min.js' ));

    }
}
add_action( 'admin_enqueue_scripts', 'WPLOSURVEY_NS\\WPLOSURVEY_color_enqueue' );



/**
 * Register meta boxes.
 */
function WPLOSURVEY_register_meta_boxes() {

    add_meta_box( 'meta-survey-box-survey-id', __( 'Survey Form Setup', 'wplosurvey-textdomain' ), 'WPLOSURVEY_NS\\WPLOSURVEY_id_callback', 'survey' );

    add_meta_box( 'meta-survey-box-survey-questions', __( 'Survey Questions', 'wplosurvey-textdomain' ), 'WPLOSURVEY_NS\\WPLOSURVEY_questions_callback', 'survey' );

    add_meta_box( 'meta-survey-box-survey-css', __( 'Form CSS Styling', 'wplosurvey-textdomain' ), 'WPLOSURVEY_NS\\WPLOSURVEY_css_callback', 'survey' );

    add_meta_box( 'meta-survey-box-survey-custom-css', __( 'Custom CSS Coding', 'wplosurvey-textdomain' ), 'WPLOSURVEY_NS\\WPLOSURVEY_custom_css_callback', 'survey' );

    add_meta_box( 'meta-survey-box-survey-thankyou', __( 'Thank You Section', 'wplosurvey-textdomain' ), 'WPLOSURVEY_NS\\WPLOSURVEY_thank_you_callback', 'survey' );

    add_meta_box( 'meta-survey-box-survey-analytics', __( 'Post Transaction Analytics', 'wplosurvey-textdomain' ), 'WPLOSURVEY_NS\\WPLOSURVEY_post_analytics', 'survey' );


 }
add_action( 'add_meta_boxes', 'WPLOSURVEY_NS\\WPLOSURVEY_register_meta_boxes', 1 );


//Reorder metaboxes
add_filter( 'get_user_option_meta-survey-box-order_survey', 'WPLOSURVEY_NS\\WPLOSURVEY_metabox_order' );
function WPLOSURVEY_metabox_order( $order )
{
    return array(
        'normal' => join(
            ",",
            array(       // Arrange here as you desire
                'meta-survey-box-survey-id',
                'meta-survey-box-survey-questions',
                'meta-survey-box-survey-css',
                'meta-survey-box-survey-custom-css',
                'meta-survey-box-survey-thankyou',
                'meta-survey-box-survey-analytics',
            )
        ),
    );
}

//Add passing of form id to form when creating new post

//add_filter( 'default_content', 'WPLOSURVEY_NS\\WPLOSURVEY_lo_form_id', 10, 2 );
//
//function WPLOSURVEY_lo_form_id( $content, $post )
//{
//    if ( ! empty ( $_GET['lo_form_id'] )
//        and current_user_can( 'edit_post', $post->ID )
//        and '' === $content
//    )
//    {
//        return $_GET['lo_form_id'];
//    }
//
//    return $content;
//}

//Move Yoast to bottom
if(in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins')))){
    // do stuff only if Yoast is installed and active
    function WPLOSURVEY_yoasttobottom() {
        return 'low';
    }
    add_filter( 'wpseo_metabox_prio', 'WPLOSURVEY_NS\\WPLOSURVEY_yoasttobottom');
}


//Add Tabbed Navbar
    add_action( 'edit_form_after_title', function() {
        global $typenow;
        if( $typenow == 'survey' ) { ?>

            <?php global $post;
            $id = $post->ID;
            ?>

            <?php
            $sCode = '<?php echo do_shortcode(\'[wplosurvey_insert_post ids=' . ($id) . ']\'); ?>';
            ?>

            <div id="shortcode-box">
                <strong><label for="shortcode-link">Embed shortcode:</label></strong>
                <input type="text" name="shortcode-link" id="shortcode-link" value="[wplosurvey_insert_post ids=<?php echo esc_attr($id);?>]" readonly="readonly"><input type="text" name="shortcode-link" id="shortcode-link-php" value="<?php echo esc_attr($sCode)?>" readonly="readonly">

            </div>
            <h2 class="nav-tab-wrapper" style="padding-bottom:0!important;">
                <a href="#" class="nav-tab survey-tab-active" onclick="return tabSetup(event)"><span class="dashicons dashicons-admin-generic"></span> Form Setup</a>
                <a href="#" class="nav-tab" onclick="return tabStyle(event)"><span class="dashicons dashicons-admin-appearance"></span> Form Styling</a>
                <a href="#" class="nav-tab" onclick="return tabSections(event)"><span class="dashicons dashicons-admin-page"></span> Survey Success Actions</a>
                <a href="#" class="nav-tab" onclick="return tabAnalytics(event)"><span class="dashicons dashicons-chart-bar"></span> Analytics</a>
            </h2>
            <?php
        }
    });



/**
 * Meta box display callbacks.
 *
 * @param WP_Post $post Current post object.
 */


function WPLOSURVEY_css_callback( $post )
{
    wp_nonce_field(basename(__FILE__), 'WPLOSURVEY_nonce');
    $survey_stored_meta = get_post_meta($post->ID);

    ?>

    <p class="df-row-container description-below">
        <label for="meta-survey-input-justify" class="df-row-title"><?php _e( 'Justify Inputs', 'wplosurvey-textdomain' )?></label>
        <select name="meta-survey-input-justify" id="meta-survey-input-justify" class="df-row-input">
            <option value="none" <?php if ( isset ( $survey_stored_meta['meta-survey-input-justify'] ) ) selected( $survey_stored_meta['meta-survey-input-justify'][0], 'left' );  else echo('selected="selected"'); ?>><?php _e( 'Left', 'wplosurvey-textdomain' )?></option>';
            <option value="center" <?php if ( isset ( $survey_stored_meta['meta-survey-input-justify'] ) ) selected( $survey_stored_meta['meta-survey-input-justify'][0], 'center' ); ?>><?php _e( 'Center', 'wplosurvey-textdomain' )?></option>';
        </select>
    </p>
    <p class="description-box">Justify options for inputs on form- useful if you have only a single input and want it to be evenly centered when embedding.  Otherwise none is default function.</p>

    <p class="df-row-container description-below">
        <label for="meta-survey-input-single-line" class="df-row-title"><?php _e( 'Single or Two Inputs per Line', 'wplosurvey-textdomain' )?></label>
        <select name="meta-survey-input-single-line" id="meta-survey-input-single-line" class="df-row-input">
            <option value="single" <?php if ( isset ( $survey_stored_meta['meta-survey-input-single-line'] ) ) selected( $survey_stored_meta['meta-survey-input-single-line'][0], 'single' ); else echo('selected="selected"'); ?>><?php _e( 'Single', 'wplosurvey-textdomain' )?></option>';
            <option value="two" <?php if ( isset ( $survey_stored_meta['meta-survey-input-single-line'] ) ) selected( $survey_stored_meta['meta-survey-input-single-line'][0], 'two' ); ?>><?php _e( 'Two', 'wplosurvey-textdomain' )?></option>';
        </select>
    </p>
    <p class="description-box">Make all inputs single line, instead of two per line.  Default is single.</p>

    <div class="df-row-container description-below">
        <span class="df-row-title"><?php _e( 'Move Email Input to Top of Form', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-email-top-btn-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-email-top-btn" id="meta-survey-radio-email-top-btn-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-email-top-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-email-top-btn'][0], 'on' ); ?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-email-top-btn-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-email-top-btn" id="meta-survey-radio-email-top-btn-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-email-top-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-email-top-btn'][0], 'off' );  else echo('checked="checked"'); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>
    <p class="description-box">Email inputs are normally farther down the list on LO forms- move it up to the top of your survey using this setting.</p>

    <div class="df-row-container description-below">
        <span class="df-row-title"><?php _e( 'Enable Progress Bar for Survey', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-progress-bar-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-progress-bar" id="meta-survey-radio-progress-bar-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-progress-bar'] ) ) checked( $survey_stored_meta['meta-survey-radio-progress-bar'][0], 'on' ); ?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-progress-bar-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-progress-bar" id="meta-survey-radio-progress-bar-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-progress-bar'] ) ) checked( $survey_stored_meta['meta-survey-radio-progress-bar'][0], 'off' );  else echo('checked="checked"'); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>
    <p class="description-box">Enable a progress bar to track your survey's progress- used for petition campaigns.</p>

    <p class="df-row-container description-below">
        <label for="meta-survey-progress-bar-color" class="df-row-title"><?php _e( 'Progress Bar Color', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-progress-bar-color" class="df-row-input meta-survey-progress-bar-color" id="meta-survey-progress-bar-color" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-progress-bar-color'] ) ) echo esc_attr($survey_stored_meta['meta-survey-progress-bar-color'][0]); else echo esc_attr("#2c96f0"); ?>" />
    </p>
    <p class="description-box">Progress bar fill color.</p>

    <p class="df-row-container description-below">
        <label for="meta-survey-progress-bar-goal" class="df-row-title"><?php _e( 'Progress Bar Target Goal', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-progress-bar-goal" class="df-row-input meta-survey-progress-bar-goal" id="meta-survey-progress-bar-goal" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-progress-bar-goal'] ) ) echo esc_attr($survey_stored_meta['meta-survey-progress-bar-goal'][0]); else echo esc_attr("0"); ?>" />
    </p>
    <p class="description-box">Your target goal for your progress bar, required to make progress bar work.</p>


    <p class="df-row-container description-below">
        <label for="meta-survey-color" class="df-row-title"><?php _e( 'Primary Color', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-color" class="df-row-input meta-survey-color" id="meta-survey-color" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-color'] ) ) echo esc_attr($survey_stored_meta['meta-survey-color'][0]); else echo esc_attr("#2c96f0"); ?>" />
    </p>
    <p class="description-box">Used for input border.</p>

    <p class="df-row-container">
        <label for="meta-survey-input-border-width" class="df-row-title"><?php _e( 'Input Border Width', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-input-border-width" class="df-row-input meta-survey-input-border-width" id="meta-survey-input-border-width" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-input-border-width'] ) ) echo esc_attr($survey_stored_meta['meta-survey-input-border-width'][0]); else echo esc_attr("1px"); ?>" />
    </p>

    <p class="df-row-container">
        <label for="meta-survey-input-radius" class="df-row-title"><?php _e( 'Input Border Radius', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-input-radius" class="df-row-input meta-survey-input-radius" id="meta-survey-input-radius" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-input-radius'] ) ) echo esc_attr($survey_stored_meta['meta-survey-input-radius'][0]); else echo esc_attr("4px"); ?>" />
    </p>

    <p class="df-row-container">
        <label for="meta-survey-color-hover" class="df-row-title"><?php _e( 'Primary Color Hover/Active/Focus', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-color-hover" class="df-row-input meta-survey-color-hover" id="meta-survey-color-hover" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-color-hover'] ) ) echo esc_attr($survey_stored_meta['meta-survey-color-hover'][0]); else echo esc_attr("#66c1ff"); ?>" />
    </p>

    <p class="df-row-container">
        <label for="meta-survey-button-radius" class="df-row-title"><?php _e( 'Button Border Radius', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-button-radius" class="df-row-input meta-survey-button-radius" id="meta-survey-button-radius" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-button-radius'] ) ) echo esc_attr($survey_stored_meta['meta-survey-button-radius'][0]); else echo esc_attr("4px"); ?>" />
    </p>

    <p class="df-row-container">
        <label for="meta-survey-button-color" class="df-row-title"><?php _e( 'Main Button Color', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-button-color" class="df-row-input meta-survey-button-color" id="meta-survey-button-color" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-button-color'] ) ) echo esc_attr($survey_stored_meta['meta-survey-button-color'][0]); else echo esc_attr("#2c96f0"); ?>" />
    </p>

    <p class="df-row-container">
        <label for="meta-survey-button-color-hover" class="df-row-title"><?php _e( 'Main Button Color Hover/Active/Focus', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-button-color-hover" class="df-row-input meta-survey-button-color-hover" id="meta-survey-button-color-hover" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-button-color-hover'] ) ) echo esc_attr($survey_stored_meta['meta-survey-button-color-hover'][0]); else echo esc_attr("#66c1ff"); ?>" />
    </p>

    <p class="df-row-container description-below">
        <label for="meta-survey-font-size" class="df-row-title"><?php _e( 'Base Font Size', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-font-size" class="df-row-input" id="meta-survey-font-size" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-font-size'] ) ) echo esc_attr($survey_stored_meta['meta-survey-font-size'][0]); else echo esc_attr("16"); ?>" />
    </p>
    <p class="description-box">Set the base font size in pixels for your form- all elements will be based off this root value.  Eg. 16</p>


    <p class="df-row-container description-below">
        <label for="meta-survey-font-paragraph" class="df-row-title"><?php _e( 'Paragraph Font', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-font-paragraph" class="df-row-input" id="meta-survey-font-paragraph" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-font-paragraph'] ) ) echo esc_attr($survey_stored_meta['meta-survey-font-paragraph'][0]); else echo esc_attr("Arial, Serif"); ?>" />
    </p>
    <p class="description-box">Load your site paragraph font here in standard css font-family format. Use single quotes, not double. Eg: 'Arvo', Arial, Serif</p>

    <p class="df-row-container description-below">
        <label for="meta-survey-font-header" class="df-row-title"><?php _e( 'Header Font', 'wplosurvey-textdomain' )?></label>
        <input name="meta-survey-font-header" class="df-row-input" id="meta-survey-font-header" type="text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-font-header'] ) ) echo esc_attr($survey_stored_meta['meta-survey-font-header'][0]); else echo esc_attr("Arial, Serif"); ?>" />
    </p>
    <p class="description-box">Load your site header font here in standard css font-family format. Use single quotes, not double. Eg: 'Arvo', Arial, Serif</p>


    <div class="df-row-container description-below">
        <span class="df-row-title"><?php _e( 'Enable Font Awesome 5 Icons', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-fa-btn-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-fa-btn" id="meta-survey-radio-fa-btn-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-fa-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-fa-btn'][0], 'on' ); ?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-fa-btn-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-fa-btn" id="meta-survey-radio-fa-btn-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-fa-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-fa-btn'][0], 'off' );  else echo('checked="checked"'); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>
    <p class="description-box">Enables Font Awesome 5 Icons for nice looking checkboxes etc- if you already have a version of font awesome loaded on your website, leave this disabled.</p>


    <?php

}

function WPLOSURVEY_custom_css_callback( $post )
{
    wp_nonce_field(basename(__FILE__), 'WPLOSURVEY_nonce');
    $survey_stored_meta = get_post_meta($post->ID);

    ?>

    <p>
        <label for="meta-survey-custom-css" class="df-row-title"><?php _e( 'Enter custom css to apply only to this page:', 'wplosurvey-textdomain' )?></label>
        <textarea name="meta-survey-custom-css" id="meta-survey-custom-css"><?php if ( isset ( $survey_stored_meta['meta-survey-custom-css'] ) ) echo esc_html($survey_stored_meta['meta-survey-custom-css'][0]); ?></textarea>
    </p>

    <?php
}

function WPLOSURVEY_id_callback( $post ) {

    wp_nonce_field( basename( __FILE__ ), 'WPLOSURVEY_nonce' );
    $survey_stored_meta = get_post_meta( $post->ID );


    $currentPage = get_page( $post->ID );
        // page is published

    require_once(plugin_dir_path( __FILE__ ) . "meta-survey-box-survey-inputs-inc.php");

    ?>


    <!-- Survey Inputs Section -->

    <div style="display:flex;align-items:baseline;"><h3>Welcome to WPLO Survey 2.5</h3><p>&nbsp;by <a href="https://edgewebapps.com" target="_blank">Edge Web Apps</a></p></div>
    <p class="description-box margin-bottom-1x">All Luminate Online survey elements are now supported, with the exception of captcha (WPLO Survey already has built in survey bot protection). If you have any questions, contact us at <a href="https://wordpress.org/support/plugin/wplo-survey/">https://wordpress.org/plugins/wplo-survey/</a> or send us an email at <a href="mailto:info@edgewebapps.com?subject=WPLO%20Survey%20Support">info@edgewebapps.com.</a></p>
    <ol>
        <li>
            <a href="<?php echo esc_url( get_site_url() . '/wp-admin/edit.php?post_type=survey&page=WPLOSURVEY-list/')?>">View this auto-generated list of currently published LO surveys here</a>, and create a new survey in WordPress based on it.
            <br />
            <strong>OR</strong>
            <br />
            <a href="<?php echo esc_url( get_site_url() . '/wp-admin/post-new.php?post_type=survey')?>">Add a new WordPress survey</a>, and manually enter your survey ID from your Luminate Online Survey below.  To find this, find your survey in Luminate Online and click edit.  Take a look at the url in your browser bar and get the survey ID from it.
            <br />
            <img src="<?php echo(plugins_url( 'assets/images/survey_id.png' , __FILE__ ));?>" alt="Survey ID Location" style="margin-top:1rem;" />
        </li>
        <li>
            Click <strong>Get Survey Questions</strong>.
        </li>
        <li>
            Set <strong>Form Styling</strong>, <strong>Survey Success Actions</strong> or <strong>Analytics</strong> settings before publishing your form in WordPress (optional).
        </li>
        <li>
            Click <strong>Publish</strong> (or update if you've already published) on this page.  You're ready to use fully responsive Luminate Online surveys in WordPress that can be embedded anywhere on your site (use the [wplosurvey] embed code at the top of this page and paste anywhere in your wordpress content boxes).
        </li>
    </ol>

    <p><strong>If you update your survey fields in Luminate Online, you need to update the survey again in WordPress.  Just open the survey in WordPress and click "Get Survey Questions" below, and then "Update" to the right to sync the changes again.</strong></p>

    <br />

    <div class="df-row-container description-below">
        <label for="meta-survey-svy-setup" class="df-row-title-setup" style="width:200px;"><?php _e( 'Survey ID', 'wplosurvey-textdomain' )?></label>
        <div class="df-row-input-setup">
            <input type="text" name="meta-survey-svy-setup" id="meta-survey-svy-setup" value="<?php if ( isset ( $survey_stored_meta['meta-survey-svy-setup'] ) ) echo esc_attr($survey_stored_meta['meta-survey-svy-setup'][0]); elseif (isset($_GET['lo_survey_id'])) echo esc_attr($_GET['lo_survey_id']); ?>" />
            <button type="button" class="button" onclick="return WPLOSURVEY_update_surveyInputs(event);">Get Survey Questions</button>
        </div>
    </div>
    <div id="connection-test"></div>

    <?php if (get_option('WPLOSURVEY_apiKey') != null && get_option('WPLOSURVEY_nonsecure') != null && get_option('WPLOSURVEY_secure') != null && isset ( $survey_stored_meta['meta-survey-svy-setup'][0] ))  : ?>
        <script type="text/javascript">
            luminateExtend.init({
                apiKey: '<?php echo get_option( 'WPLOSURVEY_apiKey' ); ?>',
                path: {
                    nonsecure: '<?php echo get_option( 'WPLOSURVEY_nonsecure' ); ?>',
                    secure: '<?php echo get_option( 'WPLOSURVEY_secure' ); ?>'
                }
                <?php if( isset($survey_stored_meta['meta-survey-multi-locale']) && ( ($survey_stored_meta['meta-survey-multi-locale'][0]) !== "none")):?>
                ,locale: '<?php echo($survey_stored_meta['meta-survey-multi-locale'][0])?>'
                <?php endif;?>
            });
            luminateExtend.api.request({
                api: 'CRSurveyAPI',
                data: "method=getSurvey&survey_id=" + <?php echo esc_attr($survey_stored_meta['meta-survey-svy-setup'][0]) ?>,
                callback: submitSurveyCallback,
                requiresAuth: "true"
            });

        </script>
    <?php endif ?>



    <div class="df-row-container description-below">
        <label for="meta-survey-survey-contact-email" class="df-row-title"><?php _e( 'Contact E-mail', 'wplosurvey-textdomain' )?></label>
        <div class="df-row-input">
            <input type="text" name="meta-survey-survey-contact-email" id="meta-survey-survey-contact-email" value="<?php if ( isset ( $survey_stored_meta['meta-survey-survey-contact-email'] ) ) echo esc_attr($survey_stored_meta['meta-survey-survey-contact-email'][0]); ?>" />
        </div>
    </div>
    <p class="description-box margin-bottom-3x">This contact e-mail is provided to users if an error occurs and they need to contact your support department.</p>


    <?php if (get_option('WPLOSURVEY_apiKey') != null && get_option('WPLOSURVEY_nonsecure') != null && get_option('WPLOSURVEY_secure') != null && isset ( $survey_stored_meta['meta-survey-svy-setup'][0] ))  : ?>
        <script type="text/javascript">
            getLocalesCallback = {
                error: function(data) {
                    console.log(data);
                    jQuery("#form-locales").prepend(
                        '<h3>Available Form Locales:</h3>'
                    )
                },
                success: function(data) {
                    console.log(data);
                    if (data.listSupportedLocalesResponse.supportedLocale)
                    {
                        var formLocalesElement = jQuery("#form-locales");
                        formLocalesElement.prepend(
                            '<h3>Available Form Locales:</h3>'
                        );

                        var formLocaleSelect = jQuery("#localeSelect");

                        var formLocalePreSelected = jQuery("#checkMultiLocaleValue").val();

                        jQuery(data.listSupportedLocalesResponse.supportedLocale).each(function(){
                            if(formLocalePreSelected === this.fullyQualifiedName){
                                formLocaleSelect.append('<option value="'+ this.fullyQualifiedName +'" selected>' + this.displayName + '</option>');
                            }
                            else{
                                formLocaleSelect.append('<option value="'+ this.fullyQualifiedName +'">' + this.displayName + '</option>');
                            }
                        });
                    }
                }
            };


            luminateExtend.init({
                apiKey: '<?php echo get_option( 'WPLOSURVEY_apiKey' ); ?>',
                path: {
                    nonsecure: '<?php echo get_option( 'WPLOSURVEY_nonsecure' ); ?>',
                    secure: '<?php echo get_option( 'WPLOSURVEY_secure' ); ?>'
                }
            });
            luminateExtend.api.request({
                api: "CRContentAPI",
                data: "method=listSupportedLocales",
                callback: getLocalesCallback,
                requiresAuth: "true"
            });

        </script>
    <?php endif ?>


    <?php if (!get_option( 'WPLOSURVEY_apiKey' ) | !get_option( 'WPLOSURVEY_nonsecure' ) | !get_option( 'WPLOSURVEY_secure' ) ) : ?>
        <div class="notice notice-error is-dismissible">
            <p>Before getting started, head over to <a href="<?php echo esc_url( get_site_url() . '/wp-admin/edit.php?post_type=survey&page=WPLOSURVEY-options/')?>">Plugin Setup</a> and enter your Luminate API settings!</p>
        </div>
    <?php endif ?>

    <?php if ($currentPage->post_status !== 'publish') : ?>
        <div class="notice notice-warning is-dismissible">
            <p>If you're unable to publish or update your post on WP 5.x Gutenberg and keep getting a 403 error, goto  settings->permalinks and save permalinks twice.  If you have "Classic Editor" installed, you may also want to try switching the "default editor for all users" to the opposite of what it is currently on, save, and then switch it back to the previous setting and save.  If you're using an application level firewall like Wordfence, you may have to temporarily put your firewall back into learning mode so it learns that this plugin's functionality is safe to allow through.</p>
        </div>
    <?php endif ?>

    <?php

}



function WPLOSURVEY_questions_callback( $post ) {

    wp_nonce_field( basename( __FILE__ ), 'WPLOSURVEY_nonce' );
    $survey_stored_meta = get_post_meta( $post->ID );
    $post_note_privacy_meta_content = get_post_meta($post->ID, '_meta-survey-privacy-paragraph', TRUE);

    ?>

<!--    <div class="df-row-container description-below">-->
<!--        <p class="wplo-head-title">Survey </p>-->
<!--    </div>-->

    <p class="description-box">If working in a multi-locale environment in LO, select the locale for this survey and click update.</p>
    <input id="checkMultiLocaleValue" type="hidden" value="<?php if ( isset ( $survey_stored_meta['meta-survey-multi-locale'] )) echo($survey_stored_meta['meta-survey-multi-locale'][0])?>">
    <div id="form-locales">
        <label for="localeSelect">Choose a locale:</label>
        <select id="localeSelect" name="meta-survey-multi-locale">
            <option value="none" <?php if ( isset ( $survey_stored_meta['meta-survey-multi-locale'] ) ) selected( $survey_stored_meta['meta-survey-multi-locale'][0], 'none' ); ?>><?php _e( 'Default', 'wplosurvey-textdomain' )?></option>';
        </select>
    </div>

    <br />
    <hr />
    <br />
    <p class="description-box">Survey fields will auto-populate here if you've set your form up correctly.  You don't need to do anything with these- as long as some inputs show up below you're likely ready to go.</p>

    <div id="form-test"></div>


    <p class="df-row-title description-below" style="margin-bottom:0;padding-bottom:10px;">Privacy Info Paragraph (below submit button)</p>
    <p class="description-box margin-bottom-1x">If you'd like to display a privacy info message below your survey (eg small text saying "By completing this form you agree to sign up for our mailing list"), enter your information below.  Otherwise leave blank and WPLO Survey will ignore this field.</p>
    <?php wp_editor( $post_note_privacy_meta_content, 'meta-survey-privacy-paragraph', array('textarea_rows' => '5'));

    ?>

    <?php
}



function WPLOSURVEY_thank_you_callback( $post ) {

    wp_nonce_field( basename( __FILE__ ), 'WPLOSURVEY_nonce' );
    $survey_stored_meta = get_post_meta( $post->ID );
    $post_note_meta_content = get_post_meta($post->ID, '_meta-survey-thank-you-paragraph', TRUE);
    ?>

    <div class="df-row-container">
        <span class="df-row-title"><?php _e( 'Load Thank You Content From:', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-ty-content-btn-wp" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-ty-content-btn" id="meta-survey-radio-ty-content-btn-wp" value="wp" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-content-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-ty-content-btn'][0], 'wp' );?>>
                <?php _e( 'WordPress (below)', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-ty-content-btn-lo" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-ty-content-btn" id="meta-survey-radio-ty-content-btn-lo" value="lo" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-content-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-ty-content-btn'][0], 'lo' );  else echo('checked="checked"');?>>
                <?php _e( 'LO Survey TY Content', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>

    <p class="df-row-title description-below" style="margin-bottom:0;padding-bottom:10px;">Thank You Paragraph</p>
    <?php wp_editor( $post_note_meta_content, 'meta-survey-thank-you-paragraph', array('textarea_rows' => '5'));
    ?>
    <div class="df-row-container">
        <p class="description-box">
            Enter your thank you message for the inline thank you page.  This editor screen allows you to embed custom html, javascript, pixel trackers etc that can fire on completion of survey.  <br><strong>Note: adding html and scripts requires admin level user capabilities in WordPress.</strong>
        </p>
    </div>

    <div class="df-row-container">
        <span class="df-row-title"><?php _e( 'Turn On/Off Survey Reset Button', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-reset-btn-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-reset-btn" id="meta-survey-radio-reset-btn-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-reset-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-reset-btn'][0], 'on' );?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-reset-btn-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-reset-btn" id="meta-survey-radio-reset-btn-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-reset-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-reset-btn'][0], 'off' );  else echo('checked="checked"');?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>

    <div class="df-row-container">
        <span class="df-row-title"><?php _e( 'Turn On/Off Survey Skip Button', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-skip-btn-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-skip-btn" id="meta-survey-radio-skip-btn-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-skip-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-skip-btn'][0], 'on' ); ?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-skip-btn-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-skip-btn" id="meta-survey-radio-skip-btn-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-skip-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-skip-btn'][0], 'off' );  else echo('checked="checked"');?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>

    <div class="df-row-container">
        <span class="df-row-title"><?php _e( 'Add Link Button to Thank You Page', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-ty-btn-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-ty-btn" id="meta-survey-radio-ty-btn-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-ty-btn'][0], 'on' );  else echo('checked="checked"');?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-ty-btn-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-ty-btn" id="meta-survey-radio-ty-btn-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-ty-btn'][0], 'off' ); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>

    <p class="df-row-container">
        <label for="meta-survey-radio-ty-btn-link" class="df-row-title"><?php _e( 'TY Button Link', 'wplosurvey-textdomain' )?></label>
        <input type="text" name="meta-survey-radio-ty-btn-link" class="df-row-input" id="meta-survey-radio-ty-btn-link" value="<?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-btn-link'] ) ) echo esc_attr($survey_stored_meta['meta-survey-radio-ty-btn-link'][0]); else echo esc_url(home_url()); ?>" />
    </p>

    <p class="df-row-container">
        <label for="meta-survey-radio-ty-btn-text" class="df-row-title"><?php _e( 'TY Button Text', 'wplosurvey-textdomain' )?></label>
        <input type="text" name="meta-survey-radio-ty-btn-text" class="df-row-input" id="meta-survey-radio-ty-btn-text" value="<?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-btn-text'] ) ) echo esc_attr($survey_stored_meta['meta-survey-radio-ty-btn-text'][0]); else echo esc_attr("Homepage"); ?>" />
    </p>

    <div class="df-row-container description-below">
        <span class="df-row-title"><?php _e( 'Send Directly to Survey Submit Url from LO', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-ty-btn-override-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-ty-btn-override" id="meta-survey-radio-ty-btn-override-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-btn-override'] ) ) checked( $survey_stored_meta['meta-survey-radio-ty-btn-override'][0], 'on' );?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-ty-btn-override-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-ty-btn-override" id="meta-survey-radio-ty-btn-override-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-ty-btn-override'] ) ) checked( $survey_stored_meta['meta-survey-radio-ty-btn-override'][0], 'off' );  else echo('checked="checked"'); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>
    <p class="description-box">If you don't want dynamic thank you content on your survey, you can send users to the "Survey Submitted Page" url you set in your LO survey setup (under "Identify Survey", question 11).</p>


    <div class="df-row-container description-below">
        <span class="df-row-title"><?php _e( 'Logout User after Survey Submission', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-logoutSvy-btn-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-logoutSvy-btn" id="meta-survey-radio-logoutSvy-btn-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-logoutSvy-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-logoutSvy-btn'][0], 'on' ); ?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-logoutSvy-btn-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-logoutSvy-btn" id="meta-survey-radio-logoutSvy-btn-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-logoutSvy-btn'] ) ) checked( $survey_stored_meta['meta-survey-radio-logoutSvy-btn'][0], 'off' );  else echo('checked="checked"'); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>
    <p class="description-box">Allows you to have survey log users out of LO session after they submit their form (LO normally establishes a type of session after a survey submission).  Generally safe to leave off, but if you want to enforce unique responses only and you don't need users to be logged into LO always (eg you're not on a P2P site or in an admin area).</p>


    <?php

}


function WPLOSURVEY_post_analytics( $post ) {

    wp_nonce_field( basename( __FILE__ ), 'WPLOSURVEY_nonce' );
    $survey_stored_meta = get_post_meta( $post->ID );

    ?>

    <div class="df-row-container description-below">
        <span class="df-row-title"><?php _e( 'Built in Google Analytics', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-analytics-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-analytics" id="meta-survey-radio-analytics-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-analytics'] ) ) checked( $survey_stored_meta['meta-survey-radio-analytics'][0], 'on' ); ?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-analytics-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-analytics" id="meta-survey-radio-analytics-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-analytics'] ) ) checked( $survey_stored_meta['meta-survey-radio-analytics'][0], 'off' );  else echo('checked="checked"'); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>
    <p class="description-box">WPLO comes built in with Google Analytics tracking as part of your form, through a Google Tag Manager datalayer.  Default values are setup below, which will need to be mapped in GTM for your events data to show up in GA.</p>

    <p class="description-box">To setup tracking in GTM, you'll need to do 3 things:</p>

    <ol class="margin-bottom-3x">
        <li>
            Create a variable that holds your Universal Analytics (your UA ID from Google Analytics).  You can find this under the <strong>Variables</strong> menu option on the left in GTM, and then scroll down to <strong>User Defined Variables</strong> and click new.  Setup a new variable for your Universal Analytics (<a href="<?php echo(plugins_url( 'assets/images/gtm_variable_ua.png' , __FILE__ ));?>" target="_blank">screenshot of where to find this option</a>), fill out the fields, and then save.
        </li>
        <li>
            Then, you'll need to setup a GTM trigger.  You can find this under the <strong>Triggers</strong> menu option on the left in GTM, and then click <strong>New</strong>.  Click <strong>Custom Event</strong> from the list here, and then <a href="<?php echo(plugins_url( 'assets/images/gtm_trigger_values.png' , __FILE__ ));?>" target="_blank">fill it out like this screenshot</a> and save.
        </li>
        <li>
            Finally, setup a GTM tag.  You can find this under the <strong>Tags</strong> menu option on the left in GTM, and then click <strong>New</strong>.  Fill out a new tag <a href="<?php echo(plugins_url( 'assets/images/gtm_tag_setup.png' , __FILE__ ));?>" target="_blank">like this screenshot here</a>.  Click save, and then publish your GTM workspace!
        </li>
    </ol>

    <p class="df-row-container">
        <label for="meta-survey-analytics-event" class="df-row-title"><?php _e( 'GA Event', 'wplosurvey-textdomain' )?></label>
        <input type="text" name="meta-survey-analytics-event" class="df-row-input" id="meta-survey-analytics-event" value="<?php if ( isset ( $survey_stored_meta['meta-survey-analytics-event'] ) ) echo esc_attr($survey_stored_meta['meta-survey-analytics-event'][0]); else echo esc_attr("wploSurveySignup"); ?>" />
    </p>

    <p class="df-row-container">
        <label for="meta-survey-analytics-event-category" class="df-row-title"><?php _e( 'GA Event Category', 'wplosurvey-textdomain' )?></label>
        <input type="text" name="meta-survey-analytics-event-category" class="df-row-input" id="meta-survey-analytics-event-category" value="<?php if ( isset ( $survey_stored_meta['meta-survey-analytics-event-category'] ) ) echo esc_attr($survey_stored_meta['meta-survey-analytics-event-category'][0]); else echo esc_attr("Main Survey Form"); ?>" />
    </p>

    <p class="df-row-container description-below">
        <label for="meta-survey-analytics-event-value" class="df-row-title"><?php _e( 'GA Event Value', 'wplosurvey-textdomain' )?></label>
        <input type="text" name="meta-survey-analytics-event-value" class="df-row-input" id="meta-survey-analytics-event-value" value="<?php if ( isset ( $survey_stored_meta['meta-survey-analytics-event-value'] ) ) echo esc_attr($survey_stored_meta['meta-survey-analytics-event-value'][0]); else echo esc_attr("10"); ?>" />
    </p>
    <p class="description-box">Weight the conversion value for this event- for example, survey completions might be worth 5, link clicks worth 1, and newsletter signups worth 10 on your site.</p>


    <div class="df-row-container description-below">
        <span class="df-row-title"><?php _e( 'Send Facebook Analytics Complete Registration Event', 'wplosurvey-textdomain' )?></span>
        <div class="df-row-input">
            <label for="meta-survey-radio-fb-analytics-on" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-fb-analytics" id="meta-survey-radio-fb-analytics-on" value="on" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-fb-analytics'] ) ) checked( $survey_stored_meta['meta-survey-radio-fb-analytics'][0], 'on' ); ?>>
                <?php _e( 'On', 'wplosurvey-textdomain' )?>
            </label>
            <label for="meta-survey-radio-fb-analytics-off" class="meta-survey-radio-cls">
                <input type="radio" name="meta-survey-radio-fb-analytics" id="meta-survey-radio-fb-analytics-off" value="off" <?php if ( isset ( $survey_stored_meta['meta-survey-radio-fb-analytics'] ) ) checked( $survey_stored_meta['meta-survey-radio-fb-analytics'][0], 'off' );  else echo('checked="checked"'); ?>>
                <?php _e( 'Off', 'wplosurvey-textdomain' )?>
            </label>
        </div>
    </div>
    <p class="description-box">If you have facebook pixel installed on your WordPress site, you can turn on FB analytics tracking here.</p>

    <?php

}


/**
 * Saves the custom meta input
 */
function WPLOSURVEY_meta_save( $post_id, $post ) {

    //Only save if survey post type
    if ( 'survey' !== $post->post_type ) {
        return;
    }

    //print_r($post_id);

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset($_POST[ 'WPLOSURVEY_nonce' ] ) && wp_verify_nonce( $_POST[ 'WPLOSURVEY_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    $fields = [
        'meta-survey-custom-css',
        'meta-survey-analytics-event',
        'meta-survey-analytics-event-category',
        'meta-survey-analytics-event-value',
        'meta-survey-sv-surveyname-label',
        'meta-survey-sv-introduction-label',
        'meta-survey-sv-numbered-label',
        'meta-survey-sv-reset-label',
        'meta-survey-sv-submitlabel-label',
        'meta-survey-sv-cancel-label',
        'meta-survey-sv-submiturl-label',
        'meta-survey-sv-skipurl-label',
        'meta-survey-sv-count-label',
        'meta-survey-svy-setup',
        'meta-survey-font-header',
        'meta-survey-font-paragraph',
        'meta-survey-radio-ty-content-btn',
        'meta-survey-radio-ty-btn-link',
        'meta-survey-radio-ty-btn-text',
        'meta-survey-radio-ty-btn-override',
        'meta-survey-font-size',
        'meta-survey-button-radius',
        'meta-survey-input-single-line',
        'meta-survey-input-justify',
        'meta-survey-input-border-width',
        'meta-survey-input-radius',
        'meta-survey-progress-bar-goal',
        'meta-survey-multi-locale'
    ];

    $sub_fields = array();
    $new_fields = array();
    $meta_loop = 0;


    // Loop through question results
    while ($meta_loop <= ($_POST[ 'WPLOSURVEY_loopCount' ])) {
        $sub_meta_loop = 0;
        // Push values to array for each iteration for Caption
        array_push($new_fields,"meta-survey-sv-caption-" . ($meta_loop) . "-type","meta-survey-sv-caption-" . ($meta_loop) . "-text", "meta-survey-sv-caption-" . ($meta_loop) . "-id", "meta-survey-sv-caption-" . ($meta_loop) . "-required", "meta-survey-sv-caption-" . ($meta_loop) . "-hidden");

        // questions
        array_push($new_fields,"meta-survey-sv-question-" . ($meta_loop) . "-type","meta-survey-sv-question-" . ($meta_loop) . "-text", "meta-survey-sv-question-" . ($meta_loop) . "-id", "meta-survey-sv-question-" . ($meta_loop) . "-required");
            //Loop through sub-question field results
            while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
                // Push values to array for each iteration for question choices
                array_push($sub_fields,"meta-survey-sv-question-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-question-" . ($meta_loop) . "-fieldName-" . $sub_meta_loop, "meta-survey-sv-question-" . ($meta_loop) . "-fieldStatus-" . $sub_meta_loop, "meta-survey-sv-question-" . ($meta_loop) . "-fieldValues-" . $sub_meta_loop);
                $sub_meta_loop++;
            }
            $sub_meta_loop = 0;

        // date question
        array_push($new_fields,"meta-survey-sv-datequestion-" . ($meta_loop) . "-type","meta-survey-sv-datequestion-" . ($meta_loop) . "-text", "meta-survey-sv-datequestion-" . ($meta_loop) . "-id", "meta-survey-sv-datequestion-" . ($meta_loop) . "-required");

        // hidden interests
        array_push($new_fields,"meta-survey-sv-hiddenInterest-" . ($meta_loop) . "-type","meta-survey-sv-hiddenInterest-" . ($meta_loop) . "-text", "meta-survey-sv-hiddenInterest-" . ($meta_loop) . "-id", "meta-survey-sv-hiddenInterest-" . ($meta_loop) . "-required");
        //Loop through sub-question field results
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for hidden interests
            array_push($sub_fields,"meta-survey-sv-hiddenInterest-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-hiddenInterest-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // hidden text value
        array_push($new_fields,"meta-survey-sv-hiddentextvalue-" . ($meta_loop) . "-type","meta-survey-sv-hiddentextvalue-" . ($meta_loop) . "-text", "meta-survey-sv-hiddentextvalue-" . ($meta_loop) . "-id", "meta-survey-sv-hiddentextvalue-" . ($meta_loop) . "-required");

        // hidden true false
        array_push($new_fields,"meta-survey-sv-hiddentruefalse-" . ($meta_loop) . "-type","meta-survey-sv-hiddentruefalse-" . ($meta_loop) . "-text", "meta-survey-sv-hiddentruefalse-" . ($meta_loop) . "-id", "meta-survey-sv-hiddentruefalse-" . ($meta_loop) . "-required");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for true false
            array_push($sub_fields,"meta-survey-sv-truefalse-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-truefalse-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // Categories
        array_push($new_fields,"meta-survey-sv-categories-" . ($meta_loop) . "-type","meta-survey-sv-categories-" . ($meta_loop) . "-text", "meta-survey-sv-categories-" . ($meta_loop) . "-id", "meta-survey-sv-categories-" . ($meta_loop) . "-required");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for categories
            array_push($sub_fields,"meta-survey-sv-categories-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-categories-" . ($meta_loop) . "-value-" . $sub_meta_loop, "meta-survey-sv-categories-" . ($meta_loop) . "-selected-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // combo choice
        array_push($new_fields,"meta-survey-sv-combochoice-" . ($meta_loop) . "-type","meta-survey-sv-combochoice-" . ($meta_loop) . "-text", "meta-survey-sv-combochoice-" . ($meta_loop) . "-id", "meta-survey-sv-combochoice-" . ($meta_loop) . "-required", "meta-survey-sv-combochoice-" . ($meta_loop) . "-min", "meta-survey-sv-combochoice-" . ($meta_loop) . "-max");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for combo choice
            array_push($sub_fields,"meta-survey-sv-combochoice-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-combochoice-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // multi multi checkbox
        array_push($new_fields,"meta-survey-sv-multimulti-" . ($meta_loop) . "-type","meta-survey-sv-multimulti-" . ($meta_loop) . "-text", "meta-survey-sv-multimulti-" . ($meta_loop) . "-id", "meta-survey-sv-multimulti-" . ($meta_loop) . "-required", "meta-survey-sv-multimulti-" . ($meta_loop) . "-min", "meta-survey-sv-multimulti-" . ($meta_loop) . "-max");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for multi multi checkbox
            array_push($sub_fields,"meta-survey-sv-multimulti-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-multimulti-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // multi single
        array_push($new_fields,"meta-survey-sv-multisingle-" . ($meta_loop) . "-type","meta-survey-sv-multisingle-" . ($meta_loop) . "-text", "meta-survey-sv-multisingle-" . ($meta_loop) . "-id", "meta-survey-sv-multisingle-" . ($meta_loop) . "-required", "meta-survey-sv-multisingle-" . ($meta_loop) . "-max");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for multi single
            array_push($sub_fields,"meta-survey-sv-multisingle-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-multisingle-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // multi single radio
        array_push($new_fields,"meta-survey-sv-multisingleradio-" . ($meta_loop) . "-type","meta-survey-sv-multisingleradio-" . ($meta_loop) . "-text", "meta-survey-sv-multisingleradio-" . ($meta_loop) . "-id", "meta-survey-sv-multisingleradio-" . ($meta_loop) . "-required");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for multi single radio
            array_push($sub_fields,"meta-survey-sv-multisingleradio-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-multisingleradio-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // numeric value
        array_push($new_fields,"meta-survey-sv-numericvalue-" . ($meta_loop) . "-type","meta-survey-sv-numericvalue-" . ($meta_loop) . "-text", "meta-survey-sv-numericvalue-" . ($meta_loop) . "-id", "meta-survey-sv-numericvalue-" . ($meta_loop) . "-required");

        // rating scale
        array_push($new_fields,"meta-survey-sv-ratingscale-" . ($meta_loop) . "-type","meta-survey-sv-ratingscale-" . ($meta_loop) . "-text", "meta-survey-sv-ratingscale-" . ($meta_loop) . "-id", "meta-survey-sv-ratingscale-" . ($meta_loop) . "-required");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for rating scale
            array_push($sub_fields,"meta-survey-sv-ratingscale-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-ratingscale-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // shorttextvalue
        array_push($new_fields,"meta-survey-sv-shorttext-" . ($meta_loop) . "-type","meta-survey-sv-shorttext-" . ($meta_loop) . "-text", "meta-survey-sv-shorttext-" . ($meta_loop) . "-id", "meta-survey-sv-shorttext-" . ($meta_loop) . "-required");

        // textvalue
        array_push($new_fields,"meta-survey-sv-textvalue-" . ($meta_loop) . "-type","meta-survey-sv-textvalue-" . ($meta_loop) . "-text", "meta-survey-sv-textvalue-" . ($meta_loop) . "-id", "meta-survey-sv-textvalue-" . ($meta_loop) . "-required");

        // yes no
        array_push($new_fields,"meta-survey-sv-truefalse-" . ($meta_loop) . "-type","meta-survey-sv-truefalse-" . ($meta_loop) . "-text", "meta-survey-sv-truefalse-" . ($meta_loop) . "-id", "meta-survey-sv-truefalse-" . ($meta_loop) . "-required");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for yes no
            array_push($sub_fields,"meta-survey-sv-yesno-" . ($meta_loop) . "-label-" . $sub_meta_loop, "meta-survey-sv-yesno-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;

        // large text value
        array_push($new_fields,"meta-survey-sv-largetextvalue-" . ($meta_loop) . "-type","meta-survey-sv-largetextvalue-" . ($meta_loop) . "-text", "meta-survey-sv-largetextvalue-" . ($meta_loop) . "-id", "meta-survey-sv-largetextvalue-" . ($meta_loop) . "-required", "meta-survey-sv-largetextvalue-" . ($meta_loop) . "-hint");

        // yes no
        array_push($new_fields,"meta-survey-sv-yesno-" . ($meta_loop) . "-type","meta-survey-sv-yesno-" . ($meta_loop) . "-text", "meta-survey-sv-yesno-" . ($meta_loop) . "-id", "meta-survey-sv-yesno-" . ($meta_loop) . "-required");

        // captcha
        array_push($new_fields,"meta-survey-sv-captcha-" . ($meta_loop) . "-type","meta-survey-sv-captcha-" . ($meta_loop) . "-text", "meta-survey-sv-captcha-" . ($meta_loop) . "-id", "meta-survey-sv-captcha-" . ($meta_loop) . "-required");
        while ($sub_meta_loop <= ($_POST[ 'WPLOSURVEY_subloopMaxCount' ])) {
            // sub-questions for captcha
            array_push($sub_fields,"meta-survey-sv-captcha-" . ($meta_loop) . "-imagesource-" . $sub_meta_loop, "meta-survey-sv-audiolink-" . ($meta_loop) . "-value-" . $sub_meta_loop, "meta-survey-sv-audiolinklabel-" . ($meta_loop) . "-value-" . $sub_meta_loop, "meta-survey-sv-standaloneplayerlabel-" . ($meta_loop) . "-value-" . $sub_meta_loop, "meta-survey-sv-newwindowlabel-" . ($meta_loop) . "-value-" . $sub_meta_loop, "meta-survey-sv-changeimagelabel-" . ($meta_loop) . "-value-" . $sub_meta_loop);
            $sub_meta_loop++;
        }
        $sub_meta_loop = 0;


        $meta_loop++;
    }



    $fields = array_merge($fields, $new_fields, $sub_fields);

    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
        else {
            delete_post_meta( $post_id, $field );
//            update_post_meta( $post_id, $field, null );
        }
    }



    $wysiwyg_fields = [
        'meta-survey-thank-you-paragraph',
        'meta-survey-privacy-paragraph'
    ];

    foreach ( $wysiwyg_fields as $wysiwyg_field ) {
        if ( array_key_exists( $wysiwyg_field, $_POST ) ) {

            //Only admin users with "unflitered html" capability can save scripts/all html using the wysiwyg fields.
            //We do a sanitize filter for anyone without this capability using the default wp allowed html tags.
            //If user is an admin (super admin for multisite), or has user capability "unfiltered html" this is skipped,
            //since admins will want to add extra html/scripts that fire inline sometimes.
            if( !current_user_can('unfiltered_html') ) {
                $_POST[$wysiwyg_field] = wp_kses_post_deep( $_POST[$wysiwyg_field] );
            }

            update_post_meta( $post_id, "_" . $wysiwyg_field, $_POST[$wysiwyg_field] );
        }
    }


    // Checks for radio inputs and saves if needed
    $meta_radio_fields = [
        'meta-survey-radio',
        'meta-survey-radio-analytics',
        'meta-survey-radio-fb-analytics',
        'meta-survey-radio-ty-btn',
        'meta-survey-radio-reset-btn',
        'meta-survey-radio-skip-btn',
        'meta-survey-radio-email-top-btn',
        'meta-survey-radio-progress-bar',
        'meta-survey-radio-fa-btn',
        'meta-survey-radio-logoutSvy-btn'
    ];

    foreach ( $meta_radio_fields as $meta_radio_field ) {
        if ( array_key_exists( $meta_radio_field, $_POST ) ) {
            update_post_meta( $post_id, $meta_radio_field, sanitize_text_field($_POST[$meta_radio_field]) );
        }
    }



    // Checks for email inputs and saves if needed.
    $meta_email_fields = [
        'meta-survey-survey-contact-email',
    ];

    foreach ( $meta_email_fields as $meta_email_field ) {
        if ( array_key_exists( $meta_email_field, $_POST ) ) {
            update_post_meta( $post_id, $meta_email_field, sanitize_email($_POST[$meta_email_field]) );
        }
    }


    // Checks for meta-survey-color inputs and saves if needed.  Colors can be either HEX or RGB.
    $meta_color_fields = [
        'meta-survey-color',
        'meta-survey-color-hover',
        'meta-survey-button-color',
        'meta-survey-button-color-hover',
        'meta-survey-progress-bar-color'
    ];

    foreach ( $meta_color_fields as $meta_color_field ) {
        if ( array_key_exists( $meta_color_field, $_POST ) ) {
            update_post_meta( $post_id, $meta_color_field, sanitize_text_field($_POST[$meta_color_field]) );
        }
    }



}
add_action( 'save_post', 'WPLOSURVEY_NS\\WPLOSURVEY_meta_save', 10,2 );



/**
 * Creates a custom post type for the plugin
 */
function WPLOSURVEY_create_post() {
    register_post_type( 'survey,',
        array(
            'labels'       => array(
                'name'       => __( 'WPLO Survey' ),
            ),
            'public'       => true,
            'hierarchical' => true,
            'has_archive'  => false,
            'show_in_rest' => true,
            'supports'     => array(
                'title',
                'thumbnail',
            ),
            'rewrite' => array(
                'slug' => 'survey',
                'with_front' => false
            ),
            'menu_icon' => 'dashicons-welcome-add-page',
//            'taxonomies'   => array(
//                'post_tag',
//                'category',
//            )
        )
    );

}
add_action( 'init', 'WPLOSURVEY_NS\\WPLOSURVEY_create_post' );

/**
 * Custom shortcode for rendering your survey form anywhere
 */
function WPLOSURVEY_register_insert_shortcode($atts){

    extract(shortcode_atts(array(
        'ids' => '', // this is what I need
    ), $atts));

    $dir = plugin_dir_url(__FILE__);
    $dir2 = plugin_dir_path(__FILE__);

    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_script( 'wplodf-popperjs' );
    wp_enqueue_script( 'wplodf-bootstrap4jsbundlemin' );
    wp_enqueue_script( 'luminateSurveyjs' );
    //Don't load luminate extend if WPLO Donate is installed, handles it by itself
    if (!class_exists('WPLODONATIONS_Templater')){
        wp_enqueue_script( 'wplodf-luminateExtendjs', $dir . 'assets/js/luminateExtend.min.js', array('jquery'), filemtime( $dir2 . 'assets/js/luminateExtend.min.js' ));
    }
    wp_enqueue_script( 'jquery-validate' );
    wp_enqueue_script( 'wplodf-cleave' );


    $shortcodeID = $atts['ids'];

    // creating the array of arguments for the query
    $new_atts = array(
        'post_type'        => "survey",
        'post_id'          => $atts['ids'],
    );


    // The Query
    $the_query = new \WP_Query( $new_atts );

    // The Loop
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
//            $the_query->the_post();
            ob_start();
            include_once(plugin_dir_path( __FILE__ ) . "single-survey-form.php");
            return ob_get_clean();
        }

        /* Restore original Post Data */

        wp_reset_postdata();

    }

}
add_shortcode( 'wplosurvey_insert_post', 'WPLOSURVEY_NS\\WPLOSURVEY_register_insert_shortcode' );



/**
 * Register Beaver Builder module if Beaver Builder is active
 */
function WPLOSURVEY_register_beaver_builder() {
    //Check if beaver builder loaded, if so load module
    if ( class_exists( 'FLBuilder' ) ) {
        require_once 'beaver-wplo-survey-module/beaver-wplo-survey-module.php';
    }
}
add_action( 'init', 'WPLOSURVEY_NS\\WPLOSURVEY_register_beaver_builder' );


/**
 * Add description to plugin edit page, first page you see when in plugin
 */
add_filter( 'views_edit-survey', function( $views )
{
    echo '<div class="postbox" style="margin-top:10px;"><div class="inside">
   <div style="display:flex;align-items:baseline;"><h3>Welcome to WPLO Survey 2.5</h3><p>&nbsp;by <a href="https://edgewebapps.com" target="_blank">Edge Web Apps</a></p></div>
    <p class="description-box margin-bottom-1x">All Luminate Online survey elements are now supported, with the exception of captcha (WPLO Survey already has built in survey bot protection). If you have any questions, contact us at <a href="https://wordpress.org/support/plugin/wplo-survey/">https://wordpress.org/plugins/wplo-survey/</a> or send us an email at <a href="mailto:info@edgewebapps.com?subject=WPLO%20Survey%20Support">info@edgewebapps.com.</a></p>
    <ol>
        <li>
            <a href="' . esc_url( get_site_url() . "/wp-admin/edit.php?post_type=survey&page=WPLOSURVEY-list/") . '">View this auto-generated list of currently published LO surveys here</a>, and create a new survey in WordPress based on it.
            <br />
            <strong>OR</strong>
            <br />
            <a href="' . esc_url( get_site_url() . "/wp-admin/post-new.php?post_type=survey") .'">Add a new WordPress survey</a>, and manually enter your survey ID from your Luminate Online Survey.  To find this, find your survey in Luminate Online and click edit.  Take a look at the url in your browser bar and get the survey ID from it.
        </li>
        <li>
            Click <strong>Get Survey Questions</strong> when editing your WPLO Survey form.
        </li>
        <li>
            Set <strong>Form Styling</strong>, <strong>Survey Success Actions</strong> or <strong>Analytics</strong> settings before publishing your form in WordPress (optional).
        </li>
        <li>
            Click <strong>Publish</strong> (or update if you\'ve already published) on this page.  You\'re ready to use fully responsive Luminate Online surveys in WordPress that can be embedded anywhere on your site (use the [wplosurvey] embed code at the top of this page and paste anywhere in your wordpress content boxes).
        </li>
    </ol>
    <p><strong>If you update your survey fields in Luminate Online, you need to update the survey again in WordPress.  Just open the survey in WordPress and click "Get Survey Questions", and then "Update" to sync the changes again.</strong></p>
    </div></div>
    ';

    return $views;
} );

/**
 * Add extra columns + data for custom post type display
 */

add_filter( 'manage_survey_posts_columns', 'WPLOSURVEY_NS\\WPLOSURVEY_set_custom_columns' );
function WPLOSURVEY_set_custom_columns($columns) {
    $columns['WordPress Embed Code'] = __( 'WordPress Embed Code', 'wplosurvey-textdomain' );
    $columns['PHP Embed Code'] = __( 'PHP Embed Code', 'wplosurvey-textdomain' );

    return $columns;
}

add_action( 'manage_survey_posts_custom_column' , 'WPLOSURVEY_NS\\WPLOSURVEY_custom_column', 10, 2 );
function WPLOSURVEY_custom_column( $column, $post_id ) {
    switch ( $column ) {

        case 'WordPress Embed Code' :
            $terms = get_the_term_list( $post_id , 'book_author' , '' , ',' , '' );
            echo __("<input type=\"text\" name=\"shortcode-link\" id=\"shortcode-link\" value=\"[wplosurvey_insert_post ids=" . ($post_id) . "]\" readonly=\"readonly\">");
            break;

        case 'PHP Embed Code' :
            $sCode = '<?php echo do_shortcode(\'[wplosurvey_insert_post ids=' . ($post_id) . ']\'); ?>';
            echo __("<input type=\"text\" name=\"shortcode-link\" id=\"shortcode-link-php\" value=\"" . ($sCode) . "\" readonly=\"readonly\">");
            break;

    }
}


/**
 * Load survey form template for plugin custom post type
 */
function WPLOSURVEY_load_template($template) {
    global $post;

    if ($post->post_type == "survey" && $template !== locate_template(array("single-survey-form.php"))){
        return plugin_dir_path( __FILE__ ) . "single-survey-form.php";
    }

    return $template;
}

add_filter('single_template', 'WPLOSURVEY_NS\\WPLOSURVEY_load_template');

/**
 * Options page for CPT
 */

add_action('admin_menu', 'WPLOSURVEY_NS\\register_WPLOSURVEY_options_page');
function register_WPLOSURVEY_options_page() {
    add_submenu_page( 'edit.php?post_type=survey', 'Plugin Setup', 'Plugin Setup', 'manage_options', 'WPLOSURVEY-options', 'WPLOSURVEY_NS\\WPLOSURVEY_callback' );
    add_action( 'admin_init', 'WPLOSURVEY_NS\\WPLOSURVEY_apiKey' );
    add_action( 'admin_init', 'WPLOSURVEY_NS\\WPLOSURVEY_nonsecure' );
    add_action( 'admin_init', 'WPLOSURVEY_NS\\WPLOSURVEY_secure' );
    add_action( 'admin_init', 'WPLOSURVEY_NS\\WPLOSURVEY_testFormId' );
}

/**
 * Lo autopopulated Survey list for CPT
 */
add_action('admin_menu', 'WPLOSURVEY_NS\\register_WPLOSURVEY_list_page');
function register_WPLOSURVEY_list_page() {
    add_submenu_page( 'edit.php?post_type=survey', 'LO Survey List', 'LO Survey List', 'manage_options', 'WPLOSURVEY-list', 'WPLOSURVEY_NS\\WPLOSURVEY_list_callback' );
}


if( !function_exists("WPLOSURVEY_apiKey") ) {
    function WPLOSURVEY_apiKey() {
        register_setting( 'WPLOSURVEY-options', 'WPLOSURVEY_apiKey' );
    }
}

if( !function_exists("WPLOSURVEY_nonsecure") ) {
    function WPLOSURVEY_nonsecure() {
        register_setting( 'WPLOSURVEY-options', 'WPLOSURVEY_nonsecure' );
    }

}

if( !function_exists("WPLOSURVEY_secure") ) {
    function WPLOSURVEY_secure() {
        register_setting( 'WPLOSURVEY-options', 'WPLOSURVEY_secure' );
    }
}

if( !function_exists("WPLOSURVEY_testFormId") ) {
    function WPLOSURVEY_testFormId() {
        register_setting( 'WPLOSURVEY-options', 'WPLOSURVEY_testFormId' );
    }
}

function WPLOSURVEY_list_callback() {
    ?>
    <h1>LO Survey List</h1>
    <p>Click an LO survey below to create a WPLO Survey to use in WordPress.</p>

    <?php require_once(plugin_dir_path( __FILE__ ) . "cnct-set.php"); ?>

    <script type="text/javascript">

        listSurveysCallback = {
            error: function(data) {
                console.log(data);
                jQuery("#lo-list-container").append(
                    '<h3>Testing Connection:</h3>' +
                    '' +
                    '<h2><span class="dashicons dashicons-no" style="color:red;"></span> Connection not working!  Check browser console log for more info.</h2>'
                )
            },
            success: function(data) {
                console.log(data);
                if (data.listSurveysResponse.surveys)
                {

                    var formatOptions = {
                        day:    '2-digit',
                        month:  '2-digit',
                        year:   'numeric',
                        hour:   '2-digit',
                        minute: '2-digit',
                        hour12: true
                    };

                    jQuery("#lo-list-container").empty();

                    for (let index = 0; index < data.listSurveysResponse.surveys.length; ++index) {
                        let value = data.listSurveysResponse.surveys[index];

                        var date = new Date(value.publishedDate);

                        var dateString = date.toLocaleDateString('en-US', formatOptions);
                        dateString = dateString.replace(',', '')
                            .replace('PM', 'p.m.')
                            .replace('AM', 'a.m.');

                        jQuery("#lo-list-container").append(
                            '            <tr id="post-393" class="author-self level-0 post-393 type-survey status-publish hentry">\n' +
                            '                <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">\n' +
                            '                    <strong><a class="row-title" href="<?php echo get_site_url() ?>/wp-admin/post-new.php?post_type=survey&lo_survey_id='+ value.surveyId + '" aria-label="“Test Survey” (New)">' + value.surveyName + '</a></strong>\n' +
                            '                </td>\n' +
                            '                <td class="title column-title has-row-actions column-primary page-title" data-colname="Date">\n' +
                            '                    <strong>' + date + '</strong>\n' +
                            '                </td>\n' +
                            '            </tr>'
                        );
                    }


                }
            }
        };

        luminateExtend.init({
            apiKey: '<?php echo get_option( 'WPLOSURVEY_apiKey' ); ?>',
            path: {
                nonsecure: '<?php echo get_option( 'WPLOSURVEY_nonsecure' ); ?>',
                secure: '<?php echo get_option( 'WPLOSURVEY_secure' ); ?>'
            }
            <?php if( isset($survey_stored_meta['meta-survey-multi-locale']) && ( ($survey_stored_meta['meta-survey-multi-locale'][0]) !== "none")):?>
            ,locale: '<?php echo($survey_stored_meta['meta-survey-multi-locale'][0])?>'
            <?php endif;?>
        });

        luminateExtend.api.request({
            api: 'CRSurveyAPI',
            data: "method=listSurveys&list_sort_column=publishedDate&list_page_size=500&list_ascending=false",
            callback: listSurveysCallback,
            requiresAuth: "true"
        });


    </script>

    <table class="wp-list-table widefat fixed striped pages" style="max-width:1000px;">

        <tbody id="lo-list-container">



        </tbody>

    </table>


    <?php
}


function WPLOSURVEY_callback() {
    ?>
    <h1>Survey Form Server Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'WPLOSURVEY-options' ); ?>
        <?php do_settings_sections( 'WPLOSURVEY-options' ); ?>


        <?php if (get_option('WPLOSURVEY_apiKey') != null && get_option('WPLOSURVEY_nonsecure') != null && get_option('WPLOSURVEY_secure') != null && get_option('WPLOSURVEY_testFormId') != null)  : ?>

            <?php require_once(plugin_dir_path( __FILE__ ) . "cnct-set.php"); ?>

            <script type="text/javascript">

                submitSurveyCallback = {
                    error: function(data) {
                        console.log(data);
                            jQuery("#form-test").append(
                                '<h3>Testing Connection:</h3>' +
                                '' +
                                '<h2><span class="dashicons dashicons-no" style="color:red;"></span> Connection not working!  Check browser console log for more info.</h2>'
                            )
                    },
                    success: function(data) {
                        if (data.getSurveyResponse.survey.surveyId)
                        {
                            jQuery("#form-test").append(
                                '<h3>Testing Connection:</h3>' +
                            '' +
                                '<h2><span class="dashicons dashicons-yes" style="color:green;"></span> Connection verified as working!</h2>'
                            )
                        }
                    }
                };

                luminateExtend.init({
                    apiKey: '<?php echo get_option( 'WPLOSURVEY_apiKey' ); ?>',
                    path: {
                        nonsecure: '<?php echo get_option( 'WPLOSURVEY_nonsecure' ); ?>',
                        secure: '<?php echo get_option( 'WPLOSURVEY_secure' ); ?>'
                    }
                    <?php if( isset($survey_stored_meta['meta-survey-multi-locale']) && ( ($survey_stored_meta['meta-survey-multi-locale'][0]) !== "none")):?>
                    ,locale: '<?php echo($survey_stored_meta['meta-survey-multi-locale'][0])?>'
                    <?php endif;?>
                });

                luminateExtend.api.request({
                    api: 'CRSurveyAPI',
                    data: "method=getSurvey&survey_id=<?php echo get_option( 'WPLOSURVEY_testFormId' ); ?>",
                    callback: submitSurveyCallback,
                    requiresAuth: "true"
                });


            </script>

        <div id="form-test"></div>

        <?php endif ?>


        <p>For information on where to find these settings or set them up, visit <a href="http://open.convio.com/api/apidoc/general/site_configuration.html" target="_blank">BlackBaud's Luminate API library</a>.</p>

        <p>
            <label for="WPLOSURVEY_apiKey" class="df-row-title">Organization Public API Key</label><br />
            <span>Example: yourorgkey</span><br />
            <input type="text" name="WPLOSURVEY_apiKey" id="WPLOSURVEY_apiKey" value="<?php echo get_option( 'WPLOSURVEY_apiKey' ); ?>" />
        </p>

        <p>
            <label for="WPLOSURVEY_nonsecure" class="df-row-title">Nonsecure Server Path</label><br />
            <span>Example: http://www.myorganization.com/site/</span><br />
            <input type="text" name="WPLOSURVEY_nonsecure" id="WPLOSURVEY_nonsecure" value="<?php echo get_option( 'WPLOSURVEY_nonsecure' ); ?>"/>
        </p>

        <p>
            <label for="WPLOSURVEY_secure" class="df-row-title">Secure Server Path</label><br />
            <span>Example: https://secure2.convio.net/myorg/site/</span><br />
            <input type="text" name="WPLOSURVEY_secure" id="WPLOSURVEY_secure" value="<?php echo get_option( 'WPLOSURVEY_secure' ); ?>"/>
        </p>

        <p>
            <label for="WPLOSURVEY_testFormId" class="df-row-title">Enter a Published Survey ID for testing server connection</label><br />
            <span>Example: 1001</span><br />
            <input type="text" name="WPLOSURVEY_testFormId" id="WPLOSURVEY_testFormId" value="<?php echo get_option( 'WPLOSURVEY_testFormId' ); ?>"/>
        </p>


        <?php submit_button(); ?>




    </form>

    <?php
}


if( !function_exists("WPLOSURVEY_NS\\WPLOSURVEY_apiKey") )
{

    function WPLOSURVEY_apiKey($content)
    {
        $extra_info = get_option( 'WPLOSURVEY_apiKey' );

        return $content . $extra_info;
    }

    add_filter( 'the_content', 'WPLOSURVEY_NS\\WPLOSURVEY_apiKey' );

}

if( !function_exists("WPLOSURVEY_NS\\WPLOSURVEY_nonsecure") )
{

    function WPLOSURVEY_nonsecure($content)
    {
        $extra_info = get_option( 'WPLOSURVEY_nonsecure' );

        return $content . $extra_info;
    }

    add_filter( 'the_content', 'WPLOSURVEY_NS\\WPLOSURVEY_nonsecure' );

}

if( !function_exists("WPLOSURVEY_NS\\WPLOSURVEY_secure") )
{

    function WPLOSURVEY_secure($content)
    {
        $extra_info = get_option( 'WPLOSURVEY_secure' );

        return $content . $extra_info;
    }

    add_filter( 'the_content', 'WPLOSURVEY_NS\\WPLOSURVEY_secure' );

}


//Survey Checking functions

/**
 * Used to increase failure counter (adapted from https://github.com/dominiquevienne/honeypot/blob/master/src/Dominiquevienne/Honeypot/Honeypot.php)
 *
 * @return $this
 */
function WPLOSURVEY_increaseFailureCounter()
{
    if(isset($_SESSION["sessionFailedAttempts"])) {
        $_SESSION["sessionFailedAttempts"]++;
    } else {
        $_SESSION["sessionFailedAttempts"] = 1;
    }
    return;
}


//Ajax for pre survey checking

add_action( 'wp_ajax_nopriv_dosurvey', 'WPLOSURVEY_NS\\WPLOSURVEY_dosurvey' );
add_action( 'wp_ajax_dosurvey', 'WPLOSURVEY_NS\\WPLOSURVEY_dosurvey' );

function WPLOSURVEY_dosurvey() {

    /** Encryption for timestamp check */
    function my_encrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, OPENSSL_ZERO_PADDING, $iv);
        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return $encrypted . '::' . base64_encode($iv);
    }

    function my_decrypt($data, $key) {
        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);
        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        list($encrypted_data, $iv) = explode('::', $data, 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, base64_decode($iv));
    }

    define("WPLOSURVEY_MIN_TIME_TO_FILL_FORM", 5); // define the minimum time required to fill the form to 5 seconds

    //Survey

    if (isset($_POST['action']) && (sanitize_text_field($_POST['action']) == 'dosurvey')) {
        //Check if bot is filling out this fake phone number field
        $honeyPot = sanitize_text_field($_POST['phone_number_95231er']);

        $key = "POSqAny0+Glb/51sqmn6gM11A4lrJUzEJ9sYTGFKu94=";

        $encryptedLoadedFormTime = sanitize_text_field($_POST['formLoaded9lb4TkY0']);
        $loadedFormTime = my_decrypt($encryptedLoadedFormTime, $key); // decrypt it
        $formFilledInSeconds = time() - $loadedFormTime;

        if (empty($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION["sessionFailedAttempts"]) && $_SESSION["sessionFailedAttempts"] > 10) {
            $post_data = array('code' => '42fy',
                'message' => 'We\'re sorry, but our server is unable to process your request right now.');
            $post_data = json_encode(array('surveyRejected' => $post_data), JSON_FORCE_OBJECT);
            print_r($post_data);
            exit();
        }

        //Does honeypot check
        if (trim($honeyPot) != '') {
            // This is a spam robot. Take action!
            WPLOSURVEY_increaseFailureCounter();
            $post_data = array('code' => '42fy',
                'message' => 'We\'re sorry, but your form could not be sent right now.');
            $post_data = json_encode(array('surveyRejected' => $post_data), JSON_FORCE_OBJECT);
            print_r($post_data);
            exit();
        }

        //Checks how long it took to fill the form out
        if (!isset($encryptedLoadedFormTime) || $formFilledInSeconds < WPLOSURVEY_MIN_TIME_TO_FILL_FORM) {
            // This is a spam robot. Take action!
            WPLOSURVEY_increaseFailureCounter();
            $post_data = array('code' => '42fy',
                'message' => 'We\'re sorry, but your form could not be processed right now.');
            $post_data = json_encode(array('surveyRejected' => $post_data), JSON_FORCE_OBJECT);
            print_r($post_data);
            exit();
        }

        //If exit doesn't happen due to bot checks, continue normal survey process through luminate extend

        $post_data = array('data_checked' => 'true');
        $post_data = (array('surveyAccepted' => $post_data));

        wp_send_json($post_data);


    }


    wp_die(); // this is required to terminate immediately and return a proper response

}



/**
 * Activation Hook, does a flush rewrite so permalinks work for CPT
 */

register_activation_hook( __FILE__, 'WPLOSURVEY_NS\\WPLOSURVEY_survey_activate' );
/**
 * Add a flag that will allow to flush the rewrite rules when needed.
 */
function WPLOSURVEY_survey_activate() {
    if ( ! get_option( 'WPLOSURVEY_survey_flush_rewrite_rules_flag' ) ) {
        add_option( 'WPLOSURVEY_survey_flush_rewrite_rules_flag', true );
    }
}

add_action( 'init', 'WPLOSURVEY_NS\\WPLOSURVEY_survey_flush_rewrite_rules_maybe', 20 );
/**
 * Flush rewrite rules if above option flag exists,
 * and then removes flag.
 */
function WPLOSURVEY_survey_flush_rewrite_rules_maybe() {
    if ( get_option( 'WPLOSURVEY_survey_flush_rewrite_rules_flag' ) ) {
        flush_rewrite_rules();
        delete_option( 'WPLOSURVEY_survey_flush_rewrite_rules_flag' );
    }
}

