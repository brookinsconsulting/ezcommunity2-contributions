<?php
//
// $Id: datasupplier.php,v 1.10 2001/08/03 08:06:36 bf Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZPollMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "polls" :
    {
        include( "ezpoll/user/pollist.php" );
    }
    break;

    case "vote" :
    {
        $PollID = $url_array[3];
        if ( isSet( $url_array[4] ) )
             $ChoiceID = $url_array[4];
        include( "ezpoll/user/vote.php" );
    }
    break;

    case "result" :
    {
        $Show = $url_array[4];
        $PollID = $url_array[3];
        include( "ezpoll/user/result.php" );

        $poll = new eZPoll( $PollID );
        if ( $poll->get( $PollID ) )
        {
            $forum = $poll->forum();
            $ForumID = $forum->id();
            include( "ezforum/user/messagesimplelist.php" );
        }
    }
    break;

    case "votebox" :
    {
        $PollID = $url_array[3];
        include( "ezpoll/user/votebox.php" );
    }
    break;
    case "votepage" :
    {
        $PollID = $url_array[3];
        include( "ezpoll/user/votepage.php" );
    }
    break;

    case "userlogin" :
    {
        $VoteID = $url_array[4];
        include( "ezpoll/user/userlogin.php" );
    }    
    break;

    case "test":
    {
        include( "ezcontact/test.php" );
    }

}
?>
