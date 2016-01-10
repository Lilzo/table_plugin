<?php

require_once( '/../source/class.exam.php' );
require_once( '/../source/class.student.php' );

global $wpdb;

if( isset($_POST['student-id'])){
    $selected_student_id = $_POST['student-id'];;
    $_SESSION['student-id'] = $_POST['student-id'];
}
if( $_SESSION['student-id']){
      $selected_student_id = $_SESSION['student-id'];
}

//$student_id =isset($_SESSION['student-id']) ?  $_SESSION['student-id'] : '';
$student = new Student();
$exam = new Exam();


if($_POST['delete-exam']){
    $exam_id_delete = $_POST['delete-exam'];
    $exam_name = $_POST['exam-name'];
    $exam->remove_exam($exam_id_delete, $exam_name); 
}

if ($_POST['submitted-exam-info']) {
    $submitted_student_id = trim($_POST['submitted-student-id']);
    $exam_id = trim($_POST['exam-id']);;

    if (trim($_POST['exam-name']) === '') {
        $errorMessage[] = 'You did not enter exam name.';
        $hasErrors = true;
    } else {
        $exam_name = trim($_POST['exam-name']);
    }

    if (trim($_POST['exam-mark']) === '') {
        $errorMessage[] = 'You did not enter exam mark.';
        $hasErrors = true;
    } else {
        $exam_mark = trim($_POST['exam-mark']);
    }

    if (trim($_POST['exam_date']) === '') {
        $errorMessage[] = 'You did not enter date of exam.';
        $hasErrors = true;
    } else {
        $date_unformated = trim($_POST['exam_date']);
        $exam_date = date_format(date_create($date_unformated), 'Y-m-d');
    }

    if (!isset($hasErrors)) {
        $exam->set_exam_info($submitted_student_id, $exam_name, $exam_date, $exam_mark, $exam_id);
        $exam->add_exam_info_to_db();
    }
   
}
?>
<!--Form in dialog box for editing or adding new student-->
<div id="dialog-exam" style="display:none;"  title="Exam info">
      <p class="validateTips">All form fields are required.</p>
      <form id="exam" method="post" action="">
        <fieldset>
          <label for="exam-name">Exam name</label> 
          <input type="hidden" name="submitted-student-id" id="submitted-student-id" value="<?php echo $selected_student_id ?>"/>
          <input type="hidden" name="exam-id" id="exam-id"/>
          <input type="hidden" name="submitted-exam-info" value="true" id="submitted-exam"/>
          <input type="text" name="exam-name" id="exam-name" class="text ui-widget-content ui-corner-all hide-input"/>
          <label for="exam-mark">Mark</label>
          <input type="text" name="exam-mark" id="exam-mark" class="text ui-widget-content ui-corner-all hide-input"/>
          <p>Date: <input type="text" class="hide-input" name="exam_date" id="datepicker"></p>
          <!-- Allow form submission with keyboard without duplicating the dialog button -->
          <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
      </form>
    </div>


<!--Confirmation dialog box div-->
<div id="dialog-confirm" title="Delete item">
    <p id="delete-confirmation" style="visibility:hidden">These items will be permanently deleted and cannot be recovered. Are you sure?</p>
</div>


<div class="wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2>Exams</h2>
</div>
<?php

$student->get_students();
$student->get_student_dropdownlist($selected_student_id);
$student_name = $student->get_student_name_by_id($selected_student_id);




if($hasErrors){
    echo '<div class="ui-error">';
    foreach ($errorMessage as $error_message){
        echo '<h3>'.$error_message .'</h3>';
    }
    echo '<h3>Please try again! </h3>';
    echo '</div>';
}

/*Calling js scripts and css styles*/
wp_enqueue_script('jquery-ui-js');
wp_enqueue_script('jquery-global');
wp_enqueue_style('jquery-ui-css');


if($student_name){
    $exam->set_student_id($selected_student_id);
    $exam->get_exams();
    $exam->set_exam_display($student_name);
    
    echo '<button id="create-exam">Add exam info</button>';
} else {
    echo '<div id="users-contain" class="ui-widget">
        <h2>Please select student from dropdown list!</h2>';
    }


