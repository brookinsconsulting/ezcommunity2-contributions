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
include_once( "ezcv/classes/ezextracurricular.php" );

if( !is_numeric( $CVID ) && $Action == "new" )
{
    header( "Location: /" );
}

$error = false;

$t = new eZTemplate( "ezcv/user/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/user/intl", $Language, "extracurricular.php" );
$intl = new INIFile( "ezcv/user/intl/" . $Language . "/extracurricular.php.ini", false );
$t->set_file( array(                    
    "extracurricular_edit" => "extracurricularedit.tpl"
    ) );

$extracurricular = new eZExtracurricular();

if( is_numeric( $ExtracurricularID ) )
{
    $extracurricular->get( $ExtracurricularID );

    $cv = new eZCV();
    $cv = $cv->getByExtracurricular( $ExtracurricularID );
    $CVID = $cv->id();

    $StartDate = new eZDate();
    $StartDate->setMySQLDate( $extracurricular->start() );
    $EndDate = new eZDate();
    $EndDate->setMySQLDate( $extracurricular->end() );
    
    $t->set_var( "startyear", $StartDate->year() );
    $t->set_var( "startmonth", $StartDate->month() );
    $t->set_var( "startday", $StartDate->day() );
    $t->set_var( "endyear", $EndDate->year() );
    $t->set_var( "endmonth", $EndDate->month() );
    $t->set_var( "endday", $EndDate->day() );
    $t->set_var( "extracurricular_organization", $extracurricular->organization() );
    $t->set_var( "extracurricular_position", $extracurricular->position() );
    $t->set_var( "extracurricular_comment", $extracurricular->comment() );
    $t->set_var( "extracurricular_speciality", $extracurricular->speciality() );
    $t->set_var( "extracurricular_id", $extracurricular->id() );  
}
else
{
    $t->set_var( "startyear", "$StartYear" );
    $t->set_var( "startmonth", "$StartMonth" );
    $t->set_var( "startday", "$StartDay" );
    $t->set_var( "endyear", "$EndYear" );
    $t->set_var( "endmonth", "$EndMonth" );
    $t->set_var( "endday", "$EndDay" );
    $t->set_var( "extracurricular_organization", "$Organization" );
    $t->set_var( "extracurricular_position", "$Position" );
    $t->set_var( "extracurricular_comment", "$Comment" );
    $t->set_var( "extracurricular_speciality", "$Speciality" );    
    $t->set_var( "extracurricular_id", "" );  
}

if( $Action == "delete" && is_numeric( $ExtracurricularID ) )
{
    $cv->deleteExtracurricular( $ExtracurricularID );
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
    
    $extracurricular->setStart( $StartDate->mySQLDate() );
    $extracurricular->setEnd( $EndDate->mySQLDate() );
    $extracurricular->setOrganization( $Organization );
    $extracurricular->setPosition( $Position );
    $extracurricular->setComment( $Comment );
    $extracurricular->setSpeciality( $Speciality );
    $extracurricular->store();

    $cv->addExtracurricular( $extracurricular );
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
$t->pparse( "output", "extracurricular_edit"  );


?>
