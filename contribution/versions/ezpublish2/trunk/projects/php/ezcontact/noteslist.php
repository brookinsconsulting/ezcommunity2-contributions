<?

/*

*/

include  "template.inc";
require "ezcontact_ce/dbsettings.php";
require  "ezphputils.php";

require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/eznote.php";

include( $DOCUMENTROOT . "checksession.php" );

$t = new Template( "." );
$t->set_file( array( 
    "notes_list" => $DOCUMENTROOT . "templates/noteslist.tpl",
    "notes_item" => $DOCUMENTROOT . "templates/noteitem.tpl"
    ) );

$session = new eZSession();
if ( !$session->get( $AuthenticatedSession ) )
{
    die( "Du må logge deg på." );
}

$usr = new eZUser();
$usr->get( $session->userID() );

$t->set_var( "user", $usr->login() );

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
    
    $t->set_var( "document_root", $DOCUMENTROOT );
    $t->parse( "notes", "notes_item", true );
}

$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "notes_list" );

?>
