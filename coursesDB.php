<?php
  $WEBSITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/";
  $DATABASE_PATH = $WEBSITE_PATH . "db/courses.sqlite3";

  class CoursesDB extends SQLite3{

    function __construct($path){
      $this->open($path);
      $this->exec("CREATE TABLE IF NOT EXISTS courses(
        course_id integer NOT NULL PRIMARY KEY AUTOINCREMENT,
        name text NOT NULL,
        teacher_name text NOT NULL,
        max_students integer NOT NULL,
        information text)"
       );
      $this->exec("CREATE TABLE IF NOT EXISTS students(
        student_id integer NOT NULL PRIMARY KEY AUTOINCREMENT,
        name text NOT NULL,
        surname text NOT NULL,
        class text NOT NULL,
        course_id integer NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses (course_id))"
       );
     }

     function addStudent($name, $surname, $class, $course_id){
       $statement = $this->prepare('INSERT INTO students(name, surname, class, course_id)
          VALUES (:name, :surname, :class, :course_id)');
       $statement->bindValue(':name', $name);
       $statement->bindValue(':surname', $surname);
       $statement->bindValue(':class', $class);
       $statement->bindValue(':course_id', $course_id);
       $result =  $statement->execute();
       if(!$result)
         return false;
       else
         return $this->lastInsertRowID();
     }

     function addCourse($name, $teacher_name, $max_students, $information){
        $statement = $this->prepare('INSERT INTO courses(name, teacher_name, max_students, information)
          VALUES (:name, :teacher_name, :max_students, :information)');
        $statement->bindValue(':name', $name);
        $statement->bindValue(':teacher_name', $teacher_name);
        $statement->bindValue(':max_students', $max_students);
        $statement->bindValue(':information', $information);
        $result =  $statement->execute();
        if(!$result)
          return false;
        else
          return $this->lastInsertRowID();
     }

     function removeStudent($student_id){
        $statement = $this->prepare('DELETE FROM students WHERE student_id = :student_id');
        $statement->bindValue(':student_id', $student_id);
        return $statement->execute();
     }

     function removeCourse($course_id){
        $statement = $this->prepare('DELETE FROM courses WHERE course_id = :course_id');
        $statement->bindValue(':course_id', $course_id);
        if(!$statement->execute()){
          return false;
        }
        $statement = $this->prepare('DELETE FROM students WHERE course_id = :course_id');
        $statement->bindValue(':course_id', $course_id);
        return $statement->execute();
     }

     function getStudentsFromCourseId($course_id){
       $statement = $this->prepare('SELECT name, surname, class, student_id FROM students WHERE course_id = :course_id ORDER BY class, surname, name');
       $statement->bindValue(':course_id', $course_id);
       $result = $statement->execute();
       if(!$result){
         return false;
       }
       $students = array();
       while($student = $result->fetchArray(SQLITE3_ASSOC)){
         array_push($students, $student);
       }
       return $students;
     }

     function getCourses(){
       $result = $this->query('SELECT courses.course_id, courses.name, courses.teacher_name, courses.max_students, courses.information, count(students.course_id) as num_students
       FROM courses LEFT JOIN students ON (courses.course_id = students.course_id) GROUP BY courses.course_id ORDER BY courses.name');
       if(!$result){
         return false;
       }
       $courses = array();
       while($course = $result->fetchArray(SQLITE3_ASSOC)){
         array_push($courses, $course);
       }
       return $courses;
     }

     function getCourse($course_id){
       $statement = $this->prepare('SELECT name, teacher_name, max_students, information FROM courses WHERE course_id = :course_id');
       $statement->bindValue(':course_id', $course_id);
       $result = $statement->execute();
       if(!$result){
         return false;
       }
       $course = $result->fetchArray(SQLITE3_ASSOC);
       return $course;
     }

     function modifyCourse($course_id, $name, $teacher_name, $max_students, $information){
       $statement = $this->prepare('UPDATE courses SET name = :name, teacher_name = :teacher_name, max_students = :max_students, information = :information WHERE course_id = :course_id');
       $statement->bindValue(':name', $name);
       $statement->bindValue(':teacher_name', $teacher_name);
       $statement->bindValue(':max_students', $max_students);
       $statement->bindValue(':information', $information);
       $statement->bindValue(':course_id', $course_id);
       return $statement->execute();
     }
  }
?>
