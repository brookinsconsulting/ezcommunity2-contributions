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

$address = new eZAddress();

$address_array = $address->getByOwner( $person->id() );

for ( $i=0; $i<count( $address_array ); $i++ )
{
    $t->set_var( "street1", $address_array[$i][ "Street1" ]  );
    $t->set_var( "street2", $address_array[$i][ "Street2" ]  );
    $t->set_var( "zip", $address_array[$i][ "Zip" ]  );

    $zip = new eZZip();
    $zip->get( $address_array[$i][ "Zip" ] );
         
    $t->set_var( "place", $zip->place() );
}

$t->pparse( "output", "person_info" );

?>
