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

$t = new eZTemplate( "ezcv/admin/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/admin/intl", $Language, "certificate.php" );
$intl = new INIFile( "ezcv/admin/intl/" . $Language . "/certificate.php.ini", false );
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
    $t->set_var( "certificate_category", $certificate->category() );
    $t->set_var( "certificate_category_id", $certificate->categoryID() );
    $t->set_var( "certificate_description", $certificate->description() );
    $t->set_var( "certificate_type", $certificate->type() );
    $t->set_var( "certificate_type_id", $certificate->typeID() );
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
    $t->set_var( "certificate_category", "$Category" );
    $t->set_var( "certificate_category_id", "$CategoryID" );
    $t->set_var( "certificate_description", "$Description" );    
    $t->set_var( "certificate_type", "$Type" );  
    $t->set_var( "certificate_type_id", "$TypeID" );  
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
    $Type = explode( ".", $TypeID );

    if( $Type[0] == t )
    {
        $TypeID = $Type[1];
    }
    else
    {
        if( $Action == "insert" )
        {
            $Action = "new";
        }
        
        if( $Action == "insert" )        
        {
            $Action = "edit";
        }
    }
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
    
    $certificate->setReceived( $StartDate->mySQLDate() );
    $certificate->setExpires( $EndDate->mySQLDate() );
    $certificate->setCertificateType( $TypeID );
    $certificate->store();

    $cv->addCertificate( $certificate );
    $cv->store();
    
    header( "Location: /cv/cv/edit/$CVID" );
}
$t->set_var( "cv_id", "$CVID" );

function byType( $inStartID, $indent, $inParentID, $maxLevel = 3 )
{
    global $t;

    $type = new eZCertificateType();
    $typeArray = $type->getByCertificateCategoryID( $inStartID );
    
    if( $indent > $maxLevel )
    {
        $indent == $maxLevel;
    }
    $indentLine = str_pad( $indentLine, $indent * 2, "_" );

    foreach( $typeArray as $ct )
    {
        $TypeID = $ct->id();
        $t->set_var( "select_parent_id", "t." . $TypeID );
        $t->set_var( "select_parent_name", $indentLine . $ct->name() . "*" );
        $t->set_var( "theme_class", "selectable" );
        $t->set_var( "selected", "" );
        
        if( $TypeID == $inParentID )
        {
            $t->set_var( "selected", "selected" );
        }
        
        $t->parse( "parent_item", "parent_item_tpl", true );
    }   
}

function byParent( $inStartID, $indent, $inParentID, $maxLevel = 3 )
{
    global $t;

    $type = new eZCertificateCategory();
    $typeArray = $type->getByParentID( $inStartID );
    
    if( $indent > $maxLevel )
    {
        $indent == $maxLevel;
    }
    $indentLine = str_pad( $indentLine, $indent * 2, "_" );
    
    foreach( $typeArray as $ct )
    {
        $CategoryID = $ct->id();
        $t->set_var( "select_parent_id", $CategoryID );
        $t->set_var( "select_parent_name", $indentLine . $ct->name() );
        $t->set_var( "selected", "" );
        
        $t->parse( "parent_item", "parent_item_tpl", true );
        byParent( $ct->id(), $indent + 1, $inParentID );
        byType( $ct->id(), $indent + 1, $inParentID );
    }
}

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
    
    $certificateID = $certificate->certificateTypeID();
    
    if( $certificateID > 0 )
    {
        $selected = true;
    }
    else
    {
        $selected = false;
    }

    $type = new eZCertificateType();
    $types = $type->getAll();

    byParent( 0, 0, $TypeID );

    if( count( $types ) == 0 )
    {
        $t->set_var( "parent_item", "" );
    }

    if( $selected == false )
    {
        $t->set_var( "root_selected", "selected" );
    }
    else
    {
        $t->set_var( "root_selected", "" );
    }
    
}
$t->setAllStrings();
$t->set_var( "action_value", $ActionValue );
$t->pparse( "output", "certificate_edit"  );


?>
