<?php 
	
	class Course 
	{
		private $name;
		private $course_number;
		private $id;
		
		function __construct($name, $course_number, $id = null)
		{
			$this->name = $name;
			$this->course_number = $course_number;
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
		
		function setCourseNumber($new_course_number)
		{
			$this->course_number = $new_course_number;
		}
		
		function getCourseNumber()
		{
			return $this->course_number;
		}
		
		function getId()
		{
			return $this->id;
		}
		
		function save()
		{
			$GLOBALS['DB']->exec("INSERT INTO courses (name, course_number) VALUES ('{$this->getName()}', '{$this->getCourseNumber()}');");
			$this->id = $GLOBALS['DB']->lastInsertId();
		}
		
		static function getAll()
		{
			$returned_course = $GLOBALS['DB']->query("SELECT * FROM courses;");
			$courses = array();
			foreach($returned_course as $course) {
				$name = $course['name'];
				$course_number = $course['course_number'];
				$id = $course['id'];
				$new_course = new Course($name, $course_number, $id);
				array_push($courses, $new_course);
			}
			return $courses;
		}
		
		static function deleteAll()
		{
			$GLOBALS['DB']->exec("DELETE FROM courses;");
		}
		
		// function getCourses()
		// {
		// 	$query = $GLOBALS['DB']->query("SELECT student_id FROM registrar WHERE course_id = {$this->getId()};");
		// 	$course_ids = $query->fetchAll(PDO::FETCH_ASSOC);
			
		// 	$courses = array();
		// 	foreach($course_ids as $id){
		// 		$course_id = $id['course_id'];
		// 		$result = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$course_id};");
				
		// 		$returned_course = $result->fetchAll(PDO::FETCH_ASSOC);
		// 		$course_name = $returned_course[0]['name'];
		// 		$course_number = $returned_course[0]['course_number'];
		// 		$id = $returned_course[0]['id'];
		// 		$new_course = new Course($course_name, $course_number, $id);
		// 		array_push($courses, $new_course);
		// 	}
		// 	return $courses;
		// }
		function getStudents()
		{
			$students = array();
			$returned_students = $GLOBALS['DB']->query("SELECT students.* FROM
				courses JOIN registrar ON (courses.id = registrar.course_id)
						 JOIN students ON (registrar.student_id = students.id)
						WHERE courses.id = {$this->getId()};");
			var_dump($returned_students);
			foreach($returned_students as $student) {
				$student_name = $student['name'];
				$enrollment_date = $student['enrollment_date'];
				$id = $student['id'];
				$new_student = new Student($student_name, $enrollment_date, $id);
				array_push($students, $new_student);
			}
			return $students;
		}
		
		function addStudent($student)
		{
			$GLOBALS['DB']->exec("INSERT INTO registrar (student_id, course_id) VALUES ({$student->getId()}, {$this->getId()});");
		}
		
		static function find($search_id)
		{
			$found_course = null;
			$courses = course::getAll();
			foreach($courses as $course){
				$course_id = $course->getId();
				if ($course_id == $search_id){
					$found_course = $course;
				}
			}
			return $found_course;
		}
	}
?>