<?
include "template.inc";

require "ezcontact/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezaddresstype.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";

// sjekke login.......

// Legge til
if ( $Action == "insert" )
{
    $type = new eZAddressType();
    $type->setName( $AddressTypeName );
    $type->store();    

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "addresstypelist.php" );
}

// Oppdatere
if ( $Action == "update" )
{
  $type = new eZAddressType();
  $type->get( $AID );
  $type->setName( $AddressTypeName );
  $type->update();

  print( "hva?" );
  printRedirect( "../index.php?page=" . $DOCUMENTROOT . "addresstypelist.php" );
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


// hente ut rettigheter
{    
    $session = new eZSession();
    
    if ( !$session->get( $AuthenticatedSession ) )
    {
        die( "Du må logge deg på." );    
    }        
    
    $usr = new eZUser();
    $usr->get( $session->userID() );

    $usrGroup = new eZUserGroup();
    $usrGroup->get( $usr->group() );
}

// vise feilmelding dersom brukeren ikke har rettigheter.
if ( $usrGroup->addressTypeAdmin() == 'N' )
{    
    $t = new Template( "." );
    $t->set_file( array(
        "error_page" => $DOCUMENTROOT . "templates/errorpage.tpl"
        ) );

    $t->set_var( "error_message", "Du har ikke rettiheter til dette." );
    $t->pparse( "output", "error_page" );
}
else
{
    $t = new Template( "." );
    $t->set_file( array(
        "address_type_edit_page" =>  $DOCUMENTROOT . "templates/addresstypeedit.tpl"
        ) );    

    $t->set_var( "submit_text", "Legg til" );
    $t->set_var( "action_value", "insert" );
    $t->set_var( "address_type_id", "" );
    $t->set_var( "head_line", "Legg til ny addressetype" );

// Editere
    if ( $Action == "edit" )
    {
        $type = new eZAddressType();
        $type->get( $AID );
        $type->name( $AddressTypeName );
    
        $t->set_var( "submit_text", "Lagre endringer" );
        $t->set_var( "action_value", "update" );
        $t->set_var( "address_type_id", $AID  );  
        $t->set_var( "head_line", "Rediger addressetype");

        $AddressTypeName = $type->name();

//    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "addresstypelist.php" );
    }

// Sette template variabler
    $t->set_var( "document_root", $DOCUMENTROOT );
    $t->set_var( "address_type_name", $AddressTypeName );

    $t->pparse( "output", "address_type_edit_page" );
}
?>
