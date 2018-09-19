<?php
require("navBar.php");
require("coursesDB.php");

session_start();

if(!isset($_SESSION['logged']) || empty($_SESSION['logged']) || !$_SESSION['logged']){
  header("location: login.php");
  exit;
}

//$coursesDB->addStudent("a", "b", "c", 2);
$course_id = $_REQUEST["course_id"];
function showModifyMenu(){
  global $course_id;
  global $DATABASE_PATH;
  $coursesDB = new CoursesDB($DATABASE_PATH);
  $course = array();
  if($course_id == -1){
    $course = array("name"=>"", "teacher_name"=>"", "max_students"=>"", "information"=>'<h3>CORSO DI CERTIFICAZIONE <i>"Nome corso"</i></h3><h4>Calendario corsi</h4><p><i>Esempio: Lun 13.30/15.00</i></p>');
  }
  else{
    $course = $coursesDB->getCourse($course_id);
  }
  echo '
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-6 col-xs-12">' .
        ($course_id == -1 ? '<h4><strong>Nuovo corso</strong></h4>' : '<h4><strong>Modifica corso</strong> - ' . $course["name"] . '</h4>')
      . '
        </div>
        <div class="col-md-6 col-xs-12 text-right">
          <a class="btn btn-danger" href="courseManager.php"><span class="glyphicon glyphicon-remove"></span> Annulla</a>
          <btn class="btn btn-success" onclick="btnSave_Click(' . $course_id . ')"><span class="glyphicon glyphicon-save"></span> Salva</a>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div id="hint"></div>
      <div class="form-group col-md-8 col-sm-12" id="form-group-name">
        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-book"></i></span>
          <input id="name" type="text" class="form-control" name="name" maxlength="40" placeholder="Titolo del corso" value="' . $course["name"] . '">
        </div>
      </div>
      <div class="form-group col-md-8 col-sm-12" id="form-group-teacher_name">
        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
          <input id="teacher_name" type="text" maxlength="40" class="form-control" name="teacher_name" placeholder="Nome dell' . "'" . 'insegnante" value="' . $course["teacher_name"] . '">
        </div>
      </div>
      <div class="form-group col-md-5 col-sm-12" id="form-group-max_students">
        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-list-alt"></i></span>
          <input id="max_students" type="number" min="1" max="50" class="form-control" name="max_students" placeholder="Numero di posti" value="' . $course["max_students"] . '">
        </div>
      </div>
      <div class="form-group col-md-10 col-sm-12" id="form-group-max_students">
        <div class="input-group">
          <textarea name="content" id="editor">' . $course["information"] .  '</textarea>
        </div>
      </div>
    </div>
  </div>
  ';

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Certification Course Manager - Gestione Corsi</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles/general.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/translations/it.js"></script>
  <script src="scripts/modifyCourse.js"></script>
</head>
<body>

<?php
    showNavBar(0);
?>

<div class="container">
    <h1 class="">Gestione corsi</h1>
    <div class="row">
        <div class="col-lg-8 col-sm-10 col-xs-12">
          <?php showModifyMenu(); ?>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
      <span class="text-muted">Sito a cura di Federico Zarantonello</span>
    </div>
</footer>
<script>
    let editor;
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
          language: 'it',
          toolbar: [
              'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockquote','undo', 'redo'
          ]
        }  )
        .then( newEditor => {
            editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        }, );
</script>
</body>
</html>
