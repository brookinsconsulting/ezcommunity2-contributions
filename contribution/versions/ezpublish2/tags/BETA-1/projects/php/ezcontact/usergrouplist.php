<?

/*
  Viser bruker grupper.
*/
  

include_once( "class.INIFile.php" );

$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "../classes/eztemplate.php" );
include_once(  "ezphputils.php" );

include_once( "ezcontact/classes/ezuser.php" );
include_once( "ezcontact/classes/ezsession.php" );
include_once( "ezcontact/classes/ezusergroup.php" );

// Sjekke session.
{
  include(  $DOC_ROOT . "checksession.php" );
}

// Hente ut rettigheter.
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

// Vise feilmelding dersom brukeren ikke har rettigheter.
if ( $usrGroup->userGroupAdmin() == 'N' )
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
    // Setter template.
    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "usergrouplist.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "user_page" => "usergrouplist.tpl",
        "user_group_item" => "usergroupitem.tpl"
        ) );    

    // Viser liste over alle grupper.
    $group = new eZUserGroup();
    $user_group_array = $group->getAll();

    for ( $i=0; $i<count( $user_group_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#eeeeee" );
        }
        else
        {
            $t->set_var( "bg_color", "#dddddd" );
        }  
  
        $t->set_var( "user_group_id", $user_group_array[$i][ "ID" ] );
        $t->set_var( "user_group_name", $user_group_array[$i][ "Name" ] );
        $t->set_var( "user_group_description", $user_group_array[$i][ "Description" ] );
        $t->parse( "user_group_list", "user_group_item", true );
    } 

    $t->set_var( "document_root", $DOC_ROOT );
    $t->pparse( "output", "user_page" );
}
?>
