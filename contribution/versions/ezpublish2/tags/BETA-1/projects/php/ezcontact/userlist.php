<?
/*
  Viser liste over brukere.
*/

include_once( "class.INIFile.php" );

$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "../classes/eztemplate.php" );
include_once(  "ezphputils.php" ); 

include_once( "ezcontact/classes/ezsession.php" );
include_once( "ezcontact/classes/ezuser.php" );
include_once( "ezcontact/classes/ezusergroup.php" ); 

// sjekke session
{
    include( $DOC_ROOT . "checksession.php" );
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
        "error_page" => $DOC_ROOT . "templates/errorpage.tpl"
        ) );

    $t->set_var( "error_message", "Du har ikke rettiheter til dette." );
    $t->pparse( "output", "error_page" );
}
else
{
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "userlist.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "user_page" => "userlist.tpl",
        "user_item" => "useritem.tpl"
        ) );    

    $user = new eZUser();
    $user_array = $user->getAll();

    for ( $i=0; $i<count( $user_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }  

        $t->set_var( "user_id", $user_array[$i][ "ID" ] );
        $t->set_var( "user_name", $user_array[$i][ "Login" ] );

        $group = new eZUserGroup( );
        $group->get( $user_array[$i][ "Grp" ] );
        $t->set_var( "user_group", $group->name() );
  
        $t->parse( "user_list", "user_item", true );
    } 

    $t->set_var( "document_root", $DOC_ROOT );
    $t->pparse( "output", "user_page" );
}
?>
