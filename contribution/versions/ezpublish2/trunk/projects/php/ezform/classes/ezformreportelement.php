<?php
//
// $Id: ezformreportelement.php,v 1.12 2002/01/23 08:50:29 jhe Exp $
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
        $this->ReferenceID =& $value[$db->fieldName( "ReferenceID" )];
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
                                  ReferenceID='$this->ReferenceID',
                                  StatisticsType='$this->StatisticsType'
                                  WHERE ID='$this->ID'" );
        }
        else
        {
            $db->lock( "eZForm_FormReportElement" );
            $this->ID = $db->nextID( "eZForm_FormReportElement", "ID" );
            $res[] = $db->query( "INSERT INTO eZForm_FormReportElement
                                  (ID, ElementID, ReportID, ReferenceID, StatisticsType)
                                  VALUES
                                  ('$this->ID','$this->ElementID','$this->ReportID','$this->ReferenceID','$this->StatisticsType')" );
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

    function reference( $as_object = true )
    {
        if ( $as_object )
            return new eZFormElement( $this->ReferenceID );
        else
            return $this->ReferenceID;
    }

    function setReference( $value )
    {
        if ( get_class( $value ) == "ezformelement" )
            $this->ReferenceID = $value->id();
        else
            $this->ReferenceID = $value;
    }
    
    function statisticsType( $as_id = true )
    {
        if ( $as_id )
            return $this->StatisticsType;
        else
        {
            $list = $this->types();
            return $list[$this->StatisticsType]["Name"];
        }
    }
    
    function setStatisticsType( $value )
    {
        $this->StatisticsType = $value;
    }

    function analyze( &$template )
    {
        $name = $this->types( $this->StatisticsType );
        switch ( $name["Name"] )
        {
            case "Nothing":
            {
                return "";
            }
            break;

            case "Frequency":
            {
                return $this->statFrequency( &$template );
            }
            break;

            case "Count":
            {
                return $this->statCount( &$template );
            }
            break;

            case "Sum":
            {
                return $this->statSum( &$template );
            }
            break;

            case "Average":
            {
                return $this->statAverage( &$template );
            }
            break;

            case "Min":
            {
                return $this->statMin( &$template );
            }
            break;

            case "Max":
            {
                return $this->statMax( &$template );
            }
            break;

            case "Median":
            {
                return $this->statMedian( &$template );
            }
            break;

            case "25percentile":
            {
                return $this->statPercentile( &$template, 25 );
            }
            break;

            case "75percentile":
            {
                return $this->statPercentile( &$template, 75 );
            }
            break;

            case "Cross-reference":
            {
                return $this->statCrossTable( &$template );
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
            print "<pre>";
            print_r( $fixedElements );
            foreach ( $fixedElements as $fElement )
            {
                $db->array_query( $res, "SELECT eZForm_FormElementResult.ID, eZForm_FormElementResult.Result FROM
                                         eZForm_FormElementResult, eZForm_FormElementFixedValues, eZForm_FormElementFixedValueLink
                                         WHERE eZForm_FormElementResult.ElementID = eZForm_FormElementFixedValueLink.ElementID AND
                                         eZForm_FormElementFixedValues.ID = eZForm_FormElementFixedValueLink.FixedValueID AND
                                         eZForm_FormElementResult.ElementID='" . $element->id() . "' AND
                                         eZForm_FormElementResult.Result LIKE '%" . $fElement->value( false ) . "%'
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

    function statPercentile( &$template, $percentile )
    {
        $template->set_var( "percentile", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT Result FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 ORDER BY (Result+0)" );

        $pos = round( ( count( $res ) / 100 ) * $percentile );
        $template->set_var( "percentile", $percentile );
        $template->set_var( "value", $res[$pos - 1]["Result"] );
        $output = $template->parse( $target, "percentile_tpl" );
        return $output;
    }

    function statCrossTable( &$t )
    {
        $t->set_var( "cross_table", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $element = $this->element();
        $reference = $this->reference();
        $db->array_query( $res, "SELECT a.Result as Result1, b.Result as Result2, count(a.Result) as Count
                                 FROM eZForm_FormElementResult as a, eZForm_FormElementResult as b
                                 WHERE a.ElementID='" . $reference->id() . "' AND b.ElementID='" . $element->id() . "' AND
                                 a.ResultID=b.ResultID AND a.Result != '' AND b.Result != ''
                                 GROUP BY a.Result, b.Result ORDER BY a.Result, b.Result" );
        $elementValues = $element->fixedValues();
        $referenceValues = $reference->fixedValues();
        
        foreach ( $elementValues as $elementItem )
        {
            $t->set_var( "header_value", $elementItem->value() );
            $t->parse( "header_cell", "header_cell_tpl", true );
        }
        $t->parse( "header_row", "header_row_tpl" );
        $i = 0;
        foreach ( $referenceValues as $referenceItem )
        {
            $t->set_var( "cross_table_cell", "" );
            $t->set_var( "reference_name", $referenceItem->value() );
            foreach ( $elementValues as $elementItem )
            {
                if ( $res[$i]["Result1"] == $referenceItem->value() &&
                     $res[$i]["Result2"] == $elementItem->value() )
                {
                    $t->set_var( "count", $res[$i]["Count"] );
                    $i++;
                }
                else
                {
                    $t->set_var( "count", "0" );
                }
                $t->parse( "cross_table_cell", "cross_table_cell_tpl", true );
            }
            $t->parse( "cross_table_row", "cross_table_row_tpl", true );
        }
        $output = $t->parse( $target, "cross_table_tpl" );
        return $output;
    }
    
    function types( $no = -1 )
    {
        $ret = array(
            array( "Name" => "Nothing", "Description" => "intl-nothing" ),
            array( "Name" => "Hide", "Description" => "intl-hide" ),
            array( "Name" => "Frequency", "Description" => "intl-frequency" ),
            array( "Name" => "Count", "Description" => "intl-count" ),
            array( "Name" => "Sum", "Description" => "intl-sum" ),
            array( "Name" => "Average", "Description" => "intl-average" ),
            array( "Name" => "Min", "Description" => "intl-min" ),
            array( "Name" => "Max", "Description" => "intl-max" ),
            array( "Name" => "Median", "Description" => "intl-median" ),
            array( "Name" => "25percentile", "Description" => "intl-25percentile" ),
            array( "Name" => "75percentile", "Description" => "intl-75percentile" ),
            array( "Name" => "Cross-reference", "Description" => "intl-cross_reference" )
            );
        
        if ( $no > -1 )
            return $ret[$no];
        else
            return $ret;
    }
    
    var $ID;
    var $ElementID;
    var $ReportID;
    var $ReferenceID;
    var $StatisticsType;
}

?>
