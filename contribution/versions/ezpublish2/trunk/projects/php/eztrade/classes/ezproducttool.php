<?php
// 
// $Id: ezproducttool.php,v 1.2 2001/06/28 08:14:54 bf Exp $
//
// Definition of eZProductTool class
//
// Jan Borsodi <jb@ez.no>
// Created on: <30-Apr-2001 18:36:08 amos>
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

//!! eZTrade
//! The class eZProductTool has helper functions for products.
/*!

*/

include_once( "eztrade/classes/ezproduct.php" );
include_once( "classes/ezcachefile.php" );

class eZProductTool
{
    /*!
      \static
      Deletes all cache files for a product. Input is a eZProduct object or an eZProduct ID.
    */
    function deleteCache( $product )
    {
        if ( is_numeric( $product ) )
            $product = new eZProduct( $product );
        $CategoryID =& $product->categoryDefinition( false );
        $CategoryArray =& $product->categories( false );
        $Hotdeal = $product->isHotDeal();
        $ProductID = $product->id();

        $files = eZCacheFile::files( "eztrade/cache/", array( array( "productview", "productprint" ),
                                                              $ProductID, $CategoryID ),
                                     "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }
        $files = eZCacheFile::files( "eztrade/cache/", array( "productlist",
                                                              array_merge( $CategoryID, $CategoryArray ) ),
                                     "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }
        if ( $Hotdeal )
        {
            $files = eZCacheFile::files( "eztrade/cache/", array( "hotdealslist", NULL ),
                                         "cache", "," );
            foreach( $files as $file )
            {
                $file->delete();
            }
        }
    }
}

?>
