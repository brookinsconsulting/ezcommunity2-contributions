<?
include  "template.inc";
require "ezphputils.php";
require "ezsession.php";
require "ezaddresstype.php";
require "ezuser.php";

// sjekke session
{
  include( "checksession.php" );
}

if ( $Action == "insert" )
{
  $type = new eZAddressType();
  $type->setName( $AddressTypeName );
  $type->store();    
}

$t = new Template( "." );
$t->set_file( array(
                    "address_type_edit_page" => "templates/addresstypeedit.tpl"
                    ) );    


$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "address_type_id", "" );

$t->set_var( "address_type_name", $AddressTypeName );


$t->pparse( "output", "address_type_edit_page" );

?>
