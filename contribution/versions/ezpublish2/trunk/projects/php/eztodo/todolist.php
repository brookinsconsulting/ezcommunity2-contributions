<?
// $Id: todolist.php,v 1.7 2000/10/03 16:00:57 ce-cvs Exp $
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
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlocale.php" );
include_once( "common/ezphputils.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );

include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );

$user = eZUser::currentUser();
if ( !$user )
{
    Header( "Location: /user/login" );
    exit();
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "intl/", $Language, "todolist.php" );
$t->setAllStrings();
$t->set_file( array(
    "todo_list" => "todolist.tpl",
    "todo_item" => "todoitem.tpl",
    "user_item" => "useritem.tpl"
    ) );

$user = new eZUser();
$user->get( $user->id() );
$UserID = $user->id();

$t->set_var( "user", $user->login() );


// Check if the user want its own todos or the public todos.
$todo = new eZTodo();

if ( $GetByUserID == $user->id() )
{
    $GetByUserID = $user->id();
    $todo_array = $todo->getByOwnerID( $GetByUserID );
    
}
else
{
    $todo_array = $todo->getByUserID( $GetByUserID );
}

// User selector.

$user = new eZUser();
$userList = $user->getAll();

foreach( $userList as $userItem )
{
    if ( !isset( $GetByUserID ) )
    {
        $GetByUserID = $user->id();
    }
    $t->set_var( "user_id", $userItem->id() );
    $t->set_var( "user_firstname", $userItem->firstName() );
    $t->set_var( "user_lastname", $userItem->lastName() );

    if ( $GetByUserID == $user->id() )
    {
        if ( $user->id() == $userItemr->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }

        $t->parse( "user_select", "user_item", true );
    }
    else
    {
        if ( $GetByUserID == $userItem->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }
        $t->parse( "user_select", "user_item", true );
    }
}

// Todo list
if ( count( $todo_array ) == 0 )
{
    $t->set_var( "todos", "Ingen todo'er funnet" );
}

$locale = new eZLocale();

foreach( $todo_array as $todoItem )
{
    $t->set_var( "todo_id", $todoItem->id() );
    $t->set_var( "todo_title", $todoItem->title() );
    $t->set_var( "todo_text", $todoItem->text() );
    $cat = new eZCategory( $todoItem->categoryID() );
    $t->set_var( "todo_category_id", $cat->title() );
    $pri = new eZPriority( $todoItem->priorityID() );
    $t->set_var( "todo_priority_id", $pri->title() );
    $t->set_var( "todo_due", $locale->format( $todoItem->due() ) );
    $t->set_var( "todo_userid", $todoItem->userID() );
    $t->set_var( "todo_permission", $todoItem->permission() );
    $t->set_var( "todo_date", $locale->format( $todoItem->date() ) );

    $t->set_var( "todo_status", $$todoItem->status() );

    $t->parse( "todos", "todo_item", true );
}


$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "todo_list" );

?>
