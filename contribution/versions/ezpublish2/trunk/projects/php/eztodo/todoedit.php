<?
// $Id: todoedit.php,v 1.4 2000/09/14 12:57:26 ce-cvs Exp $
//
// Definition of todo list.
//
// <real-name> <<mail-name>>
// Created on: <04-Sep-2000 16:53:15 ce>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTodoMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezusergroup.php" );
include_once( "classes/ezregional.php" );
include_once( "classes/ezdatetime.php" );
include_once( "common/ezphputils.php" );
include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );
include_once( "classes/ezlocale.php" );

$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 1 )
{
    print( "ER IKKE LOGGGT INN!!!!!!!!" );
}

// Save a todo in the database.
if ( $Action == "insert" )
{
    $todo = new eZTodo();

    $todo->setTitle( $Title );
    $todo->setText( $Text );
    $todo->setCategoryID( $CategoryID );
    $todo->setPriorityID( $PriorityID );
    $todo->setUserID( $UserID );
    $todo->setOwnerID( $OwnerID );
    $date = new eZDateTime();
    $todo->setDate( $date->mySQLDateTime()  );

    if ( $Permission == "on" )
    {
        $todo->setPermission( "Public" );
    }
    else
    {
        $todo->setPermission( "Private" );
    }
    if ( $Status == "on" )
    {
        $todo->setStatus( $Status = "Y" );
    }
    else
    {
        $todo->setStatus( $Status = "N" );
    }

    $Due = ( $Year . "-" . $Mnd  . "-" . $Day . " " .  $Hour . ":" . $Minute . ":00" );

    $todo->setDue( $Due );
//    $todo->setDue( $Year . $Mnd . $Hour );

    $todo->store();
    Header( "Location: index.php?page=" . $DOC_ROOT . "todolist.php" );
}

// Update a todo in the database.
if ( $Action == "update" )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    $todo->setTitle( $Title );
    $todo->setText( $Text );
    $todo->setCategoryID( $CategoryID );
    $todo->setPriorityID( $PriorityID );
    $todo->setDue( $Due );
    $todo->setUserID( $UserID );
    $todo->setOwnerID( $OwnerID );
    if ( $Status == "on" )
    {
        $todo->setStatus( "Y" );
    }
    else
    {
        $todo->setStatus( "N" );
    }
    if ( $Permission == "on" )
    {
        $todo->setPermission( "Public" );
    }
    else
    {
        $todo->setPermission( "Private" );
    }


    
    $todo->update();

    Header( "Location: index.php?page=" . $DOC_ROOT . "todolist.php" );
}

// Delete a todo in the database.
if ( $Action == "delete" )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    $todo->delete();

    Header( "Location: index.php?page=" . $DOC_ROOT . "todolist.php" );
}

// Mark a todo as done or undone.
if ( $Action == "done" )
{
    $todo = new eZTodo();
    if ( $Status == "N" )
    {
        $todo->get( $TodoID );
        $todo->setStatus( "Y" );
        $todo->update();
        Header( "Location: index.php?page=" . $DOC_ROOT . "todolist.php" );
    }
    if ( $Status == "Y" )
    {
        $todo->get( $TodoID );
        $todo->setStatus( "N" );
        $todo->update();
        Header( "Location: index.php?page=" . $DOC_ROOT . "todolist.php" );
    }
}

// Setup template.
$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "/intl", $Language, "todoedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "todoedit" => "todoedit.tpl",
    "category_selector" => "categoryselector.tpl",
    "priority_selector" => "priorityselector.tpl",
    "user_item" => "useritem.tpl",
    "owner_item" => "owneritem.tpl"
    ) );

// Template variables.
$initemplate = new INIFile( "./eztodo/intl/no_NO/todoedit.php.ini" );
$submit_text = $initemplate->read_var( "strings", "submitinsert" );
$headline = $initemplate->read_var( "strings", "headlineinsert" );
$action_value = "insert";
$title = "";
$text = "";
$year = "";
$mnd = "";
$day = "";
$hour = "";
$min = "";

