<?php
//
// $Id: results.php,v 1.6 2002/01/25 09:14:08 jhe Exp $
//
// Created on: <10-Jan-2002 08:58:22 jhe>
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
include_once( "ezform/classes/ezformtable.php" );

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "results.php" );

$t->setAllStrings();

$t->set_file( "form_results_tpl", "results.tpl" );

$t->set_block( "form_results_tpl", "element_tpl", "element" );
$t->set_block( "form_results_tpl", "result_tpl", "result" );
$t->set_block( "result_tpl", "edit_fields_tpl", "edit_fields" );
$t->set_block( "form_results_tpl", "delete_button_tpl", "delete_button" );

$form = new eZForm( $FormID );
$elements = $form->formElements();
$elementList = array();

$t->set_var( "form_name", $form->name() );
$t->set_var( "form_id", $FormID );
$t->set_var( "site_style", $SiteStyle );

$t->set_var( "result", "" );
$t->set_var( "edit_fields", "" );
$t->set_var( "delete_button", "" );

$user =& eZUser::currentUser();

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

if ( isSet( $Search ) )
{
    switch ( $Operator )
    {
        case "substring":
        {
            $t->set_var( "substring_selected", "selected" );
            $results = eZFormElement::searchForResults( $ElementID, $SearchText, $Operator );
        }
        break;

        case "starts":
        {
            $t->set_var( "starts_selected", "selected" );
            $results = eZFormElement::searchForResults( $ElementID, $SearchText, $Operator );
        }
        break;
        
        case "equal":
        {
            $t->set_var( "equal_selected", "selected" );
            $results = eZFormElement::searchForResults( $ElementID, $SearchText, $Operator );
        }
        break;

        case "not":
        {
            $t->set_var( "not_selected", "selected" );
            $results = eZFormElement::searchForResults( $ElementID, $SearchText, $Operator );
        }
        break;
        
        case "greater":
        {
            $t->set_var( "greater_selected", "selected" );
            $results = eZFormElement::searchForResults( $ElementID, $SearchText, $Operator );
        }
        break;

        case "less":
        {
            $t->set_var( "less_selected", "selected" );
            $results = eZFormElement::searchForResults( $ElementID, $SearchText, $Operator );
        }
        break;

        default:
        {
            $results = array();
        }
        break;
    }
}
else
{
    $results = eZFormElement::getAllResults( true, $elementList );
}

$i = 0;

if ( $user && $user->hasRootAccess() )
    $rootAccess = true;
else
    $rootAccess = false;

foreach ( $results as $result )
{
    $t->set_var( "td_class", $i % 2 == 0 ? "bglight" : "bgdark" );
    $t->set_var( "result_id", $result );
    $t->set_var( "title", eZFormElement::getResult( $result, $form->titleField() ) );

    if ( $rootAccess )
        $t->parse( "edit_fields", "edit_fields_tpl" );
    
    $t->parse( "result", "result_tpl", true );
    $i++;
}

if ( $rootAccess )
    $t->parse( "delete_button", "delete_button_tpl" );

$t->pparse( "output", "form_results_tpl" );

?>
