<?
include  "template.inc";
require "ezphputils.php";
require "ezsession.php";
require "ezuser.php";
require "ezphonetype.php";

// sjekke session
{
  include( "checksession.php" );
}

if ( $Action == "insert" )
{
  $type = new eZPhoneType();
  $type->setName( $PhoneTypeName );
  $type->store();    
}

$t = new Template( "." );
$t->set_file( array(
                    "phone_type_edit_page" => "templates/phonetypeedit.tpl"
                    ) );    


$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "phone_type_id", "" );

$t->set_var( "phone_type_name", $PhoneTypeName );


$t->pparse( "output", "phone_type_edit_page" );
?>
