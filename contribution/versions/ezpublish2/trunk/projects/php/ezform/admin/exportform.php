<?php
//
// $Id: exportform.php,v 1.7 2002/01/29 09:26:28 jhe Exp $
//
// Created on: <07-Jan-2002 12:54:53 jhe>
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
include_once( "ezform/classes/ezformtable.php" );

ob_end_clean();

$form = new eZForm( $FormID );

$filename = $form->name() . ".txt";
ini_alter( "max_execution_time", "600" );
header( "Cache-Control:" );
header( "Content-disposition: attachment; filename=$filename" );
header( "Content-Type: application/vnd.ms-excel" );

$results = eZFormElement::getAllResults();

$elementList = $form->formElements();

$elements = array();

for ( $i = 0; $i < count( $elementList ); $i++ )
{
    $eType = $elementList[$i]->elementType();
    if ( $eType->name() == "text_field_item" ||
         $eType->name() == "text_area_item" ||
         $eType->name() == "dropdown_item" ||
         $eType->name() == "multiple_select_item" ||
         $eType->name() == "radiobox_item" ||
         $eType->name() == "checkbox_item" ||
         $eType->name() == "numerical_float_item" ||
         $eType->name() == "numerical_integer_item" ||
         $eType->name() == "user_email_item" ||
         $eType->name() == "table_item" )
    {
        if ( $eType->name() == "table_item" )
        {
            $tableElements = eZFormTable::tableElements( $elementList[$i]->id() );
            foreach ( $tableElements as $te )
            {
                $eType = $elementList[$i]->elementType();
                if ( $eType->name() == "text_field_item" ||
                     $eType->name() == "text_area_item" ||
                     $eType->name() == "dropdown_item" ||
                     $eType->name() == "multiple_select_item" ||
                     $eType->name() == "radiobox_item" ||
                     $eType->name() == "checkbox_item" ||
                     $eType->name() == "numerical_float_item" ||
                     $eType->name() == "numerical_integer_item" ||
                     $eType->name() == "user_email_item" )
                {
                    $elements[] = $te;                    
                }
            }
        }
        else
        {
            $elements[] = $elementList[$i];
        }
    }
}

unset( $elementList );

if ( count( $results ) > 0 )
{
    foreach ( $elements as $el )
    {
        print $el->name() . "\t";
    }
    print "\r\n";

    foreach ( $results as $res )
    {
        $values = eZFormElement::getThisUsersResults( $elements, $res );

        foreach ( $values as $v )
        {
            $resValue = $v["Result"];
            $resValue = str_replace( "\n", "<br>", $resValue );
            $resValue = str_replace( "\r", "", $resValue );
            if ( $resValue )
                print $resValue . "\t";
            else
                print "\t";
        }
        print "\r\n<br>";
        unset( $values );
        
    }
}

exit();

?>
