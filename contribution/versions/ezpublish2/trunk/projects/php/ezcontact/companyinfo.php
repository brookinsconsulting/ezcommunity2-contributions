<?
include  "template.inc";
require "ezphputils.php";
require "ezperson.php";
require "ezpersontype.php";
require "ezsession.php";
require "ezuser.php";
require "ezcompany.php";

$t = new Template( ".");
$t->set_file( "company_info", "templates/companyinfo.tpl");

$company = new eZCompany();
$company->get( $CID );

$company_type = new eZCompanyType();
$company_type->get( $company->contactType());
$t->set_var( "company_name", $company->name());
$t->set_var( "comment", $company->comment());
$t->set_var( "contact_name", $company->contactType());

$t->pparse( "output", "company_info" );
?>
