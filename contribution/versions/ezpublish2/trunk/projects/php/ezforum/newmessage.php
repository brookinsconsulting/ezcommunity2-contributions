<?
/*!
    $Id: newmessage.php,v 1.16 2000/10/17 13:44:44 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:52:43 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php");
include_once( "$DOCROOT/classes/ezforumcategory.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezsession.php" );
include_once( "$DOCROOT/classes/eztemplate.php" );

$msg = new eZforumMessage;

$session = new eZSession();
$ini = new INIFile( "ezforum.ini" ); // get language settings
$Language = $ini->read_var( "MAIN", "Language" );

$t = new eZTemplate( "$DOCROOT/templates", "$DOCROOT/intl", $Language, "newmessage.php" );
$t->setAllStrings();

$t->set_file( Array( "newmessage" => "newmessage.tpl",
                     "navigation-bottom" => "navigation-bottom.tpl" ) );

$t->set_var( "category_id", $category_id);
$t->set_var( "docroot", $DOCROOT);

if ( $session->validate( $AuthenticatedSession ) == 0)
{
    $UserId = $session->UserID();
}
else
{
    $UserId = 0;
}

$info = eZforumCategory::categoryForumInfo( $forum_id);
$infoString = $info["CategoryName"] . "::" . $info["ForumName"];
    
$t->set_var("info", $infoString );
$t->set_var("forum_id", $forum_id);
$t->set_var("user", eZUser::resolveUser( $UserId ) );

$t->set_var( "link1-url", "main.php" );
$t->set_var( "link2-url", "search.php");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output", "newmessage");
?>
