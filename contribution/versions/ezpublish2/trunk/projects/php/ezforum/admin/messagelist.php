<?
/*!
  $Id: messagelist.php,v 1.5 2000/10/20 13:31:31 ce-cvs Exp $

  Author: Lars Wilhelmsen <lw@ez.no>
    
  Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
  Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/admin/" . "/intl", $Language, "messagelist.php" );
$t->setAllStrings();

$t->set_file( Array( "message_page" => "messagelist.tpl" ) );

$t->set_block( "message_page", "message_item_tpl", "message_item" );

$forum = new eZForum( $ForumID );
$t->set_var( "forum_name", $forum->name() );
$category = new eZForumCategory( $forum->categoryID()  );
$CategoryID = $forum->categoryID();
$t->set_var( "category_name", $category->name() );

$locale = new eZLocale( $Language );

if ( !isset( $Offset ) )
    $Offset = 0;

if ( !isset( $Limit ) )
    $Limit = 30;

$messages = $forum->messageTree( $Offset, $Limit );

$ini = new INIFile( $DOC_ROOT . "/admin/" . "intl/" . $Language . "/messagelist.php.ini", false );
$true =  $ini->read_var( "strings", "true" );
$false =  $ini->read_var( "strings", "false" );

if ( !$messages )
{
    $noitem = $ini->read_var( "strings", "noitem" );
    $t->set_var( "message_item", $noitem );
}
else
{

    $level = 0;
    $i = 0;
    foreach ( $messages as $message )
        {
            if ( ( $i % 2 ) == 0 )
                $t->set_var( "td_class", "bglight" );
            else
                $t->set_var( "td_class", "bgdark" );
    
            $level = $message->depth();
    
            if ( $level > 0 )
                $t->set_var( "spacer", str_repeat( "&nbsp;", $level ) );
            else
                $t->set_var( "spacer", "" );
    
            $t->set_var( "message_topic", $message->topic() );

            $t->set_var( "message_postingtime", $locale->format( $message->postingTime() ) );

            $t->set_var( "message_id", $message->id() );

            $user = $message->user();
    
            $t->set_var( "message_user", $user->firstName() . " " . $user->lastName() );

            if( $message->emailNotice() == "Y" )
                $t->set_var( "emailnotice", $true );
            else
                $t->set_var( "emailnotice", $false );


            $t->set_var( "limit", $Limit );
            $t->set_var( "prev_offset", $Offset - $Limit );
            $t->set_var( "next_offset", $Offset + $Limit );    
    
            $t->parse( "message_item", "message_item_tpl", true );
            $i++;
        }
} 

$t->set_var( "link1-url", "");
$t->set_var( "link2-url", "search.php");

$t->set_var( "back-url", "admin/forum.php" );
$t->set_var( "docroot", $DOC_ROOT );
$t->set_var( "category_id", $CategoryID );
$t->set_var( "forum_id", $ForumID );

$t->pparse( "output", "message_page" );
?>
