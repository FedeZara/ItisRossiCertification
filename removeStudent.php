<?php
require "coursesDB.php";
$coursesDB = new CoursesDB($DATABASE_PATH);
$studentsToRemove = $_REQUEST['studentsToRemove'];

$succeeded = True;
foreach($studentsToRemove as $s){
  $result = $coursesDB->removeStudent($s);
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
