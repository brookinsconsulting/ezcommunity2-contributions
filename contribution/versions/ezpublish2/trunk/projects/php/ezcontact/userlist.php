<?

include  "template.inc";
require "ezphputils.php";
require "ezcontact_ce/dbsettings.php";
require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";

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
if ( $usrGroup->userAdmin() == 'N' )
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
        "user_page" => $DOCUMENTROOT . "templates/userlist.tpl",
        "user_item" => $DOCUMENTROOT . "templates/useritem.tpl"
        ) );    

    $user = new eZUser();
    $user_array = $user->getAll();

    for ( $i=0; $i<count( $user_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $menuTemplate->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $menuTemplate->set_var( "bg_color", "#dddddd" );
        }  

        $menuTemplate->set_var( "user_id", $user_array[$i][ "ID" ] );
        $menuTemplate->set_var( "user_name", $user_array[$i][ "Login" ] );

        $group = new eZUserGroup( );
        $group->get( $user_array[$i][ "Grp" ] );
        $menuTemplate->set_var( "user_group", $group->name() );
  
        $menuTemplate->parse( "user_list", "user_item", true );
    } 

    $menuTemplate->set_var( "document_root", $DOCUMENTROOT );
    $menuTemplate->pparse( "output", "user_page" );
}
?>
