<?php
//
// $Id: viewreport.php,v 1.7 2002/02/04 10:42:56 jhe Exp $
//
// Created on: <21-Jan-2002 09:40:52 jhe>
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
include_once( "ezform/classes/ezformrenderer.php" );

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "viewreport.php" );

$t->setAllStrings();

$t->set_file( "view_report_tpl", "viewreport.tpl" );

$t->set_block( "view_report_tpl", "element_tpl", "element" );

$t->set_var( "report_id", $ReportID );

$report = new eZFormReport( $ReportID );
$form = $report->form();

$elements = $form->formElements();

foreach ( $elements as $element )
{
    $eType = $element->elementType();
    if ( $eType->name() == "table_item" )
    {
        $table = new eZFormTable( $element->id() );
        $tableElements = $table->tableElements();
        foreach ( $tableElements as $te )
        {
            $elementList[] = $te;
            $eT = $element->elementType();
            if ( !( $eT->name() == "text_label_item" ||
                    $eT->name() == "text_header_1_item" ||
                    $eT->name() == "text_header_2_item" ||
                    $eT->name() == "hr_line_item" ||
                    $eT->name() == "empty_item" ) )
            {
                $t->set_var( "element_id", $te->id() );
                if ( strlen( $te->name() ) > 40 )
                    $t->set_var( "element_name", substr( $te->name(), 0, 40 ) . "..." );
                else
                    $t->set_var( "element_name", $te->name() );

                $t->set_var( "selected", $ElementID == $te->id() ? "selected" : "" );
                $t->parse( "element", "element_tpl", true );
            }
        }
    }
    else
    {
        $elementList[] = $element;
        if ( !( $eType->name() == "text_label_item" ||
                $eType->name() == "text_header_1_item" ||
                $eType->name() == "text_header_2_item" ||
                $eType->name() == "hr_line_item" ||
                $eType->name() == "empty_item" ) )
        {
            $t->set_var( "element_id", $element->id() );
            if ( strlen( $element->name() ) > 40 )
                $t->set_var( "element_name", substr( $element->name(), 0, 40 ) . "..." );
            else
                $t->set_var( "element_name", $element->name() );
            $t->set_var( "selected", $ElementID == $element->id() ? "selected" : "" );
            $t->parse( "element", "element_tpl", true );
        }
    }
}

$t->set_var( "search_text", $SearchText );

$t->set_var( "substring_selected", "" );
$t->set_var( "starts_selected", "" );
$t->set_var( "equal_selected", "" );
$t->set_var( "not_selected", "" );
$t->set_var( "greater_selected", "" );
$t->set_var( "less_selected", "" );
$t->set_var( "between_selected", "" );

if ( isSet( $Operator ) )
    $t->set_var( $Operator . "_selected", "selected" );

$renderer =& new eZFormRenderer( $form );

$t->set_var( "form_name", $form->name() );

if ( isSet( $Search ) )
{
    $output = $renderer->renderResult( $ReportID, true, true, $ElementID, $Operator, $SearchText, $result_count );
}
else
{
    $output = $renderer->renderResult( $ReportID, true, true, false, false, false, $result_count );
    $result_count = count( eZFormElement::getAllResults( true, eZForm::getAllFormElements( $form->id() ) ) );
}

// Begin GRUK hack...
$results = $renderer->getResults( $ElementID, $Operator, $SearchText );
if ( !$results )
    $results = array();
$i = 0;
foreach ( $results as $res )
{
    if ( $i == 0 )
        $resString = "AND (";
    else
        $resString .= "OR ";
    $resString .= "ResultID = $res ";
    $i++;
    if ( $i == count( $results ) )
        $resString .= ")";
}

$db =& eZDB::globalDatabase();
$res = array();
$db->array_query( $res, "SELECT REPLACE(TRIM(Result), CHAR(160), '')+0 AS Results FROM eZForm_FormElementResult
                         WHERE ElementID >= 459 AND ElementID <= 483 AND Result != '' $resString
                         GROUP BY REPLACE(TRIM(Result), CHAR(160), '')" );
$t->set_var( "gruk_antall_kommuner", count( $res ) );

$i = 0;
foreach ( $res as $r )
{
    if ( $i == 0 )
        $whereStr = "AND (";
    else
        $whereStr .= "OR ";
    $whereStr .= "ResultID LIKE '" . $r[$db->fieldName( "Results" )] . "%' ";
    $i++;
    if ( $i == count( $res ) )
        $whereStr .= ")";
}
$db->query_single( $res, "SELECT COUNT(ID) AS Count FROM eZForm_FormElementResult
                          WHERE ElementID = 102 $whereStr" );

$t->set_var( "gruk_rapporterte_kommuner", $res[$db->fieldName( "Count" )] );
// End GRUK hack...


$t->set_var( "result_count", $result_count );
$t->set_var( "form", $output );

$urlString = $GLOBALS["REQUEST_URI"] . "?";

if ( $QUERY_STRING != "" )
{
    if ( strstr( $QUERY_STRING, "PrintableVersion" ) )
        $urlString .= $QUERY_STRING;
    else
        $urlString .= $QUERY_STRING . "&PrintableVersion=enabled";
}
else
{
    $urlString .= $QUERY_STRING . "PrintableVersion=enabled";
}

$t->set_var( "printable_url", $urlString );

$t->pparse( "output", "view_report_tpl" );

?>
