<?
/*
    Edit a certificate
 */
include_once( "classes/INIFile.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZCVMain", "Language" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );
include_once( "ezcv/classes/ezcv.php" );
include_once( "ezcv/classes/ezcourse.php" );

if( !is_numeric( $CVID ) && $Action == "new" )
{
    header( "Location: /" );
}

$error = false;

$t = new eZTemplate( "ezcv/user/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/user/intl", $Language, "course.php" );
$intl = new INIFile( "ezcv/user/intl/" . $Language . "/course.php.ini", false );
$t->set_file( array(                    
    "course_edit" => "courseedit.tpl"
    ) );

$course = new eZCourse();

if( is_numeric( $CourseID ) )
{
    $course->get( $CourseID );

    $cv = new eZCV();
    $cv = $cv->getByCourse( $CourseID );
    $CVID = $cv->id();

    $StartDate = new eZDate();
    $StartDate->setMySQLDate( $course->courseStart() );
    $StopDate = new eZDate();
    $StopDate->setMySQLDate( $course->courseStop() );
    
    $t->set_var( "startyear", $StartDate->year() );
    $t->set_var( "startmonth", $StartDate->month() );
    $t->set_var( "startday", $StartDate->day() );
    $t->set_var( "stopyear", $StopDate->year() );
    $t->set_var( "stopmonth", $StopDate->month() );
    $t->set_var( "stopday", $StopDate->day() );
    $t->set_var( "course_name", $course->courseName() );
    $t->set_var( "course_place", $course->coursePlace() );
    $t->set_var( "course_id", $course->id() );  
}
else
{
    $t->set_var( "startyear", "$StartYear" );
    $t->set_var( "startmonth", "$StartMonth" );
    $t->set_var( "startday", "$StartDay" );
    $t->set_var( "stopyear", "$StopYear" );
    $t->set_var( "stopmonth", "$StopMonth" );
    $t->set_var( "stopday", "$StopDay" );
    $t->set_var( "course_name", "$CourseName" );
    $t->set_var( "course_place", "$CoursePlace" );
    $t->set_var( "course_id", "" );  
}

if( $Action == "delete" && is_numeric( $CourseID ) )
{
    $cv->deleteCourse( $CourseID );
    header( "Location: /cv/cv/edit/$CVID" );
}

$t->set_var( "cv_id", "$CVID" );

if( $Action == "insert" || $Action == "update" )
{
    $cv = new eZCV();
    $cv->get( $CVID );
    
    $StartDate = new eZDate();
    $StartDate->setYear( $StartYear );
    $StartDate->setMonth( $StartMonth );
    $StartDate->setDay( $StartDay );
    $StopDate = new eZDate();
    $StopDate->setYear( $StopYear );
    $StopDate->setMonth( $StopMonth );
    $StopDate->setDay( $StopDay );

    $course->setCourseStart( $StartDate->mySQLDate() );
    $course->setCourseStop( $StopDate->mySQLDate() );
    $course->setCourseName( $CourseName );
    $course->setCoursePlace( $CoursePlace );

    $course->store();

    $cv->addCourse( $course );
    $cv->store();
    
    header( "Location: /cv/cv/edit/$CVID" );
}

if( $Action == "edit" )
{
    $ActionValue = "update";
}

if( $Action == "new" )
{
    $ActionValue = "insert";
}

$t->setAllStrings();
$t->set_var( "action_value", $ActionValue );
$t->pparse( "output", "course_edit"  );


?>
