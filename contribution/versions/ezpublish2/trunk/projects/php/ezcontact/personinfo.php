<?
include "template.inc";
require "ezphputils.php";

require "classes/ezperson.php";
require "classes/ezpersontype.php";
require "classes/ezsession.php";
require "classes/ezuser.php";
require "classes/ezcompany.php";
require "classes/ezaddress.php";
require "classes/ezzip.php";
require "classes/ezpersonaddressdict.php";

$t = new Template( ".");  
$t->set_file( "person_info",  "templates/personinfo.tpl" );

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

for ( $i=0; $i<count( $dict_array ); $i++ )
{
    $address = new eZAddress();
    $address->get( $dict_array[ $i ][ "AddressID" ] );
    
    $t->set_var( "street1", $address->street1() );
    $t->set_var( "street2", $address->street2() );
    $t->set_var( "zip", $address->zip() );

    $zip = new eZZip();
    $zip->get( $address->zip() );
         
    $t->set_var( "place", $zip->place() );
}

$t->pparse( "output", "person_info" );

?>
