function btnSave_Click(course_id){
  var correct = true;
  var name = $("#name").val();
  var teacher_name = $("#teacher_name").val();
  var max_students = $("#max_students").val();
  var information = editor.getData();
  if(name == ""){
    correct = false;
    $("form-group-name").addClass("has-error has-feedback");
    $("#name").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
  }
  else{
    $("#form-group-name").removeClass("has-error has-feedback");
    $("#form-group-name .glyphicon-remove").remove();
  }
  if(teacher_name == ""){
    correct = false;
    $("#form-group-teacher_name").addClass("has-error has-feedback");
    $("#teacher_name").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
  }
  else{
    $("#form-group-teacher_name").removeClass("has-error has-feedback");
    $("#form-group-teacher_name .glyphicon-remove").remove();
  }
  if(max_students == ""){
    correct = false;
    $("#form-group-max_students").addClass("has-error has-feedback");
    $("#max_students").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
  }
  else{
    $("#form-group-max_students").removeClass("has-error has-feedback");
    $("#form-group-max_students .glyphicon-remove").remove();
  }
  if(correct){
    $.ajax({
       type: "POST",
       url: "saveCourse.php",
       data:
       {
         name: name,
         teacher_name: teacher_name,
         max_students: max_students,
         course_id: course_id,
         information: information
       },
       success: function(result) {
         if(result == "done"){
           window.location.replace("courseManager.php");
         }
         else{
           $("#hint").html('<div class="alert alert-danger alert-dismissible fade in">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            "<strong>Errore</strong> sconosciuto durante la modifica/creazione del corso! "+
          '</div>')
         }
        }
      });
  }
  else{
    $("#hint").html('<div class="alert alert-danger alert-dismissible fade in">' +
     '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
     'Riempire <strong> tutti </strong> i campi richiesti! '+
   '</div>')
  }
}
