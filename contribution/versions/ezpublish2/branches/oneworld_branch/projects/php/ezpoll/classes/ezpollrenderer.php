<?php
// 
// $Id: ezpollrenderer.php,v 1.1.2.2 2002/06/04 08:41:21 pkej Exp $
//
// eZPollRenderer class
//
// Created on: <11-Jun-2001 12:07:57 pkej>
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

//!! eZPoll
//! eZPollRenderer documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezpoll/classes/ezpoll.php" );
include_once( "ezuser/classes/ezuser.php" );


class eZPollRenderer
{

    /*!
      Constructs a new eZPollRenderer object.
    */
    function eZPollRenderer( $poll = "" )
    {
        $this->Poll = $poll;
    }
    

    /*!
        Renders a poll
     */
    function &renderPoll( $poll = "" )
    {
        global $ini;
        global $menuCachedFile;
        global $noItem;
        global $GlobalSiteDesign;

        $Language = $ini->read_var( "eZPollMain", "Language" );

        include_once( "ezpoll/classes/ezpoll.php" );
        include_once( "ezpoll/classes/ezpollchoice.php" );

        $t = new eZTemplate( "ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                             "ezpoll/user/intl/", $Language, "articlevotebox.php" );

        $t->setAllStrings();

        $t->set_file( array(
            "vote_box" => "articlevotebox.tpl"
            ) );

        $t->set_block( "vote_box", "vote_item_tpl", "vote_item" );
        $t->set_block( "vote_box", "novote_item_tpl", "novote_item" );

        $t->set_var( "vote_item", "" );
        $t->set_var( "novote_item", "" );
        $t->set_var( "head_line", "" );
        $t->set_var( "sitedesign", $GlobalSiteDesign );

        if ( $poll )
        {
	        if (! $PollID)
	        {
	            $PollID = $poll->id();
	        }
            $poll = new eZPoll( $PollID );

            if ( $poll->isClosed() )
            {
                $renderPoll = false;
            }
            else
            {
                $renderPoll = true;
            }
            
            $choice = new eZPollChoice();

            $choiceList = $choice->getAll( $PollID );

            if ( empty( $choiceList ) || $renderPoll == false )
            {
                $t->set_var( "vote_item", "" );
                $t->set_var( "novote_item", $noItem );
                $t->parse( "novote_item", "novote_item_tpl" );
            }
            else
            {
                foreach( $choiceList as $choiceItem )
                {
                    $t->set_var( "choice_name", $choiceItem->name() );
                    $t->set_var( "choice_id", $choiceItem->id() );

                    $t->set_var( "novote_item", "" );
                    $t->parse( "vote_item", "vote_item_tpl", true );
                }
            }

            $t->set_var( "head_line", $poll->name() );
            $t->set_var( "poll_id", $PollID );

        }

        if ( $generateStaticPage == true )
        {
            $fp = eZFile::fopen( $menuCachedFile, "w+");

            $output = $t->parse( $target, "vote_box" );
            // print the output the first time while printing the cache file.

            print( $output );
            fwrite ( $fp, $output );
            fclose( $fp );
        }
        else
        {
		    $output = $t->parse( "", "vote_box" );
        }



        return $output;
    }
    
    var $Poll;
}

?>
