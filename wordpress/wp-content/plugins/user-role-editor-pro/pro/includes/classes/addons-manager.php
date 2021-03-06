<?php
/*
 * User Role Editor Pro WordPress plugin - main class
 * Author: Vladimir Garagulya
 * Author email: support@role-editor.com
 * Author URI: https://www.role-editor.com
 * License: GPL v3
 * 
*/

class URE_Addons_Manager {

    private static $instance = null; // object exemplar reference  
    private $lib = null;
    private $addons = null;
    
    
    public static function get_instance() {
        
        if (self::$instance === null) {            
            // new static() will work too
            self::$instance = new URE_Addons_Manager();
        }

        return self::$instance;
    }
    // end of get_instance()
    
    
    private function __construct() {
        
        $this->lib = URE_Lib_Pro::get_instance();
        $this->init_addons_list();
        
    }
    // end of __construct()
    
    
    public static function execute_once() {

/*      
 *  Do not delete: Data conversion could be needed again in a future.
        if (class_exists('URE_Admin_Menu_Hashes')) {
            URE_Admin_Menu_Hashes::require_data_conversion();
        }
*/        
    }
    // end of execute_once()
    
    
    private function add($addon_id, $access_data_key = null, $replicator_title = '') {
        
        $addon = new stdClass();
        $addon->id = $addon_id;
        $addon->active = false;        
        $addon->access_data_key = $access_data_key;
        $addon->replicator_title = $replicator_title;
        $this->addons[$addon->id] = $addon;
        
    }
    // end of add()
    
