<?
/*
  Redigerer en person
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );

include_once( "common/ezphputils.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezaddresstype.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "ezcontact/classes/ezphonetype.php" );
include_once( "ezcontact/classes/ezzip.php" );
include_once( "ezcontact/classes/ezpersonaddressdict.php" );
include_once( "ezcontact/classes/ezpersonphonedict.php" );

include_once( "ezcontact/topmenu.php" );

// Setter template.
$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "personedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "address_info" =>  "addressinfo.tpl",
    "phone_info" =>  "phoneinfo.tpl",
    "person_info" =>  "personinfo.tpl" ) );

$person = new eZPerson();
$person->get( $PID );

$company = new eZCompany();
$company->get( $person->company() );

$t->set_var( "person_name", $person->firstName() . " " . $person->lastName() );

$t->set_var( "company", $company->name() );
$t->set_var( "comment", $person->comment() );

$usr = new eZUser();
$usr->get( $person->owner() );
//  $t->set_var( "owner", $usr->nickname() );

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
