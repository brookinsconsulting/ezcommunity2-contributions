<?
/*
  Denne filen sjekker om brukeren er logget på..

*/

require "ezcontact/dbsettings.php";

$t = new Template( "." );
$t->set_file( "top_menu", $DOCUMENTROOT .  "templates/topmenu.tpl" );

$t->set_var( "document_root", $DOCUMENTROOT );


$session = new eZSession();

if ( !$session->get( $AuthenticatedSession ) )
{
    die( "Du må logge deg på." );    
}        


$usr = new eZUser();
$usr->get( $session->userID() );
$t->set_var( "current_user", $usr->login() );
$t->pparse( "output", "top_menu" );

?>
