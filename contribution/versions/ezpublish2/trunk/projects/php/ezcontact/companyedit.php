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
require $DOCUMENTROOT . "classes/ezphone.php";
require $DOCUMENTROOT . "classes/ezphonetype.php";
require $DOCUMENTROOT . "classes/ezcompanyphonedict.php";
require $DOCUMENTROOT . "classes/ezcompanyaddressdict.php";

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

    //
    // Adresse og telefonnummer funker ikke med oppretting
    // pga at det er ulike forms.. adresser og telefonnummer
    // må derfor legges til etterpå.
    //
    
    // adresse
//      $newAddress = new eZAddress();
//      $newAddress->setStreet1( $Street1 );
//      $newAddress->setStreet2( $Street2 );
//      $newAddress->setZip( $Zip );
//      $newAddress->setAddressType( $AddressType );
//      $aid = $newAddress->store();

//      $dict = new eZCompanyAddressDict( );
//      $dict->setCompanyID( $cid );
//      $dict->setAddressID( $aid );
//      $dict->store();

    // telefonnummer
//      $phone = new eZPhone( );
//      $phone->setNumber( $PhoneNumber );
//      $phone->setType( $PhoneType );
//      $pid = $phone->store();    
  
//      $dict = new eZCompanyPhoneDict();
  
//      $dict->setCompanyID( $cid );
//      $dict->setPhoneID( $pid );
//      $dict->store();  
}

if ( $Action == "update" )
{
    $company = new eZCompany();
    $company->get( $CID  );
    
    $company->setName( $CompanyName );  
    $company->setContactType( $CompanyType );

    $company->setComment( $Comment );

    $company->update();    
}


// Slette fra company list
if ( $Action == "delete" )
{
    $deleteCompany = new eZCompany();
    $deleteCompany->get( $CID );
    $deleteCompany->delete();
    
    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "contactlist.php" );
}

if ( $PhoneAction == "AddPhone" )
{
    $phone = new eZPhone( );
    $phone->setNumber( $PhoneNumber );
    $phone->setType( $PhoneType );
    $pid = $phone->store();    
    
    $dict = new eZCompanyPhoneDict();

    $dict->setCompanyID( $CID );
    $dict->setPhoneID( $pid );
    $dict->store();
}

if ( $PhoneAction == "UpdatePhone" )
{
    $phone = new eZPhone( );
    $phone->get( $PhoneID );
    
    $phone->setNumber( $PhoneNumber );
    $phone->setType( $PhoneType );
    $phone->update();
}

if ( $PhoneAction == "DeletePhone" )
{
    $phone = new eZPhone( );
    $phone->get( $PhoneID );

    $dict = new eZCompanyPhoneDict();
    $dict->getByPhone( $phone->id() );
    
    $phone->delete();
    $dict->delete();
}

if ( $AddressAction == "AddAddress" )
{
    $address = new eZAddress( );
    $address->setStreet1( $Street1 );
    $address->setStreet2( $Street2 );
    $address->setZip( $Zip );
    $address->setAddressType( $AddressType );
    $pid = $address->store();

    $dict = new eZCompanyAddressDict();

    $dict->setCompanyID( $CID );
    $dict->setAddressID( $pid );
    $dict->store();
}


if ( $AddressAction == "UpdateAddress" )
{
    $address = new eZAddress( );
    $address->get( $AddressID );
    $address->setStreet1( $Street1 );
    $address->setStreet2( $Street2 );
    $address->setZip( $Zip );
    $address->setAddressType( $AddressType );
    $address->update();

//      $dict = new eZCompanyAddressDict();

//      $dict->setCompanyID( $CID );
//      $dict->setAddressID( $pid );
//      $dict->store();
}


if ( $AddressAction == "DeleteAddress" )
{
    $address = new eZAddress( );
    $address->get( $AddressID );

    $dict = new eZCompanyAddressDict();
    $dict->getByAddress( $address->id() );
    
    $address->delete();
    $dict->delete();
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
    "phone_item" => $DOCUMENTROOT . "templates/phoneitem.tpl",
    "address_item" => $DOCUMENTROOT . "templates/addressitem.tpl"
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

$t->set_var( "phone_action_type", "hidden" );
$t->set_var( "phone_list", "" );

$t->set_var( "address_action_type", "hidden" );
$t->set_var( "address_list", "" );



$address_select_dict = "";
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
  
    $address_select_dict[ $address_type_array[$i][ "ID" ] ] = $i;

    $t->parse( "address_type", "address_type_select", true );
}

$phone_select_dict = "";
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

// redigering av firma
if ( $Action == "edit" )
{
    $company = new eZCompany();
    $company->get( $CID );
    
    $CompanyName = $company->name();
    $Comment = $company->comment();
    $CompanyType = $company->contactType();

    $phone = new eZPhone( );
    
    $phone_dict = new eZCompanyPhoneDict();
    
    $phone_dict_array = $phone_dict->getByCompany( $CID );

    // telefonliste
    for ( $i=0; $i<count( $phone_dict_array ); $i++ )
    {
        $phone->get( $phone_dict_array[ $i ][ "PhoneID" ] );
        $phoneType->get( $phone->type() );
        
        $t->set_var( "phone_id", $phone->id() );
        $t->set_var( "phone_number", $phone->number() );
        $t->set_var( "phone_type_name", $phoneType->name() );

        $t->set_var( "phone_type_id", $phone_select_dict[ $phoneType->id() ] );

        $t->set_var( "script_name", "companyedit.php" );
        $t->set_var( "company_id", $CID );
        
        
        $t->parse( "phone_list", "phone_item", true );                
    }

    $address = new eZAddress();
    $address_dict = new eZCompanyAddressDict();
    $address_dict_array = $address_dict->getByCompany( $CID );
    
    // adresseliste
    for ( $i=0; $i<count( $address_dict_array ); $i++ )
    {
        $address->get( $address_dict_array[ $i ][ "AddressID" ] );
        $addressType->get( $address->addressType() );
        
        $t->set_var( "address_id", $address->id() );
        $t->set_var( "address_street1", $address->street1() );
        $t->set_var( "address_street2", $address->street2() );
        $t->set_var( "address_zip", $address->zip() );
        $t->set_var( "address_type_name", $addressType->name() );

        $t->set_var( "address_type_id", $address_select_dict[ $addressType->id() ] );

        $t->set_var( "company_id", $CID );
        
        $t->parse( "address_list", "address_item", true );                
    }
    
    $Action = "update";
    
    $t->set_var( "address_action", "AddAddress" );    
    $t->set_var( "address_action_value", "Legg til" );
    $t->set_var( "address_action_type", "submit" );    

    $t->set_var( "phone_action", "AddPhone" );
    $t->set_var( "phone_edit_id", "-1" );
    $t->set_var( "phone_action_value", "Legg til" );
    $t->set_var( "phone_action_type", "submit" );
}


// company type selector må være UNDER edit fordi at rett firmatype
// skal bli satt..
// 
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

$t->set_var( "company_name", $CompanyName );

$t->set_var( "company_comment", $Comment );

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
