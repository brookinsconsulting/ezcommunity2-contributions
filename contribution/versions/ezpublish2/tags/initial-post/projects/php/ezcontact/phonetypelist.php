<?
include  "template.inc";
require "ezphputils.php";
require "ezsession.php";
require "ezuser.php";
require "ezusergroup.php";
require "ezphonetype.php";

// sjekke session
{
  include( "checksession.php" );
}

$t = new Template( "." );
$t->set_file( array(
                    "phone_type_page" => "templates/phonetypelist.tpl",
                    "phone_type_item" => "templates/phonetypeitem.tpl"
                    ) );

$phone_type = new eZPhoneType();
$phone_type_array = $phone_type->getAll();

for ( $i=0; $i<count( $phone_type_array ); $i++ )
{
  if ( ( $i % 2 ) == 0 )
  {
    $t->set_var( "bg_color", "#eeeeee" );
  }
  else
  {
    $t->set_var( "bg_color", "#dddddd" );
  }  

  $t->set_var( "phone_type_id", $phone_type_array[$i][ "ID" ] );
  $t->set_var( "phone_type_name", $phone_type_array[$i][ "Name" ] );

  $t->parse( "phone_type_list", "phone_type_item", true );
} 

$t->pparse( "output", "phone_type_page" );

?>
