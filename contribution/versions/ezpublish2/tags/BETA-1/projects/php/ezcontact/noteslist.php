<?
/*
  Lister opp alle notatene av en bruker
*/

include_once( "class.INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezusergroup.php" );
include_once( "classes/ezuser.php" );
include_once( "ezphputils.php" );

include_once( "ezcontact/topmenu.php" );

include_once( "ezcontact/classes/eznote.php" );

// Sjekker rettigheter
$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 0 )
{
    if ( eZUserGroup::verifyCommand( $session->userID(), "eZContact_Read" ) == 1 )
    {
        $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "noteslist.php" );
        $t->setAllStrings();

        $t->set_file( array( 
            "notes_list" => "noteslist.tpl",
            "notes_item" => "noteitem.tpl"
            ) );

        $usr = new eZUser();
        $usr->get( $session->userID() );

        $t->set_var( "user", $usr->nickname() );

        $note = new eZNote();

        $note_array = $note->getAllByUser( $usr->id() );

        $t->set_var( "notes", "<h3>Ingen notater funnet!</h3>", true );
        for ( $i=0; $i<count( $note_array ); $i++ )    
        {
            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "bg_color", "#eeeeee" );
            }
            else
            {
                $t->set_var( "bg_color", "#dddddd" );
            }
    
            $t->set_var( "note_id", $note_array[ $i ][ "ID" ] );

            $t->set_var( "note_title", $note_array[ $i ][ "Title" ] );
    
            $t->set_var( "document_root", $DOC_ROOT );
            $t->parse( "notes", "notes_item", true );
        }

        $t->set_var( "document_root", $DOC_ROOT );

        $t->pparse( "output", "notes_list" );

    }
    else
    {
        print( "\nDu har ikke rettigheter\n" );
    }
}
else
{
    Header( "Location: index.php?page=common/error.php" );
}
?>
