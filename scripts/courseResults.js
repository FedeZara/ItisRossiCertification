removing = false;
adding = false;
studentsToRemove = [];

function update_buttons(course_id) {
  max_students = parseInt($("#course-" + course_id + " #max_students").html());
  num_students = parseInt($("#course-" + course_id + " #num_students").html());
  if (num_students == 0) {
    $("#course-" + course_id + " #btnRed").addClass("disabled");
    $("#course-" + course_id + " #btnRed").prop("disabled", true);
    $("#course-" + course_id + " #btnGreen").removeClass("disabled");
    $("#course-" + course_id + " #btnGreen").prop("disabled", false);
  } else if (num_students == max_students) {
    $("#course-" + course_id + " #btnGreen").addClass("disabled");
    $("#course-" + course_id + " #btnGreen").prop("disabled", true);
    $("#course-" + course_id + " #btnRed").removeClass("disabled");
    $("#course-" + course_id + " #btnRed").prop("disabled", false);
  } else {
    $("#course-" + course_id + " #btnGreen").removeClass("disabled");
    $("#course-" + course_id + " #btnGreen").prop("disabled", false);
    $("#course-" + course_id + " #btnRed").removeClass("disabled");
    $("#course-" + course_id + " #btnRed").prop("disabled", false);
  }
}

function btnGreen_Click(course_id) {
  if (removing) {
    if (studentsToRemove.length > 0) {
      $.ajax({
        type: "POST",
        url: "removeStudent.php",
        data: {
          studentsToRemove: studentsToRemove
        },
        success: function (result) {
          if (result == "done") {
            var studentsLeft = $("#course-" + course_id + " table tr").length - studentsToRemove.length - 1;
            for (var i = 0; i < studentsToRemove.length; i++)
              $("#tr" + studentsToRemove[i]).remove();
            studentsToRemove = [];
            $("#course-" + course_id + " #num_students").html(studentsLeft);
            if (studentsLeft == 0) {
              $("#course-" + course_id + " table").after('<div class="alert alert-info">' +
                'Il corso non ha ancora ricevuto iscrizioni.' +
                '</div>');
              $("#course-" + course_id + " table").remove();
            }
          } else {
            for (var i = 0; i < studentsToRemove.length; i++)
              $("#tr" + studentsToRemove[i]).toggleClass("danger");
            studentsToRemove = [];
            $("#course-" + course_id + " #hint").html(
              '<div class="alert alert-danger alert-dismissible fade in">' +
              '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
              '<strong>Errore</strong> sconosciuto durante la rimozione degli studenti! ' +
              '</div>');
          }
          update_buttons(course_id);
        }
      });
    }
    $(".btn-removeStudent-" + course_id).hide();
    $("#course-" + course_id + " #btnGreen").html('<span class="glyphicon glyphicon-plus"></span> Aggiungi');
    $("#course-" + course_id + " #btnRed").html('<span class="glyphicon glyphicon-remove"></span> Rimuovi');
    removing = false;
    update_buttons(course_id);

  } else if (adding) {
    var correct = true;
    var name = $("#course-" + course_id + " #name").val();
    var surname = $("#course-" + course_id + " #surname").val();
    var _class = $("#course-" + course_id + " #class").val();
    if (name == "") {
      correct = false;
      $("#course-" + course_id + " #form-group-name").addClass("has-error has-feedback");
      $("#course-" + course_id + " #name").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
    } else {
      $("#course-" + course_id + " #form-group-name").removeClass("has-error has-feedback");
      $("#course-" + course_id + " #form-group-name .glyphicon-remove").remove();
    }
    if (surname == "") {
      correct = false;
      $("#course-" + course_id + " #form-group-surname").addClass("has-error has-feedback");
      $("#course-" + course_id + " #surname").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
    } else {
      $("#course-" + course_id + " #form-group-surname").removeClass("has-error has-feedback");
      $("#course-" + course_id + " #form-group-surname .glyphicon-remove").remove();
    }
    if (_class == "") {
      correct = false;
      $("#course-" + course_id + " #form-group-class").addClass("has-error has-feedback");
      $("#course-" + course_id + " #class").after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
    } else {
      $("#course-" + course_id + " #form-group-class").removeClass("has-error has-feedback");
      $("#course-" + course_id + " #form-group-class .glyphicon-remove").remove();
    }
    if (correct) {
      $.ajax({
        type: "POST",
        url: "addStudent.php",
        data: {
          name: name,
          surname: surname,
          class: _class,
          course_id: course_id
        },
        success: function (result) {
          student_id = parseInt(result);
          if (!isNaN(student_id)) {
            $(".btn-removeStudent-" + course_id).hide();
            $("#course-" + course_id + " #form").html("");
            $("#course-" + course_id + " #btnGreen").html('<span class="glyphicon glyphicon-plus"></span> Aggiungi');
            $("#course-" + course_id + " #btnRed").html('<span class="glyphicon glyphicon-remove"></span> Rimuovi');
            adding = false;
            $("#course-" + course_id + " #hint").html('');
            num_students = parseInt($("#course-" + course_id + " #num_students").html());
            $("#course-" + course_id + " #num_students").html(num_students + 1);
            if (num_students == 0) {
              $("#course-" + course_id + " .panel-body .alert").remove();
              $("#course-" + course_id + " .panel-body").prepend('<table class="table table-hover">' +
                '<thead>' +
                '<tr>' +
                '<th>Classe</th>' +
                '<th>Cognome</th>' +
                '<th>Nome</th>' +
                '<th style="width: 5%"></th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '<tr id="tr' + student_id + '"><td>' + _class + '</td><td>' + surname + '</td><td>' + name + '</td><td>' +
                '<button type="button" class="btn close btn-removeStudent btn-removeStudent-' + course_id + '" onclick="addStudentToRemove(' + student_id + ')">&times;</button>' +
                '</td></tr></tbody></table>');
            } else {
              $("#course-" + course_id + " table tr:last").after('<tr id="tr' + student_id + '"><td>' + _class + '</td><td>' + surname + '</td><td>' + name + '</td><td>' +
                '<button type="button" class="btn close btn-removeStudent btn-removeStudent-' + course_id + '" onclick="addStudentToRemove(' + student_id + ')">&times;</button>' +
                '</td></tr>');
            }
            update_buttons(course_id);
          } else {
            $("#course-" + course_id + " #hint").html('<div class="alert alert-danger alert-dismissible fade in">' +
              '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
              "<strong>Errore</strong> sconosciuto durante l'aggiunta degli studenti! " +
              '</div>');
          }
        }
      });
    } else {
      $("#course-" + course_id + " #hint").html('<div class="alert alert-danger alert-dismissible fade in">' +
        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
        'Compilare correttamente i campi. ' +
        '</div>');
    }

  } else {
    $("#course-" + course_id + " #form").html(
      '<form id="form" class="row" data-toggle="validator" role="form">' +
      '<div class="form-group  has-feedback col-md-3 col-xs-12" id="form-group-class">' +
      '<div class="input-group">' +
      '<span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>' +
      '<input class="form-control" id="class" type="text" placeholder="Classe" pattern="^([1-5][a-zA-Z]{3})$" data-error="Inserire una classe valida!" required>' +
      '</div>' +
      '<div class="help-block with-errors"></div>' +
      '</div>' +
      '<div class="form-group has-feedback col-md-4 col-xs-12" id="form-group-surname">' +
      '<div class="input-group">' +
      '<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>' +
      '<input class="form-control" id="surname" type="text" placeholder="Cognome" pattern="^[a-zA-Z ]{2,30}$" data-error="Inserire un cognome valido!" required>' +
      '</div>' +
      '<div class="help-block with-errors"></div>' +
      '</div>' +
      '<div class="form-group has-feedback col-md-4 col-xs-12" id="form-group-name">' +
      '<div class="input-group">' +
      '<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>' +
      '<input class="form-control" id="name" type="text" placeholder="Nome" pattern="^[a-zA-Z ]{2,30}$" data-error="Inserire un nome valido!" required>' +
      '</div>' +
      '<div class="help-block with-errors"></div>' +
      '</div>' +
      '</form>');

    $("#form").validator('update');
    $("#course-" + course_id + " #btnGreen").html('<span class="glyphicon glyphicon-ok"></span>');
    $("#course-" + course_id + " #btnRed").html('<span class="glyphicon glyphicon-remove"></span>');
    adding = true;
    $("#course-" + course_id + " #btnGreen").removeClass("disabled");
    $("#course-" + course_id + " #btnGreen").prop("disabled", false);
    $("#course-" + course_id + " #btnRed").removeClass("disabled");
    $("#course-" + course_id + " #btnRed").prop("disabled", false);
  }
}

