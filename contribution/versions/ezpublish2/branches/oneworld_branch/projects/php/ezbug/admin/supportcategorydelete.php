<?php
//
// $Id: supportcategorydelete.php,v 1.1 2001/11/06 12:33:54 jhe Exp $
//
// Created on: <05-Nov-2001 18:28:35 jhe>
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

include_once( "ezbug/classes/ezbugsupportcategory.php" );

require( "ezuser/admin/admincheck.php" );

if ( !is_array( $SupportArrayID ) )
{
    $SupportArrayID = array( $SupportArrayID );
}

foreach ( $SupportArrayID as $supportID )
{
    eZBugSupportCategory::delete( $supportID );
}

include_once( "classes/ezhttptool.php" );
eZHTTPTool::header( "Location: /bug/support/category/list/" );
exit();

?>
