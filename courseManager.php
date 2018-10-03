<?php
require "navBar.php";
require "coursesDB.php";

session_start();

if (!isset($_SESSION['logged']) || empty($_SESSION['logged']) || !$_SESSION['logged']) {
    header("location: login.php");
    exit;
}

$coursesDB = new CoursesDB();
function showCourses()
{
    global $coursesDB;
    $courses = $coursesDB->getCourses();
    if (count($courses) == 0) {
        echo '<div class="alert alert-warning">
            <strong>Nessun corso creato!</strong> <br>
            Crea un nuovo corso selezionando <a href="modifyCourse.php?course_id=-1" class="alert-link">Nuovo corso</a>.
          </div>';
    } else {
        echo '<div class="row">';
        for ($i = 0; $i < count($courses); $i++) {
            $course_id = $courses[$i]["course_id"];
            echo '<div id="hint' . $course_id . '"></div>';
            echo '<div class="col-lg-6" id="div' . $course_id . '">
                <div class="panel panel-default">
                  <div class="panel-heading clickable clearfix" onclick="selectCourse(' . $course_id . ')">
                    <div class="row">
                      <div class="col-xs-12 col-md-9">
                        <h4 class="panel-title pull-left" style="padding-top: 7.5px; padding-bottom: 7.5px;">' . $courses[$i]["name"] . '</h4>
                      </div>
                      <div class="col-xs-12 col-md-3 text-right">
                        <div class="btn-group">
                          <a href="modifyCourse.php?course_id=' . $course_id . '" class="btn btn-sm btn-default" id="btnModify""><span class="glyphicon glyphicon-pencil"></span></a>
                          <button type="button" class="btn btn-sm btn-default" data-toggle="confirmation"
                          data-btn-ok-label="Cancella" data-btn-ok-class="btn-success"
                          data-btn-ok-icon-class="material-icons" data-btn-ok-icon-content="check"
                          data-btn-cancel-label="Annulla" data-btn-cancel-class="btn-danger"
                          data-btn-cancel-icon-class="material-icons" data-btn-cancel-icon-content="close"
                          data-popout="true"
                          data-title="Vuoi davvero cancellare il corso?" data-content="Tutte le iscrizioni al corso verranno perse..." id="btnRemove" onclick="btnRemove_Click(' . $course_id . ')"><span class="glyphicon glyphicon-remove"></span></button>
                        </div>
                      </div>
                    </div>
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
        echo '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pannello di amministrazione - Gestione Corsi</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://bootswatch.com/3/flatly/bootstrap.css">
  <link rel="stylesheet" href="styles/courseManager.css">
  <link rel="stylesheet" href="styles/general.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="scripts/courseManager.js"></script>
  <script src="scripts/popper.js"></script>
  <script src="scripts/bootstrap-confirmation.js"></script>
</head>
<body>

<?php
showNavBar(0);
?>

<div class="container">
  <div class="row">
    <div class = "col-sm-9 col-xs-12">
      <h1 class="">Gestione corsi</h1>
    </div>
    <div class = "col-sm-3 col-xs-12 text-right">
      <a href="modifyCourse.php?course_id=-1" class="btn btn-primary btn-newCourse">
        <span class="glyphicon glyphicon-plus"></span> Nuovo corso
      </a>
    </div>
  </div>
  <div>
    <?php showCourses();?>
  </div>
</div>
<footer class="footer">
    <div class="container">
      <span class="text-muted">Sito a cura di Federico Zarantonello</span>
    </div>
</footer>
</body>
</html>
