<?
include "template.inc";
require "ezphputils.php";

require "classes/ezperson.php";
require "classes/ezpersontype.php";
require "classes/ezsession.php";
require "classes/ezuser.php";
require "classes/ezcompany.php";
require "classes/ezaddress.php";
require "classes/ezaddresstype.php";
require "classes/ezphone.php";
require "classes/ezphonetype.php";
require "classes/ezzip.php";
require "classes/ezpersonaddressdict.php";
require "classes/ezpersonphonedict.php";

$t = new Template( ".");  
$t->set_file( array(
    "address_info" =>  "templates/addressinfo.tpl",
    "phone_info" =>  "templates/phoneinfo.tpl",
    "person_info" =>  "templates/personinfo.tpl" ) );

$person = new eZPerson();
$person->get( $PID );

$company = new eZCompany();
$company->get( $person->company() );

$t->set_var( "person_name", $person->firstName() . " " . $person->lastName() );

$t->set_var( "company", $company->name() );
$t->set_var( "comment", $person->comment() );

$usr = new eZUser();
$usr->get( $person->owner() );
$t->set_var( "owner", $usr->login() );

$dict = new eZPersonAddressDict();
$dict_array = $dict->getByPerson( $person->id() );

$t->set_var( "address_info_list", "" );

for ( $i=0; $i<count( $dict_array ); $i++ )
{
    $address = new eZAddress();
    $address->get( $dict_array[ $i ][ "AddressID" ] );

    $address_type = new eZAddressType();
    $address_type->get( $address->addressType() );    

    $t->set_var( "address_type", $address_type->name() );
    
    $t->set_var( "street1", $address->street1() );
    $t->set_var( "street2", $address->street2() );
    $t->set_var( "zip", $address->zip() );

    $zip = new eZZip();
    $zip->get( $address->zip() );
         
    $t->set_var( "place", $zip->place() );
    $t->parse( "address_info_list", "address_info", true );
}

$dict = new eZPersonPhoneDict();
$dict_array = $dict->getByPerson( $person->id() );

$t->set_var( "phone_info_list", "" );
for ( $i=0; $i<count( $dict_array ); $i++ )
{
    $phone = new eZPhone();
    $phone->get( $dict_array[ $i ][ "PhoneID" ] );

    $phone_type = new eZPhoneType();
    $phone_type->get( $phone->type() );    

    $t->set_var( "phone_type", $phone_type->name() );
    
    $t->set_var( "phone_number", $phone->number() );

    $t->parse( "phone_info_list", "phone_info", true );
}

$t->pparse( "output", "person_info" );

?>
