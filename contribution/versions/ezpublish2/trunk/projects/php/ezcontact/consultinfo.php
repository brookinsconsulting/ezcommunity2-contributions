<?

include "template.inc";
require "ezcontact_ce/dbsettings.php";
require  "ezphputils.php";

require "classes/ezsession.php";
require "classes/ezuser.php";
require "classes/ezconsult.php";

$t = new Template( "." );
$t->set_file( array( 
    "consult_info" => "templates/consultinfo.tpl"
    ) );

$session = new eZSession();
if ( !$session->get( $AuthenticatedSession ) )
{
    die( "Du må logge deg på." );
}

$consult = new eZConsult();
$consult->get( $CID );
$t->set_var( "created", $consult->created() );
$t->set_var( "modified", $consult->modified() );

$user = new eZUser();
$user->get( $consult->UserID() );
$t->set_var( "user", $user->login() );



$t->set_var( "consult_title", $consult->title() );
$t->set_var( "consult_body", nl2br( $consult->body() ) );

$t->pparse( "output", "consult_info" );

?>
