<?
/*!
    $Id: newmessage.php,v 1.8 2000/08/29 12:43:27 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:52:43 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "class.INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "ezphputils.php");
include_once( $DOC_ROOT . "/classes/ezforumcategory.php" );
include_once( $DOC_ROOT . "/classes/ezforummessage.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/eztemplate.php" );

$msg = new eZforumMessage;

$session = new eZSession();
$ini = new INIFile( "ezforum.ini" ); // get language settings
$Language = $ini->read_var( "MAIN", "Language" );

$t = new eZTemplate( "$DOC_ROOT/templates", "$DOC_ROOT/intl", $Language, "newmessage.php" );
$t->setAllStrings();

$t->set_file( Array( "newmessage" => "newmessage.tpl",
                     "navigation-bottom" => "navigation-bottom.tpl" ) );

$t->set_var( "category_id", $category_id);
$t->set_var( "docroot", $DOC_ROOT);

if ( $session->validate( $AuthenticatedSession ) == 0)
{
    $UserId = $session->UserID();
}
else
{
    $UserId = 0;
}

$category = new eZForumCategory();

$info = $category->categoryForumInfo( $forum_id );
$infoString = $info["CategoryName"] . "::" . $info["ForumName"];

$user = new eZUser();
$t->set_var("info", $infoString );
$t->set_var("forum_id", $forum_id);
$t->set_var("user", $user->resolveUser( $UserId ) );

$t->set_var( "link1-url", "main.php" );
$t->set_var( "link2-url", "search.php");

$t->set_var( "back-url", "forum.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
    
$t->pparse("output", "newmessage");
?>
