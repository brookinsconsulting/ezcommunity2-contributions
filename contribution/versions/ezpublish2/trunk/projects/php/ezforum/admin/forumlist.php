<?
/*!
    $Id: forumlist.php,v 1.2 2000/10/17 11:40:49 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
$DOC_ROOT . "/admin/" . "/intl", $Language, "forumlist.php" );
$t->setAllStrings();

$t->set_file(Array( "forum_page" => "forumlist.tpl"
                   ) );

$t->set_block( "forum_page", "forum_item_tpl", "forum_item" );

// Forum list for current category
$forum = new eZForum();

$forumList = $forum->getAllByCategory( $CategoryID );

if ( !$forumList )
{
    $ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/forumlist.php.ini", false );
    $noitem =  $ini->read_var( "strings", "noitem" );

    $t->set_var( "forum_item", $noitem );
}
else
{
    $i=0;
    foreach( $forumList as $forumItem )
        {
            if ( ( $i %2 ) == 0 )
                $t->set_var( "td_class", "bgdark" );
            else
                $t->set_var( "td_class", "bglight" );

            $t->set_var( "forum_id", $forumItem->id() );
            $t->set_var( "forum_name", $forumItem->name() );
            $t->set_var( "forum_description", $forumItem->description() );

            $t->parse( "forum_item", "forum_item_tpl", true);
            $i++;
        }
}

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $CategoryID );

$t->pparse( "output", "forum_page");
?>
