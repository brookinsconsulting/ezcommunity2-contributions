<?
/*
  Editere firma.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
//  include_once( "classes/ezsession.php" );
//  include_once( "classes/ezusergroup.php" );
//  include_once( "classes/ezuser.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezaddresstype.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "ezcontact/classes/ezphonetype.php" );
include_once( "ezcontact/classes/ezonline.php" );
include_once( "ezcontact/classes/ezonline.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "classes/ezimagefile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "companyview.php" );
$t->setAllStrings();

$t->set_file( array(                    
    "company_edit" => "companyview.tpl"
    ) );

$t->set_block( "company_edit", "address_item_tpl", "address_item" );
$t->set_block( "company_edit", "phone_item_tpl", "phone_item" );
$t->set_block( "company_edit", "fax_item_tpl", "fax_item" );
$t->set_block( "company_edit", "web_item_tpl", "web_item" );
$t->set_block( "company_edit", "email_item_tpl", "email_item" );
$t->set_block( "company_edit", "image_view_tpl", "image_view" );
$t->set_block( "company_edit", "logo_view_tpl", "logo_view" );
$t->set_block( "company_edit", "no_image_tpl", "no_image" );
$t->set_block( "company_edit", "no_logo_tpl", "no_logo" );
                                            
$company = new eZCompany();
$company->get( $CompanyID );
    
$t->set_var( "name", $company->name() );
$t->set_var( "description", $company->comment() );
$t->set_var( "companyno", $company->companyNo() );

// View logo.
$logoImage = $company->logoImage();

if ( ( get_class ( $logoImage ) == "ezimage" ) && ( $logoImage->id() != 0 ) )
{
    $variation = $logoImage->requestImageVariation( 150, 150 );
        
    $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
    $t->set_var( "logo_name", $logoImage->name() );
    $t->set_var( "logo_id", $logoImage->id() );

    $t->set_var( "no_logo", "" );
    $t->parse( "logo_view", "logo_view_tpl" );
}
else
{
    $t->set_var( "logo_view", "" );
    $t->parse( "no_logo", "no_logo_tpl" );
}
    

// View company image.
$companyImage = $company->companyImage();

if ( ( get_class ( $companyImage ) == "ezimage" ) && ( $companyImage->id() != 0 ) )
{
    $variation = $companyImage->requestImageVariation( 150, 150 );

    print( $variation->imagePath() );
    $t->set_var( "image_src", "/" . $variation->imagePath() );
    $t->set_var( "image_name", $companyImage->name() );
    $t->set_var( "image_id", $companyImage->id() );

    $t->set_var( "no_image", "" );
    $t->parse( "image_view", "image_view_tpl" );
}
else
{
    $t->set_var( "image_view", "" );
    $t->parse( "no_image", "no_image_tpl" );
}


$message = "Rediger firmainformasjon";

    // Telephone list
$phoneList = $company->phones( $company->id() );

if ( count( $phoneList ) <= 2 )
{
    for( $i=0; $i<count ( $phoneList ); $i++ )
    {
        if ( $phoneList[$i]->phoneTypeID() == 1 )
        {
            $t->set_var( "tele_phone_id", $phoneList[$i]->id() );
            $t->set_var( "telephone", $phoneList[$i]->number() );
        }
        if ( $phoneList[$i]->phoneTypeID() == 2 )
        {
            $t->set_var( "fax_phone_id", $phoneList[$i]->id() );
            $t->set_var( "fax", $phoneList[$i]->number() );
        }

        $t->parse( "phone_item", "phone_item_tpl" );
        $t->parse( "fax_item", "fax_item_tpl" );
    }
}

// Address list
$addressList = $company->addresses( $company->id() );
if ( count ( $addressList ) == 1 )
{
    foreach( $addressList as $addressItem )
        {
            $t->set_var( "address_id", $addressItem->id() );
            $t->set_var( "street1", $addressItem->street1() );
            $t->set_var( "street2", $addressItem->street2() );
            $t->set_var( "zip", $addressItem->zip() );
            $t->set_var( "place", $addressItem->place() );
            
            $t->set_var( "company_id", $CompanyID );
            
            $t->set_var( "script_name", "companyedit.php" );

            $t->parse( "address_item", "address_item_tpl", true );
            
        }
}

// Online list
$onlineList = $company->onlines( $company->id() );

if ( count ( $onlineList ) <= 2 )
{
    for( $i=0; $i<count ( $onlineList ); $i++ )
    {
        if ( $onlineList[$i]->onlineTypeID() == 1 )
        {
            $t->set_var( "web_online_id", $onlineList[$i]->id() );
            $t->set_var( "web", $onlineList[$i]->URL() );
        }
        if ( $onlineList[$i]->onlineTypeID() == 2 )
        {
            $t->set_var( "email_online_id", $onlineList[$i]->id() );
            $t->set_var( "email", $onlineList[$i]->URL() );
        }
            
    }
}
$t->parse( "web_item", "web_item_tpl" );
$t->parse( "email_item", "email_item_tpl" );

// Template variabler.
$Action_value = "update";

$t->set_var( "error", "" );

$t->pparse( "output", "company_edit"  );

?>
