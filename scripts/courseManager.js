selectedCourse = -1;

$(document).ready(function(){
  $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
  });
  $(".course-panel button").each(function(p){
    console.log($( this ).data("courseId"));

    $( this ).click(function(){
      btnRemove_Click($( this ).data("courseId"));
    });
  });
});

function btnRemove_Click(course_id) {
  $.ajax({
    type: "POST",
    url: "removeCourse.php",
    data: {
      course_id: course_id
    },
    success: function (result) {
      if (result == "done") {
        $("#div" + course_id).fadeOut();
      } else {
        $("#course-" + course_id + " #hint").html(
          '<div class="alert alert-danger alert-dismissible fade in">' +
          '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
          '<strong>Errore</strong> sconosciuto durante la rimozione del corso! ' +
          '</div>');
      }
    }
  });
}

function selectCourse(course_id) {
  if (selectedCourse != -1) {
    $("#div" + selectedCourse + " .panel").removeClass("panel-primary");
    $("#div" + selectedCourse + " .panel").addClass("panel-default");
    $("#div" + selectedCourse + " #btnRemove").hide();
    $("#div" + selectedCourse + " #btnModify").hide();
  }
  if (selectedCourse != course_id) {
    $("#div" + course_id + " .panel").removeClass("panel-default");
    $("#div" + course_id + " .panel").addClass("panel-primary");
    $("#div" + course_id + " #btnRemove").show();
    $("#div" + course_id + " #btnModify").show();
    selectedCourse = course_id;
  } else {
    selectedCourse = -1;
  }
}