<?
/*!
    $Id: newmessage.php,v 1.3 2000/07/25 10:13:37 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:52:43 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "ezphputils.php");
include( "template.inc" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezforummessage.php" );
include( "$DOCROOT/classes/ezuser.php" );
include( "$DOCROOT/classes/ezsession.php" );
    
$msg = new eZforumMessage;
$usr = new eZUser;
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

function categoryForumInfo($Id)
{
    openDB();
    
    $query_id = mysql_query("SELECT ForumTable.Name AS ForumName,CategoryTable.Name AS CategoryName FROM ForumTable, CategoryTable
 WHERE CategoryTable.Id = ForumTable.CategoryId AND ForumTable.Id = '$Id'")
         or die("categoryForumInfo()");
        
    $r = mysql_fetch_array($query_id);
      
    return ($r["CategoryName"] . "::" . $r["ForumName"]);
}
    
$t->set_var("info", categoryForumInfo($forum_id) );
$t->set_var("forum_id", $forum_id);
$t->set_var("user", $usr->resolveUser( $UserId ) );

$t->set_var( "link1-url", "Gå til topp");
$t->set_var( "link1-caption", "main.php");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output", "newmessage");
?>
