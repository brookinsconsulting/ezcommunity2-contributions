<?php
//
// $Id: ezformreportelement.php,v 1.7 2002/01/22 08:22:25 jhe Exp $
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
    function eZFormReportElement( $id = -1, $reportID = -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( &$id );
        }
        else if ( $id > -1 )
        {
            $this->get( $id, $reportID );
        }
    }

    function get( $id, $reportID )
    {
        $db =& eZDB::globalDatabase();
        $res = array();
        $db->array_query( $res, "SELECT * FROM eZForm_FormReportElement WHERE ElementID='$id' AND ReportID='$reportID'" );
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
        $this->ReportID =& $value[$db->fieldName( "ReportID" )];
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
                                  ReportID='$this->ReportID',
                                  StatisticsType='$this->StatisticsType'
                                  WHERE ID='$this->ID'" );
        }
        else
        {
            $db->lock( "eZForm_FormReportElement" );
            $this->ID = $db->nextID( "eZForm_FormReportElement", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormReportElement
                                  (ID, ElementID, ReportID, StatisticsType)
                                  VALUES
                                  ('$this->ID','$this->ElementID','$this->ReportID','$this->StatisticsType')" );
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

    function report( $as_object = true )
    {
        if ( $as_object )
            return new eZFormReport( $this->ReportID );
        else
            return $this->ReportID;
    }

    function setReport( $value )
    {
        if ( get_class( $value ) == "ezformreport" )
            $this->ReportID = $value->id();
        else if ( is_numeric( $value ) )
            $this->ReportID = $value;
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

            case 3:
            {
                return $this->statSum( &$template );
            }
            break;

            case 4:
            {
                return $this->statAverage( &$template );
            }
            break;

            case 5:
            {
                return $this->statMin( &$template );
            }
            break;

            case 6:
            {
                return $this->statMax( &$template );
            }
            break;

            case 7:
            {
                return $this->statMedian( &$template );
            }
            break;
        }
    }

    function statFrequency( &$template )
    {
        $template->set_var( "frequency_element", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $element = $this->element();

        if ( $element->ElementType->name() == "checkbox_item" )
        {
            $fixedElements = $element->fixedValues();
            foreach ( $fixedElements as $fElement )
            {
                $db->array_query( $res, "SELECT eZForm_FormElementResult.ID, eZForm_FormElementResult.Result FROM
                                         eZForm_FormElementResult, eZForm_FormElementFixedValues, eZForm_FormElementFixedValueLink
                                         WHERE eZForm_FormElementResult.ElementID = eZForm_FormElementFixedValueLink.ElementID AND
                                         eZForm_FormElementFixedValues.ID = eZForm_FormElementFixedValueLink.FixedValueID AND
                                         eZForm_FormElementResult.ElementID=705 AND
                                         eZForm_FormElementResult.Result LIKE '%" . $fElement->value() . "%'
                                         GROUP BY eZForm_FormElementResult.ID " );
                $template->set_var( "result", $fElement->value() );
                $template->set_var( "count", count( $res ) );
                $template->parse( "frequency_element", "frequency_element_tpl", true );
            }
        }
        else
        {
            $db->array_query( $res, "SELECT Result, Count(Result) AS Count
                                     FROM eZForm_FormElementResult WHERE ElementID='$this->ElementID'
                                     GROUP BY TRIM(Result) ORDER BY Result" );
            foreach ( $res as $result )
            {
                $template->set_var( "result", $result[$db->fieldName( "Result" )] );
                $template->set_var( "count", $result[$db->fieldName( "Count" )] );
                $template->parse( "frequency_element", "frequency_element_tpl", true );
            }
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

    function statSum( &$template )
    {
        $template->set_var( "sum", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT SUM(eZForm_FormElementResult.Result) as Sum
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'" );
        $template->set_var( "sum", $res[$db->fieldName( "Sum" )] );
        $output = $template->parse( $target, "sum_tpl" );
        return $output;        
    }

    function statAverage( &$template )
    {
        $template->set_var( "average" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT AVG(eZForm_FormElementResult.Result) as Avg
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'" );
        $template->set_var( "average", $res[$db->fieldName( "Avg" )] );
        $output = $template->parse( $target, "average_tpl" );
        return $output;
    }

    function statMin( &$template )
    {
        $template->set_var( "min" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT MIN(eZForm_FormElementResult.Result) as Min
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'" );
        $template->set_var( "min", $res[$db->fieldName( "Min" )] );
        $output = $template->parse( $target, "min_tpl" );
        return $output;
    }
    
    function statMax( &$template )
    {
        $template->set_var( "max" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT MAX(eZForm_FormElementResult.Result) as Max
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'" );
        $template->set_var( "max", $res[$db->fieldName( "Max" )] );
        $output = $template->parse( $target, "max_tpl" );
        return $output;
    }

    function statMedian( &$template )
    {
        $template->set_var( "median", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT Result FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 ORDER BY (Result+0)" );
        if ( count( $res ) == 0 )
        {
            $median = 0;
        }
        else
        {
            if ( count( $res ) % 2 == 0 )
            {
                $median = ( $res[(count( $res ) / 2 )]["Result"] + $res[(count( $res ) / 2 ) - 1]["Result"] ) / 2;
            }
            else
            {
                $median = $res[(count( $res ) / 2 ) - 1]["Result"];
            }
        }
        $template->set_var( "median", $median );
        $output = $template->parse( $target, "median_tpl" );
        return $output;
    }
    
    function types()
    {
        $ret = array(
            array( "Name" => "nothing", "Description" => "intl-nothing" ),
            array( "Name" => "frequency", "Description" => "intl-frequency" ),
            array( "Name" => "count", "Description" => "intl-count" ),
            array( "Name" => "sum", "Description" => "intl-sum" ),
            array( "Name" => "average", "Description" => "intl-average" ),
            array( "Name" => "min", "Description" => "intl-min" ),
            array( "Name" => "max", "Description" => "intl-max" ),
            array( "Name" => "median", "Description" => "intl-median" )
            );
        return $ret;
    }
    
    var $ID;
    var $ElementID;
    var $ReportID;
    var $StatisticsType;
}

?>
