<?php

/**
 * Dokan rewrite rules class
 *
 * @package Dokan
 */
class Dokan_Rewrites {

    public $query_vars = array();
    public $custom_store_url = '';

    /**
     * Hook into the functions
     */
    public function __construct() {
        $this->custom_store_url = dokan_get_option( 'custom_store_url', 'dokan_general', 'store' );

        add_action( 'init', array( $this, 'register_rule' ) );

        add_filter( 'template_include', array( $this, 'store_template' ) );
        add_filter( 'template_include', array( $this,  'product_edit_template' ) );
        add_filter( 'template_include', array( $this,  'store_toc_template' ) );

        add_filter( 'query_vars', array( $this, 'register_query_var' ) );
        add_filter( 'pre_get_posts', array( $this, 'store_query_filter' ) );
        add_filter( 'woocommerce_get_breadcrumb', array( $this, 'store_page_breadcrumb'), 10 ,1  );
    }

    /**
     * Generate breadcrumb for store page
     *
     * @since 2.4.7
     *
     * @param array $crumbs
     *
     * @return array $crumbs
     */
    public function store_page_breadcrumb( $crumbs ){
        if (  dokan_is_store_page() ) {
            $author      = get_query_var( $this->custom_store_url );
            $seller_info = get_user_by( 'slug', $author );
            $crumbs[1]   = array( ucwords($this->custom_store_url) , site_url().'/'.$this->custom_store_url );
            $crumbs[2]   = array( $author, dokan_get_store_url( $seller_info->data->ID ) );
        }

        return $crumbs;
    }

    /**
     * Check if WooCommerce installed or not
     *
     * @return boolean
     */
    public function is_woo_installed() {
        return function_exists( 'WC' );
    }

    /**
     * Register the rewrite rule
     *
     * @return void
     */
    function register_rule() {
        $this->query_vars = apply_filters( 'dokan_query_var_filter', array(
            'products',
            'new-product',
            'orders',
            'withdraw',
            'settings',
            'edit-account'
        ) );

        foreach ( $this->query_vars as $var ) {
            add_rewrite_endpoint( $var, EP_PAGES );
        }

        $permalinks = get_option( 'woocommerce_permalinks', array() );
        if ( isset( $permalinks['product_base'] ) ) {
            $base = substr( $permalinks['product_base'], 1 );
        }

        // default base is product
        $base = empty( $base ) ? 'product' : $base;

        // special treatment for product cat
        if ( stripos( $base, 'product_cat' ) ) {

            // get the category base. usually: shop
            $base_array = explode( '/', ltrim( $base, '/' ) ); // remove first '/' and explode
            $cat_base = isset( $base_array[0] ) ? $base_array[0] : 'shop';

            add_rewrite_rule( $cat_base . '/(.+?)/([^/]+)(/[0-9]+)?/edit?$', 'index.php?product_cat=$matches[1]&product=$matches[2]&page=$matches[3]&edit=true', 'top' );

        } else {
            add_rewrite_rule( $base . '/([^/]+)(/[0-9]+)?/edit/?$', 'index.php?product=$matches[1]&page=$matches[2]&edit=true', 'top' );
        }

        add_rewrite_rule( $this->custom_store_url.'/([^/]+)/?$', 'index.php?'.$this->custom_store_url.'=$matches[1]', 'top' );
        add_rewrite_rule( $this->custom_store_url.'/([^/]+)/page/?([0-9]{1,})/?$', 'index.php?'.$this->custom_store_url.'=$matches[1]&paged=$matches[2]', 'top' );

        add_rewrite_rule( $this->custom_store_url.'/([^/]+)/section/?([0-9]{1,})/?$', 'index.php?'.$this->custom_store_url.'=$matches[1]&term=$matches[2]&term_section=true', 'top' );
        add_rewrite_rule( $this->custom_store_url.'/([^/]+)/section/?([0-9]{1,})/page/?([0-9]{1,})/?$', 'index.php?'.$this->custom_store_url.'=$matches[1]&term=$matches[2]&paged=$matches[3]&term_section=true', 'top' );

        add_rewrite_rule( $this->custom_store_url.'/([^/]+)/toc?$', 'index.php?'.$this->custom_store_url.'=$matches[1]&toc=true', 'top' );
        add_rewrite_rule( $this->custom_store_url.'/([^/]+)/toc/page/?([0-9]{1,})/?$', 'index.php?'.$this->custom_store_url.'=$matches[1]&paged=$matches[2]&toc=true', 'top' );

        do_action( 'dokan_rewrite_rules_loaded', $this->custom_store_url );
    }

