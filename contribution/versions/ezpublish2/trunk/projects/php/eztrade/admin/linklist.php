<?php
// 
// $Id: linklist.php,v 1.2 2001/04/30 17:43:12 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <16-Mar-2001 14:41:10 amos>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproducttool.php" );

$INIGroup = "eZTradeMain";
$PreferencesSetting = "ProductLinkType";
$ClientModuleName = "eZTrade";
$ClientModuleType = "Product";
$ItemID = $ProductID;
$URLS = array( "back" => "/trade/productedit/edit/%s",
               "linklist" => "/trade/productedit/link/list/%s",
               "linkselect" => "/trade/productedit/link/select/%s/%s/%s/%s/%s" );
$Funcs = array( "delete" => "deleteCacheHelper" );

function deleteCacheHelper( $ProductID )
{
    eZProductTool::deleteCache( $ProductID );
}

include( "classes/admin/linklist.php" );

?>
