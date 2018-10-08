<?php
require "coursesDB.php";
$coursesDB = new CoursesDB();
$name = $_REQUEST['name'];
$surname = $_REQUEST['surname'];
$class = $_REQUEST['class'];
$course_id = $_REQUEST['course_id'];

$succeeded = true;

$course = $coursesDB->getCourse($course_id);
if ($course["max_students"] <= $course["num_students"]) {
    echo "maxReached";
} else {
    $result = $coursesDB->addStudent($name, $surname, $class, $course_id);
    if (!$result) {
        $succeeded = false;
    }

    if ($succeeded) {
        echo ($result);
    } else {
        echo "error";
    }
}
