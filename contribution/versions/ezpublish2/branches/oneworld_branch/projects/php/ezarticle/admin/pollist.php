<?
// 
// $Id: pollist.php,v 1.1.2.1 2002/06/03 07:27:13 pkej Exp $
//
// Created on: <15-Jun-2001 15:02:54 pkej>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezpoll/classes/ezpoll.php" );
include_once( "ezarticle/classes/ezarticlepoll.php" );

$ActionValue = "list";
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZPollMain", "Language" );

$article = new eZArticle( $ArticleID );
$selectedPoll =& eZArticlePoll::articleHasPoll( $article );

if( isset( $OK ) )
{
    if( $selectedPollID > 0 )
    {
        $poll =& new eZPoll( $selectedPollID );
        $article->deletePolls();
        $article->addPoll( $poll );
        $article->store();
        $selectedPoll =& $poll;
    }
    
    eZHTTPTool::header( "Location: /article/articleedit/edit/$ArticleID/" );
    exit();
}

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "pollist.php" );
$t->setAllStrings();

$t->set_file( array(
    "poll_list_page_tpl" => "pollist.tpl"
    ) );

$t->set_block( "poll_list_page_tpl", "no_polls_item_tpl", "no_polls_item" );
$t->set_block( "poll_list_page_tpl", "poll_list_tpl", "poll_list" );
$t->set_block( "poll_list_tpl", "poll_item_tpl", "poll_item" );

$t->set_var( "poll_item", "" );
$t->set_var( "poll_list", "" );
$t->set_var( "no_polls_item", "" );

$totalCount =& eZPoll::count();
$polls =& eZPoll::getAll();

if( count( $polls ) == 0 )
{
    $t->parse( "no_polls_item", "no_polls_item_tpl" );
}
else
{
    $i = 0;
    foreach( $polls as $poll )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        if( $selectedPoll->id() == $poll->id() )
        {
            $t->set_var( "selected", "selected" );
        }
        else
        {
            $t->set_var( "selected", "" );
        }
        
        $t->set_var( "poll_id", $poll->id() );
        $t->set_var( "poll_name", $poll->name() );
        $t->parse( "poll_item", "poll_item_tpl", true );
        
        $i++;
    }
    
    
    $t->parse( "poll_list", "poll_list_tpl" );
}

$t->set_var( "article_id", $ArticleID );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "site_style", $SiteStyle );
$t->pparse( "output", "poll_list_page_tpl" );

?>
