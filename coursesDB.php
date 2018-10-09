<?php
$WEBSITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/";
class CoursesDB
{
    private $db_connection;
    public function __construct()
    {
        $this->db_connection = pg_connect(getenv("DATABASE_URL"));
        $result = pg_query($this->db_connection, "CREATE TABLE IF NOT EXISTS courses(
        course_id SERIAL PRIMARY KEY,
        name text NOT NULL,
        teacher_name text NOT NULL,
        max_students integer NOT NULL,
        information text)"
        );
        $result = pg_query($this->db_connection, "CREATE TABLE IF NOT EXISTS students(
        student_id SERIAL PRIMARY KEY,
        name text NOT NULL,
        surname text NOT NULL,
        class text NOT NULL,
        course_id integer NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses (course_id))"
        );
    }

    public function addStudent($name, $surname, $class, $course_id)
    {
        $result = pg_query($this->db_connection, "INSERT INTO students(name, surname, class, course_id)
          VALUES ('$name', '$surname', '$class', '$course_id') RETURNING student_id");
        if (!$result) {
            return false;
        } else {
            $arr = pg_fetch_array($result, NULL, PGSQL_NUM);
            return $arr[0];
        }
    }

    public function addCourse($name, $teacher_name, $max_students, $information)
    {
        $result = pg_query($this->db_connection, "INSERT INTO courses(name, teacher_name, max_students, information)
          VALUES ('$name', '$teacher_name', '$max_students', '$information') RETURNING course_id");
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    public function removeStudent($student_id)
    {
        return pg_query($this->db_connection, "DELETE FROM students WHERE student_id = '$student_id'");
    }

    public function removeCourse($course_id)
    {
        if (!pg_query($this->db_connection, "DELETE FROM students WHERE course_id = '$course_id'")) {
            return false;
        }
        return pg_query($this->db_connection, "DELETE FROM courses WHERE course_id = '$course_id'");
    }

    public function getStudentsFromCourseId($course_id)
    {
        $result = pg_query($this->db_connection, "SELECT name, surname, class, student_id FROM students WHERE course_id = '$course_id' ORDER BY student_id");
        if (!$result) {
            return false;
        }
        $students = array();
        while ($student = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
            array_push($students, $student);
        }
        return $students;
    }

    public function getCourses()
    {
        $result = pg_query($this->db_connection, 'SELECT courses.course_id, courses.name, courses.teacher_name, courses.max_students, courses.information, count(students.course_id) as num_students
          FROM courses LEFT JOIN students ON (courses.course_id = students.course_id) GROUP BY courses.course_id ORDER BY courses.name');
        if (!$result) {
            return false;
        }
        $courses = array();
        while ($course = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
            array_push($courses, $course);
        }
        return $courses;
    }

    public function getCourse($course_id)
    {
        $result = pg_query($this->db_connection, "SELECT courses.name, courses.teacher_name, courses.max_students, courses.information, 
            count(students.course_id) as num_students FROM courses LEFT JOIN students ON (courses.course_id = students.course_id) WHERE courses.course_id = '$course_id' GROUP BY courses.name, courses.teacher_name, courses.max_students, courses.information");
        if (!$result) {
            return false;
        }
        $course = pg_fetch_array($result, NULL, PGSQL_ASSOC);
        return $course;
    }

    public function modifyCourse($course_id, $name, $teacher_name, $max_students, $information)
    {
        return pg_query($this->db_connection, "UPDATE courses SET name = '$name', teacher_name = '$teacher_name', max_students = '$max_students', information = '$information' WHERE course_id = '$course_id'");
    }
}
