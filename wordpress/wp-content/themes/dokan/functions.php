<?php
/**
 * Dokan functions and definitions
 *
 * @package Dokan
 * @since Dokan 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Dokan 1.0
 */

if ( !isset( $content_width ) ) {
    $content_width = 640;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since Dokan 1.0
 */
class WeDevs_Dokan_Theme {

    function __construct() {

        //includes file
        $this->includes();

        // init actions and filter
        $this->init_filters();
        $this->init_actions();

        // initialize classes
        $this->init_classes();
    }

    /**
     * Initialize filters
     *
     * @return void
     */
    function init_filters() {
        add_filter( 'wp_title', array( $this, 'wp_title' ), 10, 2 );
    }

    /**
     * Init action hooks
     *
     * @return void
     */
    function init_actions() {
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
        add_action( 'dokan_admin_menu', array( $this, 'slider_page' ) );
    }

    public function init_classes() {
        Dokan_Slider::init();
    }


    function includes() {
        $lib_dir     = dirname( __FILE__ ) . '/lib/';
        $inc_dir     = dirname( __FILE__ ) . '/includes/';
        $classes_dir = dirname( __FILE__ ) . '/classes/';

        require_once $classes_dir . 'slider.php';

        require_once $inc_dir . 'wc-functions.php';
        require_once $inc_dir . 'wc-template.php';

        if ( is_child_theme() && file_exists( get_stylesheet_directory() . '/classes/customizer.php' ) ) {
            require_once get_stylesheet_directory() . '/classes/customizer.php';
        } else {
            require_once $classes_dir . 'customizer.php';
        }

        if ( is_admin() ) {

        } else {
            require_once $lib_dir . 'bootstrap-walker.php';
            require_once $inc_dir . 'template-tags.php';
        }
    }

    /**
     * Setup dokan
     *
     * @uses `after_setup_theme` hook
     */
    function setup() {

        /**
         * Make theme available for translation
         * Translations can be filed in the /languages/ directory
         */
        load_theme_textdomain( 'dokan', get_template_directory() . '/languages' );

        /**
         * Add default posts and comments RSS feed links to head
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * Enable support for Post Thumbnails
         */
        add_theme_support( 'post-thumbnails' );

        /**
         * This theme uses wp_nav_menu() in one location.
         */
        register_nav_menus( array(
            'primary'  => __( 'Primary Menu', 'dokan' ),
            'top-left' => __( 'Top Left', 'dokan' ),
            'footer'   => __( 'Footer Menu', 'dokan' ),
        ) );

        add_theme_support( 'woocommerce' );

        /*
         * This theme supports custom background color and image,
         * and here we also set up the default background color.
         */
        add_theme_support( 'custom-background', array(
            'default-color' => 'F7F7F7',
        ) );

        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
    }

    /**
     * Register widgetized area and update sidebar with default widgets
     *
     * @since Dokan 1.0
     */
    function widgets_init() {

        $sidebars = array(
            array( 'name' => __( 'General Sidebar', 'dokan' ), 'id' => 'sidebar-1' ),
            array( 'name' => __( 'Home Sidebar', 'dokan' ), 'id' => 'sidebar-home' ),
            array( 'name' => __( 'Blog Sidebar', 'dokan' ), 'id' => 'sidebar-blog' ),
            array( 'name' => __( 'Header Sidebar', 'dokan' ), 'id' => 'sidebar-header' ),
            array( 'name' => __( 'Shop Archive', 'dokan' ), 'id' => 'sidebar-shop' ),
            array( 'name' => __( 'Single Product', 'dokan' ), 'id' => 'sidebar-single-product' ),
            array( 'name' => __( 'Store', 'dokan' ), 'id' => 'sidebar-store' ),
            array( 'name' => __( 'Footer Sidebar - 1', 'dokan' ), 'id' => 'footer-1' ),
            array( 'name' => __( 'Footer Sidebar - 2', 'dokan' ), 'id' => 'footer-2' ),
            array( 'name' => __( 'Footer Sidebar - 3', 'dokan' ), 'id' => 'footer-3' ),
            array( 'name' => __( 'Footer Sidebar - 4', 'dokan' ), 'id' => 'footer-4' ),
        );

        $args = apply_filters( 'dokan_widget_args', array(
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );

        foreach ( $sidebars as $sidebar ) {

            $args['name'] = $sidebar['name'];
            $args['id'] = $sidebar['id'];

            register_sidebar( $args );
        }
    }

    /**
     * Enqueue scripts and styles
     *
     * @since Dokan 1.0
     */
    function scripts() {

        $protocol           = is_ssl() ? 'https' : 'http';
        $template_directory = get_template_directory_uri();
        $skin               = get_theme_mod( 'color_skin', 'orange.css' );

        // register styles
        wp_enqueue_style( 'bootstrap', $template_directory . '/assets/css/bootstrap.css', false, null );
        wp_enqueue_style( 'flexslider', $template_directory . '/assets/css/flexslider.css', false, null );
        wp_enqueue_style( 'fontawesome' );
        wp_enqueue_style( 'dokan-opensans', $protocol . '://fonts.googleapis.com/css?family=Open+Sans:400,700' );
        wp_enqueue_style( 'dokan-theme', $template_directory . '/style.css', false, null );
        wp_enqueue_style( 'dokan-theme2', $template_directory . '/box.css', false, null );
        wp_enqueue_style( 'dokan-theme5', $template_directory . '/box2.css', false, null );
        wp_enqueue_style( 'dokan-theme3', $template_directory . '/ios7-switch.css', false, null );
        wp_enqueue_style( 'dokan-theme4', $template_directory . '/ios7-switch.modernizr.css', false, null );
        wp_enqueue_style( 'dokan-theme-skin', $template_directory . '/assets/css/skins/' . $skin, false, null );
        //wp_enqueue_script( 'keyboard-image-navigation2', $template_directory . '/ios7-switch.js', array( 'jquery' ) );
        wp_enqueue_script( 'keyboard-image-navigation', $template_directory . '/box.js', array( 'jquery' ) );

        wp_enqueue_script( 'maskedinput-child', $template_directory . '/jquery.maskedinput.js', array( 'jquery' ), null, true );
        wp_enqueue_media( 'maskedinput-child' );

        // use jQuery tipTip from WooCommerce if not enqueued already
        if ( ! wp_script_is( 'jquery-tiptip', 'enqueued' ) ) {
          wp_enqueue_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WC_VERSION, true );
        }


        /****** Scripts ******/
        if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        if ( is_singular() && wp_attachment_is_image() ) {
            wp_enqueue_script( 'keyboard-image-navigation', $template_directory . '/assets/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
        }

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui' );

        wp_enqueue_script( 'bootstrap-min', $template_directory . '/assets/js/bootstrap.min.js', false, null, true );
        wp_enqueue_script( 'flexslider', $template_directory . '/assets/js/jquery.flexslider-min.js', array( 'jquery' ) );

        wp_enqueue_script( 'dokan-theme-scripts', $template_directory . '/assets/js/script.js', false, null, true );
    }

    /**
     * Create a nicely formatted and more specific title element text for output
     * in head of document, based on current view.
     *
     * @since Dokan 1.0.4
     *
     * @param string  $title Default title text for current view.
     * @param string  $sep   Optional separator.
     * @return string The filtered title.
     */
    function wp_title( $title, $sep ) {
        global $paged, $page;

        if ( is_feed() ) {
            return $title;
        }

        // Add the site name.
        $title .= get_bloginfo( 'name' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title = "$title $sep $site_description";
        }

        // Add a page number if necessary.
        if ( $paged >= 2 || $page >= 2 ) {
            $title = "$title $sep " . sprintf( __( 'Page %s', 'dokan' ), max( $paged, $page ) );
        }

        return $title;
    }

    public function slider_page() {
        add_submenu_page( 'dokan', __( 'Slider', 'dokan' ), __( 'Slider', 'dokan' ), 'manage_options', 'edit.php?post_type=dokan_slider' );
    }

}

$dokan = new WeDevs_Dokan_Theme();

require_once('_new_product.php');

require_once('_remove_link_adm.php');

require_once('_produtos_add.php');

require_once('_delete_produtos_lavanderia.php');

/**/
