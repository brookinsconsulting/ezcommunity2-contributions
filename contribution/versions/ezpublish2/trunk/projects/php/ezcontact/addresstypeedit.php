<?
include "template.inc";
require "ezcontact/dbsettings.php";
require "ezphputils.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezaddresstype.php";
require $DOCUMENTROOT . "classes/ezuser.php";


// sjekke login.......

// Legge til
if ( $Action == "insert" )
{
    $type = new eZAddressType();
    $type->setName( $AddressTypeName );
    $type->store();    
}

// Oppdatere
if ( $Action == "update" )
{
  $type = new eZAddressType();
  $type->get( $AID );
  $type->setName( $AddressTypeName );
  $type->update();
}

// Slette
if ( $Action == "delete" )
{
    $type = new eZAddressType();
    $type->get( $AID );
    $type->delete( );
    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "addresstypelist.php " );
}

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



$t->set_var( "document_root", $DOCUMENTROOT );
$t->set_var( "address_type_name", $AddressTypeName );

$t->pparse( "output", "address_type_edit_page" );

?>
