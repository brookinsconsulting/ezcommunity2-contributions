<?
/*!
    $Id: category.php,v 1.31 2000/10/11 12:33:56 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:05 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" ); // get language settings

include_once( "common/ezphputils.php" );

//  include_once( "classes/template.inc" );

include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforumforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/templates", "ezforum/intl", $Language, "category.php" );

$t->setAllStrings();

$t->set_file( "category_tpl", "category.tpl" );

$t->set_block( "category_tpl", "forum_tpl", "forum" );

$t->set_var( "category_id", $category_id );

$category = new eZForumCategory( $category_id );

print( $category->name( ) );

$forumPath = "<img src=\"ezforum/images/pil.gif\" width=\"10\" height=\"10\" border=\"0\"> <a href=\"index.php?page=" . $DOC_ROOT .  "category.php&category_id=" . $category_id . "\">" . $category->name() . "</a>";

$t->set_var( "forum_path", $forumPath );

$forums = $category->forums( );

$i=0;
foreach( $forums as $forum )
{
    $t->set_var( "name", $forum->name() );    
    $t->set_var( "description", $forum->description() );    

    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight"  );
    else
        $t->set_var( "td_class", "bgdark"  );

    $t->parse( "forum", "forum_tpl", true );

    $i++;
}


$t->pparse( "output", "category_tpl" );

?>
