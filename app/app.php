<?php

    // makes libraries available to the application

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";

    // creates a new Silex\Application object

    $app = new Silex\Application();
    $app['debug'] = true;

    $server = 'mysql:host=localhost;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    // GETS
    //==========================================================================

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/courses", function() use ($app) {
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->get("/students", function() use ($app) {
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->get("/course/{id}", function($id) use ($app) {
        $course = Course::find($id);
        return $app['twig']->render('course.html.twig',
            array( 'course' => $course,
                   'students' => $course->getStudents(),
                   'all_students' => Student::getAll()));
    });

    // POSTS
    //==========================================================================

    $app->post("/courses", function() use ($app) {
        $name = $_POST['course_name'];
        $course_number = $_POST['course_number'];
        $course = new Course($name, $course_number);
        $course->save();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/students", function() use ($app) {
        $name = $_POST['student_name'];
        $date_enrolled = $_POST['date_enrolled'];
        $student = new Student($name, $date_enrolled);
        $student->save();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->post("/add_students_to_course", function() use ($app) {
    $student = Student::find($_POST['student_id']);
    $course = Course::find($_POST['course_id']);
    $course->addStudent($student);
    return $app['twig']->render('course.html.twig',
        array( 'course' => $course,
               'all_courses' => Course::getAll(),
               'students' => $course->getStudents(),
               'all_students' => Student::getAll() ) );
    });

    // DELETES
    //==========================================================================

    $app->post("/delete_courses", function() use ($app) {
        Course::deleteAll();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/delete_students", function() use ($app) {
        Student::deleteAll();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    return $app;


?>
