<?php
//
// $Id: ezformreportelement.php,v 1.25 2002/02/01 18:11:49 jhe Exp $
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
        if ( $this->ReferenceID == 0 )
        {
            return false;
        }
        else
        {
            if ( $as_object )
                return new eZFormElement( $this->ReferenceID );
            else
                return $this->ReferenceID;
        }
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

    function analyze( &$template, $resultArray = false )
    {
        $resultString = "";
        if ( is_array( $resultArray ) )
        {
            if ( count( $resultArray ) == 0 )
            {
                $resultString = "AND eZForm_FormElementResult.ID < 0 ";
            }
            $i = 0;
            foreach ( $resultArray as $result )
            {
                if ( $i == 0 )
                    $resultString .= " AND (";
                else
                    $resultString .= " OR ";
                $resultString .= "eZForm_FormElementResult.ResultID=$result";
                $i++;
                if ( $i == count( $resultArray ) )
                    $resultString .= ")";
            }
        }
        
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
                return $this->statFrequency( &$template, $resultString );
            }
            break;

            case "Count":
            {
                return $this->statCount( &$template, $resultString );
            }
            break;

            case "Sum":
            {
                return $this->statSum( &$template, $resultString );
            }
            break;

            case "Average":
            {
                return $this->statAverage( &$template, $resultString );
            }
            break;

            case "Min":
            {
                return $this->statMin( &$template, $resultString );
            }
            break;

            case "Max":
            {
                return $this->statMax( &$template, $resultString );
            }
            break;

            case "Median":
            {
                return $this->statMedian( &$template, $resultString );
            }
            break;

            case "25percentile":
            {
                return $this->statPercentile( &$template, 25, $resultString );
            }
            break;

            case "75percentile":
            {
                return $this->statPercentile( &$template, 75, $resultString );
            }
            break;

            case "Cross-reference":
            {
                return $this->statCrossTable( &$template, $resultString );
            }
            break;

            case "Graph":
            {
                return $this->statGraph( &$template, $resultString );
            }
            break;

            case "Min25Median75Max":
            {
                return $this->statMin25Median75Max( &$template, $resultString );
            }
            break;

            case "List":
            {
                return $this->statList( &$template, $resultString );
            }
        }
    }

    function statFrequency( &$t, $resultString )
    {
        $t->set_var( "frequency_element", "" );
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
                                         eZForm_FormElementResult.ElementID='" . $element->id() . "' AND
                                         eZForm_FormElementResult.Result LIKE '%" . $fElement->value( false ) . "%'
                                         $resultString
                                         GROUP BY eZForm_FormElementResult.ID " );
                $t->set_var( "result", $fElement->value() );
                $t->set_var( "count", count( $res ) );
                $t->parse( "frequency_element", "frequency_element_tpl", true );
            }
        }
        else
        {
            $db->array_query( $res, "SELECT Result, Count(Result) AS Count
                                     FROM eZForm_FormElementResult WHERE ElementID='$this->ElementID'
                                     $resultString
                                     GROUP BY TRIM(Result) ORDER BY Result" );
            foreach ( $res as $result )
            {
                $t->set_var( "result", $result[$db->fieldName( "Result" )] );
                $t->set_var( "count", $result[$db->fieldName( "Count" )] );
                $t->parse( "frequency_element", "frequency_element_tpl", true );
            }
        }
        
        $output = $t->parse( $target, "frequency_tpl" );
        return $output;
    }

    function statCount( &$t, $resultString )
    {
        $t->set_var( "count", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT Count(Result) AS Count
                                 FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 $resultString AND
                                 Result != ''" );
        $t->set_var( "count", $res[$db->fieldName( "Count" )] );
        $output = $t->parse( $target, "count_tpl" );
        return $output;        
    }

    function statSum( &$t, $resultString )
    {
        $t->set_var( "sum", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT SUM(REPLACE((TRIM(eZForm_FormElementResult.Result)), char(160), '')) as Sum
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'
                                  $resultString" );
        $t->set_var( "sum", $res[$db->fieldName( "Sum" )] );
        $output = $t->parse( $target, "sum_tpl" );
        return $output;        
    }

    function statAverage( &$t, $resultString )
    {
        $t->set_var( "average" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT AVG(REPLACE((TRIM(eZForm_FormElementResult.Result)), char(160), '')) as Avg
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'
                                  $resultString" );
        $t->set_var( "average", $res[$db->fieldName( "Avg" )] );
        $output = $t->parse( $target, "average_tpl" );
        return $output;
    }

    function statMin( &$t, $resultString )
    {
        $t->set_var( "min" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT MIN(REPLACE((TRIM(eZForm_FormElementResult.Result)), char(160), '')+0) as Min
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'
                                  $resultString" );
        $t->set_var( "min", $res[$db->fieldName( "Min" )] );
        $output = $t->parse( $target, "min_tpl" );
        return $output;
    }
    
    function statMax( &$t, $resultString )
    {
        $t->set_var( "max" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT MAX(REPLACE((TRIM(eZForm_FormElementResult.Result)), char(160), '')+0) as Max
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'
                                  $resultString" );
        $t->set_var( "max", $res[$db->fieldName( "Max" )] );
        $output = $t->parse( $target, "max_tpl" );
        return $output;
    }

    function statMedian( &$t, $resultString )
    {
        $t->set_var( "median", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT (REPLACE((TRIM(Result)), char(160), '')+0) AS Result
                                 FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 $resultString
                                 ORDER BY Result" );
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
        $t->set_var( "median", $median );
        $output = $t->parse( $target, "median_tpl" );
        return $output;
    }

    function statPercentile( &$t, $percentile, $resultString )
    {
        $t->set_var( "percentile", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT (REPLACE((TRIM(Result)), char(160), '')+0) AS Result
                                 FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 $resultString
                                 ORDER BY Result" );

        $pos = round( ( count( $res ) / 100 ) * $percentile );
        $t->set_var( "percentile", $percentile );
        $t->set_var( "value", $res[$pos - 1]["Result"] );
        $output = $t->parse( $target, "percentile_tpl" );
        return $output;
    }

    function statCrossTable( &$t, $resultString )
    {
        $t->set_var( "cross_table", "" );
        $resultString = ereg_replace( "eZForm_FormElementResult", "a", $resultString ) .
                        ereg_replace( "eZForm_FormElementResult", "b", $resultString );
        $res = array();
        $db =& eZDB::globalDatabase();
        $element = $this->element();
        $reference = $this->reference();
        $db->array_query( $res, "SELECT a.Result as Result1, b.Result as Result2, count(a.Result) as Count
                                 FROM eZForm_FormElementResult as a, eZForm_FormElementResult as b
                                 WHERE a.ElementID='" . $reference->id() . "' AND b.ElementID='" . $element->id() . "' AND
                                 a.ResultID=b.ResultID AND a.Result != '' AND b.Result != ''
                                 $resultString
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

    function statGraph( &$t, $resultString )
    {
        $renderer = new eZFormRenderer();
        $t->set_var( "graph", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $elementID = $this->element( false );
        $db->query_single( $res, "SELECT COUNT(eZForm_FormElementResult.Result) as Count FROM
                                  eZForm_FormTableElementDict, eZForm_FormElementResult WHERE
                                  eZForm_FormTableElementDict.TableID='" . $elementID . "' AND
                                  eZForm_FormElementResult.ElementID=eZForm_FormTableElementDict.ElementID
                                  $resultString
                                  GROUP BY eZForm_FormTableElementDict.ElementID
                                  ORDER BY Count desc" );
        $max = $res[$db->fieldName( "Count" )];

        $db->array_query( $res, "SELECT eZForm_FormElementResult.ElementID, COUNT(eZForm_FormElementResult.Result) as Count FROM
                                 eZForm_FormTableElementDict, eZForm_FormElementResult WHERE
                                 eZForm_FormTableElementDict.TableID='" . $elementID . "' AND
                                 eZForm_FormElementResult.ElementID=eZForm_FormTableElementDict.ElementID AND
                                 eZForm_FormElementResult.Result != '' 
                                 $resultString
                                 GROUP BY eZForm_FormTableElementDict.ElementID" );
        $i = 0;
        $i2 = 0;
        $table = new eZFormTable( $elementID );
        $elements = $table->tableElements();
        
        for ( $row = 0; $row < $table->rows(); $row++ )
        {
            $t->set_var( "graph_cell", "" );
            for ( $col = 0; $col < $table->cols(); $col++ )
            {
                $colspan = 0;
                for ( $check = $col + 1; $check < $table->cols(); $check++ )
                {
                    $nextPos = $check + $table->cols() * $row;
                    $nextType = $elements[$nextPos]->elementType();
                    if ( $nextType->name() == "empty_item" )
                    {
                        if ( $colspan == 0 )
                            $colspan = 2;
                        else
                            $colspan++;
                    }
                    else
                    {
                        $check = $table->cols();
                    }
                }
                
                if ( $colspan > 0 )
                    $t->set_var( "colspan", "colspan=\"$colspan\"" );
                else
                    $t->set_var( "colspan", "" );

                if ( $res[$i][$db->fieldName( "ElementID" )] == $elements[$i2]->id() )
                {
                    $t->set_var( "width", round( ( $res[$i][$db->fieldName( "Count" )] / $max ) * 100 ) );
                    $t->set_var( "leftover-width", 100 - round( ( $res[$i][$db->fieldName( "Count" )] / $max ) * 100 ) );
                    $t->parse( "bar", "bar_tpl" );
                    $t->set_var( "text", "" );
                    $i++;
                }
                else
                {
                    $t->set_var( "bar", "" );
                    $t->set_var( "text", $renderer->renderElement( $elements[$i2], true, false, true, true, false ) );
                }
                if ( $colspan > 0 )
                {
                    $i2 += $colspan - 1;
                    $col += $colspan - 1;
                }

                $t->parse( "graph_cell", "graph_cell_tpl", true );
                $i2++;
            }
            $t->parse( "graph_row", "graph_row_tpl", true );
        }
        $output = $t->parse( $target, "graph_table_tpl" );
        return $output;
    }

    function statMin25Median75Max( &$t, $resultString )
    {
        $t->set_var( "min25median75max", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT MIN(REPLACE((TRIM(eZForm_FormElementResult.Result)), char(160), '')+0) as Min
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'
                                  $resultString" );
        $t->set_var( "min", $res[$db->fieldName( "Min" )] );
        $db->array_query( $res, "SELECT (REPLACE((TRIM(Result)), char(160), '')+0) AS Result
                                 FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 $resultString
                                 ORDER BY Result" );
        $pos = round( ( count( $res ) / 100 ) * 25 );
        $t->set_var( "25", $res[$pos - 1]["Result"] );
        $db->array_query( $res, "SELECT (REPLACE((TRIM(Result)), char(160), '')+0) AS Result
                                 FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 $resultString
                                 ORDER BY Result" );
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
        $t->set_var( "median", $median );
        $db->array_query( $res, "SELECT (REPLACE((TRIM(Result)), char(160), '')+0) AS Result
                                 FROM eZForm_FormElementResult
                                 WHERE ElementID='$this->ElementID'
                                 $resultString
                                 ORDER BY Result" );
        $pos = round( ( count( $res ) / 100 ) * 75 );
        $t->set_var( "75", $res[$pos - 1]["Result"] );
        $db->query_single( $res, "SELECT MAX(REPLACE((TRIM(eZForm_FormElementResult.Result)), char(160), '')+0) as Max
                                  FROM eZForm_FormElementResult
                                  WHERE ElementID='$this->ElementID'
                                  $resultString" );
        $t->set_var( "max", $res[$db->fieldName( "Max" )] );
        $output = $t->parse( $target, "min25median75max_tpl" );
        return $output;
        
    }

    function statList( &$t, $resultString )
    {
        $t->set_var( "list", "" );
        $t->set_var( "list_row", "" );
        $res = array();
        $db =& eZDB::globalDatabase();
        $element = $this->element();
        $reference = $this->reference();
        if ( get_class( $reference ) == "ezformelement" )
        {
            $db->array_query( $res, "SELECT Result, ResultID, ElementID FROM eZForm_FormElementResult
                                     WHERE (eZForm_FormElementResult.ElementID='" . $element->id() . "' OR
                                     eZForm_FormElementResult.ElementID='" . $reference->id() . "') AND
                                     eZForm_FormElementResult.Result != ''
                                     $resultString
                                     ORDER BY ResultID" );

            for ( $i = 0; $i < count( $res ) - 1; $i++ )
            {
                if ( $res[$i]["ElementID"] != $res[$i + 1]["ElementID"] &&
                     $res[$i]["ResultID"] == $res[$i + 1]["ResultID"] )
                {
                    if ( $res[$i]["ElementID"] == $element->id() )
                    {
                        $t->set_var( "element_value", $res[$i]["Result"] );
                    }
                    else
                    {
                        $t->set_var( "header_value", $res[$i]["Result"] );
                    }
                    
                    if ( $res[$i + 1]["ElementID"] == $element->id() )
                    {
                        $t->set_var( "element_value", $res[$i + 1]["Result"] );
                    }
                    else
                    {
                        $t->set_var( "header_value", $res[$i + 1]["Result"] );
                    }
                    $t->parse( "list_row", "list_row_tpl", true );
                    $i++;
                }
            }
            $output = $t->parse( $target, "list_tpl" );
        }
        else
        {
            $output = "";
        }
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
            array( "Name" => "Cross-reference", "Description" => "intl-cross_reference" ),
            array( "Name" => "Graph", "Description" => "intl-graphical_representation" ),
            array( "Name" => "Min25Median75Max", "Description" => "intl-min25median75max" ),
            array( "Name" => "List", "Description" => "intl-list" )
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
