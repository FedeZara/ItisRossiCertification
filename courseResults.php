<?php
require "navBar.php";
require "coursesDB.php";

session_start();

if (!isset($_SESSION['logged']) || empty($_SESSION['logged']) || !$_SESSION['logged']) {
    header("location: login.php");
    exit;
}

$coursesDB = new CoursesDB();
//$coursesDB->addStudent("a", "b", "c", 1);
//$coursesDB->addStudent("a", "b", "c", 2);
function showResults()
{
    global $coursesDB;
    $courses = $coursesDB->getCourses();
    if (count($courses) == 0) {
        echo '<div class="alert alert-warning">
                <strong>Nessun corso creato!</strong> <br>
                Vai nella sezione <a href="courseManager.php" class="alert-link">Gestisci corsi</a> per creare un nuovo corso.
              </div>';
    }
    for ($i = 0; $i < count($courses); $i++) {
        $course_id = $courses[$i]["course_id"];
        echo '<div class="panel panel-default"  id="course-' . $course_id . '">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $course_id . '" id="a' . $course_id . '">
                      ' . $courses[$i]["name"] . '     </a>
                      <span class="badge" > <span id="num_students"> ' . $courses[$i]["num_students"] . ' </span>  /  <span id="max_students">' . $courses[$i]["max_students"] . ' </span></span>
                    </h4>
                  </div>
                  <div id="collapse' . $course_id . '" class="panel-collapse collapse">
                   <div class="panel-body">';
        $students = $coursesDB->getStudentsFromCourseId($courses[$i]["course_id"]);
        if (count($students) == 0) {
            echo '<div class="alert alert-info">
                      Il corso non ha ancora ricevuto iscrizioni.
                      </div>';
        } else {
            echo '<table class="table table-hover">
                   <thead>
                     <tr>
                       <th>Classe</th>
                       <th>Cognome</th>
                       <th>Nome</th>
                       <th style="width: 5%"></th>
                     </tr>
                   </thead>
                   <tbody>';
            foreach ($students as $s) {
                echo '<tr id="tr' . $s["student_id"] . '">
                       <td>' . $s["class"] . '</td>
                       <td>' . $s["surname"] . '</td>
                       <td>' . $s["name"] . '</td>
                       <td>
                           <button type="button" class="btn close btn-removeStudent btn-removeStudent-' . $course_id . '" onclick="addStudentToRemove(' . $s["student_id"] . ')">&times;</button>
                       </td>
                     </tr>';
            }
            echo '  </tbody>
                 </table>';
        }
        echo '   <div id="form"></div>
                  <div id="hint" style="float: left"></div>
                  <div style="float: right">
                    <button type="button" class="btn btn-danger" id="btnRed" onclick="btnRed_Click(' . $course_id . ')" ' . ($courses[$i]["num_students"] == 0 ? 'disabled' : '') . '><span class="glyphicon glyphicon-remove"></span> Rimuovi</button>
                    <button type="button" class="btn btn-success" id="btnGreen" onclick="btnGreen_Click(' . $course_id . ')" ' . ($courses[$i]["num_students"] == $courses[$i]["max_students"] ? 'disabled' : '') . '><span class="glyphicon glyphicon-plus"></span> Aggiungi</button>
                  </div>
                  </div>
                </div>
              </div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pannello di amministrazione - Risultati Corsi</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://bootswatch.com/3/flatly/bootstrap.css">
  <link rel="stylesheet" href="styles/courseResults.css">
  <link rel="stylesheet" href="styles/general.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="scripts/courseResults.js" type="text/javascript"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>

</head>
<body>

<?php
showNavBar(1);
?>

<div class="container">
  <div class="row">
    <div class = "col-lg-5 col-sm-9 col-xs-12">
      <h1>Risultati corsi</h1>
    </div>
    <div class = "col-lg-3 col-sm-3 col-xs-12 text-right">
      <btn onclick="downloadExcel()" class="btn btn-success btn-excel">
        <span class="glyphicon glyphicon-save-file"></span> Scarica Excel
      </btn>
      <a href="results.xlsx" style="" id="a-excel" download><span></span></a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8 col-xs-12">
        <div id="error"></div>
        <div class="panel-group" id="accordion">
          <?php
showResults();
?>
        </div>
    </div>
  </div>
</div>
<footer class="footer">
    <div class="container">
      <span class="text-muted">Sito a cura di Federico Zarantonello</span>
    </div>
</footer>
</body>
</html>
