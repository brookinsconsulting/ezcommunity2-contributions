<?
/*
  Editere firma.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
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

$t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                     "ezcontact/user/intl", $Language, "companyview.php" );
$intl = new INIFile( "ezcontact/user/intl/$Language/companyview.php.ini", false );
$t->setAllStrings();

$t->set_file( array(                    
    "company_edit" => "companyview.tpl"
    ) );

$t->set_block( "company_edit", "address_item_tpl", "address_item" );
$t->set_var( "address_item", "&nbsp;" );
$t->set_block( "company_edit", "image_view_tpl", "image_view" );
$t->set_var( "image_view", "&nbsp;" );
$t->set_block( "company_edit", "logo_view_tpl", "logo_view" );
$t->set_var( "logo_view", "&nbsp;" );
$t->set_block( "company_edit", "no_image_tpl", "no_image" );
$t->set_var( "no_image", "&nbsp;" );
$t->set_block( "company_edit", "no_logo_tpl", "no_logo" );
$t->set_var( "no_logo", "&nbsp;" );

$t->set_block( "company_edit", "online_item_tpl", "online_item" );
$t->set_var( "online_item", "&nbsp;" );
$t->set_block( "online_item_tpl", "online_line_tpl", "online_line" );
$t->set_var( "online_line", "&nbsp;" );
$t->set_block( "company_edit", "no_online_item_tpl", "no_online_item" );
$t->set_var( "no_online_item", "&nbsp;" );
                                           
$t->set_block( "company_edit", "phone_item_tpl", "phone_item" );
$t->set_var( "phone_item", "&nbsp;" );
$t->set_block( "phone_item_tpl", "phone_line_tpl", "phone_line" );
$t->set_var( "phone_line", "&nbsp;" );
$t->set_block( "company_edit", "no_phone_item_tpl", "no_phone_item" );
$t->set_var( "no_phone_item", "&nbsp;" );
                                           
$company = new eZCompany();
$company->get( $CompanyID );


$t->set_var( "name", $company->name() );
$t->set_var( "description", $company->comment() );
$t->set_var( "company_no", $company->companyNo() );


// View logo.
$logoImage = $company->logoImage();

if ( ( get_class ( $logoImage ) == "ezimage" ) && ( $logoImage->id() != 0 ) )
{
    $variation = $logoImage->requestImageVariation( 150, 150 );
        
    $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
    $t->set_var( "logo_name", $logoImage->name() );
    $t->set_var( "logo_id", $logoImage->id() );

    $t->parse( "logo_view", "logo_view_tpl" );
}
else
{
    $t->parse( "no_logo", "no_logo_tpl" );
}
    

// View company image.
$companyImage = $company->companyImage();
    
if ( ( get_class ( $companyImage ) == "ezimage" ) && ( $companyImage->id() != 0 ) )
{
    $variation = $companyImage->requestImageVariation( 150, 150 );
        
    $t->set_var( "image_src", "/" . $variation->imagePath() );
    $t->set_var( "image_name", $companyImage->name() );
    $t->set_var( "image_id", $companyImage->id() );

    $t->parse( "image_view", "image_view_tpl" );
}
else
{
    $t->parse( "no_image", "no_image_tpl" );
}


$message = "Rediger firmainformasjon";


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



{
    // Telephone list
    $phoneList = $company->phones( $company->id() );

    $count = count( $phoneList );

    if( $count != 0 )
    {
        for( $i=0; $i < $count; $i++ )
        {
            $t->set_var( "phone_id", $phoneList[$i]->id() );
            $t->set_var( "phone", $phoneList[$i]->number() );

            $phoneType = $phoneList[$i]->phoneType();

            $t->set_var( "phone_type_id", $phoneType->id() );
            $t->set_var( "phone_type_name", $intl->read_var( "strings", "phone_" . $phoneType->name() ) );

            $t->set_var( "phone_width", 100/$count );
            $t->parse( "phone_line", "phone_line_tpl", true );
        }
        $t->parse( "phone_item", "phone_item_tpl" );
    }
    else
    {
        $t->parse( "no_phone_item", "no_phone_item_tpl" );
    }
    
    
    
    // Online list
    $OnlineList = $company->onlines( $company->id() );
    $count = count( $OnlineList );
    if ( $count != 0)
    {
        for( $i=0; $i< $count; $i++ )
        {
            $t->set_var( "online_id", $OnlineList[$i]->id() );
            $t->set_var( "online", $OnlineList[$i]->URL() );
            $t->set_var( "online_url_type", $OnlineList[$i]->URLType() );
            
            $onlineType = $OnlineList[$i]->onlineType();

            $t->set_var( "online_type_id", $onlineType->id() );
            $t->set_var( "online_type_name", $intl->read_var( "strings", "online_" . $onlineType->name() ) );
            $t->set_var( "online_url_type", $OnlineList[$i]->urlType() );
            $t->set_var( "online_width", 100/$count );
            $t->parse( "online_line", "online_line_tpl", true );
        }
        $t->parse( "online_item", "online_item_tpl" );
    }
    else
    {
        $t->parse( "no_online_item", "no_online_item_tpl" );
    }
}
// Template variabler.
$Action_value = "update";

$t->pparse( "output", "company_edit"  );

?>
