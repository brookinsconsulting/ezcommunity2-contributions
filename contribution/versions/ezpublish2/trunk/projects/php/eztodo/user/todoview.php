<?
// $Id: todoview.php,v 1.5 2001/04/04 11:59:46 wojciechp Exp $
//
// Definition of todo list.
//
// <real-name> <<mail-name>>
// Created on: <04-Sep-2000 16:53:15 ce>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );

$lanugageIni = new INIFile( "eztodo/user/intl/" . $Language . "/todoview.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );
include_once( "eztodo/classes/ezstatus.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdatetime.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );

$locale = new eZLocale( $Language );


// Setup template.
$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl", $Language, "todoview.php" );
$t->setAllStrings();

$t->set_file( array(
    "todo_edit_page" => "todoview.tpl",
    ) );

$t->set_block( "todo_edit_page", "category_select_tpl", "category_select" );
$t->set_block( "todo_edit_page", "priority_select_tpl", "priority_select" );
$t->set_block( "todo_edit_page", "status_select_tpl", "status_select" );
//$t->set_block( "todo_edit_page", "mark_as_done", "mark_done" );
$t->set_block( "todo_edit_page", "user_item_tpl", "user_item" );

$t->set_block( "todo_edit_page", "errors_tpl", "errors" );
$t->set_var( "errors", "&nbsp;" );

$todo = new eZTodo();
$todo->get( $TodoID );


if ( $todo->permission() == "Public" )
{
    $t->set_var( "todo_permission", $lanugageIni->read_var( "strings", "public" ) );
}
else
{
    $t->set_var( "todo_permission", $lanugageIni->read_var( "strings", "private" ) );
}

$t->set_var( "todo_name", $todo->name() );
$t->set_var( "todo_description", $todo->description() );
$t->set_var( "todo_id", $todo->id() );


$owner = new eZUser( $todo->ownerID() );
$t->set_var( "first_name", $owner->firstName() );
$t->set_var( "last_name", $owner->lastName() );

$category = new eZCategory();
$category_array =& $category->getAll();

for( $i=0; $i<count( $category_array ); $i++ )
{
    if ( $todo->categoryID() == $category_array[$i]->id() )
    {
        $t->set_var( "todo_category", $category_array[$i]->name() );
    }
    else
    {
        $t->set_var( "todo_category", "" );
    }
    $t->parse( "category_select", "category_select_tpl", true );
}

$priority = new eZPriority();
$priority_array =& $priority->getAll();

for( $i=0; $i<count( $priority_array ); $i++ )
{
    if ( $todo->priorityID() == $priority_array[$i]->id() )
    {
        $t->set_var( "todo_priority", $priority_array[$i]->name() );
    }
    else
    {
        $t->set_var( "todo_priority", "" );
    }
    $t->parse( "priority_select", "priority_select_tpl", true );
}

$status = new eZStatus();
$status_array =& $status->getAll();

for( $i=0; $i<count( $status_array ); $i++ )
{
    if ( $todo->statusID() == $status_array[$i]->id() )
    {
        $t->set_var( "todo_status", $status_array[$i]->name() );
    }
    else
    {
        $t->set_var( "todo_status", "" );
    }
    $t->parse( "status_select", "status_select_tpl", true );
}

$user = new eZUser( $todo->userID() );

$t->set_var( "user_id", $user->id() );
$t->set_var( "user_firstname", $user->firstName() );
$t->set_var( "user_lastname", $user->lastName() );



$t->pparse( "output", "todo_edit_page" );

?>
