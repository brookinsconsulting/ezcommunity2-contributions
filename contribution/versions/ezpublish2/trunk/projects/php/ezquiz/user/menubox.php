<?
// 
// $Id: menubox.php,v 1.1 2001/05/30 12:56:46 pkej Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <17-Oct-2000 12:16:07 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "classes/ezcachefile.php" );

$Language = $ini->read_var( "eZQuizMain", "Language" );
$PageCaching = $ini->read_var( "eZQuizMain", "PageCaching" );

createQuizMenu();

function createQuizMenu()
{
    global $ini;
    global $Language;
    global $quizMenuCachedFile;
    global $GenerateStaticPage;
	global $GlobalSiteDesign;
    

        
    include_once( "classes/eztemplate.php" );
    include_once( "ezquiz/classes/ezquizgame.php" );

    $t = new eZTemplate( "ezquiz/user/" . $ini->read_var( "eZQuizMain", "TemplateDir" ),
                         "ezquiz/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->set_block( "menu_box_tpl", "current_game_item_tpl", "current_game_item" );
    $t->set_block( "menu_box_tpl", "next_game_item_tpl", "next_game_item" );
    $t->set_block( "menu_box_tpl", "quiz_menu_item_tpl", "quiz_menu_item" );
    $t->set_block( "menu_box_tpl", "my_quiz_item_tpl", "my_quiz_item" );

    $t->set_var( "current_game_item", "" );
    $t->set_var( "next_game_item", "" );
    $t->set_var( "quiz_menu_item_tpl", "" );
    $t->set_var( "my_quiz_item_tpl", "" );
    
    $t->set_var( "sitedesign", $GlobalSiteDesign );

    if( eZUser::currentUser() != false )
    {
        $t->parse( "my_quiz_item", "my_quiz_item_tpl" );
    }
    
    if( true )
    {
        $t->parse( "my_quiz_item", "quiz_menu_item_tpl" );
    }

    $game = new eZQuizGame();
    $games = $game->openGames( $Offset, $Limit );
    $count = count( $games );
    
    if( $count >= 1 )
    {
        $t->set_var( "game_id", $game->id() );
        $t->set_var( "game_name", $game->name() );
        $t->parse( "current_game_item", "current_game_item_tpl" );
    }
    else
    {
        $games = $game->opensNext( $Offset, $Limit );
        $count = count( $games );
        
        if( $count >= 1 )
        {
            $t->set_var( "game_id", $game->id() );
            $t->set_var( "game_name", $game->name() );
            
            $start = $game->startDate();
            if( $start->day() != 0  )
            {
                $t->set_var( "game_start", $locale->format( $start, true ) );
            }
            $t->parse( "next_game_item", "next_game_item_tpl" );
        }
    }

    if ( get_class( $menuCacheFile ) == "ezcachefile" )
    {
        $output = $t->parse( $target, "menu_box_tpl" );
        $menuCacheFile->store( $output );
        print( $output );
    }
    else
    {
		$t->pparse( "output", "menu_box_tpl" );
    }
    
}

function createMyQuizMenu()
{
}

?>
