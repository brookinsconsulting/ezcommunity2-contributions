<?

include  "template.inc";
require "ezcontact/dbsettings.php";
require  "ezphputils.php";

require "classes/ezsession.php";
require "classes/ezuser.php";
require "classes/eznote.php";

$t = new Template( "." );
$t->set_file( array( 
    "note_info" => "templates/noteinfo.tpl"
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
