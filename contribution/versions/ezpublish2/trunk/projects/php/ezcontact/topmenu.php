<?
include_once( "classes/eztemplate.php" );
//  include_once( "classes/ezsession.php" );
//  include_once( "classes/ezusergroup.php" );
//  include_once( "classes/ezuser.php" );
include_once( "common/ezphputils.php" );
include_once( "classes/INIFile.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
//      if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {
//          $usr = new eZUser();
//          $usr->get( $session->userID() );
        $t = new Template( "." );
        $DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );
        $t->set_file( "top_menu", "ezcontact/templates/topmenu.tpl" );
        $t->set_var( "document_root", $DOC_ROOT );
//          $t->set_var( "current_user", $usr->nickname() );
        $t->pparse( "output", "top_menu" );
    }
//      else
//      {
//          print( "\nDu har ikke rettigheter\n" );
//      }
}
else
{
    Header( "Location: index.php?page=common/error.php" );
}
?>
