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
require $DOCUMENTROOT . "classes/ezphone.php";
require $DOCUMENTROOT . "classes/ezphonetype.php";
require $DOCUMENTROOT . "classes/ezpersonphonedict.php";
require $DOCUMENTROOT . "classes/ezpersonaddressdict.php";

print ( "blah" );

// Oppdatere informasjon.
if ( $Action == "update" )
{
    $updatePerson = new eZPerson();
    $updatePerson->get( $PID );
    $updatePerson->setFirstName( $FirstName );
    $updatePerson->setLastName( $LastName );
    $updatePerson->setContactType( $PersonType );
    $updatePerson->setCompany( $Company );
    $updatePerson->setComment( $Comment );
    $updatePerson->update();
}

// Slette person fra databasen.
if ( $Action == "delete" )
{
    $deletePerson = new eZPerson();
    $deletePerson->get ( $PID );
    $deletePerson->delete();

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "contactlist.php" );
    
}

// Legge til kontakt person.
if ( $Action == "insert" )
{
  $newPerson = new eZPerson();
  $newPerson->setFirstName( $FirstName );
  $newPerson->setLastName( $LastName );
  $newPerson->setContactType( $PersonType );

  $newPerson->setCompany( $CompanyID );
  $newPerson->setComment( $Comment );

  { // hente ut gjeldene bruker.
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
}

// Legge til telefon
if ( $PhoneAction == "AddPhone" )
{
    print ( "banan" );
    $phone = new eZPhone();
    $phone->setNumber( $PhoneNumber );
    $phone->setType( $PhoneType );
    $pid = $phone->store();

    $dict = new eZPersonPhoneDict();

    $dict->setPhoneID( $PID );
    $dict->setPhoneID( $pid );
    $dict->store();
}

// Oppdatere telefon
if ( $PhoneAction == "UpdatePhone" )
{
    $phone = new eZPhone();
    $phone->get( $PhoneID );

    $phone->setNumber( $PhoneNumber );
    $phone->setType( $PhoneType );
    $phone->update();
}

// Slette telefon
if ( $PhoneAction == "delete" )
{
    $phone = new eZPhone();
    $phone->get( $PhoneID );
    $phone->delete();
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
                    "address_type_select" => $DOCUMENTROOT . "templates/addresstypeselect.tpl",
                    "phone_type_select" => $DOCUMENTROOT . "templates/phonetypeselect.tpl",
                    "phone_item" => $DOCUMENTROOT . "templates/phoneitem.tpl"
                    ) );


$message = "Legg til ny kontakt person informasjon";
$submit_text = "Legg til";
$action_value = "insert";

$person = new eZPerson();
$personType = new eZPersonType();
$company = new eZCompany();
$addressType = new eZAddressType();
$phoneType = new eZPhoneType();

$person_type_array = $personType->getAll( );
$company_array = $company->getAll( );
$address_type_array = $addressType->getAll( );
$phone_type_array = $phoneType->getAll( );

$t->set_var( "phone_action_type", "hidden" );


// Editere kontakt person
if ( $Action == "edit" )
{
        print ( "tjo" );
    $editPerson = new eZPerson();
    $editPerson->get( $PID );
    
    $FirstName = $editPerson->firstName();
    $LastName = $editPerson->lastName();
    $PersonType = $editPerson->contactType();
    $Company = $editPerson->company();
    $Comment = $editPerson->comment();

    $message = "Rediger kontakt person informasjon";
    $submit_text = "Endre informasjon";    
    $action_value = "update";
    $person_id = $PID;


        $phone = new eZPhone();

         $dict = new eZPersonPhoneDict();

        $dict_array = $dict->getByPerson( $PID );
    
    for ( $i=0; $i<count( $dict_array ); $i++ )
    {
        $phone->get( $dict_array[ $i ][ "PhoneID" ] );
        $phoneType->get( $phone->type() );

        $t->set_var( "phone_id", $phone->id() );
        $t->set_var( "phone_number", $phone->number() );
        $t->set_var( "phone_type_name", $phoneType->name() );

        $t->set_var( "phone_type_id", $phone_select_dict[ $phoneType->id() ] );

        $t->parse( "phone_list", "phone_item", true );
    }

    $t->set_var( "phone_action", "AddPhone" );
    $t->set_var( "phone_edit_id", "-1" );
    $t->set_var( "phone_action_value", "Legg til" );
    $t->set_var( "phone_action_type", "submit" );
        
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

    $phone_select_dict[ $phone_type_array[$i][ "ID" ] ] = $i;
  
    $t->parse( "phone_type", "phone_type_select", true );
}


$t->set_var( "comment", $Comment );

$t->set_var( "street_1", $Street1 );
$t->set_var( "street_2", $Street2 );
$t->set_var( "zip_code", $Zip );

$t->set_var( "phone_edit_number", $PhoneNumber );
$t->set_var( "phone_edit_id", $PhoneID );

$t->set_var( "submit_text", $submit_text );
$t->set_var( "action_value", $action_value );
$t->set_var( "message", $message );

$t->set_var( "document_root", $DOCUMENTROOT );
$t->set_var( "person_id", $PID );

$t->pparse( "output", "person_edit"  );
?>
