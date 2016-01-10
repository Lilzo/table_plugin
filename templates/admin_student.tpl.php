<?php
require_once( '/../source/class.student.php' );

    global $wpdb;
    $wpdb->show_errors();
    $table_students = $wpdb->prefix . 'students';

    $student = new Student();
   
/*Call function to delete student if request is sent */
if($_POST['delete-student']){
    $student_id = $_POST['delete-student'];
    $student_name = $_POST['student-name'];
    $student->remove_student($student_id, $student_name); 
}

/*If there's request for editing or adding new student.
 * Validating and calling fucntion to edit or to insert new student
Displaying error messages if there is any */
if ($_POST['submitted-student']) {
    if (trim($_POST['full-name']) === '') {
        $errorMessage[] = 'You did not enter name.';
        $hasErrors = true;
    } else {
        $full_name = trim($_POST['full-name']);
    }

    if (trim($_POST['date-of-birth']) === '') {
        $errorMessage[] = 'You did not enter date of birth.';
        $hasErrors = true;
    } else {
        $date_unformated = trim($_POST['date-of-birth']);
        $date_of_birth = date_format(date_create($date_unformated), 'Y-m-d');
    }

    if (trim($_POST['student-id']) === '') {
        $student_id = FALSE;
    } else {
        $student_id = $_POST['student-id'];
    }


    if (!isset($hasErrors)) {
        $student->set_student_info($full_name, $date_of_birth, $student_id); 
        $student->add_student_to_db();
    }
}
/*Displaying errors if exists.*/
if($hasErrors){
    echo '<div class="ui-error">';
    foreach ($errorMessage as $error_message){
        echo '<h3>'.$error_message .'</h3>';
    }
    echo '<h3>Please try again! </h3>';
    echo '</div>';
}
?>
<!--Form in dialog box for editing or adding new student-->
<div id="dialog-student" style="display:none;" title="Student info">
          <p class="validateTips">All form fields are required.</p>
          <form id="student" method="post" action="">
            <fieldset>
              <label for="name">Name</label>
              <input type="hidden" name="student-id"  id="student-id"/>
              <input type="hidden" name="submitted-student" value="true" id="submitted-student"/>
              <input type="text" name="full-name" id="full-name" class="text ui-widget-content ui-corner-all hide-input"/>
              <p>Date: <input type="text" class="hide-input" name="date-of-birth" id="datepicker"></p>
              <!-- Allow form submission with keyboard without duplicating the dialog button -->
              <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
            </fieldset>
          </form>
        </div>

<!--Confirmation dialog box div*-->
<div id="dialog-confirm" title="Delete student?">
    <p id="delete-confirmation" style="visibility:hidden">These items will be permanently deleted and cannot be recovered. Are you sure?</p>
</div> 
<?php
/*Calling js scripts and css styles*/
wp_enqueue_script('jquery-ui-js');
wp_enqueue_script('jquery-global');
wp_enqueue_style('jquery-ui-css');

/*Geting and seting studnts info for table*/
$student->get_students();
$student->set_students_display();
?>
<button id="create-user">Add new student</button>