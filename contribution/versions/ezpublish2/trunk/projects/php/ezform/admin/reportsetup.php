<?php
//
// $Id: reportsetup.php,v 1.5 2002/01/22 17:37:48 jhe Exp $
//
// Created on: <17-Jan-2002 18:09:19 jhe>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformtable.php" );
include_once( "ezform/classes/ezformreport.php" );
include_once( "ezform/classes/ezformreportelement.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFormMain", "Language" );

$result = new eZFormReport( $ReportID );
$form = $result->form();

if ( $TableID > 0 )
{
    $elements = eZFormTable::tableElements( $TableID );
}
else
{
    $elements = $form->formElements();
}

if ( $Action == "store" )
{
    $types = eZFormReportElement::types();
    foreach ( $elements as $element )
    {
        $value = "StatisticsType" . $element->id();
        $repElement = new eZFormReportElement( $element->id(), $ReportID );
        $repElement->setReport( $ReportID );
        $repElement->setStatisticsType( $$value );
        if ( $types[$$value]["Name"] == "Cross-reference" )
        {
            $value = "CrossReference" . $element->id();
            if ( isSet( $$value ) )
                $repElement->setReference( $$value );
        }
        else
        {
            $repElement->setReference( "" );
        }
        $repElement->store();
    }

    include_once( "classes/ezhttptool.php" );
    if ( isSet( $OK ) )
    {
        if ( $TableID > 0 )
            eZHTTPTool::header( "Location: /form/report/setup/$ReportID" );
        else
            eZHTTPTool::header( "Location: /form/report/edit/$ReportID" );
        exit();
    }
    else if ( isSet( $Update ) )
    {
        if ( $TableID > 0 )
            eZHTTPTool::header( "Location: /form/report/setup/$ReportID/$TableID" );
        else
            eZHTTPTool::header( "Location: /form/report/setup/$ReportID" );
        exit();
    }       
}

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "reportsetup.php" );

$t->set_file( "report_setup_tpl", "reportsetup.tpl" );

$t->set_block( "report_setup_tpl", "form_element_tpl", "form_element" );
$t->set_block( "form_element_tpl", "statistics_type_tpl", "statistics_type" );
$t->set_block( "form_element_tpl", "table_item_tpl", "table_item" );
$t->set_block( "form_element_tpl", "cross_reference_tpl", "cross_reference" );
$t->set_block( "cross_reference_tpl", "cross_reference_item_tpl", "cross_reference_item" );

$t->set_var( "form_element", "" );
$t->set_var( "cross_reference", "" );
$t->set_var( "cross_reference_item", "" );
$t->set_var( "report_id", $ReportID );
$t->set_var( "table_id", $TableID );

$i = 0;

$statTypes = eZFormReportElement::types();

foreach ( $elements as $element )
{
    $repElement = new eZFormReportElement( $element->id(), $ReportID );
    $t->set_var( "td_class", $i % 2 == 0 ? "bglight" : "bgdark" );
    $t->set_var( "element_id", $element->id() );
    $t->set_var( "element_name", $element->name() );
    $eType = $element->elementType();
    $t->set_var( "element_type", $eType->description() );
    if ( $eType->name() == "table_item" )
        $t->parse( "table_item", "table_item_tpl" );
    else
        $t->set_var( "table_item", "" );

    $t->set_var( "statistics_type", "" );
    for ( $stat = 0; $stat < count( $statTypes ); $stat++ )
    {
        $t->set_var( "statistics_id", $stat );
        $t->set_var( "statistics_name", $statTypes[$stat]["Description"] );
        $t->set_var( "selected", $stat == $repElement->statisticsType() ? "selected" : "" );
        if ( $repElement->statisticsType( false ) == "Cross-reference" )
        {
            foreach ( $form->formElements() as $elementItem )
            {
                $eType = $element->elementType();
                if ( $eType->name() == "table_item" )
                {
                    $table = new eZFormTable( $element->id() );
                    $tableElements = $table->tableElements();
                    foreach ( $tableElements as $te )
                    {
                        $t->set_var( "reference_element_id", $te->id() );
                        if ( strlen( $te->name() ) > 40 )
                            $t->set_var( "reference_name", substr( $te->name(), 0, 40 ) . "..." );
                        else
                            $t->set_var( "reference_name", $te->name() );
                        if ( $te->element( false ) == $repElement->reference( false ) )
                            $t->set_var( "reference_selected", "selected" );
                        else
                            $t->set_var( "reference_selected", "" );
                        $t->parse( "cross_reference_item", "cross_reference_item_tpl", true );
                    }
                }
                else
                {
                    $t->set_var( "reference_element_id", $elementItem->id() );
                    if ( strlen( $elementItem->name() ) > 40 )
                        $t->set_var( "reference_name", substr( $elementItem->name(), 0, 40 ) . "..." );
                    else
                        $t->set_var( "reference_name", $elementItem->name() );
                    if ( $elementItem->id( false ) == $repElement->reference( false ) )
                        $t->set_var( "reference_selected", "selected" );
                    else
                        $t->set_var( "reference_selected", "" );
                    $t->parse( "cross_reference_item", "cross_reference_item_tpl", true );
                }
            }
            $t->parse( "cross_reference", "cross_reference_tpl" );
        }
        else
        {
            $t->set_var( "cross_reference", "" );
        }
        
        $t->parse( "statistics_type", "statistics_type_tpl", true );
    }
    
    $t->parse( "form_element", "form_element_tpl", true );
    $i++;
}

$t->setAllStrings();
    
$t->pparse( "output", "report_setup_tpl" );

?>
