<?php
//
// $Id: ezformreportelement.php,v 1.2 2002/01/21 11:29:57 jhe Exp $
//
// Definition of eZFormReportElement class
//
// Created on: <18-Jan-2002 09:40:26 jhe>
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

//!! 
//! The class eZFormReportElement does
/*!

*/

include_once( "classes/ezdb.php" );
include_once( "ezform/classes/ezformelement.php" );

class eZFormReportElement
{
    function eZFormReportElement( $id = -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( &$id );
        }
        else if ( $id > -1 )
        {
            $this->get( $id );
        }
    }

    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $res = array();
        $db->array_query( $res, "SELECT * FROM eZForm_FormReportElement WHERE ElementID='$id'" );
        if ( count( $res ) > 0 )
            $this->fill( &$res[0] );
        else
            $this->ElementID = $id;
    }

    function fill( &$value )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $value[$db->fieldName( "ID" )];
        $this->ElementID =& $value[$db->fieldName( "ElementID" )];
        $this->StatisticsType =& $value[$db->fieldName( "StatisticsType" )];
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res = array();
        if ( $this->ID > 0 )
        {
            $res[] = $db->query( "UPDATE eZForm_FormReportElement SET
                                  ElementID='$this->ElementID',
                                  StatisticsType='$this->StatisticsType'
                                  WHERE ID='$this->ID'" );
        }
        else
        {
            $db->lock( "eZForm_FormReportElement" );
            $this->ID = $db->nextID( "eZForm_FormReportElement", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormReportElement
                                  (ID, ElementID, StatisticsType)
                                  VALUES
                                  ('$this->ID','$this->ElementID','$this->StatisticsType')" );
            $db->unlock();
        }
        eZDB::finish( $res, $db );
    }
    
    function id()
    {
        return $this->ID;
    }

    function element( $as_object = true )
    {
        if ( $as_object )
            return new eZFormElement( $this->ElementID );
        else
            return $this->ElementID;
    }

    function setElement( $value )
    {
        if ( get_class( $value ) == "ezformelement" )
            $this->ElementID = $value->id();
        else if ( is_numeric( $value ) )
            $this->ElementID = $value;
    }

    function statisticsType()
    {
        return $this->StatisticsType;
    }

    function setStatisticsType( $value )
    {
        $this->StatisticsType = $value;
    }

    function analyze( &$template )
    {
        switch ( $this->StatisticsType )
        {
            case 0:
            {
                return "";
            }
            case 1:
            {
                return $this->statFrequency( &$template );
            }
            break;

            case 2:
            {
                return $this->statCount( &$template );
            }
            break;
        }
    }

    function statFrequency( &$template )
    {
        $template->set_var( "frequency_element", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT Result, Count(Result) AS Count
                                 FROM eZForm_FormElementResult WHERE ElementID='$this->ElementID'
                                 GROUP BY Result ORDER BY Result" );
        foreach ( $res as $result )
        {
            $template->set_var( "result", $result[$db->fieldName( "Result" )] );
            $template->set_var( "count", $result[$db->fieldName( "Count" )] );
            $template->parse( "frequency_element", "frequency_element_tpl", true );
        }
        $output = $template->parse( $target, "frequency_tpl" );
        return $output;
    }

    function statCount( &$template )
    {
        $template->set_var( "count", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT Count(Result) AS Count
                                 FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID' AND
                                 Result != ''" );
        $template->set_var( "count", $res[$db->fieldName( "Count" )] );
        $output = $template->parse( $target, "count_tpl" );
        return $output;        
    }
    
    function types()
    {
        $ret = array(
            array( "Name" => "nothing", "Description" => "intl-nothing" ),
            array( "Name" => "frequency", "Description" => "intl-frequency" ),
            array( "Name" => "count", "Description" => "intl-count" )
            );
        return $ret;
    }
    
    var $ID;
    var $ElementID;
    var $StatisticsType;
}

?>
