<?
/*
  Denne filen sjekker om brukeren er logget på..

*/
require "ezcontact/dbsettings.php";

  $menuTemplate = new Template( "." );
  $menuTemplate->set_file( "top_menu", $DOCUMENTROOT .  "templates/topmenu.tpl" );


  $session = new eZSession();
  if ( !$session->get( $AuthenticatedSession ) )
  {
    die( "Du må logge deg på." );    
  }

  $usr = new eZUser();
  $usr->get( $session->userID() );
  $menuTemplate->set_var( "current_user", $usr->login() );
  $menuTemplate->pparse( "output", "top_menu" );

?>
