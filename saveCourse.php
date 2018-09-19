<?php
require "coursesDB.php";
$coursesDB = new CoursesDB($DATABASE_PATH);
$name = $_REQUEST['name'];
$teacher_name = $_REQUEST['teacher_name'];
$max_students = $_REQUEST['max_students'];
$course_id = $_REQUEST['course_id'];
$information = $_REQUEST['information'];

$succeeded = True;
if($course_id == -1){
  $result = $coursesDB->addCourse($name, $teacher_name, $max_students, $information);
  if(!$result)
    $succeeded = False;
}
else{
  $result = $coursesDB->modifyCourse($course_id, $name, $teacher_name, $max_students, $information);
  if(!$result)
    $succeeded = False;
}

if($succeeded){
  echo "done";
}
else{
  echo "error";
}

?>
