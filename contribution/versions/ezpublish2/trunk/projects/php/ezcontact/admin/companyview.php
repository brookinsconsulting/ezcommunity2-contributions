<?
/*
  Editere firma.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );

include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezaddress/classes/ezaddresstype.php" );
include_once( "ezaddress/classes/ezphone.php" );
include_once( "ezaddress/classes/ezphonetype.php" );
include_once( "ezaddress/classes/ezonline.php" );
include_once( "ezaddress/classes/ezonlinetype.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezcontact/classes/ezprojecttype.php" );
include_once( "ezcontact/classes/ezconsultation.php" );

include_once( "classes/ezimagefile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

$user = eZUser::currentUser();
if ( get_class( $user ) != "ezuser" )
{
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZContact", "CompanyView" ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/company/view" );
    exit();
}

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "companyview.php" );
$intl = new INIFile( "ezcontact/admin/intl/$Language/companyview.php.ini", false );
$t->setAllStrings();

$t->set_file( array(
    "company_edit" => "companyview.tpl"
    ) );

$t->set_block( "company_edit", "contact_person_tpl", "contact_person" );
$t->set_block( "company_edit", "no_contact_person_tpl", "no_contact_person" );
$t->set_block( "company_edit", "project_status_tpl", "project_status" );
$t->set_block( "company_edit", "no_project_status_tpl", "no_project_status" );

$t->set_block( "company_edit", "consultation_buttons_tpl", "consultation_buttons" );

$t->set_block( "company_edit", "consultation_table_item_tpl", "consultation_table_item" );
$t->set_block( "consultation_table_item_tpl", "consultation_item_tpl", "consultation_item" );

$t->set_block( "company_edit", "address_item_tpl", "address_item" );
$t->set_var( "address_item", "" );
$t->set_block( "company_edit", "no_address_item_tpl", "no_address_item" );
$t->set_var( "no_address_item", "" );
$t->set_block( "company_edit", "image_view_tpl", "image_view" );
$t->set_var( "image_view", "&nbsp;" );
$t->set_block( "company_edit", "logo_view_tpl", "logo_view" );
$t->set_var( "logo_view", "&nbsp;" );
$t->set_block( "company_edit", "no_image_tpl", "no_image" );
$t->set_var( "no_image", "&nbsp;" );

$t->set_block( "company_edit", "online_item_tpl", "online_item" );
$t->set_var( "online_item", "&nbsp;" );
$t->set_block( "online_item_tpl", "online_line_tpl", "online_line" );
$t->set_var( "online_line", "&nbsp;" );
$t->set_block( "online_line_tpl", "email_line_tpl", "email_line" );
$t->set_var( "email_line", "" );
$t->set_block( "online_line_tpl", "url_line_tpl", "url_line" );
$t->set_var( "url_line", "" );
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

$t->set_var( "company_id", $CompanyID );

// Address list
$addressList = $company->addresses( $company->id() );
if ( count ( $addressList ) != 0 )
{
    foreach( $addressList as $addressItem )
    {
        $t->set_var( "address_id", $addressItem->id() );
        $t->set_var( "street1", $addressItem->street1() );
        $t->set_var( "street2", $addressItem->street2() );
        $t->set_var( "zip", $addressItem->zip() );
        $t->set_var( "place", $addressItem->place() );
        $addressType = $addressItem->addressType();
        $t->set_var( "address_type_name", $addressType->name() );
        $country = $addressItem->country();
        $t->set_var( "country", $country->name() );
        
        $t->set_var( "script_name", "companyedit.php" );

        $t->parse( "address_item", "address_item_tpl", true );
            
    }
}
else
{
    $t->parse( "no_address_item", "no_address_item_tpl" );
}


// Telephone list
$phoneList = $company->phones();

$count = count( $phoneList );

if( $count != 0 )
{
    for( $i=0; $i < $count; $i++ )
    {
        $t->set_var( "phone_id", $phoneList[$i]->id() );
        $t->set_var( "phone", $phoneList[$i]->number() );

        $phoneType = $phoneList[$i]->phoneType();

        $t->set_var( "phone_type_id", $phoneType->id() );
        $t->set_var( "phone_type_name", $phoneType->name() );

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
        $onlineType = $OnlineList[$i]->onlineType();

        $prefix = $onlineType->URLPrefix();
        $vis_prefix = $prefix;
        $url = $OnlineList[$i]->URL();
        if ( $onlineType->prefixLink() )
        {
            if ( strncasecmp( $url, $prefix, count( $prefix ) ) == 0 )
            {
                $prefix = "";
            }
        }
        else
        {
            $prefix = "";
        }
        if ( $onlineType->prefixVisual() )
        {
            if ( strncasecmp( $url, $vis_prefix, count( $vis_prefix ) ) == 0 )
            {
                $vis_prefix = "";
            }
        }
        else
        {
            $vis_prefix = "";
        }

        $t->set_var( "online_prefix", $prefix );
        $t->set_var( "online_visual_prefix", $vis_prefix );
        $t->set_var( "online", $OnlineList[$i]->URL() );
        $t->set_var( "online_type_id", $onlineType->id() );
        $t->set_var( "online_type_name", $onlineType->name() );
        $t->set_var( "online_width", 100/$count );

        $t->parse( "online_line", "online_line_tpl", true );
    }
    $t->parse( "online_item", "online_item_tpl" );
}
else
{
    $t->parse( "no_online_item", "no_online_item_tpl" );
}

$t->set_var( "contact_person", "" );
$t->set_var( "no_contact_person", "" );

$contact = $company->contact();
if ( $contact )
{
    if ( $company->contactType() == "ezperson" )
        $user = new eZPerson( $contact );
    else
        $user = new eZUser( $contact );
    $t->set_var( "contact_firstname", $user->firstName() );
    $t->set_var( "contact_lastname", $user->lastName() );
    $t->parse( "contact_person", "contact_person_tpl" );
}
else
{
    $t->parse( "no_contact_person", "no_contact_person_tpl" );
}

$t->set_var( "project_status", "" );
$t->set_var( "no_project_status", "" );

$statusid = $company->projectState();
if ( $statusid )
{
    $status = new eZProjectType( $statusid );
    $t->set_var( "project_status", $status->name() );
    $t->parse( "project_status", "project_status_tpl" );
}
else
{
    $t->parse( "no_project_status", "no_project_status_tpl" );
}

// Consultation list
$user = eZUser::currentUser();
if ( get_class( $user ) == "ezuser" )
{
    $max = $ini->read_var( "eZContactMain", "MaxCompanyConsultationList" );
    $consultations = eZConsultation::findConsultationsByContact( $CompanyID, $user->id(), false, 0, $max );
    $t->set_var( "consultation_type", "company" );
    $t->set_var( "company_id", $CompanyID  );

    $locale = new eZLocale( $Language );
    $i = 0;

    foreach ( $consultations as $consultation )
    {
        if( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "bglight" );
        }
        else
        {
            $t->set_var( "bg_color", "bgdark" );
        }

        $t->set_var( "consultation_id", $consultation->id() );
        $t->set_var( "consultation_date", $locale->format( $consultation->date() ) );
        $t->set_var( "consultation_short_description", $consultation->shortDescription() );
        $t->set_var( "consultation_status_id", $consultation->state() );
        $t->set_var( "consultation_status", eZConsultation::stateName( $consultation->state() ) );
        $t->parse( "consultation_item", "consultation_item_tpl", true );
        $i++;
    }
}

if ( get_class( $user ) == "ezuser" and count( $consultations ) > 0 )
{
    $t->parse( "consultation_table_item", "consultation_table_item_tpl", true );
}
else
{
    $t->set_var( "consultation_table_item", "" );
}

if ( get_class( $user ) == "ezuser" )
{
    $t->parse( "consultation_buttons", "consultation_buttons_tpl" );
}
else
{
    $t->set_var( "consultation_buttons", "" );
}

// Template variabler.
$Action_value = "update";

$t->pparse( "output", "company_edit"  );

?>
