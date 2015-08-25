<?php 
	
	class Student 
	{
		private $name;
		private $enrollment_date;
		private $id;
		
		function __construct($name, $enrollment_date, $id = null)
		{
			$this->name = $name;
			$this->enrollment_date = $enrollment_date;
			$this->id = $id; 
		}
		
		function setName($new_name)
		{
			$this->name = $new_name;
		}
		
		function getName()
		{
			return $this->name;
		}
		
		function setEnrollmentDate($new_enrollment_date)
		{
			$this->enrollment_date = $new_enrollment_date;
		}
		
		function getEnrollmentDate()
		{
			return $this->enrollment_date;
		}
		
		function getId()
		{
			return $this->id;
		}
		
		function save()
		{
			$GLOBALS['DB']->exec("INSERT INTO students (name, enrollment_date) VALUES ('{$this->getName()}', '{$this->getEnrollmentDate()}');");
			$this->id = $GLOBALS['DB']->lastInsertId();
		}
		
		static function getAll()
		{
			$returned_student = $GLOBALS['DB']->query("SELECT * FROM students;");
			$students = array();
			foreach($returned_student as $student) {
				$name = $student['name'];
				$enrollment_date = $student['enrollment_date'];
				$id = $student['id'];
				$new_student = new Student($name, $enrollment_date, $id);
				array_push($students, $new_student);
			}
			return $students;
		}
		
		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM students;");
		}
		
		function getCourses()
		{
			$query = $GLOBALS['DB']->("SELECT course_id FROM registrar WHERE student_id = {$this->getId()};");
			$course_ids = $query->fetchAll(PDO::FETCH_ASSOC);
			
			$courses = array();
			foreach($course_ids as $id){
				$course_id = $id['course_id'];
				$result = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$course_id};");
				
				$returned_course = $result->fetchAll(PDO::FETCH_ASSOC);
				$course_name = $returned_course[0]['name'];
				$course_number = $returned_course[0]['course_number'];
				$id = $returned_course[0]['id'];
				$new_course = new Course($course_name, $course_number, $id);
				array_push($courses, $new_course);
			}
			return $courses;
		}
	}
?>