<?
// 
// $Id: bugedit.php,v 1.5 2000/12/03 18:37:28 bf-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <28-Nov-2000 19:45:35 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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
include_once( "classes/ezlog.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/eztexttool.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "ezbug/classes/ezbug.php" );
include_once( "ezbug/classes/ezbugcategory.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezbug/classes/ezbugpriority.php" );
include_once( "ezbug/classes/ezbugstatus.php" );
include_once( "ezbug/classes/ezbuglog.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "TemplateDir" ),
                     "ezbug/admin/intl", $Language, "bugedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "bug_edit_tpl" => "bugedit.tpl"
    ) );

$t->set_block( "bug_edit_tpl", "module_item_tpl", "module_item" );
$t->set_block( "bug_edit_tpl", "category_item_tpl", "category_item" );
$t->set_block( "bug_edit_tpl", "priority_item_tpl", "priority_item" );
$t->set_block( "bug_edit_tpl", "status_item_tpl", "status_item" );

$t->set_block( "bug_edit_tpl", "log_item_tpl", "log_item" );

if ( $Action == "Insert" )
{
    $user = eZUser::currentUser();

    if ( $user )
    {
        $category = new eZBugCategory( $CategoryID );
        $module = new eZBugModule( $ModuleID );
        
        $bug = new eZBug();
        $bug->setName( $Name );
        $bug->setDescription( $Description );
        $bug->setUser( $user );
        $bug->setIsHandled( false );
        if ( $IsClosed == 'on' )
            $bug->setIsClosed( true );
        else
            $bug->setIsClosed( false );
            
        $bug->store();
        Header( "Location: /bug/archive/" );
        exit();
    }
}

if ( $Action == "Update" )
{
    $user = eZUser::currentUser();

    if ( $user )
    {
        if ( isset( $Update ) )
        {        
            $category = new eZBugCategory( $CategoryID );
            $module = new eZBugModule( $ModuleID );
            
            $priority = new eZBugPriority( $PriorityID );
            $status = new eZBugStatus( $StatusID );
            
            $bug = new eZBug( $BugID );
            
            $bug->setUser( $user );
            $bug->setIsHandled( true );
            
            $bug->setPriority( $priority );
            $bug->setStatus( $status );
            
            if ( $IsClosed == 'on' )
                $bug->setIsClosed( true );
            else
                $bug->setIsClosed( false );

            $bug->removeFromModules();
            $bug->removeFromCategories();
            $bug->store();
            

            $category->addBug( $bug );
            $module->addBug( $bug );

            $log = new eZBugLog();
            $log->setDescription( $LogMessage );
            $log->setUser( $user );
            $log->setBug( $bug );
            $log->store();
            
            $Action = "Edit";            
        }
        else
        {
            Header( "Location: /bug/archive/" );
            exit();
            
        }
    }
}

$category = new eZBugCategory();
$module = new eZBugModule();
$priority = new eZBugPriority();
$status = new eZBugStatus();

// list the categories
$categories = $category->getAll();
foreach ( $categories as $category )
{
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );

    $t->parse( "category_item", "category_item_tpl", true );
}

// list the modules
$modules = $module->getAll();
foreach ( $modules as $module )
{
    $t->set_var( "module_id", $module->id() );
    $t->set_var( "module_name", $module->name() );

    $t->parse( "module_item", "module_item_tpl", true );
}

// list the priorities
$priorities = $priority->getAll();
foreach ( $priorities as $priority )
{
    $t->set_var( "priority_id", $priority->id() );
    $t->set_var( "priority_name", $priority->name() );

    $t->parse( "priority_item", "priority_item_tpl", true );
}

// list the statuses
$statuses = $status->getAll();
foreach ( $statuses as $status )
{
    $t->set_var( "status_id", $status->id() );
    $t->set_var( "status_name", $status->name() );

    $t->parse( "status_item", "status_item_tpl", true );
}

$t->set_var( "action_value", "Insert" );

if ( $Action == "Edit" )
{
    $locale = new eZLocale( $Language );

    $bug = new eZBug( $BugID );
    
    $t->set_var( "bug_id", $bug->id() );
    $t->set_var( "name_value", $bug->name() );
    $t->set_var( "description_value", eZTextTool::nl2br( $bug->description() ) );
    $t->set_var( "action_value", "Update" );

    $bugLog = new eZBugLog();
    $logList = $bugLog->getByBug( $bug );

    foreach ( $logList as $log )
    {
        $date =& $log->created();
        
        $t->set_var( "log_date", $locale->format( $date ) );
        
        
        $t->set_var( "log_description", $log->description() );
        
        $t->parse( "log_item", "log_item_tpl", true );
    }
}

$t->pparse( "output", "bug_edit_tpl" );

?>
