<?php
//
// $Id: searchsupplier.php,v 1.4 2001/10/01 17:48:15 br Exp $
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


$ModuleName = "eZ article";
$DetailedSearchPath = "/article/search/";
$DetailedSearchVariable = "SearchText";
$DetailViewPath = "/article/view/";
$IconPath = "/images/document.gif";

include_once( "ezarticle/classes/ezarticle.php" );

$article = new eZArticle();
$SearchResult = $article->search( $SearchText, "time", false, 0, $Limit, array(), $SearchCount );
// $SearchCount = $article->searchCount( $SearchText, "time", false );



?>
