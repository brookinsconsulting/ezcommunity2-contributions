<?php
//
// $Id: cron.php,v 1.2.2.3 2003/04/09 10:34:09 br Exp $
//
// Created on: <08-Jun-2001 13:16:33 ce>
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

include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfptool.php" );

$rfp = new eZRfp();
$rfpValidArray =& $rfp->getAllValid();
$rfpUnValid =& $rfp->getAllUnValid();

if ( count ( $rfpValidArray ) > 0 )
{
    foreach ( $rfpValidArray as $rfp )
    {
	$rfp->setIsPublished( true, false, true );
	$d = 0;
	$rfp->setStartDate( $d );
	$rfp->store();

    if ( $rfp->isPublished() == 1 )
        eZRfpTool::notificationMessage( $rfp );

	$catDef = $rfp->categoryDefinition();

	$cats = $rfp->categories( false ) ;
	// clear the cache files.
	eZRfpTool::deleteCache( $rfp->id(), $catDef, $cats);

	print( "Publishing rfp: " . $rfp->name() . "\n" );
    }
}

if ( count ( $rfpUnValid ) > 0 )
{
    foreach( $rfpUnValid as $rfp )
    {
	$rfp->setIsPublished( false, false, true );
	$d  = 0;
//	$rfp->setStopDate( $d );
	$rfp->store();

	$cats = $rfp->categories( false ) ;
	// clear the cache files.
	eZRfpTool::deleteCache( $rfp->id(), $catDef, $cats  );

	print( "UnPublishing rfp: " . $rfp->name() . "\n" );
    }
}

?>