    /**
     * Register the query var
     *
     * @param array  $vars
     *
     * @return array
     */
    function register_query_var( $vars ) {
        $vars[] = $this->custom_store_url;
        $vars[] = 'edit';
        $vars[] = 'term_section';
        $vars[] = 'toc';

        foreach ( $this->query_vars as $var ) {
            $vars[] = $var;
        }

        return $vars;
    }

    /**
     * Include store template
     *
     * @param type  $template
     *
     * @return string
     */
    function store_template( $template ) {

        $store_name = get_query_var( $this->custom_store_url );

        if ( ! $this->is_woo_installed() ) {
            return $template;
        }

        if ( !empty( $store_name ) ) {
            $store_user = get_user_by( 'slug', $store_name );

            // no user found
            if ( ! $store_user ) {
                return get_404_template();
            }

            // check if the user is seller
            if ( ! dokan_is_user_seller( $store_user->ID ) ) {
                return get_404_template();
            }

            return dokan_locate_template( 'store.php' );
        }

        return $template;
    }

    /**
     * Returns the terms_and_conditions template
     *
     * @since 2.3
     *
     * @param string $template
     *
     * @return string
     */
    function store_toc_template( $template ) {

        if ( ! $this->is_woo_installed() ) {
            return $template;
        }
        if ( get_query_var( 'toc' ) ) {
            return dokan_locate_template( 'store-toc.php' );
        }

        return $template;

    }

    /**
     * Returns the edit product template
     *
     * @param string  $template
     *
     * @return string
     */
    function product_edit_template( $template ) {

        if ( ! $this->is_woo_installed() ) {
            return $template;
        }

        if ( get_query_var( 'edit' ) && is_singular( 'product' ) ) {
            if ( dokan_get_option( 'product_style', 'dokan_selling', 'old' ) == 'old' && WeDevs_Dokan::init()->is_pro() ) {
                $edit_product_url = dokan_locate_template( 'products/product-edit.php', '', '', true );
            } elseif ( dokan_get_option( 'product_style', 'dokan_selling', 'old' ) == 'new' && WeDevs_Dokan::init()->is_pro() ) {
                $edit_product_url = dokan_locate_template( 'products/new-product-single.php' );
            } else {
                $edit_product_url = dokan_locate_template( 'products/new-product-single.php' );
            }

            return $edit_product_url;
        }

        return $template;
    }

    /**
     * Store query filter
     *
     * Handles the product filtering by category in store page
     *
     * @param object  $query
     *
     * @return void
     */
    function store_query_filter( $query ) {
        global $wp_query;

        $author = get_query_var( $this->custom_store_url );

        if ( !is_admin() && $query->is_main_query() && !empty( $author ) ) {
            $seller_info  = get_user_by( 'slug', $author );
            $store_info   = dokan_get_store_info( $seller_info->data->ID );
            $post_per_page = isset( $store_info['store_ppp'] ) && !empty( $store_info['store_ppp'] ) ? $store_info['store_ppp'] : 12;
            set_query_var( 'posts_per_page', $post_per_page );
            $query->set( 'post_type', 'product' );
            $query->set( 'author_name', $author );
            $query->query['term_section'] = isset( $query->query['term_section'] ) ? $query->query['term_section'] : array();

            if ( $query->query['term_section'] ) {
                $query->set( 'tax_query',
                    array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'term_id',
                            'terms'    => $query->query['term']
                        )
                    )
                );
            }
        }
    }
}
