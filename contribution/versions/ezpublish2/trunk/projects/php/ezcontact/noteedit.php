<?
/*
  Redigerer en notat for bruker
*/

include_once( "class.INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "../classes/eztemplate.php" );
include_once(  "ezphputils.php" ); 

include_once( "ezcontact/classes/ezsession.php" );
include_once( "ezcontact/classes/ezuser.php" );
include_once( "ezcontact/classes/eznote.php" );
include( "ezcontact/checksession.php" );


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
    Header( "Location: index.php?page=" . $DOC_ROOT . "notelist.php" ); 
}

// Oppdatere
if ( $Action == "update" )
{
    $note = new eZNote();
    $note->get( $NID );
    $note->setTitle( $Title );
    $note->setBody( $Body );
    $note->update( );

    Header( "Location: index.php?page=" . $DOC_ROOT . "noteslist.php" ); 
}

// Slette
if ( $Action == "delete" )
{
    $note = new eZNote();
    $note->get( $NID );
    $note->delete( );

    Header( "Location: index.php?page=" . $DOC_ROOT . "notelist.php" ); 
}

include( $DOC_ROOT . "checksession.php" );

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "noteedit.php" );
$t->setAllStrings();

$t->set_file( array( 
    "note_edit" => "noteedit.tpl"
    ) );

$t->set_var( "message", "Legg til nytt notat" );
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
$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "note_edit" );

?>
