<?php
// 
// $Id: ezformrenderer.php,v 1.54 2002/01/21 17:01:54 jhe Exp $
//
// eZFormRenderer class
//
// Created on: <11-Jun-2001 12:07:57 pkej>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZForm
//! eZFormRenderer documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformelementtext.php" );
include_once( "ezform/classes/ezformelementnumerical.php" );
include_once( "ezform/classes/ezformreportelement.php" );
include_once( "ezform/classes/ezformelementtype.php" );
include_once( "ezform/classes/ezformtable.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZFormRenderer
{

    /*!
      Constructs a new eZFormRenderer object.
    */
    function eZFormRenderer( $form = "" )
    {
        $ini =& INIFile::globalINI();
        if ( get_class( $form ) == "ezform" )
        {
            $this->Form = $form;
        }
        $Language = $ini->read_var( "eZFormMain", "Language" );

        $this->Template = new eZTemplate( "ezform/admin/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                                          "ezform/admin/intl/", $Language, "form.php" );

        $this->Template->setAllStrings();

        $this->Template->set_file( "form_renderer_page_tpl", "formrenderer.tpl" );
        
        $this->Template->set_block( "form_renderer_page_tpl", "text_field_item_tpl", "text_field_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "text_block_item_tpl", "text_block_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "text_area_item_tpl", "text_area_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "result_item_tpl", "result_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "text_label_item_tpl", "text_label_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "text_header_1_item_tpl", "text_header_1_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "text_header_2_item_tpl", "text_header_2_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "hr_line_item_tpl", "hr_line_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "empty_item_tpl", "empty_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "numerical_float_item_tpl", "numerical_float_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "numerical_integer_item_tpl", "numerical_integer_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "multiple_select_item_tpl", "multiple_select_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "header_tpl", "header" );
        $this->Template->set_block( "form_renderer_page_tpl", "frequency_tpl", "frequency" );
        $this->Template->set_block( "form_renderer_page_tpl", "sum_tpl", "sum" );
        $this->Template->set_block( "form_renderer_page_tpl", "average_tpl", "average" );
        $this->Template->set_block( "form_renderer_page_tpl", "min_tpl", "min" );
        $this->Template->set_block( "form_renderer_page_tpl", "max_tpl", "max" );
        $this->Template->set_block( "frequency_tpl", "frequency_element_tpl", "frequency_element" );
        $this->Template->set_block( "form_renderer_page_tpl", "count_tpl", "count" );
        
        $this->Template->set_block( "multiple_select_item_tpl", "multiple_select_item_sub_item_tpl", "multiple_select_item_sub_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "dropdown_item_tpl", "dropdown_item" );
        $this->Template->set_block( "dropdown_item_tpl", "dropdown_item_sub_item_tpl", "dropdown_item_sub_item" );
        $this->Template->set_block( "form_renderer_page_tpl", "user_email_item_tpl", "user_email_item" );

        $this->Template->set_block( "numerical_float_item_tpl", "numerical_float_range_tpl", "numerical_float_range" );
        $this->Template->set_block( "numerical_integer_item_tpl", "numerical_integer_range_tpl", "numerical_integer_range" );

        $this->Template->set_block( "form_renderer_page_tpl", "table_item_tpl", "table_item" );
        $this->Template->set_block( "table_item_tpl", "table_item_sub_item_tpl", "table_item_sub_item" );
        $this->Template->set_block( "table_item_sub_item_tpl", "table_item_cell_tpl", "table_item_cell" );

        $this->Template->set_block( "form_renderer_page_tpl", "radiobox_item_tpl", "radiobox_item" );
        $this->Template->set_block( "radiobox_item_tpl", "radiobox_item_sub_item_tpl", "radiobox_item_sub_item" );

        $this->Template->set_block( "form_renderer_page_tpl", "checkbox_item_tpl", "checkbox_item" );
        $this->Template->set_block( "checkbox_item_tpl", "checkbox_item_sub_item_tpl", "checkbox_item_sub_item" );

        $this->Template->set_block( "form_renderer_page_tpl", "form_list_tpl", "form_list" );
        $this->Template->set_block( "form_list_tpl", "form_item_tpl", "form_item" );
        $this->Template->set_block( "form_item_tpl", "break_tpl", "break" );
        $this->Template->set_block( "form_list_tpl", "form_start_tag_tpl", "form_start_tag" );
        $this->Template->set_block( "form_list_tpl", "form_edit_start_tag_tpl", "form_edit_start_tag" );
        $this->Template->set_block( "form_list_tpl", "form_end_tag_tpl", "form_end_tag" );
        $this->Template->set_block( "form_list_tpl", "form_buttons_tpl", "form_buttons" );
        $this->Template->set_block( "form_buttons_tpl", "previous_button_tpl", "previous_button" );
        $this->Template->set_block( "form_buttons_tpl", "ok_button_tpl", "ok_button" );
        $this->Template->set_block( "form_buttons_tpl", "next_button_tpl", "next_button" );
        
        $this->Template->set_block( "form_list_tpl", "form_instructions_tpl", "form_instructions" );
        $this->Template->set_block( "form_renderer_page_tpl", "error_list_tpl", "error_list" );
        $this->Template->set_block( "error_list_tpl", "error_item_tpl", "error_item" );

        $this->Template->set_var( "max", "" );
        $this->Template->set_var( "min", "" );
        $this->Template->set_var( "sum", "" );
        $this->Template->set_var( "average", "" );
        $this->Template->set_var( "frequency", "" );
        $this->Template->set_var( "count", "" );
        $this->Template->set_var( "error_list", "" );
        $this->Template->set_var( "error_item", "" );
        $this->Template->set_var( "form_start_tag", "" );
        $this->Template->set_var( "form_edit_start_tag", "" );
        $this->Template->set_var( "form_sender", "" );
        $this->Template->set_var( "form_end_tag", "" );
        $this->Template->set_var( "form_buttons", "" );
        $this->Template->set_var( "previous_button", "" );
        $this->Template->set_var( "ok_button", "" );
        $this->Template->set_var( "next_button", "" );
        $this->Template->set_var( "form_list", "" );
        $this->Template->set_var( "form_item", "" );
        $this->Template->set_var( "text_field_item", "" );
        $this->Template->set_var( "result_item", "" );
        $this->Template->set_var( "text_area_item", "" );
        $this->Template->set_var( "text_block_item", "" );
        $this->Template->set_var( "text_label_item", "" );
        $this->Template->set_var( "text_header_1_item", "" );
        $this->Template->set_var( "text_header_2_item", "" );
        $this->Template->set_var( "hr_line_item", "" );
        $this->Template->set_var( "empty_item", "" );
        $this->Template->set_var( "table_item", "" );
        $this->Template->set_var( "table_item_sub_item", "" );
        $this->Template->set_var( "form_instructions", "" );
        $this->Template->set_var( "form_sender_value", "" );
        $this->Template->set_var( "numerical_float_range", "" );
        $this->Template->set_var( "numerical_integer_range", "" );
        $this->Template->set_var( "user_email_item", "" );
        

        global $GlobalSectionID, $SectionIDOverride;

        if ( isSet( $SectionIDOverride ) )
        {
            $this->Template->set_var( "section_id", $SectionIDOverride );
        }
        else
        {
            $this->Template->set_var( "section_id", $GlobalSectionID );
        }

        $this->Page = -1;
    }
    
    /*!
        Renders the element which are given as an argument based on its type.
     */
    function &renderElement( $element, $setSize = true, $header = true, $result = false, $resultID = false, $report = false )
    {
        $output = "";
        if ( get_class( $element ) == "ezformelement" )
        {
            $this->Template->set_var( "sub_item", "" );

            $type =& $element->elementType();
            $name = $type->name();

            if ( ( $result &&
                   ( $name == "user_email_item" ||
                     $name == "numerical_float_item" ||
                     $name == "numerical_integer_item" ||
                     $name == "dropdown_item" ||
                     $name == "text_field_item" ||
                     $name == "text_area_item" ||
                     $name == "multiple_select_item" ) ) ||
                 ( $report &&
                   ( $name == "radiobox_item" ||
                     $name == "checkbox_item" ) ) )
            {
                $subItems = array();
                $name = "result_item";
            }
            else
            {
                $subItems =& $element->fixedValues();
            }
            
            $name = str_replace( " ", "_", $name );

            $elementName = "eZFormElement_" . $element->id();

            global $$elementName;

            if ( isSet( $$elementName ) )
                $elementValue = $$elementName;
            
            $this->Template->set_var( "field_name", $elementName );

            if ( !( isSet( $elementValue ) && $elementValue != "" ) )
            {
                if ( $report )
                {
                    $reportElement = new eZFormReportElement( $element->id(), $resultID );
                    $elementValue = $reportElement->analyze( $this->Template );
                }
                else
                {
                    if ( $resultID )
                    {
                        $elementValue = $element->result( -1, $resultID );
                        if ( $elementValue == "" )
                            $elementValue = "&nbsp;";
                    }
                    else
                        $elementValue = $element->result();
                }
            }

            if ( $name == "user_email_item" )
            {
                if ( $elementValue == "" && $result = false )
                {
                    if ( $user =& eZUser::currentUser() )
                    {
                        $elementValue =  $user->eMail();
                    }
                }
            }

            $this->Template->set_var( "field_value", $elementValue );
            $this->Template->set_var( "element_name", $element->name() );

            if ( $name == "text_block_item" )
            {
                $elementText = new eZFormElementText( $element->id() );
                $this->Template->set_var( "text_block", nl2br( $elementText->text() ) );
            }
            
            if ( $name == "numerical_float_item" ||
                 $name == "numerical_integer_item" )
            {
                $elementNumerical = new eZFormElementNumerical( $element->id() );
                
                if ( $name == "numerical_float_item" )
                {
                    if ( $elementNumerical->minValue() != "" && $elementNumerical->maxValue() != "" )
                    {
                        $this->Template->set_var( "min_value", $elementNumerical->minValue() );
                        $this->Template->set_var( "max_value", $elementNumerical->maxValue() );
                        $this->Template->parse( "numerical_float_range", "numerical_float_range_tpl" );
                    }
                }
                else
                {
                    if ( $elementNumerical->minValue() != "" && $elementNumerical->maxValue() != "" )
                    {
                        $this->Template->set_var( "min_value", $elementNumerical->minValue() );
                        $this->Template->set_var( "max_value", $elementNumerical->maxValue() );
                        $this->Template->parse( "numerical_integer_range", "numerical_float_range_tpl" );
                    }
                }
            }

            if ( $setSize )
            {
                if ( $element->size() == 0 )
                    $this->Template->set_var( "element_size", "size=\"40\"" );
                else
                    $this->Template->set_var( "element_size", "size=\"" . $element->size() . "\"" );
            }
            else
            {
                $this->Template->set_var( "element_size", "" );
            }

            if ( $element->isBreaking() )
                $this->Template->set_var( "break", "<br>" );
            else
                $this->Template->set_var( "break", "" );

            $this->Template->set_var( $name . "_sub_item", "" );

            if ( $header )
                $this->Template->parse( "header_line", "header_tpl" );
            else
                $this->Template->set_var( "header_line", "" );
            
            $checked = "";

            if ( $name == "checkbox_item" ||
                 $name == "radiobox_item" )
            {
                $checked = "checked";
                if ( $name == "checkbox_item" )
                    $elementArray = split( ",", $elementValue );
            }
            else if ( $name == "dropdown_item" )
            {
                $checked = "selected";
            }

            foreach ( $subItems as $subItem )
            {
                if ( ( $name == "checkbox_item" && in_array( $subItem->value(), $elementArray ) ) ||
                     $subItem->value() == $elementValue )
                    $this->Template->set_var( "selected", $checked );
                else
                    $this->Template->set_var( "selected", "" );
                
                $this->Template->set_var( "sub_value", $subItem->value() );
                $this->Template->parse( $name . "_sub_item", $name . "_sub_item_tpl", true );
            }
            
            $elementValue = str_replace( "eZFormElement_", "", $$elementName );

            if ( trim( $type ) != "" )
                $output =& $this->Template->parse( $target, $name . "_tpl" );
        }
        
        return $output;
    }

    /*!
      Renders form for viewing of results
    */
    function &renderResult( $resultID, $result = true, $report = false )
    {
        $elements = $this->Form->formElements();
        $elementCounter = 0;
        
        $this->Template->set_var( "form_id", $this->Form->id() );
        $this->Template->set_var( "form_name", $this->Form->name() );
        $this->Template->set_var( "form_completed_page", $this->Form->completedPage() );
        if ( $pageList == "" )
            $this->Template->set_var( "page_list", $this->Page );
        else
            $this->Template->set_var( "page_list", $pageList );
        
        if ( $this->Form->instructionPage() != "" )
        {
            $this->Template->set_var( "form_instruction_page", $this->Form->instructionPage() );
            $this->Template->set_var( "form_instruction_page_name", $this->Form->instructionPageName() );
            $this->Template->parse( "form_instructions", "form_instructions_tpl" );
        }

        $maxBreakCount = 1;
        $breakCount = 1;
        $lastBreaked = true;
        // count the max number of unbreaked elements
        foreach ( $elements as $element )
        {
            $eType = $element->elementType();
            
            if ( $element->isBreaking() || ( $eType->name() != "text_field_item" ) )
            {
                $lastBreaked = true;
                $breakCount = 1;
            }
            else
            {
                $lastBreaked = false;
            }
            
            if ( $lastBreaked == false )
            {
                $breakCount++;
                $maxBreakCount = max( $maxBreakCount, $breakCount );
            }
        }

        foreach ( $elements as $element )
        {
            if ( !$element->hide() )
            {
                $elementCounter++;
                $eType = $element->elementType();
                if ( $eType->name() == "table_item" )
                {
                    $table = new eZFormTable( $element->id() );
                    $tableElements = $table->tableElements();
                    $i = 0;
                    for ( $rows = 0; $rows < $table->rows(); $rows++ )
                    {
                        for ( $cols = 0; $cols < $table->cols(); $cols++ )
                        {
                            $elementType = $tableElements[$i]->elementType();
                            $colspan = 0;
                            for ( $check = $cols + 1; $check < $table->cols(); $check++ )
                            {
                                $nextPos = $check + $table->cols() * $rows;
                                $nextType = $tableElements[$nextPos]->elementType();
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
                                $this->Template->set_var( "colspan", "colspan=\"$colspan\"" );
                            else
                                $this->Template->set_var( "colspan", "" );
                            
                            $output = $this->renderElement( $tableElements[$i], true, false, $result, $resultID, $report );
                            
                            $this->Template->set_var( "element", $output );
                            
                            if ( $cols == 0 )
                                $this->Template->parse( "table_item_cell", "table_item_cell_tpl" );
                            else
                                $this->Template->parse( "table_item_cell", "table_item_cell_tpl", true );
                            
                            if ( $colspan > 0 )
                            {
                                $i += $colspan - 1;
                                $cols += $colspan - 1;
                            }
                            $i++;
                        }
                        if ( $rows == 0 )
                            $this->Template->parse( "table_item_sub_item", "table_item_sub_item_tpl" );
                        else
                            $this->Template->parse( "table_item_sub_item", "table_item_sub_item_tpl", true );
                        
                    }
                    $tableString = $this->Template->parse( "table_item", "table_item_tpl" );
                    
                    $this->Template->set_var( "element", $tableString );
                    $this->Template->set_var( "colspan", " colspan=\"$maxBreakCount\"" );
                    
                    $this->Template->parse( "break", "break_tpl" );
                    $this->Template->parse( "form_item", "form_item_tpl", true );
                }
                else
                {
                    $output = $this->renderElement( $element, true, true, $result, $resultID, $report );
                    
                    $this->Template->set_var( "element", $output );
                    
                    if ( $eType->name() != "text_field_item" )
                        $this->Template->set_var( "colspan", " colspan=\"$maxBreakCount\"" );
                    else
                        $this->Template->set_var( "colspan", " colspan=\"1\"" );
                    
                    if ( ( $eType->name() != "text_field_item" ) or $element->isBreaking() )
                    {
                        $this->Template->parse( "break", "break_tpl" );
                    }
                    else
                    {
                        $this->Template->set_var( "break", "" );
                    }
                    $this->Template->parse( "form_item", "form_item_tpl", true );
                }
            }
        }

        if ( !$result )
        {
            $this->Template->set_var( "result_id", $resultID );
            $this->Template->parse( "form_edit_start_tag", "form_edit_start_tag_tpl" );
            $this->Template->parse( "form_end_tag", "form_end_tag_tpl" );
            $this->Template->parse( "ok_button", "ok_button_tpl" );
            $this->Template->parse( "form_buttons", "form_buttons_tpl" );
        }
        
        $output = $this->Template->parse( $target, "form_list_tpl" );
        
        return $output;
    }
    
    /*!
        Renders a form
     */
    function &renderForm( $form = "", $addFormTags = true, $addButtons = true, $resultID = false )
    {
        global $pageList;
        $output = "";
        $render = false;
        
        if ( get_class( $form ) == "ezform" )
        {
            $this->Form =& $form;
            $render = true;
        }
        else
        {
            if ( get_class( $this->Form ) == "ezform" )
            {
                $render = true;
            }
        }

        if ( $this->Page == -1 )
        {
            $this->Page = $this->Form->formPage( -1, false );
        }
        $currentPage = $this->Form->formPage( $this->Page );
        if ( $render == true )
        {
            $elements =& $currentPage->pageElements();
            $elementCounter = 0;

            $this->Template->set_var( "form_id", $this->Form->id() );
            $this->Template->set_var( "form_name", $this->Form->name() );
            $this->Template->set_var( "form_completed_page", $this->Form->completedPage() );
            if ( $pageList == "" )
                $this->Template->set_var( "page_list", $this->Page );
            else
                $this->Template->set_var( "page_list", $pageList );
            
            if ( $this->Form->instructionPage() != "" )
            {
                $this->Template->set_var( "form_instruction_page", $this->Form->instructionPage() );
                $this->Template->set_var( "form_instruction_page_name", $this->Form->instructionPageName() );
                $this->Template->parse( "form_instructions", "form_instructions_tpl" );
            }

            $maxBreakCount = 1;
            $breakCount = 1;
            $lastBreaked = true;
            // count the max number of unbreaked elements
            foreach ( $elements as $element )
            {
                $eType = $element->elementType();
                
                if ( $element->isBreaking() || ( $eType->name() != "text_field_item" ) )
                {
                    $lastBreaked = true;
                    $breakCount = 1;
                }
                else
                {
                    $lastBreaked = false;
                }

                if ( $lastBreaked == false )
                {
                    $breakCount++;
                    $maxBreakCount = max( $maxBreakCount, $breakCount );
                }
            }

            foreach ( $elements as $element )
            {
                $elementCounter++;
                $eType = $element->elementType();
                if ( $eType->name() == "table_item" )
                {
                    $table = new eZFormTable( $element->id() );
                    $tableElements = $table->tableElements();
                    $i = 0;

                    for ( $rows = 0; $rows < $table->rows(); $rows++ )
                    {
                        for ( $cols = 0; $cols < $table->cols(); $cols++ )
                        {
                            $elementType = $tableElements[$i]->elementType();
                            $colspan = 0;
                            for ( $check = $cols + 1; $check < $table->cols(); $check++ )
                            {
                                $nextPos = $check + $table->cols() * $rows;
                                $nextType = $tableElements[$nextPos]->elementType();
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
                                $this->Template->set_var( "colspan", "colspan=\"$colspan\"" );
                            else
                                $this->Template->set_var( "colspan", "" );
                            
                            $output = $this->renderElement( $tableElements[$i], true, false, false, $resultID );
                            
                            $this->Template->set_var( "element", $output );
                            
                            if ( $cols == 0 )
                                $this->Template->parse( "table_item_cell", "table_item_cell_tpl" );
                            else
                                $this->Template->parse( "table_item_cell", "table_item_cell_tpl", true );

                            if ( $colspan > 0 )
                            {
                                $i += $colspan - 1;
                                $cols += $colspan - 1;
                            }
                            $i++;
                        }
                        if ( $rows == 0 )
                            $this->Template->parse( "table_item_sub_item", "table_item_sub_item_tpl" );
                        else
                            $this->Template->parse( "table_item_sub_item", "table_item_sub_item_tpl", true );

                    }

                    $tableString = $this->Template->parse( "table_item", "table_item_tpl" );
                    
                    $this->Template->set_var( "element", $tableString );
                    $this->Template->set_var( "colspan", " colspan=\"$maxBreakCount\"" );
                    
                    $this->Template->parse( "break", "break_tpl" );
                    $this->Template->parse( "form_item", "form_item_tpl", true );
                }
                else
                {
                    $output = $this->renderElement( $element, true, true, false, $resultID );
                    
                    $this->Template->set_var( "element", $output );

                    if ( $eType->name() != "text_field_item" )
                        $this->Template->set_var( "colspan", " colspan=\"$maxBreakCount\"" );
                    else
                        $this->Template->set_var( "colspan", " colspan=\"1\"" );
                    
                    
                    if ( ( $eType->name() != "text_field_item" ) or $element->isBreaking() )
                    {
                        $this->Template->parse( "break", "break_tpl" );
                    }
                    else
                    {
                        $this->Template->set_var( "break", "" );
                    }
                    $this->Template->parse( "form_item", "form_item_tpl", true );
                }
            }
            
            if ( $elementCounter != 0 )
            {
                if ( $addFormTags == true )
                {
                    $this->Template->parse( "form_start_tag", "form_start_tag_tpl" );
                    $this->Template->parse( "form_end_tag", "form_end_tag_tpl" );
                }

                if ( $pageList == "" || !strstr( $pageList, ":" ) )
                {
                    if ( $this->Form->pages() > 1 )
                    {
                        $this->Template->parse( "next_button", "next_button_tpl" );
                        $this->Template->set_var( "ok_button", "" );
                    }
                    else
                    {
                        $this->Template->parse( "ok_button", "ok_button_tpl" );
                        $this->Template->set_var( "next_button", "" );
                    }
                    $this->Template->set_var( "previous_button", "" );
                }
                else
                {
                    if ( $this->Form->lastPage( false ) == $this->Page )
                    {
                        $this->Template->parse( "ok_button", "ok_button_tpl" );
                        $this->Template->set_var( "next_button", "" );
                    }
                    else
                    {
                        $this->Template->set_var( "ok_button", "" );
                        $this->Template->parse( "next_button", "next_button_tpl" );
                    }
                    $this->Template->parse( "previous_button", "previous_button_tpl" );
                }
                    
                
                $this->Template->parse( "form_buttons", "form_buttons_tpl" );
                $output = $this->Template->parse( $target, "form_list_tpl" );
            }
        }

        return $output;
    }

    function storePage( $page, $result = false )
    {
        $page = new eZFormPage( $page );
        $elements = $page->pageElements();
        $i = 0;
        foreach ( $elements as $element )
        {
            $i++;
            $elementType = $element->elementType();
            if ( $elementType->name() == "table_item" )
            {
                $tableElements = eZFormTable::tableElements( $element->id() );
                foreach ( $tableElements as $tableElement )
                {
                    $elementName = "eZFormElement_" . $tableElement->id();
                    
                    global $$elementName;
                    $value = $$elementName;
                    if ( isSet( $value ) && $value != "" )
                    {
                        if ( $result )
                            $tableElement->setResult( $value, $result, true );
                        else
                            $tableElement->setResult( $value );
                    }
                }
            }
            else
            {
                $elementName = "eZFormElement_" . $element->id();
                
                global $$elementName;
                $value = $$elementName;
                if ( is_array( $value ) )
                {
                    $valueArray = $value;
                    $value = "";
                    $i = 0;
                    foreach ( $valueArray as $valueElement )
                    {
                        if ( $i > 0 )
                            $value .= ",";
                        $value .= $valueElement;
                        $i++;
                    }
                }
                if ( isSet( $value ) )
                {
                    if ( $result )
                    {
                        $element->setResult( $value, $result, true );
                    }
                    else
                        $element->setResult( $value );
                }
            }
        }
    }

    function setPage( $page = -1 )
    {
        $this->Page = $page;
    }

    function page()
    {
        return $this->Page;
    }

    function findNextPage( $pageID )
    {
        $db =& eZDB::globalDatabase();
        $page = new eZFormPage( $pageID );
        $elements = $page->pageElements();

        $elementList = array_merge( $elements, array() );
        
        foreach ( $elements as $e )
        {
            if ( $e->ElementType->name() == "table_item" )
            {
                $elementList = array_merge( $elementList, eZFormTable::tableElements( $e->id() ) );
            }
        }

        
        foreach ( $elementList as $element )
        {
            $elementName = "eZFormElement_" . $element->id();

            global $$elementName;
            $value = $$elementName;
            if ( !is_numeric( $value ) && $value != "" )
            {
                $db->query_single( $qa, "SELECT fv.ID AS ID FROM eZForm_FormElementFixedValueLink as fvl,
                                         eZForm_FormElementFixedValues as fv WHERE
                                         fvl.FixedValueID=fv.ID AND
                                         fvl.ElementID='" . $element->id() . "' AND
                                         fv.Value='$value'" );
                $value = $qa[$db->fieldName( "ID" )];
            }
            $conditionArray = $element->getConditions();
            // Small hack to make goto page work
            if ( count( $conditionArray ) == 1 )
            {
                if ( ( $conditionArray[0]["Min"] == -1000 ) &&
                     ( $conditionArray[0]["Max"] == 1000 ) )
                    return $conditionArray[0]["Page"];
            }
            foreach ( $conditionArray as $condition )
            {
                if ( $condition["Min"] <= $value &&
                     $condition["Max"] >= $value )
                {
                    return $condition["Page"];
                }
            }
        }

        $db->query_single( $qa, "SELECT FormID, Placement FROM eZForm_FormPage WHERE ID='$pageID'" );
        $next = $qa[$db->fieldName( "Placement" )] + 1;
        $db->query_single( $nextPage, "SELECT ID FROM eZForm_FormPage
                                       WHERE FormID='" . $qa[$db->fieldName( "FormID" )] . "' AND
                                       Placement='$next'" );
        if ( count( $nextPage ) != 0 )
            return $nextPage[$db->fieldName( "ID" )];
        else
            return -1;
    }
    
    /*!
        This function verifies the data from a posted form
     */
    function verifyPage( $page = -1 )
    {
        global $FormID;
        global $formName;
        global $formSender;
        global $redirectTo;
        
        $output = "";
        $errorMessages = array();
        $errorMessagesAdditionalInfo = array();
        $ini =& INIFile::globalINI();

        if ( $page == -1 )
            $page = $this->Page;
        $this->Form = new eZForm( $FormID );
        $page = new eZFormPage( $page );
        $elements = $page->pageElements();
        if ( $this->Form->isSendAsUser() )
        {
            if ( isSet( $formSender ) )
            {
                if ( eZMail::validate( $formSender ) == false )
                {
                    $errorMessages[] = "form_sender_not_valid";
                    $errorMessagesAdditionalInfo[] = "";
                }
            }
            else
            {
                $errorMessages[] = "form_sender_missing";
                $errorMessagesAdditionalInfo[] = "";
            }
        }

        $elementSize = count( $elements );
        for ( $i = 0; $i < $elementSize; $i++ )
        {
            $elementType = $elements[$i]->elementType();
            if ( $elementType->name() == "table_item" )
            {
                $elements = array_merge( $elements, eZFormTable::tableElements( $elements[$i]->id() ) );
            }
        }

        foreach ( $elements as $element )
        {
            $elementName = "eZFormElement_" . $element->id();
            $elementType = $element->elementType();
            global $$elementName;
            
            $value = $$elementName;
            if ( $element->isRequired() == true )
            {
/*                if ( $elementType->name() == "user_email_item" )
                  $value = $formSender;*/
                
                if ( empty( $value ) )
                {
                    $errorMessages[] = "required_field";
                    $errorMessagesAdditionalInfo[] = "\"" .  $element->name() . "\"" ;
                }
            }
            
            if ( $elementType->name() == "numerical_integer_item" )
            {
                if ( $value == "" )
                {
                    $$elementName = 0;
                    $value = 0;
                }
                $numElement = new eZFormElementNumerical( $element->id() );
                if ( !$numElement->validNumber( $value ) || !is_numeric( $value ) )
                {
                    $errorMessages[] = "integer_field";
                    $errorMessagesAdditionalInfo[] = "\"" .  $element->name() . "\"" ;
                }
            }

            if ( $elementType->name() == "numerical_float_item" )
            {
                if ( $value == "" )
                {
                    $$elementName = 0;
                    $value = 0;
                }
                $numElement = new eZFormElementNumerical( $element->id() );
                if ( $numElement->id() == 0 )
                {
                    if ( !is_numeric( $value ) )
                    {
                        $errorMessages[] = "float_field";
                        $errorMessagesAdditionalInfo[] = "\"" .  $element->name() . "\"" ;
                    }
                }
                else if ( !$numElement->validNumber( $value ) )
                {
                    $errorMessages[] = "float_field";
                    $errorMessagesAdditionalInfo[] = "\"" .  $element->name() . "\"" ;
                }
            }
        }

        if ( count( $errorMessages ) > 0 )
        {
            $i = 0;
            foreach ( $errorMessages as $errorMessage )
            {
                $errorMessage = $this->Template->Ini->read_var( "strings", $errorMessage );
                $this->Template->set_var( "error_message", $errorMessage );
                $this->Template->set_var( "error_value", $errorMessagesAdditionalInfo[$i] );
                $this->Template->parse( "error_item", "error_item_tpl", true );
                $i++;
            }

            $output = $this->Template->parse( $target, "error_list_tpl" );
        }

        return $output;
    }
    
    /*!
        This function will send the form to the recipients.
     */
    function sendForm()
    {
        global $FormID;
        global $formName;
        global $formSender;
        global $redirectTo;

        $formSent = false;
        
        $form = new eZForm( $FormID );
        
        $ini =& INIFile::globalINI();
        
        $emailDefaults = false;
        
        if ( $ini->read_var( "eZFormMain", "CreateEmailDefaults" ) == "enabled" )
        {
            $emailDefaults = true;
        }
        
        $elements = $form->formElements();
        
        $mail = new eZMail();
        
        $content = "";
        
        foreach ( $elements as $element )
        {
            $elementType = $element->elementType();
            if ( $elementType->name() == "table_item" )
            {
                $table = new eZFormTable( $element->id() );
                $tableElements = $table->tableElements();
                foreach ( $tableElements as $te )
                {
                    $elementName = "eZFormElement_" . $te->id();

                    $value = $te->result();
                    
                    // convert array to multiple textlines.
                    $tmpValue = "";
                    if ( is_array( $value ) )
                    {
                        foreach ( $value as $v )
                        {
                            $tmpValue .=  $v . "\n";
                        }
                        $value = $tmpValue;
                    }
                    
                    if ( $emailDefaults == true )
                    {
                        if ( $te->name() == $this->Template->Ini->read_var( "strings", "subject_label" ) )
                        {
                            $mail->setSubject( $value );
                        }
                        else
                        {
                            $content .= $te->name() . ":\n " . $value . "\n\n";
                        }
                    }
                    else
                    {
                        $content .= $te->name() . ":\n " . $value . "\n\n";
                    }
                }

            }
            else
            {
                $elementName = "eZFormElement_" . $element->id();

                $value = $element->result();
                // convert array to multiple textlines.
                $tmpValue = "";
                if ( is_array( $value ) )
                {
                    foreach ( $value as $v )
                    {
                        $tmpValue .=  $v . "\n";
                    }
                    $value = $tmpValue;
                }
                
                if ( $emailDefaults == true )
                {
                    if ( $element->name() == $this->Template->Ini->read_var( "strings", "subject_label" ) )
                    {
                        $mail->setSubject( $value );
                    }
                    else
                    {
                        $content .= $element->name() . ":\n " . $value . "\n\n";
                    }
                }
                else
                {
                    $content .= $element->name() . ":\n " . $value . "\n\n";
                }
            }
        }
        if ( $emailDefaults == false )
        {
            $mail->setSubject( $form->name() );
        }
        
        $t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "form.php" );

        $t->set_file( array(
            "form_mail_tpl" => "emailtemplate.tpl"
            ) );
        
        
        $t->set_var( "content", $content );

        $t->setAllStrings();
        
        $formatedContent = $t->parse( $target, "form_mail_tpl" );
        
        $mail->setBody( $formatedContent );
        
        if ( $form->isSendAsUser() )
        {
            $mail->setFrom( $formSender );
            $mail->setCC( $form->CC() . ", " . $formSender );
        }
        else
        {
            $mail->setFrom( $form->sender() );
            $mail->setCC( $form->CC() . ", " . $form->sender() );
        }
        
        $mail->setTo( $form->receiver() );
        
        $mail->send();
        $formSent = true;

        if ( $formSent )
        {
            if ( $form->completedPage() == "" )
            {
                eZHTTPTool::header( "Location: /" );
            }
            else
            {
                $redirectTo = $form->completedPage();
                eZHTTPTool::header( "Location: $redirectTo" );
            }
            exit();
        }
    }
    
    var $Template;
    var $Form;
    var $Page;
}

?>
