<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";

require $DOCUMENTROOT . "classes/ezperson.php";
require $DOCUMENTROOT . "classes/ezpersontype.php";
require $DOCUMENTROOT . "classes/ezcompany.php";
require $DOCUMENTROOT . "classes/ezaddress.php";
require $DOCUMENTROOT . "classes/ezaddresstype.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezcompanytype.php";
require $DOCUMENTROOT . "classes/ezcompanyaddressdict.php";
require $DOCUMENTROOT . "classes/ezphone.php";
require $DOCUMENTROOT . "classes/ezphonetype.php";
require $DOCUMENTROOT . "classes/ezcompanyphonedict.php";

if ( $Action == "insert" )
{
  $newCompany = new eZCompany();
  $newCompany->setName( $CompanyName );  
  $newCompany->setContactType( $CompanyType );

  $newCompany->setComment( $Comment );

  { // hente ut gjeldene bruker
    $session = new eZSession();
    $session->get( $AuthenticatedSession ); 
    $usr = new eZUser();
    $usr->get( $session->userID() );
  }
  $newCompany->setOwner( $usr->id() );
  $cid = $newCompany->store();
    
  $newAddress = new eZAddress();
  $newAddress->setStreet1( $Street1 );
  $newAddress->setStreet2( $Street2 );
  $newAddress->setZip( $Zip );
  $aid = $newAddress->store();

  $dict = new eZCompanyAddressDict( );
  $dict->setCompanyID( $cid );
  $dict->setAddressID( $aid );
  $dict->store();

  $phone = new eZPhone();
}

<<<<<<< companyedit.php
if ( isset( $AddPhone ) )
{
    print( "Add phone" . $PhoneNumber ." - " . $PhoneType );

    $phone = new eZPhone( );
    $phone->setNumber( $PhoneNumber );
    $phone->setType( $PhoneType );
    $pid = $phone->store();    
    
    $dict = new eZCompanyPhoneDict();

    $dict->setCompanyID( $CID );
    $dict->setPhoneID( $pid );
    $dict->store();    
}

// Slette fra company list
if ( $Action == "delete" )
{
    $deleteCompany = new eZCompany();
    $deleteCompany->get( $CID );
    $deleteCompany->delete();

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "contactlist.php" );
}

// sjekke session
{
  include( $DOCUMENTROOT . "checksession.php" );
}

$t = new Template( "." );
$t->set_file( array(                    
                    "company_edit" => $DOCUMENTROOT . "templates/companyedit.tpl",
                    "company_type_select" => $DOCUMENTROOT . "templates/companytypeselect.tpl",
                    "address_type_select" => $DOCUMENTROOT . "templates/addresstypeselect.tpl",
                    "phone_type_select" => $DOCUMENTROOT . "templates/phonetypeselect.tpl",
                    "phone_item" => $DOCUMENTROOT . "templates/phoneitem.tpl"
                    ) );

if ( !isset( $Action ) )
    $Action = "insert";

$company = new eZCompany();
$companyType = new eZCompanyType();
$company = new eZCompany();
$addressType = new eZAddressType();
$phoneType = new eZPhoneType();

$company_type_array = $companyType->getAll( );
$address_type_array = $addressType->getAll( );
$phone_type_array = $phoneType->getAll();

// company type selector
for ( $i=0; $i<count( $company_type_array ); $i++ )
{
  $t->set_var( "company_type_id", $company_type_array[$i][ "ID" ] );
  $t->set_var( "company_type_name", $company_type_array[$i][ "Name" ] );
  
  if ( $CompanyType == $company_type_array[$i][ "ID" ] )
  {
    $t->set_var( "is_selected", "selected" );
  }
  else
  {
    $t->set_var( "is_selected", "" );    
  }
  
  $t->parse( "company_type", "company_type_select", true );
}


  $t->set_var( "first_name", $FirstName );
  $t->set_var( "last_name", $LastName );

// address type selector
for ( $i=0; $i<count( $address_type_array ); $i++ )
{
  $t->set_var( "address_type_id", $address_type_array[$i][ "ID" ] );
  $t->set_var( "address_type_name", $address_type_array[$i][ "Name" ] );
  
  if ( $Address_Type == $address_type_array[$i][ "ID" ] )
  {
    $t->set_var( "is_selected", "selected" );
  }
  else
  {
    $t->set_var( "is_selected", "" );    
  }
  
  $t->parse( "address_type", "address_type_select", true );
}

// telefon type selector
for ( $i=0; $i<count( $phone_type_array ); $i++ )
{
  $t->set_var( "phone_type_id", $phone_type_array[$i][ "ID" ] );
  $t->set_var( "phone_type_name", $phone_type_array[$i][ "Name" ] );
  
  if ( $Phone_Type == $phone_type_array[$i][ "ID" ] )
  {
    $t->set_var( "is_selected", "selected" );
  }
  else
  {
    $t->set_var( "is_selected", "" );    
  }
  
  $t->parse( "phone_type", "phone_type_select", true );
}

// redigering av firma
if ( $Action == "edit" )
{

    $company = new eZCompany();
    $company->get( $CID );

    $phone = new eZPhone( );
//      $phone->setNumber( "35 53 35 47" );
//      $phone->setType( $PhoneType );
//      $pid = $phone->store();    
    
    $dict = new eZCompanyPhoneDict();

//      $dict->setCompanyID( $CID );
//      $dict->setPhoneID( $pid );
//      $dict->store(); 
    
    
    $dict_array = $dict->getByCompany( $CID );

    for ( $i=0; $i<count( $dict_array ); $i++ )
    {
        $phone->get( $dict_array[ $i ][ "PhoneID" ] );
        $phoneType->get( $phone->type() );
        
        $t->set_var( "phone_id", $phone->id() );
        $t->set_var( "phone_number", $phone->number() );
        $t->set_var( "phone_type_name", $phoneType->name() );

        $t->set_var( "phone_type_id", $i );
        $t->parse( "phone_list", "phone_item", true );                
    }

    $t->set_var( "phone_action", "AddPhone" );
    $t->set_var( "phone_action_value", "Legg til" );
    
}

$t->set_var( "comment", $Comment );

$t->set_var( "street_1", $Street1 );
$t->set_var( "street_2", $Street2 );
$t->set_var( "zip_code", $Zip );

$t->set_var( "phone_edit_number", $PhoneNumber );
$t->set_var( "phone_edit_id", $PhoneID );

$t->set_var( "submit_text", "lagre endringer" );

$t->set_var( "message", "Nytt kontakfirma" );
$t->set_var( "document_root", $DOCUMENTROOT );

$t->set_var( "edit_mode", $Action );
$t->set_var( "company_id", $CID );

$t->pparse( "output", "company_edit"  );

?>
