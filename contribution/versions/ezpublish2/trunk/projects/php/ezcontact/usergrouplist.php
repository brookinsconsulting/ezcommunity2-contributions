<?

include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";

require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/ezusergroup.php";

// sjekke session
{
  include(  $DOCUMENTROOT . "checksession.php" );
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
if ( $usrGroup->userGroupAdmin() == 'N' )
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
        "user_page" => $DOCUMENTROOT . "templates/usergrouplist.tpl",
        "user_group_item" => $DOCUMENTROOT . "templates/usergroupitem.tpl"
        ) );    

    $group = new eZUserGroup();
    $user_group_array = $group->getAll();

    for ( $i=0; $i<count( $user_group_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $menuTemplate->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $menuTemplate->set_var( "bg_color", "#dddddd" );
        }  
  
        $menuTemplate->set_var( "user_group_id", $user_group_array[$i][ "ID" ] );
        $menuTemplate->set_var( "user_group_name", $user_group_array[$i][ "Name" ] );
        $menuTemplate->set_var( "user_group_description", $user_group_array[$i][ "Description" ] );
        $menuTemplate->parse( "user_group_list", "user_group_item", true );
    } 

    $menuTemplate->set_var( "document_root", $DOCUMENTROOT );
    $menuTemplate->pparse( "output", "user_page" );
}
?>
