<?php
// 
// $Id: formview.php,v 1.11 2002/01/04 10:20:58 jhe Exp $
//
// Created on: <12-Jun-2001 13:07:24 pkej>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );
include_once( "ezform/classes/ezformelementtype.php" );
include_once( "ezform/classes/ezformrenderer.php" );
include_once( "ezmail/classes/ezmail.php" );

$ini =& INIFile::globalINI();

$page_array = explode( ":", $pageList );

if ( isSet( $Cancel ) )
{
    if ( !empty( $redirectTo ) )
    {
        eZHTTPTool::header( "Location: $redirectTo" );
    }
    else
    {
        eZHTTPTool::header( "Location: /" );
    }
    exit();
}

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "AdminTemplateDir" ),
                     "ezform/user/intl/", $Language, "form.php" );

$t->setAllStrings();

$t->set_file( "form_view_page_tpl", "formview.tpl" );

$t->set_block( "form_view_page_tpl", "mail_preview_tpl", "mail_preview" );

$t->set_var( "error", "" );
$t->set_var( "form", "" );

$renderer =& new eZFormRenderer( $form );
$form = new eZForm( $FormID );
$currentPage = $page_array[count( $page_array ) - 1];

$t->set_var( "form_id", $FormID );
$t->set_var( "form_name", $form->name() );
$t->set_var( "form_completed_page", $form->completedPage() );
$t->set_var( "form_instruction_page", $form->instructionPage() );
$t->set_var( "page_list", $pageList );

if ( isSet( $Next ) )
{
    $output =& $renderer->verifyPage( $currentPage );
    if ( $output == "" )
    {
        $renderer->storePage( $currentPage );
        $nextPage = $renderer->findNextPage( $currentPage );
        if ( $nextPage == -1 || $nextPage == "" )
        {
            if ( $form->receiver() != "" )
            {
                $renderer->sendForm();
            }
            
            if ( $form->useDatabaseStorage() )
            {
                $form->setActiveResult();
            }
            else
            {
                $form->deleteResult();
            }

            if ( $form->completedPage() != "" )
            {
                eZHTTPTool::header( "Location: " . $form->completedPage() );
            }
        }
        $pageList .= ":" . $nextPage;
    }
    else
    {
        $nextPage = $currentPage;
        $t->set_var( "error", $output );
    }
}
else if ( isSet( $Previous ) )
{
    $nextPage = $page_array[count( $page_array ) - 2];
    $page_array = array_slice( $page_array, 0, -1 );
    $pageList = implode( ":", $page_array );
}


$ActionValue = "process";

if ( !( $form->id() > 0 ) )
{
    eZHTTPTool::header( "Location: /" );
}

$errorMessages = array();

// init the section
if ( isSet( $SectionIDOverride ) )
{
    include_once( "ezsitemanager/classes/ezsection.php" );
    
    $sectionObject =& eZSection::globalSectionObject( $SectionIDOverride );
    $sectionObject->setOverrideVariables();
}

$renderer->setPage( $nextPage );
$output =& $renderer->renderForm( $form );
$t->set_var( "form", $output );

if ( isSet( $OK ) )
{
    $output =& $renderer->verifyPage( $currentPage );
    if ( $output == "" )
    {
        $renderer->storePage( $currentPage );
        if ( $form->receiver() != "" )
        {
            $renderer->sendForm();
        }
        
        if ( $form->useDatabaseStorage() )
        {
            $form->setActiveResult();
        }
        else
        {
            $form->deleteResult();
        }
        if ( $form->completedPage() != "" )
        {
            eZHTTPTool::header( "Location: " . $form->completedPage() );
        }
    }
    else
    {
        $t->set_var( "error", $output );
        $formElements = $form->formElements();
        foreach ( $formElements as $element )
        {
            
        }
    }
}

$t->pparse( "output", "form_view_page_tpl" );

?>