    private function init_addons_list() {
        
        $this->addons = array();
        
        if (class_exists('URE_Admin_Menu')) {
            $this->add('admin_menu', URE_Admin_Menu::ADMIN_MENU_ACCESS_DATA_KEY, esc_html__('Admin menu access restrictions', 'user-role-editor'));
        }
        if (class_exists('URE_Front_End_Menu_Access')) {
            $this->add('front_end_menu');
        }
        if (class_exists('URE_Widgets_Admin')) {
            $this->add('widgets_admin', URE_Widgets_Admin::ACCESS_DATA_KEY, esc_html__('Widgets admin access restrictions', 'user-role-editor'));
        }
        if (class_exists('URE_Widgets_Show_Controller')) {
            $this->add('widgets_show', URE_Widgets_Show_Controller::ACCESS_DATA_KEY, esc_html__('Widgets show access restrictions', 'user-role-editor'));
        }
        if (class_exists('URE_Meta_Boxes')) {
            $this->add('meta_boxes', URE_Meta_Boxes::ACCESS_DATA_KEY, esc_html__('Meta Boxes access restrictions', 'user-role-editor'));
        }
        if (class_exists('URE_Other_Roles')) {
            $this->add('other_roles', URE_Other_Roles::ACCESS_DATA_KEY, esc_html__('Other Roles access restrictions', 'user-role-editor'));
        }
        if (class_exists('URE_Posts_Edit_Access')) {
            $this->add('posts_edit');
        }
        if (class_exists('URE_Plugins_Access')) {
            $this->add('plugins');   // for user level only
        }
        if (class_exists('URE_Themes_Access')) {
            $this->add('themes_activation');    // for user level only
        }
        if (class_exists('URE_GF_Access')) {
            $this->add('gravity_forms');        // for user level only
        }
        if (class_exists('URE_Content_View_Restrictions')) {
            $this->add('content_view');
        }
        
    }
    // end of init_addons_list()
    
    
    private function activate($addon_id) {
        if (isset($this->addons[$addon_id])) {
            $this->addons[$addon_id]->active = true;
        } else {
            echo 'Addon '. $addon_id .' is unknown';
            die;
        }
    }
    // end of add()
    
    
    public static function get_replicator_id($addon_id) {
    
        $replicator_id = 'ure_replicate_'. $addon_id .'_access_restrictions';
        
        return $replicator_id;
    }
    // end of get_replicator_id()
    
    
    public function get_all() {
        
        return $this->addons;
        
    }
    // end of get()
    
    
    public function get_active() {
        
        $list = array();
        foreach($this->addons as $addon) {
            if ($addon->active) {
                $list[$addon->id] = $addon;
            }
        }

        return $list;
    }
    // end of get_active()
    
    
    public function get_replicatable() {
        
        $list = array();
        foreach($this->addons as $addon) {
            if ($addon->active && !empty($addon->access_data_key)) {
                $list[$addon->id] = $addon;
            }
        }

        return $list;
    }
    // end of get_replicatable()
    
    
    private function load_admin_menu_access_module() {
        
        $activate = $this->lib->get_option('activate_admin_menu_access_module', false);
        if (!empty($activate)) {
            new URE_Admin_Menu_Access();
            $this->activate('admin_menu');
        }
                
    }
    // end of load_admin_menu_access_module()
    
    
    private function load_front_end_menu_access_module() {
        
        $activate = $this->lib->get_option('activate_front_end_menu_access_module', false);
        if (!empty($activate)) {
            new URE_Front_End_Menu_Access();
            $this->activate('front_end_menu');
        }
                
    }
    // end of load_front_end_menu_access_module()
    
    
    private function load_widgets_admin_access_module() {
        
        if (!is_admin()) {
            return;
        }
        $activate = $this->lib->get_option('activate_widgets_access_module', false);
        if (!empty($activate)) {                        
            new URE_Widgets_Admin_Access();
            $this->activate('widgets_admin');
        }
                
    }
    // end of load_widgets_admin_access_module()
    
    
    private function load_widgets_show_access_module() {
        
        $activate = $this->lib->get_option('activate_widgets_show_access_module', false);
        if (!empty($activate)) {                        
            new URE_Widgets_Show_Access();
            $this->activate('widgets_show');
        }
                
    }
    // end of load_widgets_admin_access_module()
    
    
    private function load_meta_boxes_access_module() {
        
        if (!is_admin()) {
            return;
        }
        $activate = $this->lib->get_option('activate_meta_boxes_access_module', false);
        if (!empty($activate)) {
            new URE_Meta_Boxes_Access();
            $this->activate('meta_boxes');
        }
                
    }
    // end of load_widgets_access_module()    
    
    
    private function load_other_roles_access_module() {
        
        if (!is_admin()) {
            return;
        }
        $activate = $this->lib->get_option('activate_other_roles_access_module', false);
        if (!empty($activate)) {            
            new URE_Other_Roles_Access($this->lib);
            $this->activate('other_roles');
        }
                
    }
    // end of load_widgets_access_module()

    
    private function load_posts_edit_access_module() {
        if (is_network_admin()) {
            return;
        }
        
        $activate = $this->lib->get_option('manage_posts_edit_access', false);
        if (!empty($activate)) {            
            new URE_Posts_Edit_Access();
            $this->activate('posts_edit');
        }
    }
    // end of load_posts_edit_access_module()

    
    private function load_plugins_access_module() {
        if (is_network_admin()) {
            return;
        }
        if (!is_admin()) {
            return;
        }
        $activate = $this->lib->get_option('manage_plugin_activation_access', false);
        if (!empty($activate)) {                                    
            new URE_Plugins_Access();
            $this->activate('plugins');
        }
    }
    // end of load_plugin_activation_access_module()
    
    
    private function load_themes_activation_access_module() {
    
        $multisite = $this->lib->get('multisite');
        if (!$multisite) {
            return;
        }
        if (!is_admin()) {
            return;
        }
        $activate = $this->lib->get_option('manage_themes_access', false);
        if (!empty($activate)) {            
            new URE_Themes_Access();
            $this->activate('themes_activation');
        }

    }
    // end of load_themes_activation_access_module()
    

    /**
     * Load Gravity Forms Access Restriction module
     * @return void
     */
    private function load_gravity_forms_access_module() {
        
        if (!is_admin()) {
            return;
        }
        if ( !class_exists('GFForms') ) {
            return;        
        }
        $activate = $this->lib->get_option('manage_gf_access', false);
        if ($activate) {
            new URE_GF_Access();
            $this->activate('gravity_forms');
        }
        
    }
    // end of load_gf_access_module()
    
    
    private function load_content_view_access_module() {
        
        if (is_network_admin()) {
            return;
        }

        $activate = $this->lib->get_option('activate_content_for_roles', false);
        if ($activate) {            
            new URE_Content_View_Restrictions();
            $this->activate('content_view');
        }
        
    }
    // end of load_content_view_restrictions_module()
        
    
    public function load_addons() {
        
        foreach ($this->addons as $addon) {
            $method = 'load_'. $addon->id .'_access_module';
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
        
    }
    // end of load_addons()
    
}
// end of class URE_Addons_Manager