<?php
require "coursesDB.php";
$coursesDB = new CoursesDB($DATABASE_PATH);
$name = $_REQUEST['name'];
$surname = $_REQUEST['surname'];
$class = $_REQUEST['class'];
$course_id = $_REQUEST['course_id'];

$succeeded = True;

$result = $coursesDB->addStudent($name, $surname, $class, $course_id);
if(!$result)
  $succeeded = False;


if($succeeded){
  echo ($result);
}
else{
  echo "error";
}

?>
