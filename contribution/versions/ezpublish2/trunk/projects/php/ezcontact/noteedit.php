<?

include  "template.inc";
require "ezcontact/dbsettings.php";
require  "ezphputils.php";

require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";
require $DOCUMENTROOT . "classes/eznote.php";

$session = new eZSession();
if ( !$session->get( $AuthenticatedSession ) )
{
    die( "Du må logge deg på." );    
}        


if ( $Action == "insert" )
{
    $usr = new eZUser();
    $usr->get( $session->userID() );
    
    $note = new eZNote();
    $note->setTitle( $Title );
    $note->setBody( $Body );
    $note->setUserID( $usr->id() );

    $note->store();
        
    print( "inserting" ); 

}

include( $DOCUMENTROOT . "checksession.php" );

$t = new Template( "." );
$t->set_file( array( 
    "note_edit" => $DOCUMENTROOT . "templates/noteedit.tpl"    ) );


$t->set_var( "message", "Legg til ny notat" );
$t->set_var( "action", "insert" );
$t->set_var( "submit_text", "legg til" );

$t->set_var( "title", "" );
$t->set_var( "body", "" );



$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "note_edit" );


?>
