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
include_once( "ezcv/classes/ezexperience.php" );

if( !is_numeric( $CVID ) && $Action == "new" )
{
    header( "Location: /" );
}

$error = false;

$t = new eZTemplate( "ezcv/admin/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/admin/intl", $Language, "experience.php" );
$intl = new INIFile( "ezcv/admin/intl/" . $Language . "/experience.php.ini", false );
$t->set_file( array(                    
    "experience_edit" => "experienceedit.tpl"
    ) );

$experience = new eZExperience();

if( is_numeric( $ExperienceID ) )
{
    $experience->get( $ExperienceID );

    $cv = new eZCV();
    $cv = $cv->getByExperience( $ExperienceID );
    $CVID = $cv->id();

    $StartDate = new eZDate();
    $StartDate->setMySQLDate( $experience->start() );
    $EndDate = new eZDate();
    $EndDate->setMySQLDate( $experience->end() );
    
    $t->set_var( "startyear", $StartDate->year() );
    $t->set_var( "startmonth", $StartDate->month() );
    $t->set_var( "startday", $StartDate->day() );
    $t->set_var( "endyear", $EndDate->year() );
    $t->set_var( "endmonth", $EndDate->month() );
    $t->set_var( "endday", $EndDate->day() );
    $t->set_var( "experience_employer", $experience->employer() );
    $t->set_var( "experience_position", $experience->position() );
    $t->set_var( "experience_tasks", $experience->tasks() );
    $t->set_var( "experience_was_full_time", $experience->wasFullTime() );
    $t->set_var( "experience_id", $experience->id() );  
}
else
{
    $t->set_var( "startyear", "$StartYear" );
    $t->set_var( "startmonth", "$StartMonth" );
    $t->set_var( "startday", "$StartDay" );
    $t->set_var( "endyear", "$EndYear" );
    $t->set_var( "endmonth", "$EndMonth" );
    $t->set_var( "endday", "$EndDay" );
    $t->set_var( "experience_employer", "$Employer" );
    $t->set_var( "experience_position", "$Position" );
    $t->set_var( "experience_tasks", "$Tasks" );    
    $t->set_var( "experience_was_full_time", "$wasFullTime" );    
    $t->set_var( "experience_id", "" );  
}

if( $Action == "delete" && is_numeric( $ExperienceID ) )
{
    $cv->deleteExperience( $ExperienceID );
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
    
    $experience->setStart( $StartDate->mySQLDate() );
    $experience->setEnd( $EndDate->mySQLDate() );
    $experience->setEmployer( $Employer );
    $experience->setPosition( $Position );
    $experience->setTasks( $Tasks );
    $experience->setWasFullTime( $wasFullTime );
    $experience->store();

    $cv->addExperience( $experience );
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
$t->pparse( "output", "experience_edit"  );


?>
