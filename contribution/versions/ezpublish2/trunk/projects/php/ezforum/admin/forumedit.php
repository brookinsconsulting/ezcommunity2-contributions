<?
/*!
    $Id: forumedit.php,v 1.3 2000/10/20 13:31:31 ce-cvs Exp $

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

if ( $Action == "insert" )
{
    $forum = new eZForum();
    $forum->setCategoryId( $CategorySelectID );
    $forum->setName( $name );
    $forum->setDescription( $description );
    
    $forum->store();
    Header( "Location: /forum/forumlist/$CategorySelectID" );
}

if ( $Action == "update" )
{
    $forum = new eZForum();
    $forum->get( $ForumID );
    $forum->setCategoryId( $CategorySelectID );
    $forum->setName( $name );
    $forum->setDescription( $description );

    $forum->store();
    Header( "Location: /forum/forumlist/$CategorySelectID" );
}

if ( $Action == "delete" )
{
    $forum = new eZForum();
    $forum->get( $ForumID );
    $forum->delete();

    $CategoryID = $forum->categoryID();
    Header( "Location: /forum/forumlist/$CategoryID" );
}

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
"ezforum//admin/" . "/intl", $Language, "forumedit.php" );
$t->setAllStrings();

$t->set_file( array( "forum_page" => "forumedit.tpl"
                   ) );

$t->set_block( "forum_page", "category_item_tpl", "category_item" );

$t->set_var( "action_value", "insert" );

$ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/forumedit.php.ini", false );
$headline =  $ini->read_var( "strings", "head_line_insert" );
$t->set_var( "forum_name", "" );
$t->set_var( "forum_description", "" );

$forum = new eZForum( $ForumID );
$CategoryID = $forum->categoryID();

$category = new eZForumCategory();
$categoryList = $category->getAll();
foreach( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );
    if ( $categoryItem->id() == $CategoryID )
        $t->set_var( "is_selected", "selected" );
    else
        $t->set_var( "is_selected", "" );


    $t->parse( "category_item", "category_item_tpl", true );
}

if ( $Action == "edit" )
{
    $forum = new eZForum();
    $forum->get( $ForumID );

    $t->set_var( "forum_name", $forum->name() );
    $t->set_var( "forum_description", $forum->description() );
    $t->set_var( "forum_id", $ForumID);
    $t->set_var( "action_value", "update" );

    $ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/forumedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_edit" );
}
$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $CategoryID );

$t->set_var( "headline", $headline );

$t->pparse( "output", "forum_page");
?>
