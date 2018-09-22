<?php
require "coursesDB.php";
$coursesDB = new CoursesDB();
$name = $_REQUEST['name'];
$surname = $_REQUEST['surname'];
$class = $_REQUEST['class'];
$course_id = $_REQUEST['course_id'];

$succeeded = true;

$result = $coursesDB->addStudent($name, $surname, $class, $course_id);
if (!$result) {
    $succeeded = false;
}

if ($succeeded) {
    echo ($result);
} else {
    echo "error";
}
