<?
/*!
    $Id: newmessage.php,v 1.5 2000/07/26 17:03:13 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:52:43 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php");
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezsession.php" );
    
$msg = new eZforumMessage;
$t = new Template(".");
$session = new eZSession();

$t->set_file( Array( "newmessage" => "$DOCROOT/templates/newmessage.tpl",
                     "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl" ) );

$t->set_var( "category_id", $category_id);
$t->set_var( "docroot", $DOCROOT);

if ( $session->validate($AuthenticatedSession) == 0)
{
    $UserId = $session->UserID();
}
else
{
    $UserId = 0;
}

$info = eZforumCategory::categoryForumInfo($forum_id);
$infoString = $info["CateogoryName"] . "::" . $info["ForumName"];
    
$t->set_var("info", $infoString );
$t->set_var("forum_id", $forum_id);
$t->set_var("user", eZUser::resolveUser( $UserId ) );

$t->set_var( "link1-url", "main.php" );
$t->set_var( "link1-caption", "Gå til topp" );
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output", "newmessage");
?>
