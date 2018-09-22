<?php
require "coursesDB.php";
$coursesDB = new CoursesDB();
function showCourses()
{
    global $coursesDB;
    $courses = $coursesDB->getCourses();
    if (count($courses) == 0) {
        echo '<div class="col-lg-6 alert alert-warning">
            <strong>Nessun corso disponibile!</strong> <br>
            Se pensi possa esserci un errore contatta la prof.ssa Lavinia Vettore.
          </div>';
    } else {
        for ($i = 0; $i < count($courses); $i++) {
            $course_id = $courses[$i]["course_id"];
            $maxStudentReached = $courses[$i]["num_students"] == $courses[$i]["max_students"];
            echo '<div class="col-lg-6" id="div' . $course_id . '">
                <div class="panel ' . ($maxStudentReached ? 'panel-danger' : 'panel-default') . '">
                  <div class="panel-heading clearfix ' . ($maxStudentReached ? 'disabled' : 'clickable" onclick="selectCourse(' . $course_id . ')') . ' ">
                    <div class="row">
                      <div class="col-xs-12 col-md-10">
                        <h4 class="panel-title pull-left" style="padding-top: 7.5px; padding-bottom: 7.5px;">' . $courses[$i]["name"] . '</h4>
                      </div>
                      <div class="col-xs-12 col-md-2 text-right">
                        <span class="badge" style="margin-top: 7px"> <span id="num_students"> ' . $courses[$i]["num_students"] . ' </span>  /  <span id="max_students">' . $courses[$i]["max_students"] . ' </span></span>
                      </div>
                    </div>
                    ' . ($maxStudentReached ? '
                    <div class="row">
                      <div class="col-xs-12">
                        <i>Numero massimo di iscrizioni raggiunto</i>
                      </div>
                    </div>'
                : '') . '
                  </div>
                  <div class="panel-body">
                    <div>' . $courses[$i]["information"] . ' </div>
                    <hr>
                    <h4>Informazioni</h4>
                    <div>Nome corso: ' . $courses[$i]["name"] . '</div>
                    <div>Prof.ssa: ' . $courses[$i]["teacher_name"] . '</div>
                    <div>Posti massimi: ' . $courses[$i]["max_students"] . ' </div>
                  </div>
                </div>
              </div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Certificazioni di inglese - Iscrizione ai corsi</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles/index.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
  <script src="scripts/index.js" type="text/javascript"></script>
</head>
<body>
<header>
  <div class="container">
    <h1>
      Certificazioni di inglese <br>
      <small>Iscrizione ai corsi<span id="dash"> - </span><span id="school">ITIS A. Rossi Vicenza</span></small>
    </h1>
  </div>
</header>
<form data-toggle="validator" role="form">
<div class="container">
  <div class="tab1">
    <div class="row">
      <div class="col-sm-12 col-lg-8">
        <h3>Informazioni personali <br><small>Compila tutti i campi con i tuoi dati</small></h3>
      </div>
    </div>
    <div class = "row">
      <div class = "col-sm-12 col-lg-8">
        <div class="row">
          <div class="form-group has-feedback col-md-3 col-xs-12" id="form-group-class">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
              <input class="form-control" id="class" type="text" placeholder="Classe" pattern="^([1-5][a-zA-Z]{3})$" data-error="Inserire una classe valida! Es. 4AII" required>
              <span class="glyphicon form-control-feedback"></span>
            </div>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="row">
          <div class="form-group has-feedback col-md-7 col-xs-12" id="form-group-surname">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input class="form-control" id="surname" type="text" maxlength="30" placeholder="Cognome" pattern="^[a-zA-Z ]{2,30}$" data-error="Inserire un cognome valido!" required>
              <span class="glyphicon form-control-feedback"></span>
            </div>
            <div class="help-block with-errors"></div>
          </div>
        </div>
        <div class="row">
          <div class="form-group has-feedback col-md-7 col-xs-12" id="form-group-name">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
              <input class="form-control" id="name" type="text" maxlength="30" placeholder="Nome" pattern="^[a-zA-Z ]{2,30}$" data-error="Inserire un nome valido!" required>
              <span class="glyphicon form-control-feedback"></span>
            </div>
            <div class="help-block with-errors"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="tab2 hide">
    <div class="row">
      <div class="col-sm-12 col-lg-8">
        <h3>Selezione corso <br><small>Clicca sul corso a cui vuoi prendere parte</small></h3>
      </div>
    </div>
    <div class = "row">
      <?php showCourses()?>
    </div>
  </div>
  <div class="tab3 hide">
    <div class="row">
      <div class="col-sm-12 col-lg-8">
        <h3>Conferma iscrizione </h3>
      </div>
    </div>
    <div class = "row">
      <div class="col-sm-12 col-lg-8">
        <p> Inviare l'iscrizione? </p>
      </div>
    </div>
  </div>
  <div class="tab4 hide">
    <div class="row">
      <div class="col-sm-12 col-lg-8">
        <h3>Esito iscrizione</h3>
      </div>
    </div>
    <div class = "row">
      <div class="col-sm-12 col-lg-8">
        <p>  </p>
      </div>
    </div>
  </div>
  <div class="row btn-navigation">
    <div class="col-sm-12 col-lg-8 pull-left">
      <button type="button" class="btn btn-primary disabled" id="btn-prev" onclick="prevTab()" disabled><span class="glyphicon glyphicon-chevron-left"></span> Indietro</button>
      <button type="submit" class="btn btn-primary" id="btn-next" onclick="nextTab()">Avanti <span class="glyphicon glyphicon-chevron-right"></span></button>
    </div>
  </div>
  <br>
</div>
</form>
<footer class="footer">
    <div class="container">
      <span class="text-muted">Sito a cura di Federico Zarantonello</span>
    </div>
</footer>

</body>
</html>
