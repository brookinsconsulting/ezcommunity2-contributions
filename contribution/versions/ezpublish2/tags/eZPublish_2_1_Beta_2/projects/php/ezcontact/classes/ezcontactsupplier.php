<?php
// 
// $Id: ezcontactsupplier.php,v 1.3 2001/04/30 17:43:12 jb Exp $
//
// Definition of ezcontactsupplier class
//
// Jan Borsodi <jb@ez.no>
// Created on: <19-Mar-2001 16:51:20 amos>
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

//!! 
//! The class ezcontactsupplier does
/*!

*/

class eZContactSupplier
{
    function eZContactSupplier()
    {
    }

    function &urlTypes()
    {
        return $this->UrlTypes;
    }

    function &urlList( $type, $category = 0, $offset = 0 )
    {
        $ini =& INIFile::globalINI();
        $ret = false;
        switch( $type )
        {
            case "company":
            {
                include_once( "ezcontact/classes/ezcompany.php" );
                include_once( "ezcontact/classes/ezcompanytype.php" );
                $limit = $ini->read_var( "eZContactMain", "MaxCompanyList" );
                $categories = eZCompanyType::getByParentID( $category, "name" );
                $companies = eZCompany::getByCategory( $category, $offset, $limit );
                $num_companies = eZCompany::countByCategory( $category );
                $category_array = array();
                $category_url = "/contact/company/list";
                foreach( $categories as $category )
                {
                    $id = $category->id();
                    $url = "$category_url/$id";
                    $category_array[] = array( "name" => $category->name(),
                                               "id" => $id,
                                               "url" => $url );
                }
                $company_array = array();
                $company_url = "/contact/company/view";
                foreach( $companies as $company )
                {
                    $id = $company->id();
                    $url = "$company_url/$id";
                    $company_array[] = array( "name" => $company->name(),
                                              "id" => $id,
                                              "url" => $url );
                }
                $ret = array();
                $ret["categories"] = $category_array;
                $ret["items"] = $company_array;
                $ret["item_total_count"] = $num_companies;
                $ret["max_items_shown"] = $limit;
                break;
            }

//              case "person":
//              {
//                  break;
//              }
        }
        return $ret;
    }

    function &item( $type, $id, $is_category )
    {
        $ret = false;
        switch( $type )
        {
            case "company":
            {
                if ( $is_category )
                {
                    include_once( "ezcontact/classes/ezcompanytype.php" );
                    $category = new eZCompanyType( $id );
                    $category_url = "/contact/company/list";
                    $url = "$category_url/$id";
                    $ret = array( "name" => $category->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
                else
                {
                    include_once( "ezcontact/classes/ezcompany.php" );
                    $company = new eZCompany( $id );
                    $company_url = "/contact/company/view";
                    $url = "$company_url/$id";
                    $ret = array( "name" => $company->name(),
                                  "id" => $id,
                                  "url" => $url );
                }
            }

//              case "person":
//              {
//                  break;
//              }
        }
        return $ret;
    }

    var $UrlTypes = array( "company" => "{intl-contact_company}" /*,
                                                                   "person" => "{intl-contact_person}"*/ );
}

?>
