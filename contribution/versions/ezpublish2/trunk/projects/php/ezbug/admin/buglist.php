<?
// 
// $Id: buglist.php,v 1.1 2000/11/28 13:42:23 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Nov-2000 19:06:23 bf>
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

include_once( "ezbug/classes/ezbugcategory.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbug.php" );

include_once( "ezuser/classes/ezuser.php" );

$category = new eZBugCategory();

$category->setName( "GUI related" );
$category->setDescription( "This is bugs reported which are related to GUI issues." );
//$category->store();

$module = new eZBugModule();

$module->setName( "eZ trade" );
$module->setDescription( "Bugs reported here are related to the eZ trade module." );
//$module->store();

$bug = new eZBug();

$bug->setUser( eZUser::currentUser() );
$bug->setName( "Empty search result" );
$bug->setDescription( "The product search does not return anything." );
$bug->setIsHandled( false );
// $bug->store();

?>
