<?
/*
  Viser innholdet til en notat.
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

// Setter template
$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var ( "eZContactMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "noteinfo.php" );
$t->setAllStrings();

$t->set_file( array( 
    "note_info" => "noteinfo.tpl"
    ) );

$session = new eZSession();
if ( !$session->get( $AuthenticatedSession ) )
{
    die( "Du må logge deg på." );
}

$note = new eZNote();
$note->get( $NID );

$t->set_var( "note_title", $note->title() );
$t->set_var( "note_body", $note->body() );

$t->pparse( "output", "note_info" );
?>
