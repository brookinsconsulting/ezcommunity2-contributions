<?
/*!
    $Id: search.php,v 1.3 2000/08/08 09:58:22 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <26-Jul-2000 17:22:47 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/eztemplate.php" );
include_once( "$DOCROOT/classes/ezsession.php" );

$ini = new INIFile( "ezforum.ini" ); // get language settings
$Language = $ini->read_var( "MAIN", "Language" );


//preliminary setup
$usr = new eZUser;
$session = new eZSession;

$t = new eZTemplate( "$DOCROOT/templates", "$DOCROOT/intl", $Language, "main.php" );
$t->setAllStrings();

$t->set_file( Array("main" => "search.tpl",
                    "search" => "main-search.tpl",
                    "navigation" => "navigation.tpl"
                    ) );

$t->set_var( "docroot", $DOCROOT);

if ( $session->get( $AuthenticatedSession ) == 0 )
{
   $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
   $t->parse( "logout-message", "logout", true);
}
else
{
   $t->set_var( "user", "Anonym" );
   $t->set_var( "logout-message", "" );
}
$t->parse( "navigation-bar", "navigation", true);

$t->parse( "searchfield", "search", true );

$t->pparse("output","main");
?>
