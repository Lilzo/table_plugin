<?php

class Student {

    private $id;
    private $name;
    private $date_of_birth;
    private $students;
    private $wpdb;

    /*Function construct - defining global wpdb and db table names*/
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->db_table_students = $wpdb->prefix . 'students';
    }
    
    /*getting students from db
    *returning results     */
    public function get_students() {
        $results = $this->wpdb ->get_results("SELECT * FROM  $this->db_table_students");
        $this->students = $results;
        return $this->students ;
    }
    
    /*Dropdown list, displaying all students, and marking specific student by id 
     * if he is alerady being choosen*/
    public function get_student_dropdownlist($student_id = 0) {
        echo '<form id="select-student" method="post" action="" >
            <select id="student-id" name="student-id">
                <option value="">Select student</option>';
        foreach ($this->students as $row) {
            $selected = $student_id == $row->id ? 'selected' : '';
            $student_name = $student_id == $row->id ? $row->name : '';
            echo '<option ' . $selected, ' value="' . $row->id . '">' . $row->name . '</option>';
        }
        echo '
            </select>
        </form>';
        return $student_name;
    }

    /*Functions for getting student name from db by sending id number of student.*/
    public function get_student_name_by_id($id) {
        $results = $this->wpdb->get_results("SELECT * FROM $this->db_table_students WHERE id = $id");
        $this->students = $results;
        foreach ($results as $st) {
            $student_name = $st->name;
        }
        return $student_name;
    }

    /*Displaying students info with buttons for editing and deleting*/
    public function set_students_display() {
        echo '<div id="users-contain" class="ui-widget">
        <h2>Students list</h2>
        <table id="users" class="ui-widget ui-widget-content">
          <thead>
            <tr class="ui-widget-header "> <th>Name</th> <th>Date</th> </tr>
          </thead>
          <tbody>';
        if ($this->students != null) {
            foreach ($this->students as $row) {
                $date = date_format(date_create($row->date_of_birth), 'd.m.Y.');
                echo '<tr>
                        <td>' . $row->name . '</td>
                        <td>' . $date . '</td>
                        <td> <button data-id="' . $row->id . '"data-name="' . $row->name . '" data-date="' . $date . '" id="edit-id-' . $row->id . '" class="edit-user">Edit</button> </td>
                        <td> <button  data-id="' . $row->id . '" data-name="' . $row->name . '"class=" ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only delete-user">Delete</button> </td>
                    </tr>';
            }
        }
        echo'</tbody>
            </table>';
    }

    /*Shortcode fucntion. Displaying student info*/
    public function show_students_shortcode() {
        $student_table = '<div id="users-contain" class="ui-widget">
        <h2>Students list</h2>
        <table id="users" class="ui-widget ui-widget-content">
          <thead>
            <tr class="ui-widget-header "> <th>Name</th> <th>Date</th> </tr>
          </thead>
          <tbody>';
        if ($this->students != null) {
            foreach ($this->students as $row) {
                $date = date_format(date_create($row->date_of_birth), 'd.m.Y.');
                $student_table .= '<tr> <td>' . $row->name . '</td> <td>' . $date . '</td> </tr>';
            }
        }
        $student_table .= '</tbody></table>';
        return $student_table;
    }

    /* Function retreive information about new student or new information about existng.*/
    public function set_student_info($student_name, $date_of_birth, $student_id = false) {
       $this->date_of_birth = $date_of_birth;
       $this->student_name = $student_name;
       $this->student_id = $student_id;
    }
    
    /*Depending of $student_id (if it has value or it is false) this function call functions 
    * to insert new exam item or to update existing, displaying message */   
    public function add_student_to_db(){
         if ($this->student_id) {
            if($this->edit_student_info()){
                echo "<h2>Student $this->student_name  has been edited sucesfully!</h2>";
            }
        } else {
            if($this->insert_new_student()){
                echo "<h2>Student $this->student_name has been added to db!</h2>";
            }
        }
    }
    /*Adding new student to db*/
    private function insert_new_student() {
        if($this->wpdb->insert($this->db_table_students, array(
            'name' => $this->student_name ,
            'date_of_birth' => $this->date_of_birth
                ), array(
            '%s',
            '%s'
                )
        )!== false){
            return true;
        }
    }
    /* function updates db with new info about student*/
    private function edit_student_info() {
        if($this->wpdb->update($this->db_table_students, array(
            'name' => $this->student_name , 
            'date_of_birth' => $this->date_of_birth 
                ), array('id' => $this->student_id), array(
            '%s', 
            '%s' 
                ), array('%d')
        ) !== false){
            return true;
        }
    }
    /*Function removes student from db returns message*/
    public function remove_student($student_id, $student_name) {
        $this->wpdb->delete($this->db_table_students, array('id' => $student_id));
        echo "<h2>Student $student_name has been deleted!</h2>";
    }
}
