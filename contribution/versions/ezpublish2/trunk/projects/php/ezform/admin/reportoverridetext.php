<?php
//
// $Id: reportoverridetext.php,v 1.1 2002/01/25 13:23:13 jhe Exp $
//
// Created on: <25-Jan-2002 13:37:29 jhe>
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

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );
include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformreport.php" );
include_once( "ezform/classes/ezformreportelement.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZFormMain", "Language" );

if ( $Action == "store" )
{
    $repElement = new eZFormReportElement( $ElementID, $ReportID );
    $repText = $repElement->reference( true );
    if ( isSet( $NoOverride ) )
    {
        $repElement->setReference( 0 );
        if ( $repText )
            $repText->delete();
    }
    else
    {
        if ( $repText )
        {
            $repText->setName( $OverrideText );
            $repText->store();
        }
        else
        {
            $repText = new eZFormElement();
            $repTypes = eZFormElementType::getAll( 0, false );
            foreach ( $repTypes as $repType )
            {
                if ( $repType->name() == "text_label_item" )
                {
                    $repText->setElementType( $repType );
                }
            }
            $repText->setName( $OverrideText );
            $repText->store();
            $repElement->setReference( $repText );
        }
    }
    $repElement->store();

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /form/report/setup/$ReportID/$TableID" );
    exit();
}

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "reportoverridetext.php" );
$t->setAllStrings();

$t->set_file( "report_edit_tpl", "reportoverridetext.tpl" );

$t->set_var( "report_id", $ReportID );
$t->set_var( "table_id", $TableID );
$t->set_var( "element_id", $ElementID );

$repElement = new eZFormReportElement( $ElementID, $ReportID );
$repText = $repElement->reference( true );
$element = $repElement->element( true );

$t->set_var( "original_text", $element->name() );

if ( $repText )
    $t->set_var( "field_value", $repText->name() );
else
    $t->set_var( "field_value", "" );

$t->pparse( "output", "report_edit_tpl" );

?>
