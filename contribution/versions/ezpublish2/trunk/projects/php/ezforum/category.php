<?
/*!
    $Id: category.php,v 1.29 2000/10/11 10:09:06 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:05 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings

include_once( "common/ezphputils.php" );
include_once( "classes/template.inc" );
include_once( "ezforum/classes/ezforumforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$session = new eZSession;


$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "category.php" );
$t->setAllStrings();

$t->set_file( "category_tpl", "category.tpl" );

$t->set_block( "category_tpl", "forum_tpl", "forum" );

$t->set_var( "category_id", $category_id );

$category = new eZForumCategory( );
$category->get( $category_id );
$forumPath = "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "category.php&category_id=" . $category_id . "\">" . $category->name() . "</a>";
$t->set_var( "forum_path", $forumPath );

$forum = new eZForumForum();
$forums = $forum->getAllForums( $category_id );

for ($i = 0; $i < count( $forums ); $i++)
{
    arrayTemplate( $t, $forums[$i], Array( Array( "Id", "forum_id" ),
                                           Array( "Name", "name" ),
                                           Array( "Description", "description" ),
                                           Array( "Id", "messages" )
                                                  )
                   );

    $message = new eZForumMessage();
    $t->set_var( "messages", $message->countMessages( $t->get_var( "forum_id" ) ) );
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight"  );
    else
        $t->set_var( "td_class", "bgdark"  );

    $t->parse( "forum", "forum_tpl", true );
}

//  if ( count( $forums ) == 0 )
//      $t->set_var( "forums", "noforums", true);

$t->set_var( "link1-url", "main.php");
$t->set_var( "link2-url", "search.php");

$t->set_var( "back-url", "main.php");
//  $t->parse( "navigation-bar-bottom", "navigation-bottom", true);

$t->pparse( "output", "category_tpl" );
?>
