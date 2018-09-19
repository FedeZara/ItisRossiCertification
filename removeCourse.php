<?php
require "coursesDB.php";
$coursesDB = new CoursesDB($DATABASE_PATH);
$course_id = $_REQUEST['course_id'];

$succeeded = True;
$result = $coursesDB->removeCourse($course_id);
if(!$result)
  $succeeded = False;

if($succeeded){
  echo "done";
}
else{
  echo "error";
}

?>