function btnRed_Click(course_id) {
  if (removing) {
    $(".btn-removeStudent-" + course_id).hide();
    $("#course-" + course_id + " #btnGreen").html('<span class="glyphicon glyphicon-plus"></span> Aggiungi');
    $("#course-" + course_id + " #btnRed").html('<span class="glyphicon glyphicon-remove"></span> Rimuovi');
    removing = false;
    update_buttons(course_id);

    for (var i = 0; i < studentsToRemove.length; i++)
      $("#tr" + studentsToRemove[i]).toggleClass("danger");
    studentsToRemove = [];
  } else if (adding) {
    $("#course-" + course_id + " #form").html("");
    $("#course-" + course_id + " #hint").html('');
    adding = false;
    $(".btn-removeStudent-" + course_id).hide();
    $("#course-" + course_id + " #btnGreen").html('<span class="glyphicon glyphicon-plus"></span> Aggiungi');
    $("#course-" + course_id + " #btnRed").html('<span class="glyphicon glyphicon-remove"></span> Rimuovi');
    update_buttons(course_id);
  } else {
    $(".btn-removeStudent-" + course_id).show();
    $("#course-" + course_id + " #btnGreen").html('<span class="glyphicon glyphicon-ok"></span>');
    $("#course-" + course_id + " #btnRed").html('<span class="glyphicon glyphicon-remove"></span>');
    removing = true;
    $("#course-" + course_id + " #btnGreen").removeClass("disabled");
    $("#course-" + course_id + " #btnGreen").prop("disabled", false);
    $("#course-" + course_id + " #btnRed").removeClass("disabled");
    $("#course-" + course_id + " #btnRed").prop("disabled", false);
  }
}

function addStudentToRemove(student_id) {
  pos = studentsToRemove.indexOf(student_id);
  $("#tr" + student_id).toggleClass("danger");
  if (pos == -1) {
    studentsToRemove.push(student_id);
  } else {
    studentsToRemove.splice(pos, 1);
  }
}

function downloadExcel() {
  $.ajax({
    type: "POST",
    url: "createExcel.php",
    data: {},
    success: function (result) {
      if (result.indexOf("results") === 0) {
        $("#a-excel").attr("href", result);
        $("#a-excel span").trigger("click");
      } else {
        $("#error").html('<div class="alert alert-danger alert-dismissible fade in">' +
          '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
          '<strong> Errore sconosciuto </strong> durante lo scaricamento del file!' +
          '</div>');
      }
    }
  });
}