<?
/*
    Edit a certificate
 */
include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$Language = $ini->read_var( "eZCVMain", "Language" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezdate.php" );
include_once( "ezcv/classes/ezcv.php" );
include_once( "ezcv/classes/ezeducation.php" );

if( !is_numeric( $CVID ) && $Action == "new" )
{
    header( "Location: /" );
}

$error = false;

$t = new eZTemplate( "ezcv/admin/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/admin/intl", $Language, "education.php" );
$intl = new INIFile( "ezcv/admin/intl/" . $Language . "/education.php.ini", false );
$t->set_file( array(                    
    "education_edit" => "educationedit.tpl"
    ) );

$education = new eZEducation();

if( is_numeric( $EducationID ) )
{
    $education->get( $EducationID );

    $cv = new eZCV();
    $cv = $cv->getByEducation( $EducationID );
    $CVID = $cv->id();

    $StartDate = new eZDate();
    $StartDate->setMySQLDate( $education->start() );
    $EndDate = new eZDate();
    $EndDate->setMySQLDate( $education->end() );
    
    $t->set_var( "startyear", $StartDate->year() );
    $t->set_var( "startmonth", $StartDate->month() );
    $t->set_var( "startday", $StartDate->day() );
    $t->set_var( "endyear", $EndDate->year() );
    $t->set_var( "endmonth", $EndDate->month() );
    $t->set_var( "endday", $EndDate->day() );
    $t->set_var( "education_institution", $education->institution() );
    $t->set_var( "education_direction", $education->direction() );
    $t->set_var( "education_comment", $education->comment() );
    $t->set_var( "education_speciality", $education->speciality() );
    $t->set_var( "education_id", $education->id() );  
}
else
{
    $t->set_var( "startyear", "$StartYear" );
    $t->set_var( "startmonth", "$StartMonth" );
    $t->set_var( "startday", "$StartDay" );
    $t->set_var( "endyear", "$EndYear" );
    $t->set_var( "endmonth", "$EndMonth" );
    $t->set_var( "endday", "$EndDay" );
    $t->set_var( "education_institution", "$Institution" );
    $t->set_var( "education_direction", "$Direction" );
    $t->set_var( "education_comment", "$Comment" );
    $t->set_var( "education_speciality", "$Speciality" );    
    $t->set_var( "education_id", "" );  
}

if( $Action == "delete" && is_numeric( $EducationID ) )
{
    $cv->deleteEducation( $EducationID );
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
    $EndDate = new eZDate();
    $EndDate->setYear( $EndYear );
    $EndDate->setMonth( $EndMonth );
    $EndDate->setDay( $EndDay );
    
    $education->setStart( $StartDate->mySQLDate() );
    $education->setEnd( $EndDate->mySQLDate() );
    $education->setInstitution( $Institution );
    $education->setDirection( $Direction );
    $education->setComment( $Comment );
    $education->setSpeciality( $Speciality );
    $education->store();

    $cv->addEducation( $education );
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
$t->pparse( "output", "education_edit"  );


?>