// default user
$UserID = $session->userID();
$OwnerID = $session->userID();

// Edit a todo.
if ( $Action == "edit" )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    if ( $todo->status() == "Y" )
    {
        $Status = "checked";
    }
    else
    {
        $Status = "";
    }

    if ( $todo->permission() == "Public" )
    {
        $Permission = "checked";
    }
    else
    {
        $Permission = "";
    }
    $todo->due(  $Year . $Mnd . $Hour );
    $title = $todo->title();
    $text = $todo->text();
    $categoryID = $todo->categoryID();
    $priorityID = $todo->priorityID();
    $userid = $todo->userID();
    $ownerid = $todo->ownerID();
    
    $headline = "Rediger todo";
    $submit_text = "Rediger";

    $t->set_var( "todo_id", $TodoID );
    $action_value = "update";

    $PriorityID = $todo->priorityID();
    $CategoryID = $todo->categoryID();
    $UserID = $todo->userID();
    $OwnerID = $todo->ownerID();         
}

// Category selector.
$category = new eZCategory();
$category_array = $category->getAll();

for( $i=0; $i<count( $category_array ); $i++ )
{
    $t->set_var( "category_id", $category_array[ $i ]->id() );
    $t->set_var( "category_title", $category_array[ $i ]->title() );

    if ( $CategoryID == $category_array[ $i ][ "ID"] )
    {
         $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "category_select", "category_selector", true );
}

// Priority selector.
$priority = new eZPriority();
$priority_array = $priority->getAll();

for( $i=0; $i<count( $priority_array ); $i++ )
{
    $t->set_var( "priority_id", $priority_array[ $i ]->id() );
    $t->set_var( "priority_title", $priority_array[ $i ]->title() );
    
    if ( $PriorityID == $priority_array[ $i ][ "ID"] )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "priority_select", "priority_selector", true );
}

// User selector.

$user = new eZUser();
$user_array = $user->getAll();

for( $i=0; $i<count( $user_array ); $i++ )
{
    $t->set_var( "user_id", $user_array[ $i ][ "id" ] );
    $t->set_var( "user_firstname", $user_array[ $i ][ "first_name" ] );
    $t->set_var( "user_lastname", $user_array[ $i ][ "last_name" ] );

    // User select
    if ( $UserID == $user_array[ $i ][ "id"] )
    {
        $t->set_var( "user_is_selected", "selected" );
    }
    else
    {
        $t->set_var( "user_is_selected", "" );
    }

    $t->parse( "user_select", "user_item", true );
}

for( $i=0; $i<count( $user_array ); $i++ )
{
    $t->set_var( "user_id", $user_array[ $i ][ "id" ] );
    $t->set_var( "user_firstname", $user_array[ $i ][ "first_name" ] );
    $t->set_var( "user_lastname", $user_array[ $i ][ "last_name" ] );

    // Owner select
    if ( $OwnerID == $user_array[ $i ][ "id"] )
    {
        $t->set_var( "owner_is_selected", "selected" );
    }
    else
    {
        $t->set_var( "owner_is_selected", "" );
    }    

    $t->parse( "owner_select", "owner_item", true );
}


// Template variables.

$t->set_var( "submit_text", $submit_text );
$t->set_var( "head_line", $headline );
$t->set_var( "title", $title );
$t->set_var( "text", $text );
$t->set_var( "year", $year );
$t->set_var( "mnd", $mnd );
$t->set_var( "day", $day );
$t->set_var( "hour", $hour );
$t->set_var( "min", $min );
$t->set_var( "status", $Status );
$t->set_var( "permission", $Permission );

$t->set_var( "action_value", $action_value );
$t->set_var( "head_line", $headline );


$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "todoedit" );



?>
