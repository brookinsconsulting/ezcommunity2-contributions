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
include_once( "ezcv/classes/ezcertificate.php" );
include_once( "ezcv/classes/ezcertificatetype.php" );
include_once( "ezcv/classes/ezcertificatecategory.php" );

if( !is_numeric( $CVID ) )
{
    header( "Location: /" );
}

$error = false;

$t = new eZTemplate( "ezcv/user/" . $ini->read_var( "eZCVMain", "TemplateDir" ),
                     "ezcv/user/intl", $Language, "certificate.php" );
$intl = new INIFile( "ezcv/user/intl/" . $Language . "/certificate.php.ini", false );
$t->set_file( array(                    
    "certificate_edit" => "certificateedit.tpl"
    ) );
$t->set_block( "certificate_edit", "parent_item_tpl", "parent_item" );
$t->set_var( "parent_item", "" );


$certificate = new eZCertificate();
if( is_numeric( $CertificateID ) )
{
    $certificate->get( $CertificateID );
    $cv = new eZCV();
    $cv = $cv->getByCertificate( $CertificateID );
    $CVID = $cv->id();

    $StartDate = new eZDate();
    $StartDate->setMySQLDate( $certificate->received() );
    $EndDate = new eZDate();
    $EndDate->setMySQLDate( $certificate->expires() );
    
    $t->set_var( "startyear", $StartDate->year() );
    $t->set_var( "startmonth", $StartDate->month() );
    $t->set_var( "startday", $StartDate->day() );
    $t->set_var( "endyear", $EndDate->year() );
    $t->set_var( "endmonth", $EndDate->month() );
    $t->set_var( "endday", $EndDate->day() );
    $t->set_var( "certificate_institution", $certificate->institution() );
    $t->set_var( "certificate_name", $certificate->name() );
    $t->set_var( "certificate_id", $certificate->id() );  
    $t->set_var( "current_id", $certificate->id() );  
}
else
{
    $t->set_var( "startyear", "$StartYear" );
    $t->set_var( "startmonth", "$StartMonth" );
    $t->set_var( "startday", "$StartDay" );
    $t->set_var( "endyear", "$EndYear" );
    $t->set_var( "endmonth", "$EndMonth" );
    $t->set_var( "endday", "$EndDay" );
    $t->set_var( "certificate_institution", "$Institution" );
    $t->set_var( "certificate_name", "$Name" );
    $t->set_var( "certificate_id", "" );  
    $t->set_var( "current_id", "" );  
}

if( $Action == "delete" && is_numeric( $CertificateID ) )
{
    $cv->deleteEducation( $CertificateID );
    header( "Location: /cv/cv/edit/$CVID" );
}

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


    $certificate->setInstitution( $Institution );
    $certificate->setName( $Name );
    $certificate->setReceived( $StartDate->mySQLDate() );
    $certificate->setExpires( $EndDate->mySQLDate() );

    $certificate->store();

    $cv->addCertificate( $certificate );
    $cv->store();
    
    header( "Location: /cv/cv/edit/$CVID" );
}
$t->set_var( "cv_id", "$CVID" );

if( $Action == "edit" || $Action == "new" )
{
    if( $Action == "edit" )
    {
        $ActionValue = "update";
    }

    if( $Action == "new" )
    {
        $ActionValue = "insert";
    }
    
}
$t->setAllStrings();
$t->set_var( "action_value", $ActionValue );
$t->pparse( "output", "certificate_edit"  );


?>
