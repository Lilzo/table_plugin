<?php

class Exam {

    private $id;
    private $exam_name;
    private $exam_date;
    private $student_id;
    private $student_name;
    private $exam_mark;
    private $exam_id;
    private $wpdb;
    private $db_table_exams;
    private $db_table_students;
        
    /*Function construct - defining global wpdb and db table names*/
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->db_table_exams = $wpdb->prefix . 'exams';
        $this->db_table_students = $wpdb->prefix . 'students';
    }

    /*function to setting and getting $student id*/
    public function set_student_id( $student_id ){
        $this->student_id = $student_id;
    }
    
    public function get_student_id(){
        return $this->student_id;
    }
    
    /* Retreiving exam informations from db */
    public function get_exams() {
        $results = $this->wpdb->get_results("SELECT * FROM $this->db_table_exams  WHERE student_id = $this->student_id");
        $this->exams = $results;
        return $results;
    }
    
    /* Displaying table for admin with exam informations and buttons for editing and deleting. */
    public function set_exam_display() {
        $this->get_student_name();
            echo '<div id="users-contain" class="ui-widget">
        <h2>Exams for student ' . $this->student_name . ':</h2>
        <table id="users" class="ui-widget ui-widget-content">
            <table>
                <thead>
                    <tr> <th>Exam name</th>  <th>Exam date</th> <th>Exam mark</th>  </tr>
                </thead>
          <tbody>';
            foreach ($this->exams as $row) {
                $date_of_exam = date_format(date_create($row->date_of_exam), 'd.m.Y.');
                echo '<tr>
                        <td>' . $row->exam_name . '</td>
                        <td>' . $date_of_exam . '</td>
                        <td>' . $row->mark . '</td>
                        <td> <button data-student-id="' . $row->student_id . '" data-id="' . $row->id . '"data-name="' . $row->exam_name . '" data-date="' . $date_of_exam . '" id="edit-id-' . $row->id . '" data-mark="' . $row->mark . '" class="edit-exam">Edit</button> </td>
                        <td> <button  data-id="' . $row->id . '" data-name="' . $row->exam_name . '"class=" ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only delete-exam">Delete</button> </td>
                    </tr>';
            }
        echo '</tbody></table>';
    }

    /*Function return student name, selecting from db by student id*/
    private function get_student_name() {
        $student_db = $this->wpdb->get_results("SELECT * FROM $this->db_table_students WHERE id = $this->student_id");
        foreach ($student_db as $s) {
           $this->student_name = $s->name;
        }
        return $this->student_name;
    }
    
    /* shortcode function for exams of specific students */
    public function show_exam_for_student() { 
        $this->get_student_name();
        $results = $this->wpdb->get_results("SELECT * FROM $this->db_table_exams WHERE student_id = $this->student_id");
        $this->exams = $results;
                
        $table_exams = '<div id="users-contain" class="ui-widget">
        <h2>Exams for student ' . $this->student_name . ':</h2>
        <table id="users" class="ui-widget ui-widget-content">
            <table>
                <thead>
                    <tr> <th>Exam name</th>  <th>Exam date</th> <th>Exam mark</th>  </tr>
                </thead>
          <tbody>';
        foreach ($this->exams as $row) {
            $date_of_exam = date_format(date_create($row->date_of_exam), 'd.m.Y.');
           
            $table_exams .= '<tr> <td>' . $row->exam_name . '</td> <td>' . $date_of_exam . '</td> <td>' . $row->mark . '</td></tr>';
        }
        $table_exams .= '</tbody></table>';
        return $table_exams;
    }


    /* Depending of $exam_id this function call functions to insert new exam item or to update existing, returns message */
    public function set_exam_info( $student_id, $exam_name, $exam_date, $exam_mark, $exam_id = false) {
        $this->student_id = $student_id;
        $this->exam_name = $exam_name;
        $this->exam_date = $exam_date;
        $this->exam_mark = $exam_mark;
        $this->exam_id = $exam_id;
    }

  
    /*If exam_id has been set this function will call edit exam info, otherwise
     * it will call function insert_exam_ite to add new exam entry. */
    public function add_exam_info_to_db(){      
        if ($this->exam_id) {   
            if($this->edit_exam_info()){
                echo  "<h2>$this->exam_name info has been edited sucesfully!</h2>";
            }
        } else {
            if($this->insert_exam_item()){
                echo "<h2>$this->exam_name info has been added to db!</h2>";
            }
        }
    }
    /* Function for inserting new exam informations */
    private function insert_exam_item() {
        if($this->wpdb->insert($this->db_table_exams, array(
            'student_id' => $this->student_id,
            'exam_name' => $this->exam_name,
            'date_of_exam' => $this->exam_date,
            'mark' => $this->exam_mark
                ), array(
            '%d',
            '%s',
            '%s',
            '%d'
                )
        ) !== false){
           return true; 
        }  
    }

    /* Function editing exam information, updates database */
    private function edit_exam_info() {
        if($this->wpdb->update($this->db_table_exams, array(
            'student_id' => $this->student_id,
            'exam_name' => $this->exam_name,
            'date_of_exam' => $this->exam_date,
            'mark' => $this->exam_mark
                ), array('id' => $this->exam_id), array(
            '%d',
            '%s',
            '%s',
            '%d'
                ), array('%d')
        ) !== false){
            return true;
        }
    }

    /* Function removes exam item and returns message */
    public function remove_exam($exam_id, $exam_name) {
        $this->wpdb->delete($this->db_table_exams, array('id' => $exam_id));
        echo "<h2>$exam_name item has been deleted!</h2>";
    }
}