<?
include  "template.inc";
require "ezcontact_ce/dbsettings.php";
require "ezphputils.php";
require  $DOCUMENTROOT . "classes/ezsession.php";
require  $DOCUMENTROOT . "classes/ezuser.php";
require  $DOCUMENTROOT . "classes/ezusergroup.php";
require  $DOCUMENTROOT . "classes/ezphonetype.php";

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
if ( $usrGroup->phoneTypeAdmin() == 'N' )
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
        "phone_type_page" =>  $DOCUMENTROOT . "templates/phonetypelist.tpl",
        "phone_type_item" =>  $DOCUMENTROOT . "templates/phonetypeitem.tpl"
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

        $t->set_var( "document_root", $DOCUMENTROOT );
        $t->set_var( "phone_type_id", $phone_type_array[$i][ "ID" ] );
        $t->set_var( "phone_type_name", $phone_type_array[$i][ "Name" ] );

        $t->parse( "phone_type_list", "phone_type_item", true );
    } 

    $t->pparse( "output", "phone_type_page" );
}
?>
