<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once 'src/Student.php';
    require_once 'src/Course.php';
    $server = 'mysql:host=localhost;dbname=registrar_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
	
	class StudentTest extends PHPUnit_Framework_TestCase
	{
		protected function tearDown()
		{
			Student::deleteAll();
			Course::deleteAll();
		}
		
		function test_getId()
        {
            $name = "Mike";
            $enrollment_date = "2015-12-12";
            $test_student = new Student($name, $enrollment_date);
            $test_student->save();
            
            $result = $test_student->getId();
			
            $this->assertEquals(true, is_numeric($result));
        }
		
		function test_save()
        {
            //Arrange
			$name = "Mike";
            $enrollment_date = "2015-12-12";
            $test_student = new Student($name, $enrollment_date);
            $test_student->save();
            
            $result = Student::getAll();
		
            $this->assertEquals($test_student, $result[0]);
        }
		
		 function test_getAll()
        {
            //Arrange
			$name = "Mike";
            $enrollment_date = "2015-12-12";
            $test_student = new Student($name, $enrollment_date);
            $test_student->save();
			$name2 = "The Same Exact Name";
            $enrollment_date2 = "2015-12-12";
            $test_student2 = new Student($name2, $enrollment_date2);
            $test_student2->save();
            
            $result = Student::getAll();
		
            $this->assertEquals([$test_student, $test_student2], $result);
		}
		
		function test_DeleteAll()
        {
            //Arrange
			$name = "Mike";
            $enrollment_date = "2015-12-12";
            $test_student = new Student($name, $enrollment_date);
            $test_student->save();
			$name2 = "The Same Exact Name";
            $enrollment_date2 = "2015-12-12";
            $test_student2 = new Student($name2, $enrollment_date2);
            $test_student2->save();
			
			Student::deleteAll();
			
			$result = Student::getAll();
			$this->assertEquals([], $result);
		}
		
		function test_find()
		{
			$name = "Mike";
            $enrollment_date = "2015-12-12";
            $test_student = new Student($name, $enrollment_date);
            $test_student->save();
			$name2 = "The Same Exact Name";
            $enrollment_date2 = "2015-12-12";
            $test_student2 = new Student($name2, $enrollment_date2);
            $test_student2->save();
			
			$result = Student::find($test_student->getId());
			
			$this->assertEquals($test_student, $result);
		}
		
		function testAddCourse()
        {
             $name = "Jimmy";
			 $enrollment_date = "2015";
             $test_student = new Student($name, $enrollment_date);
             $test_student->save();
             
             $course_name = "Real Analysis I";
             $course_number = "Math 540";
             $test_course = new Course($course_name, $course_number);
             $test_course->save();
             
             $test_student->addCourse($test_course);
             
             $this->assertEquals([$test_course], $test_student->getCourses());
        }
	}