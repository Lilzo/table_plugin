/*Function calling dialog box for adding exam item*/
    $("#create-exam").button().on("click", function() {
        $('#exam-id').val($(this).attr("data-id"));
            $('#exam-name').val('');
            $('#exam-mark').val('');
            $('#datepicker').val('');
        $("#dialog-exam").dialog("open");
    });
    
/*Fucntion calling dialog box for editin user
 * takes info of selected user and placing it as placeholder
 * */ 
    $(".edit-exam").button().on("click", function() {
        if ($(this).attr("data-id")) {
            $('#exam-id').val($(this).attr("data-id"));
            $('#submitted-student-id').val($(this).attr("data-student-id"));
            $('#exam-name').val($(this).attr("data-name"));
            $('#exam-mark').val($(this).attr("data-mark"));
            $('#datepicker').val($(this).attr("data-date"));
        }
        $("#dialog-exam").dialog("open");
    });

/*Function calling dialog box for new user*/
    $("#create-user").button().on("click", function() {
        $('#student-id').val('');
        $('#full-name').val('');
        $('#datepicker').val('');
        $("#dialog-student").dialog("open");
    });
    
/*Fucntion calling dialog box for editin user
 * takes info of selected user and placing it as placeholder
 * */ 
    $(".edit-user").button().on("click", function() {
        if ($(this).attr("data-id")) {
            $('#student-id').val($(this).attr("data-id"));
            $('#full-name').val($(this).attr("data-name"));
            $('#datepicker').val($(this).attr("data-date"));
        }
        $("#dialog-student").dialog("open");
    });
/*Fucntion that calling datepicker*/
$(function() {
    $("#datepicker").datepicker({
        dateFormat: "dd.mm.yy."
    });
});

/*Function checking length
 * Retruns boolean
 * inputs: element we checking, element name, min length and max lengnth 
 * */
function checkLength(o, n, min, max) {
    if (o.val().length > max || o.val().length < min) {
        o.addClass("ui-state-error");
        updateTips("Length of " + n + " must be between " +
                min + " and " + max + ".");
        return false;
    } else {
        return true;
    }
}

/* Function is checking 
 * Input: Input we want to check, regularexpression, message to dispaly if false
 * Return boolean                */
function checkRegexp(o, regexp, n) {
    if (!(regexp.test(o.val()))) {
        o.addClass("ui-state-error");
        updateTips(n);
        return false;
    } else {
        return true;
    }
}

/*Function adding classes to display error message
  */
function updateTips(t) {
    tips.text(t).addClass("ui-state-highlight");
    setTimeout(function() {
        tips.removeClass("ui-state-highlight", 1500);
    }, 500);
}

/*Function calling dialog box
 * Defines options, buttons, calling function to 
 * validate inputs
 */

$(function() {

    var name = $("#full-name");
    var date = $("#datepicker");
    allFields = $([]).add(name).add(date);
    tips = $(".validateTips");
    //dialog = $("#dialog-student");

    $("#dialog-student").dialog({
        autoOpen: false,
        height: 520,
        width: 450,
        modal: true,
        buttons: {
            "Submit": function() {
                var valid = true;
                valid = valid && checkLength(name, "username", 3, 16);
                valid = valid && checkLength(date, "date", 3, 16);
                valid = valid && checkRegexp(name, /^[a-z]([a-zA-Z\s])+$/i, "Username may consist of a-z, spaces and must begin with a letter.");
                if (valid) {
                    $("#student").submit();
                } else {
                    allFields.removeClass("ui-state-error");
                }
            },
            Cancel: function() {
                $("#dialog-student").dialog("close");
            }
        },
        close: function() {
            allFields.removeClass("ui-state-error");
        }
    });
    
    
    var examName = $("#exam-name");
    var examMark = $("#exam-mark");
    var examDate = $("#datepicker");
    
    
     allFields = $([]).add(examName).add(examMark).add(examDate);
    $("#dialog-exam").dialog({
        autoOpen: false,
        height: 520,
        width: 450,
        modal: true,
        buttons: {
            "Submit": function() {
                var valid = true;
                valid = valid && checkLength(examName, "exam name", 3, 16);
                valid = valid && checkLength(examMark, "exam mark", 1, 1);
                valid = valid && checkLength(examDate, "date", 3, 16);
                valid = valid && checkRegexp(examName, /^[a-z]([a-zA-Z\s])+$/i, "Exam name may consist of a-z, spaces and must begin with a letter.");
                valid = valid && checkRegexp(examMark, /^[1-5]*$/i, "Exam Mark may consist only number 1-5.");//"^[1-5]*$"
                if (valid) {
                   $("#exam").submit();
                } else {
                    allFields.removeClass("ui-state-error");
                }
            },
            Cancel: function() {
                $("#dialog-exam").dialog("close");
            }
        },
        close: function() {
            allFields.removeClass("ui-state-error");
        }
    });

});

/*
 * fucntion called on clikc on delete button
 * opening confirmation box. On confirmation creates form and submiting id of 
 * user which should be deleted. 
 */

$(".delete-user").click(function(event) {
    var studentID = $(this).attr("data-id");
    var studentName = $(this).attr("data-name");
    $("#delete-confirmation").css("visibility", "visible");
    $("#dialog-confirm").dialog({
        resizable: false,
        height: 170,
        modal: true,
        buttons: {
            "Delete": function() {
                //event.preventDefault();
                var deleteStudentForm = jQuery('<form>', {
                    'method': 'post',
                    'action': ''
                }).append(jQuery('<input>', {
                    'name': 'delete-student',
                    'value': studentID,
                    'type': 'hidden'
                })).append(jQuery('<input>', {
                    'name': 'student-name',
                    'value': studentName,
                    'type': 'hidden'
                }));
                deleteStudentForm.submit();
            },
            Cancel: function() {
                $("#dialog-confirm").dialog("close");
            }
        }
    });
});

$(".delete-exam").click(function(event) {
    var examId = $(this).attr("data-id");
    var examName = $(this).attr("data-name");
    $("#delete-confirmation").css("visibility", "visible");
    $("#dialog-confirm").dialog({
        resizable: false,
        height: 170,
        modal: true,
        buttons: {
            "Delete": function() {
                //event.preventDefault();
                var deleteExamForm = jQuery('<form>', {
                    'method': 'post',
                    'action': ''
                }).append(jQuery('<input>', {
                    'name': 'delete-exam',
                    'value': examId,
                    'type': 'hidden'
                })).append(jQuery('<input>', {
                    'name': 'exam-name',
                    'value': examName,
                    'type': 'hidden'
                }));
                deleteExamForm.submit();
            },
            Cancel: function() {
                $("#dialog-confirm").dialog("close");
            }
        }
    });
});

/*submit form on change*/
$( "#student-id" ).change(function() {
  $('#select-student').trigger('submit');
});