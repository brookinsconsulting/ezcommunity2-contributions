<?
include  "template.inc";
require "ezphputils.php";

require "classes/ezperson.php";
require "classes/ezpersontype.php";
require "classes/ezsession.php";
require "classes/ezuser.php";
require "classes/ezcompany.php";
require "classes/ezcompanytype.php";

$t = new Template( ".");
$t->set_file( "company_info",  "templates/companyinfo.tpl" );

$company = new eZCompany();
$company->get( $CID );


$company_type = new eZCompanyType();
$company_type->get( $company->contactType() );
$t->set_var( "company_name", $company->name() );
$t->set_var( "comment", $company->comment() );
$t->set_var( "contact_name", $company->contactType() );

$usr = new eZUser();
$usr->get( $company->owner() );
$t->set_var( "owner", $usr->login() );


$t->pparse( "output", "company_info" );
?>
