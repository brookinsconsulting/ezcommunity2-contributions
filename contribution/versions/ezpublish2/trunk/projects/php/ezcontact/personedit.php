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
require $DOCUMENTROOT . "classes/ezpersonphonedict.php";
require $DOCUMENTROOT . "classes/ezpersonaddressdict.php";



// Oppdatere informasjon
if ( $Action == "update" )
{
    $updatePerson = new eZPerson();
    $updatePerson->setFirstName( $FirstName );
    $updatePerson->setLastName( $LastName );
    $updatePerson->setContactType( $PersonType );
    $updatePerson->setCompany( $Company );
    $updatePerson->setComment( $Comment );
    $updatePerson->update();
}


// Legge til kontakt person
if ( $Action == "insert" )
{
  $newPerson = new eZPerson();
  $newPerson->setFirstName( $FirstName );
  $newPerson->setLastName( $LastName );
  $newPerson->setContactType( $PersonType );

  $newPerson->setCompany( $CompanyID );
  $newPerson->setComment( $Comment );

  { // hente ut gjeldene bruker
    $session = new eZSession();
    $session->get( $AuthenticatedSession ); 
    $usr = new eZUser();
    $usr->get( $session->userID() );
  }
  $newPerson->setOwner( $usr->id() );
  $pid = $newPerson->store();
    
  $newAddress = new eZAddress();
  $newAddress->setStreet1( $Street1 );
  $newAddress->setStreet2( $Street2 );
  $newAddress->setZip( $Zip );
  $aid = $newAddress->store();

  $link = new eZPersonAddressDict();
  $link->setPersonID( $pid );
  $link->setAddressID( $aid );
  $link->store();

  $message = "Legg til ny kontakt person informasjon";
  $submit_text = "Legg til";
  $action_value = "insert";
}

// sjekke session
{
  include( $DOCUMENTROOT . "checksession.php" );
}

$t = new Template( "." );
$t->set_file( array(                    
                    "person_edit" => $DOCUMENTROOT . "templates/personedit.tpl",
                    "person_type_select" => $DOCUMENTROOT . "templates/persontypeselect.tpl",
                    "company_select" => $DOCUMENTROOT . "templates/companyselect.tpl",
                    "address_type_select" => $DOCUMENTROOT . "templates/addresstypeselect.tpl"
                    ) );

$person = new eZPerson();
$personType = new eZPersonType();
$company = new eZCompany();
$addressType = new eZAddressType();

$person_type_array = $personType->getAll( );
$company_array = $company->getAll( );
$address_type_array = $addressType->getAll( );

// Editere kontakt person
if ( $Action == "edit" )
{
    print ( "banan" );
    $editPerson = new eZPerson();
    $editPerson->get( $PID );
    
    $FirstName = $editPerson->firstName();
    $LastName = $editPerson->lastName();
    $PersonType = $editPerson->contactType();
    $Company = $editPerson->company();
    $Comment = $editPerson->comment();

    print ( "eple" );

    $message = "Rediger kontakt person informasjon";
    $submit_text = "Endre informasjon";
    $action_value = "update";
}


// person type selector
for ( $i=0; $i<count( $person_type_array ); $i++ )
{
  $t->set_var( "person_type_id", $person_type_array[$i][ "ID" ] );
  $t->set_var( "type", $person_type_array[$i][ "Name" ] );
  
  if ( $PersonType == $person_type_array[$i][ "ID" ] )
  {
    $t->set_var( "is_selected", "selected" );
  }
  else
  {
    $t->set_var( "is_selected", "" );    
  }
  
  $t->parse( "person_type", "person_type_select", true );
}

// company type selector
for ( $i=0; $i<count( $company_array ); $i++ )
{
  $t->set_var( "company_type_id", $company_array[$i][ "ID" ] );
  $t->set_var( "company", $company_array[$i][ "Name" ] );
  
  if ( $CompanyID == $company_array[$i][ "ID" ] )
  {
    $t->set_var( "is_selected", "selected" );
  }
  else
  {
    $t->set_var( "is_selected", "" );    
  }
  
  $t->parse( "company_type", "company_select", true );
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


$t->set_var( "comment", $Comment );

$t->set_var( "street_1", $Street1 );
$t->set_var( "street_2", $Street2 );
$t->set_var( "zip_code", $Zip );


$t->set_var( "submit_text", $submit_text );
$t->set_var( "action_value", $action_value );
$t->set_var( "message", $message );

$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "person_edit"  );
?>
