<?
include "template.inc";
require "ezphputils.php";

require "classes/ezperson.php";
require "classes/ezpersontype.php";
require "classes/ezsession.php";
require "classes/ezuser.php";
require "classes/ezcompany.php";

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

$t->pparse( "output", "person_info" );

?>
