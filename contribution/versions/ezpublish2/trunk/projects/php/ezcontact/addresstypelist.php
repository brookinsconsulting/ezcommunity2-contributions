<?
include  "template.inc";
require "ezcontact_ce/dbsettings.php";
require "ezphputils.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";
require $DOCUMENTROOT . "classes/ezaddresstype.php";

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

    $menuTemplate->set_var( "document_root", $DOCUMENTROOT );
    $menuTemplate->pparse( "output", "address_type_page" );
}
?>
