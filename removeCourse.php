<?php
require "coursesDB.php";
$coursesDB = new CoursesDB();
$course_id = $_REQUEST['course_id'];

$succeeded = true;
$result = $coursesDB->removeCourse($course_id);
if (!$result) {
    $succeeded = false;
}

if ($succeeded) {
    echo "done";
} else {
    echo "error";
}
