<?php
//
// $Id: searchsupplier.php,v 1.3 2001/10/01 17:47:24 br Exp $
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


$ModuleName = "eZ forum";
$DetailedSearchPath = "/forum/search/";
$DetailedSearchVariable = "QueryString";
$DetailViewPath = "/forum/message/";
$IconPath = "/images/message.gif";

include_once( "ezforum/classes/ezforum.php" );

$forum = new eZForum();

$SearchResult = $forum->search( $SearchText, 0, $Limit, $SearchCount );

// $SearchCount = $forum->getQueryCount( $SearchText );


?>
