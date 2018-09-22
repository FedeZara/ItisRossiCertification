<?php
require "coursesDB.php";
$coursesDB = new CoursesDB();
$studentsToRemove = $_REQUEST['studentsToRemove'];

$succeeded = true;
foreach ($studentsToRemove as $s) {
    $result = $coursesDB->removeStudent($s);
    if (!$result) {
        $succeeded = false;
    }

}

if ($succeeded) {
    echo "done";
} else {
    echo "error";
}
