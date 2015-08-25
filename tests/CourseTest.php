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

	class CourseTest extends PHPUnit_Framework_TestCase
	{
		protected function tearDown()
		{
			Student::deleteAll();
			Course::deleteAll();
		}

		function test_getId()
        {
            $name = "Math";
            $course_number = "662";
            $test_course = new Course($name, $course_number);
            $test_course->save();

            $result = $test_course->getId();

            $this->assertEquals(true, is_numeric($result));
        }

		function test_save()
        {
            //Arrange
			$name = "Math";
            $course_number = "112";
            $test_course = new Course($name, $course_number);
            $test_course->save();

            $result = Course::getAll();

            $this->assertEquals($test_course, $result[0]);
        }

		 function test_getAll()
        {
            //Arrange
			$name = "Math";
            $course_number = "112";
            $test_course = new Course($name, $course_number);
            $test_course->save();

			$name2 = "History";
            $course_number2 = "112";
            $test_course2 = new Course($name2, $course_number2);
            $test_course2->save();

            $result = Course::getAll();

            $this->assertEquals([$test_course, $test_course2], $result);
		}

		function test_DeleteAll()
        {
            //Arrange
			$name = "Math";
            $course_number = "112";
            $test_course = new course($name, $course_number);
            $test_course->save();

			$name2 = "The Same Exact Name";
            $course_number2 = "112";
            $test_course2 = new course($name2, $course_number2);
            $test_course2->save();

			course::deleteAll();

			$result = course::getAll();
			$this->assertEquals([], $result);
		}

		function test_find()
		{
			$name = "Math";
            $course_number = "112";
            $test_course = new course($name, $course_number);
            $test_course->save();

			$name2 = "The Same Exact Name";
            $course_number2 = "112";
            $test_course2 = new course($name2, $course_number2);
            $test_course2->save();

			$result = course::find($test_course->getId());

			$this->assertEquals($test_course, $result);
		}

		function testAddStudent()
        {
             $name = "Jimmy";
			 $enrollment_date = "2015";
             $test_student = new Student($name, $enrollment_date);
             $test_student->save();

             $course_name = "Real Analysis I";
             $course_number = "Math 540";
             $test_course = new Course($course_name, $course_number);
             $test_course->save();

             $test_course->addStudent($test_student);

             $this->assertEquals([$test_student], $test_course->getStudents());
        }

        function testGetStudents()
        {
            $name = "Mike";
            $enrollment_date = "2015-12-12";
            $test_student = new Student($name, $enrollment_date);
            $test_student->save();

            $name2 = "The Same Exact Name";
            $enrollment_date2 = "2015-12-12";
            $test_student2 = new Student($name2, $enrollment_date2);
            $test_student2->save();

            $course_name = "Real Analysis I";
            $course_number = "Math 540";
            $test_course = new Course($course_name, $course_number);
            $test_course->save();

            $test_course->addStudent($test_student);
            $test_course->addStudent($test_student2);

            $this->assertEquals($test_course->getStudents(), [$test_student, $test_student2]);             
        }



	}
