<?
// 
// $Id: forumlist.php,v 1.5 2000/12/19 13:52:04 ce Exp $
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,4
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforum.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$Language = $ini->read_var( "eZForumMain", "Language" );

$t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir"),
                     "ezforum/user/intl", $Language, "forumlist.php" );

$t->setAllStrings();

$t->set_file( "forumlist", "forumlist.tpl" );

$t->set_block( "forumlist", "forum_item_tpl", "forum_item" );

$category = new eZForumCategory( $CategoryID );

$t->set_var( "category_id", $category->id( ) );
$t->set_var( "category_name", $category->name( ) );

$forumList = $category->forums( );

if ( !$forumList )
{
    $languageIni = new INIFile( "ezforum/user/intl/" . $Language . "/categorylist.php.ini", false );
    $noitem =  $languageIni->read_var( "strings", "noitem" );

    $t->set_var( "forum_item", $noitem );
}

$i=0;
foreach( $forumList as $forum )
{
    $t->set_var( "forum_id", $forum->id() );

    $t->set_var( "name", $forum->name() );    
    $t->set_var( "description", $forum->description() );

    $t->set_var( "threads", $forum->threadCount() );    
    $t->set_var( "messages", $forum->messageCount() );    
    

    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight"  );
    else
        $t->set_var( "td_class", "bgdark"  );

    $t->parse( "forum_item", "forum_item_tpl", true );

    $i++;
}

$t->set_var( "category_id", $CategoryID );

$t->pparse( "output", "forumlist" );

?>
