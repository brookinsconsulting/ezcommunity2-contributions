<?
/*
  Handle contact persons for a position
*/

include_once( "classes/INIFile.php" );
include_once( "classes/ezmail.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZClassifiedMain", "Language" );

include_once( "ezclassified/classes/ezposition.php" );
include_once( "ezclassified/classes/ezcategory.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezonline.php" );
include_once( "ezcontact/classes/ezaddress.php" );

if( $Action == "delete" )
{
    Header( "Location: /classified/edit/$ClassifiedID" );
    exit();
}

$error = false;

$t = new eZTemplate( "ezclassified/admin/" . $ini->read_var( "eZClassifiedMain", "AdminTemplateDir" ),
                     "ezclassified/admin/intl", $Language, "classifiedpersonedit.php" );
$t->setAllStrings();

$t->set_file( array(                    
    "classified_person_edit" => "classifiedpersonedit.tpl"
    ) );

//  $t->set_block( "classified_person_edit", "delete_button_tpl", "delete_button" );
$t->set_block( "classified_person_edit", "person_form_tpl", "person_form" );
$t->set_block( "classified_person_edit", "no_person_form_tpl", "no_person_form" );

$t->set_block( "classified_person_edit", "person_item_tpl", "person_item" );

$t->set_block( "classified_person_edit", "address_item_tpl", "address_item" );

$t->set_block( "classified_person_edit", "work_phone_item_tpl", "work_phone_item" );
$t->set_block( "classified_person_edit", "work_fax_item_tpl", "work_fax_item" );

$t->set_block( "classified_person_edit", "email_item_tpl", "email_item" );

$t->set_block( "classified_person_edit", "errors_tpl", "errors_item" );

$t->set_block( "errors_tpl", "error_firstname_item_tpl", "error_firstname_item" );
$t->set_block( "errors_tpl", "error_lastname_item_tpl", "error_lastname_item" );
$t->set_block( "errors_tpl", "error_birthdate_item_tpl", "error_birthdate_item" );
$t->set_block( "errors_tpl", "error_email_item_tpl", "error_email_item" );
$t->set_block( "errors_tpl", "error_personno_item_tpl", "error_personno_item" );
$t->set_block( "errors_tpl", "error_loginname_item_tpl", "error_loginname_item" );
$t->set_block( "errors_tpl", "error_password_item_tpl", "error_password_item" );
$t->set_block( "errors_tpl", "error_password_too_short_item_tpl", "error_password_too_short_item" );
$t->set_block( "errors_tpl", "error_email_not_valid_item_tpl", "error_email_not_valid_item" );
$t->set_block( "errors_tpl", "error_address_item_tpl", "error_address_item" );

$t->set_var( "person_form", "" );
$t->set_var( "no_person_form", "" );
$t->set_var( "company_item", "" );
$t->set_var( "firstname", "" );
$t->set_var( "lastname", "" );
$t->set_var( "contact_person_id", "" );
$t->set_var( "email", "" );
$t->set_var( "work_phone", "" );
$t->set_var( "work_fax", "" );
$t->set_var( "title", "" );

if ( $Action == "insert" || $Action == "update" )
{
    $t->set_var( "error_email_item", "" );
    $t->set_var( "error_email_not_valid_item", "" );
    $t->set_var( "error_firstname_item", "" );
    $t->set_var( "error_lastname_item", "" );

    if( empty( $Online[0] ) )
    {
        $t->parse( "error_email_item", "error_email_item_tpl" );
        $error = true;
    }
    else
    {
        if( !eZMail::validate( $Online[0] ) )
        {
            $t->parse( "error_email_not_valid_item", "error_email_not_valid_item_tpl" );
            $error = true;
        }
    }
        
    if( empty( $FirstName ) )
    {
        $t->parse( "error_firstname_item", "error_firstname_item_tpl" );
        $error = true;
    }
    
    if( empty( $LastName ) )
    {
        $t->parse( "error_lastname_item", "error_lastname_item_tpl" );
        $error = true;
    }

    if( $error == true )
    {
        $t->parse( "errors_item", "errors_tpl" );
    }
}

if( $error == false )
{
    $t->set_var( "errors_item", "" );
}
else
{
    $Action = "formdata";
}

if ( $Action == "insert" )
{
    $user = new eZUser();
    $user->setFirstName( $FirstName );
    $user->setLastName( $LastName );
    $user->store();

    $PersonID = $user->id();

    Header( "Location: /classified/person/edit/$ClassifiedID/$CompanyEdit/$PersonID" );
    exit();
}

//      Header( "Location: /classified/edit/$ClassifiedID" );
//      exit();

if ( $Action == "new" )
{
    $Action_value = "insert";
    $t->parse( "no_person_form", "no_person_form_tpl" );
}
else if ( $Action == "edit" )
{
    $Action_value = "update";
    $t->parse( "person_form", "person_form_tpl" );
    $t->set_var( "person_id", $PersonID );
}
else
{
    $t->parse( "no_person_form", "no_person_form_tpl" );
}

$t->parse( "email_item", "email_item_tpl" );
$t->parse( "work_phone_item", "work_phone_item_tpl" );
$t->parse( "work_fax_item", "work_fax_item_tpl" );

$t->set_var( "classified_id", $ClassifiedID );
$t->set_var( "company_id", $CompanyID );

//  // Company list
//  $company = new eZCompany();
//  $companyList = $company->getAll();

//  foreach( $companyList as $companyItem )
//  {
//      $t->set_var( "company_name", $companyItem->name() );
//      $t->set_var( "company_id", $companyItem->id() );
//      if ( $companyItem->id() == $CompanyID )
//          $t->set_var( "is_selected", "selected" );
//      else
//          $t->set_var( "is_selected", "" );

//      $t->parse( "company_item", "company_item_tpl", true );
//  }
//  $t->set_var( "company_select", "" );

// Person information

if ( $PersonID > 0 )
{
    $contactPerson = new eZPerson( $PersonID );
    $t->set_var( "firstname", $contactPerson->firstName() );
    $t->set_var( "lastname", $contactPerson->lastName() );
    $t->set_var( "contact_person_id", $contactPerson->id() );
    $mail = $contactPerson->emailAddress();
    if ( $mail )
    {
        $t->set_var( "email", $mail );
    }

    $work_phone = $contactPerson->workPhone();
    if ( $work_phone )
    {
        $t->set_var( "work_phone", $work_phone );
    }

    $fax_phone = $contactPerson->faxPhone();
    if ( $fax_phone )
    {
        $t->set_var( "work_fax", $fax_phone );
    }

    if ( $contactPerson->hasTitle( $CompanyID ) )
        $t->set_var( "title", $contactPerson->title( $CompanyID ) );
    else
        $t->set_var( "title", "" );
}

if( $Action == "formdata" )
{
    $Action_value = "insert";
    $t->set_var( "firstname", $FirstName );
    $t->set_var( "lastname", $LastName );

    $t->set_var( "work_phone", $WorkPhone );
    $t->set_var( "work_fax", $WorkFax );

    $t->set_var( "email", $Online[0] );
    $t->set_var( "person_id", "" );

    $t->parse( "email_item", "email_item_tpl" );
    $t->parse( "work_phone_item", "work_phone_item_tpl" );
    $t->parse( "work_fax_item", "work_fax_item_tpl" );
}

// Template variables.

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "classified_person_edit"  );

?>
