<?
/*
  Viser infoe over alle kontakter
*/
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$errorIni = new INIFIle( "ezcontact/user/intl/" . $Language . "/companyinfo.php.ini", false );

include_once( "classes/eztemplate.php" );

include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezonline.php" );
include_once( "ezcontact/classes/ezphone.php" );

// include_once( "ezcontact/topmenu.php" );

$t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                     "ezcontact/user/intl/", $Language, "companyinfo.php" );
$t->setAllStrings();

$t->set_file( array(
    "company_info" => "companyinfo.tpl" ) );

$t->set_block( "company_info", "company_item_tpl", "company_item" );
$t->set_block( "company_info", "error_tpl", "error" );
$t->set_block( "company_info", "logo_tpl", "logo" );
$t->set_block( "company_info", "image_tpl", "image" );

$company = new eZCompany( $CompanyID );

if ( $company )
{
    $t->set_var( "headline", $company->name() );
    $t->set_var( "company_name", $company->name() );
    $t->set_var( "company_comment", $company->comment() );
    $t->set_var( "company_no", $company->companyNo() );

    // Address list
    $addressList = $company->addresses( $CompanyID );
    for( $i=0; $i<count( $addressList ); $i++ )
    {
        if ( count ( $addressList ) == 1 )
        {
            $t->set_var( "street1", $addressList[$i]->street1() );
            $t->set_var( "street2", $addressList[$i]->street2() );
            $t->set_var( "zip", $addressList[$i]->zip() );
            $t->set_var( "place", $addressList[$i]->place() );
        }
    }

    // Phone list
    $phoneList = $company->phones( $CompanyID );
    for( $i=0; $i<count( $phoneList ); $i++ )
    {
        if ( $phoneList[$i]->phoneTypeID() == 1 )
        {
            $t->set_var( "telephone", $phoneList[$i]->number() );
        }
        if ( $phoneList[$i]->phoneTypeID() == 2 )
        {
            $t->set_var( "fax", $phoneList[$i]->number() );
        }
    }

    // Online list
    $onlineList = $company->onlines( $CompanyID );
    for( $i=0; $i<count( $onlineList ); $i++ )
    {
        if ( $onlineList[$i]->onlineTypeID() == 1 )
        {
            $t->set_var( "web", $onlineList[$i]->URL() );
        }
        if ( $onlineList[$i]->onlineTypeID() == 2 )
        {
            $t->set_var( "email", $onlineList[$i]->URL() );
        }
    }

    $logoImage = $company->logoImage();

    if ( get_class ( $logoImage ) )
    {
        $variation = $logoImage->requestImageVariation( 150, 150 );
        
        $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
        $t->set_var( "logo_name", $logoImage->name() );
        
        $t->parse( "logo", "logo_tpl" );
    }

    $companyImage = $company->companyImage();
    
    if ( get_class ( $companyImage ) )
    {
        $variation = $companyImage->requestImageVariation( 150, 150 );
        
        $t->set_var( "image_src", "/" . $variation->imagePath() );
        $t->set_var( "image_name", $companyImage->name() );
        
        $t->parse( "image", "image_tpl" );
    }

}

$t->set_var( "error", "" );
$t->pparse( "output", "company_info");

?>
