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

// Legg til
if ( $Action == "insert" )
{
    $usr = new eZUser();
    $usr->get( $session->userID() );
    
    $note = new eZNote();
    $note->setTitle( $Title );
    $note->setBody( $Body );
    $note->setUserID( $usr->id() );

    $note->store();
    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "noteslist.php " );
}

// Oppdatere
if ( $Action == "update" )
{
    $note = new eZNote();
    print( "-->".$NID );
    $note->get( $NID );
    $note->setTitle( $Title );
    $note->setBody( $Body );
    $note->update( );

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "noteslist.php " );
}


// Slette
if ( $Action == "delete" )
{
    $note = new eZNote();
    $note->get( $NID );
    $note->delete( );

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "noteslist.php " );
}


include( $DOCUMENTROOT . "checksession.php" );

$t = new Template( "." );
$t->set_file( array( 
    "note_edit" => $DOCUMENTROOT . "templates/noteedit.tpl"    ) );


$t->set_var( "message", "Legg til ny notat" );
//$t->set_var( "action", "insert" );
$action = "insert";
$t->set_var( "submit_text", "legg til" );

$t->set_var( "title", "" );
$t->set_var( "body", "" );

if ( $Action == "edit" )
{
    $note = new eZNote();
    $note->get( $NID );

    $t->set_var( "note_id", $NID );
    $t->set_var( "title", $note->title() );
    $t->set_var( "body", $note->body() );

    $action = "update";
    $t->set_var( "submit_text", "Lagre endringer" );
    $t->set_var( "message", "Rediger notat" );
}

$t->set_var( "action_value", $action );
$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "note_edit" );


?>
