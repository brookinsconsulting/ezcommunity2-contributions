<?php
// 
// $Id: ezrfpsupplier.php,v 1.4 2001/07/19 12:19:21 jakobn Exp $
//
// Definition of eZRfpSupplier class
//
// Created on: <04-May-2001 17:14:30 amos>
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

//!! eZRfp
//! The class eZRfpSupplier supples rfp categories and rfp for other modules.
/*!

*/

class eZRfpSupplier
{
    function eZRfpSupplier()
    {
    }

    /*!
      Returns an array of available types.
    */
    function &urlTypes()
    {
        return $this->UrlTypes;
    }

    /*!
      Returns the name of the module.
    */
    function moduleName()
    {
        return "eZRfp";
    }

    /*!
      Returns a list of categories and/or contacts.
    */
    function &urlList( $type, $category = 0, $offset = 0 )
    {
        $ini =& INIFile::globalINI();
        $ret = false;
        switch( $type )
        {
            case "rfp":
            {
                include_once( "ezrfp/classes/ezrfp.php" );
                include_once( "ezrfp/classes/ezrfpcategory.php" );
                $limit = $ini->read_var( "eZRfpMain", "AdminListLimit" );
                $cat = new eZRfpCategory( $category );
                $categories = $cat->getByParent( $cat, false, "name" );
                $rfps = $cat->rfps( "alpha", false, true, $offset, $limit );
                $num_rfps = $cat->rfpCount( false, true );
                $path = $cat->path( $category );
                $category_path = array();
                foreach( $path as $path_item )
                {
                    $category_path[] = array( "id" => $path_item[0],
                                              "name" => $path_item[1] );
                }
                $category_array = array();
                $category_url = "/rfp/archive";
                foreach( $categories as $category )
                {
                    $id = $category->id();
                    $url = "$category_url/$id";
                    $category_array[] = array( "name" => $category->name(),
                                               "id" => $id,
                                               "url" => $url );
                }
                $rfp_array = array();
                $rfp_url = "/rfp/view";
                foreach( $rfps as $rfp )
                {
                    $id = $rfp->id();
                    $cat = $rfp->categoryDefinition();
                    $cat = $cat->id();
                    $url = "$rfp_url/$id/$cat/1";
                    $rfp_array[] = array( "name" => $rfp->name(),
                                              "id" => $id,
                                              "url" => $url );
                }
                $ret = array();
                $ret["path"] = $category_path;
                $ret["categories"] = $category_array;
                $ret["items"] = $rfp_array;
                $ret["item_total_count"] = $num_rfps;
                $ret["max_items_shown"] = $limit;
                break;
            }
        }
        return $ret;
    }

    function &item( $type, $id, $is_category )
    {
        $ret = false;
        switch( $type )
        {
            case "rfp":
            {
                if ( $is_category )
                {
                    include_once( "ezrfp/classes/ezrfpcategory.php" );
                    $category = new eZRfpCategory( $id );
                    $category_url = "/rfp/archive";
                    $url = "$category_url/$id";
                    $ret = array( "name" => $category->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
                else
                {
                    include_once( "ezrfp/classes/ezrfp.php" );
                    $rfp = new eZRfp( $id );
                    $rfp_url = "/rfp/view";
                    $cat = $rfp->categoryDefinition();
                    $cat = $cat->id();
                    $url = "$rfp_url/$id/$cat/1";
                    $ret = array( "name" => $rfp->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
            }
        }
        return $ret;
    }

    var $UrlTypes = array( "rfp" => "{intl-rfp}" );
}

?>
