<?php
//
// $Id: tableedit.php,v 1.1 2001/12/13 12:40:16 jhe Exp $
//
// Created on: <13-Dec-2001 10:51:41 jhe>
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
include_once( "classes/ezhttptool.php" );

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformelementtype.php" );
include_once( "ezform/classes/ezformtable.php" );

$ini =& INIFile::globalINI();

if ( isSet( $Cancel ) )
{
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

$table = new eZFormTable( $ElementID );

if ( $Action == "up" )
{
    $element = new eZFormElement( $ElementID );
    $table->moveUp( $element );
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}

if ( $Action == "down" )
{
    $element = new eZFormElement( $ElementID );
    $table->moveDown( $element );
    eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
    exit();
}


if ( isSet( $DeleteSelected ) )
{
    foreach ( $elementDelete as $deleteMe )
    {
        $element = new eZFormElement( $deleteMe );
        $element->delete();
    }
}

$cells = $table->rows() * $table->cols()
if ( isSet( $OK ) || isSet( $Update ) )
{
    for ( $i = 0; $i < $cells; $i++ )
    {
        $element = new eZFormElement( $elementID[$i] );
        $elementType = new eZFormElementType( $elementTypeID[$i] );
        $element->setElementType( $elementType );
        $element->setName( $elementName[$i] );
        $element->setSize( $Size[$i] );

        $required = false;
        $break = false;
        if ( count( $elementRequired ) > 0 )
        {
            foreach ( $elementRequired as $requiredID )
            {
                if ( $elementID[$i] == $requiredID )
                {
                    $element->setRequired( true );
                    $required = true;
                }
            }
        }
        if ( count( $ElementBreak ) > 0 )
        {
            foreach ( $ElementBreak as $breakID )
            {
                if ( $elementID[$i] == $breakID )
                {
                    $element->setBreak( true );
                    $break = true;
                }
            }
        }
        
        $element->setBreak( $break );
        $element->setRequired( $required );
        
        $element->store();

        if ( isSet( $OK ) )
        {
            eZHTTPTool::header( "Location: /form/form/edit/$FormID/" );
            exit();
        }

    }
}


$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/admin/intl/", $Language, "form.php" );

$t->set_file( "form_edit_page_tpl", "formedit.tpl" );



?>
