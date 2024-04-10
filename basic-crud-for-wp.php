<?php
/*
 * Plugin Name:       My Basic CRUD For WP
 * Plugin URI:        #
 * Description:       CRUD for WP 
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Noman
 * Author URI:        #
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-crud
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define the constant TEXTDMMAIN
define('TEXT_DOMAIN', 'My-basics-crud');

// Function to run on plugin activation
function init_function_on_plugin_active()
{
    global $wpdb;

    // Create the custom table name with the WordPress table prefix
    $tableName = $wpdb->prefix . 'my_crud_table';

    // SQL query to create the custom table
    $sql = "CREATE TABLE IF NOT EXISTS $tableName (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        PRIMARY KEY (id)
    )";

    // Include necessary upgrade functions
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Execute the SQL query using dbDelta for table creation
    dbDelta($sql);

    // Check if the table is empty before inserting dummy data
    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $tableName"));

    if ($count == 0) {
        // Define an array of dummy data to insert (sanitize data)
        $dummyData = array(
            array('John Doe', 'john.doe@example.com', '1234567890'),
            array('Jane Smith', 'jane.smith@example.com', '9876543210'),
            array('Alice Johnson', 'alice.johnson@example.com', '5555555555'),
            array('Bob Johnson', 'bob.johnson@example.com', '4444444444'),
            array('Emily Brown', 'emily.brown@example.com', '9999999999')
        );

        // Loop through the dummy data array and insert each row into the table
        foreach ($dummyData as $data) {
            $inserted = $wpdb->insert(
                $tableName,
                array(
                    'name' => sanitize_text_field($data[0]),
                    'email' => sanitize_email($data[1]),
                    'phone' => sanitize_text_field($data[2])
                ),
                array(
                    '%s', // 'name' is a string
                    '%s', // 'email' is a string
                    '%s'  // 'phone' is a string
                )
            );

            if ($inserted === false) {
                // Error handling if insertion fails
                error_log('Failed to insert dummy data into the my_crud_table.');
            } else {
                // Dummy data inserted successfully
                error_log('Dummy data inserted into the my_crud_table.');
            }
        }
    } else {
        // Table is not empty, no need to insert dummy data
        error_log('Table already contains data, no dummy data inserted.');
    }
}
register_activation_hook(__FILE__, 'init_function_on_plugin_active');

// Function to run on plugin deletion
function cleanup_function_on_plugin_delete()
{
    global $wpdb;

    // Create the custom table name with the WordPress table prefix
    $tableName = $wpdb->prefix . 'my_crud_table';

    // Check if the table exists before attempting to drop it
    if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") == $tableName) {
        // SQL query to drop the custom table if it exists (sanitize table name)
        $sql = "DROP TABLE $tableName";

        // Execute the SQL query to drop the table
        $wpdb->query($sql);

        // Optionally, perform additional cleanup tasks here
        // such as removing any options or data associated with the plugin

        error_log('Custom table my_crud_table dropped on plugin deletion.');
    }
}
register_uninstall_hook(__FILE__, 'cleanup_function_on_plugin_delete');
