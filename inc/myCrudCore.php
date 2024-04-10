<?php

class myCrudCore
{
    public function __construct()
    {
        //Create the admin page 
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    //Admin Page
    public function add_admin_menu()
    {
        add_menu_page('WP CRUD', 'WP CRUD', 'manage_options', 'wp-crud-admin-view', array($this, 'my_curd_admin_view'), 'dashicons-groups', 20);
    }

    //Admin page callback 'my-curd-admin-view' 
    public function my_curd_admin_view()
    {
        //Views > view-crud-admin.php
        require_once plugin_dir_path(__FILE__) . '../views/view-crud-admin.php';
    }
}
