currTabIndex = 1;
selectedCourse = -1;

function prevTab() {
  $(".tab" + currTabIndex).addClass("hide");
  currTabIndex--;
  $(".tab" + currTabIndex).removeClass("hide");
  if (currTabIndex == 1) {
    disableButton("#btn-prev");
    $("#btn-next").prop("type", "submit");
  }
  if (currTabIndex == 2) {
    $("#btn-next").html('Avanti <span class="glyphicon glyphicon-chevron-right"></span>');
  }
  ableButton("#btn-next");
}

function disableButton(btn_id) {
  $(btn_id).addClass("disabled");
  $(btn_id).prop("disabled", true);
}

function ableButton(btn_id) {
  $(btn_id).removeClass("disabled");
  $(btn_id).prop("disabled", false);
}

function nextTab() {
  if (currTabIndex == 3) {
    $.ajax({
      type: "POST",
      url: "addStudent.php",
      data: {
        name: $("#name").val(),
        surname: $("#surname").val(),
        class: $("#class").val(),
        course_id: selectedCourse
      },
      success: function (result) {
        student_id = parseInt(result);
        $("#btn-next").hide();
        $("#btn-prev").hide();
        if (!isNaN(student_id)) {
          $(".tab4 p").html('<div class="alert alert-success alert-dismissible">' +
            "<strong>Iscrizione avvenuta con successo!</strong> <br>La tua iscrizione è stata registrata." +
            '</div>');
        } 
        else if(result == "maxReached"){
          $(".tab4 p").html('<div class="alert alert-danger alert-dismissible">' +
            "<strong>Numero massimo di posti raggiunto!</strong> <br>Sembrerebbe che tu non abbia fatto in tempo ad iscriverti al corso...<br>Ricompila il form e seleziona un altro corso. <br>Contatta la prof.ssa Lavinia Vettore se pensi ci possa essere un errore." +
            '</div>');
        }
        else {
          $(".tab4 p").html('<div class="alert alert-danger alert-dismissible">' +
            "<strong>Errore sconosciuto durante la registrazione!</strong> <br>Contatta la prof.ssa Lavinia Vettore per cercare di trovare una soluzione." +
            '</div>');
        }
      }
    });
    $(".tab" + currTabIndex).addClass("hide");
    currTabIndex++;
    $(".tab" + currTabIndex).removeClass("hide");

    ableButton("#btn-prev");
    if (currTabIndex == 3) {
      $("#btn-next").html('Conferma <span class="glyphicon glyphicon-ok"></span>');
    }
  } else {
    var correct = true;
    if (currTabIndex == 1) {
      correct = tab1Validation();
    }
    if (correct) {
      $("#btn-next").prop("type", "button");
      $(".tab" + currTabIndex).addClass("hide");
      currTabIndex++;
      $(".tab" + currTabIndex).removeClass("hide");

      ableButton("#btn-prev");
      if (currTabIndex == 3) {
        $("#btn-next").html('Conferma <span class="glyphicon glyphicon-ok"></span>');
      }
    }
  }
}

function tab1Validation() {
  if ($("#btn-next").hasClass("disabled"))
    return false;
  var correct = true;
  var name = $("#name").val();
  var surname = $("#surname").val();
  var _class = $("#class").val();
  if (name == "") {
    correct = false;
    $("#form-group-name").addClass("has-error has-feedback");
    $("#name").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
  } else {
    $("#form-group-name").removeClass("has-error has-feedback");
    $("#form-group-name .glyphicon-remove").remove();
  }
  if (surname == "") {
    correct = false;
    $("#form-group-surname").addClass("has-error has-feedback");
    $("#surname").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
  } else {
    $("#form-group-surname").removeClass("has-error has-feedback");
    $("#form-group-surname .glyphicon-remove").remove();
  }
  if (_class == "") {
    correct = false;
    $("#form-group-class").addClass("has-error has-feedback");
    $("#class").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
  } else {
    $("#form-group-class").removeClass("has-error has-feedback");
    $("#form-group-class .glyphicon-remove").remove();
  }
  if (correct) {
    if (selectedCourse == -1)
      disableButton("#btn-next");
    return true;
  } else {
    $(".tab1").append('<div class="alert alert-danger alert-dismissible fade in col-sm-12 col-lg-8">' +
      '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
      'Riempire <strong> tutti </strong> i campi richiesti! ' +
      '</div>')
    return false;
  }
}

function selectCourse(course_id) {
  ableButton("#btn-next");
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
    disableButton("#btn-next");
  }
}