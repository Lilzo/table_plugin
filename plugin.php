<?php

/*
  Plugin Name: Table Plugin
  Plugin URI: http://www.localhost:8580
  Description: Plugin for student and exams menagment (adding, editing and deleting students and their exams information)
  Author: Ozren
  Version: 1.0
  Author URI: http://www.localhost:8580
 */



global $jal_db_version;
$jal_db_version = '1.0';


/* function creating database structure to wordpress db*/
function jal_install() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    global $wpdb;
    global $table_plugin_db_version;

    $table_students = $wpdb->prefix . 'students';
    $table_exams = $wpdb->prefix . 'exams';
    $charset_collate = $wpdb->get_charset_collate();

    $sql_students = "CREATE TABLE $table_students (
		id int(9) NOT NULL AUTO_INCREMENT,
                name varchar(50) NOT NULL,
		date_of_birth date DEFAULT '0000-00-00' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
    dbDelta($sql_students);

    $sql_exams = "CREATE TABLE $table_exams (
		id int(9) NOT NULL AUTO_INCREMENT,
                student_id int NOT NULL,
                exam_name varchar(50) NOT NULL,
		date_of_exam date DEFAULT '0000-00-00' NOT NULL,
                mark int NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";
    dbDelta($sql_exams);

    add_option('jal_db_version', $table_plugin_db_version);
}


register_activation_hook(__FILE__, 'jal_install');

/*                                                      *
 *  Delete plugin databases when uninstaling plugin     *
 *                                                      */

function plugin_db_uninstall() {
    global $wpdb;

    $table_students = $wpdb->prefix . 'students';
    $table_exams = $wpdb->prefix . 'exams';

    $wpdb->query("DROP TABLE IF EXISTS $table_students");
    $wpdb->query("DROP TABLE IF EXISTS $table_exams");
}
register_uninstall_hook(__FILE__, 'plugin_db_uninstall');
/* Session open       */
add_action('init', 'myStartSession', 1);
function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

/*function loading js and css content*/
function load_jquery_ui_and_css() {

    wp_register_script('jquery-ui-js', '/wp-content/plugins/table_plugin/js/jquery-ui.js');
    wp_register_script('jquery-1.11.3', '/wp-content/plugins/table_plugin/js/jquery-1.11.3.js');
    wp_register_script('jquery-global', '/wp-content/plugins/table_plugin/js/global-plugin.js');
    wp_register_style('jquery-ui-css', '/wp-content/plugins/table_plugin/css/jquery-ui.css');
   // wp_register_style('plugin-custom-css', '/wp-content/plugins/table_plugin/css/plugin-custom.css');
   // 
   // wp_enqueue_style('plugin-custom-css'); 
    wp_enqueue_script('jquery-1.11.3');    
    
}
 add_action('init', 'load_jquery_ui_and_css');


/* functions built for shortcode*/    
    function show_students(){
        require_once 'source/class.student.php';
        $student = new Student();
        $student->get_students();
        $students = $student->show_students_shortcode();
        echo $students;
    }
    
    function show_exams($atts){
        $student_id = shortcode_atts( array(
        'student_id' => $id,
    ), $atts );
         $id = $student_id['student_id'];
        require_once 'source/class.exam.php';
        $exam = new Exam();
        $a = $exam->set_student_id($id);
        $exams = $exam->show_exam_for_student();
        //$exam->get_student_name();
        echo $exams;
    }

   /* Adding shortcode*/
     add_shortcode('my_plugin_students', 'show_students');
     add_shortcode('my_plugin_exams', 'show_exams');

/*
 *   Adding Menu and submenu items and functions which includes content from templates folder
 */
function table_plugin_menu() {
    add_menu_page('Table Plugin', 'Table Plugin Menu', 'manage_options', 'table_plugin', 'table_plugin_page');
    add_submenu_page('table_plugin', 'Manage exams', 'Exams', 'manage_options', 'exams', 'table_plugin_exams');
}

add_action('admin_menu', 'table_plugin_menu');

function table_plugin_page() {
     include_once( '/templates/admin_student.tpl.php' );
}
function table_plugin_exams() {
   include_once( '/templates/admin_exam.tpl.php' );
}
?>