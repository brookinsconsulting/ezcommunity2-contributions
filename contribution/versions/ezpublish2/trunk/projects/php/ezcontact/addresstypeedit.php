<?
include "template.inc";
require "ezcontact/dbsettings.php";
require "ezphputils.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezaddresstype.php";
require $DOCUMENTROOT . "classes/ezuser.php";

// sjekke session
{
    include( $DOCUMENTROOT . "checksession.php" );
} 

$t = new Template( "." );
$t->set_file( array(
                    "address_type_edit_page" =>  $DOCUMENTROOT . "templates/addresstypeedit.tpl"
                    ) );    


$t->set_var( "submit_text", "Legg til" );
$t->set_var( "action_value", "insert" );
$t->set_var( "address_type_id", "" );
$t->set_var( "head_line", "Endre addresse type" );

// Legge til
if ( $Action == "insert" )
{
    $type = new eZAddressType();
    $type->setName( $AddressTypeName );
    $type->store();    
}

// Editere
if ( $Action == "edit" )
{
    $type = new eZAddressType();
    $type->get( $AID );
    $type->name( $AddressTypeName );
    
    $t->set_var( "submit_text", "Lagre endringer" );
    $t->set_var( "action_value", "update" );
    $AddressTypeName = $type->name();
    $t->set_var( "address_type_id", $AID  );  
    $t->set_var( "head_line", "Rediger addresse type");
}

// Oppdatere
if ( $Action == "update" )
{
  $type = new eZAddressType();
  $type->get( $AID );
  $type->setName( $AddressTypeName );
  $type->update(); 
}

$t->set_var( "document_root", $DOCUMENTROOT );
$t->set_var( "address_type_name", $AddressTypeName );

$t->pparse( "output", "address_type_edit_page" );

?>
