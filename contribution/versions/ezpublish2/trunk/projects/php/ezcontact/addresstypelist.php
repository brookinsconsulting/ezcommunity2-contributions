<?
include  "template.inc";
require "ezcontact/dbsettings.php";
require "ezphputils.php";
require $DOCUMENTROOT . "ezsession.php";
require $DOCUMENTROOT . "ezuser.php";
require $DOCUMENTROOT . "ezusergroup.php";
require $DOCUMENTROOT . "ezaddresstype.php";

// sjekke session
{
  include( $DOCUMENTROOT . "checksession.php" );
}

$menuTemplate = new Template( "." );
$menuTemplate->set_file( array(
                               "address_type_page" => $DOCUMENTROOT . "templates/addresstypelist.tpl",
                               "address_type_item" => $DOCUMENTROOT . "templates/addresstypeitem.tpl"
                               ) );

$address_type = new eZAddressType();
$address_type_array = $address_type->getAll();

for ( $i=0; $i<count( $address_type_array ); $i++ )
{
  if ( ( $i % 2 ) == 0 )
  {
    $menuTemplate->set_var( "bg_color", "#eeeeee" );
  }
  else
  {
    $menuTemplate->set_var( "bg_color", "#dddddd" );
  }  

  $menuTemplate->set_var( "address_type_id", $address_type_array[$i][ "ID" ] );
  $menuTemplate->set_var( "address_type_name", $address_type_array[$i][ "Name" ] );

  $menuTemplate->parse( "address_type_list", "address_type_item", true );
} 

$menuTemplate->pparse( "output", "address_type_page" );
?>
