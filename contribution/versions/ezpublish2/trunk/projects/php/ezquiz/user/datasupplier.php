<?
// 
// $Id: datasupplier.php,v 1.2 2001/05/30 10:39:40 pkej Exp $
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <28-May-2001 11:24:41 pkej>
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
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$PageCaching = $ini->read_var( "eZQuizMain", "PageCaching" );

switch ( $url_array[2] )
{
    case "game":
    {
        $Action = $url_array[3];

        switch ( $Action )
        {
            case "list":
            {
                $Offset = $url_array[4];
                
                if  ( !is_numeric( $Offset ) )
                {
                    $Offset = 0;
                }
                
                if ( $PageCaching == "enabled" )
                {
                    include_once( "classes/ezcachefile.php" );
                    $file = new eZCacheFile( "ezquiz/cache/", array( "quiz" . $Action, $Offset ),
                                             "cache", "," );
                    $cachedFile = $file->filename( true );

                    if ( $file->exists() )
                    {
                        include( $cachedFile );
                    }
                    else
                    {
                        $GenerateStaticPage = "true";
                        include( "ezquiz/user/quizlist.php" );
                    }

                }
            }
            break;
            
            case "open":
            {
            }
            break;
            
            case "scores":
            {
            }
            break;
            
            case "closed":
            {
            }
            break;
            
            case "view":
            case "play":
            {
                $GameID = $url_array[4];
                
                $user =&  eZUser::currentUser();
                
                if( get_class( $user ) != "ezuser" )
                {
                    eZHTTPTool::header( "Location: /user/login?RedirectURL=" . urlencode( "/quiz/game/play/$GameID" ) );
                }
                else
                {
                    include_once( "classes/ezlocale.php" );
                    include_once( "classes/ezdate.php" );
                    include_once( "ezquiz/classes/ezquizgame.php" );
                    
                    $game = new eZQuizGame( $GameID );
                    $gameStop = $game->stopDate();
                    $gameStart = $game->startDate();
                    $today = new eZDate();

                    $locale = new eZLocale( $Language );

                    if( $gameStart->isGreater( $today, true ) )
                    {
                        if( $today->isGreater( $gameStop, true ) )
                        {
                            $QuestionNum = $url_array[5];
                            if  ( !is_numeric( $QuestionNum ) )
                            {
                                $QuestionNum = 0;
                            }
                            
                            include( "ezquiz/user/quizplay.php" );
                        }
                        else
                        {
                            include( "ezquiz/user/quizclosed.php" );
                        }
                    }
                    else
                    {
                        include( "ezquiz/user/quizunopened.php" );
                    }
                    

                }
            }
            break;
            
            case "instructions":
            {
            }
            break;
        }
        break;
    }
    
    case "my":
    {
        $Action = $url_array[3];
        
        $user =&  eZUser::currentUser();
        
        if( get_class( $user ) != "ezuser" )
        {
        }

        
        switch ( $Action )
        {
            case "unfinished":
            {
            }
            break;
            
            case "open":
            {
            }
            break;
            
            case "closed":
            {
            }
            break;
            
            case "scores":
            {
            }
            break;
        }
        break;
    }
}

?>
