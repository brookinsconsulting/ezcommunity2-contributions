<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezperson.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";
require $DOCUMENTROOT . "classes/ezpersontype.php";

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
if ( $usrGroup->personTypeAdmin() == 'N' )
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
        "persontype_page" => $DOCUMENTROOT . "templates/persontypelist.tpl",
        "persontype_item" => $DOCUMENTROOT . "templates/persontypeitem.tpl"
        ) );    

    $persontype = new eZPersonType();
    $persontype_array = $persontype->getAll();

    for ( $i=0; $i<count( $persontype_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }

        $t->set_var( "persontype_id", $persontype_array[$i][ "ID" ] );
        $t->set_var( "persontype_name", $persontype_array[$i][ "Name" ] );
        $t->set_var( "description", $persontype_array[$i][ "Description" ] );
        $t->parse( "persontype_list", "persontype_item", true );
    }

    $t->set_var( "document_root", $DOCUMENTROOT );
    $t->pparse( "output", "persontype_page" );
}
?>
