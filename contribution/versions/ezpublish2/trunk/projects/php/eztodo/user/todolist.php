<?
// $Id: todolist.php,v 1.5 2001/01/16 13:47:07 ce Exp $
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

$iniLanguage = new INIFile( "eztodo/user/intl/$Language/todolist.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );

$user = eZUser::currentUser();
if ( $user == false )
{
    Header( "Location: /" );
    exit();
}

$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl/", $Language, "todolist.php" );
$t->setAllStrings();
$t->set_file( array(
    "todo_list_page" => "todolist.tpl"
    ) );

$t->set_block( "todo_list_page", "todo_item_tpl", "todo_item" );
$t->set_block( "todo_list_page", "user_item_tpl", "user_item" );
$t->set_block( "todo_list_page", "no_found_tpl", "no_found" );


$todo = new eZTodo();

// Check if the user want its own todos or the public todos. This needs the "view-others-todo permission".
$currentUserID = $user->id();
if ( eZPermission::checkPermission( $user, "eZTodo", "ViewOtherUsers" ) )
{
    if ( $GetByUserID != "" )
    {
        if ( $GetByUserID == $currentUserID )
        {
            $todo_array = $todo->getByUserID( $currentUserID );
        }
        else
        {
            $todo_array = $todo->getByOthers( $GetByUserID );
        }
    }
    else
    {
        $todo_array = $todo->getByUserID( $currentUserID );
    }
}
else
{
    $todo_array = $todo->getByUserID( $currentUserID );
}


    
// User selector.
$user = new eZUser();
$userList = $user->getAll();

foreach( $userList as $userItem )
{
    if ( !isset( $GetByUserID ) )
    {
        $GetByUserID = $currentUserID;
    }
    $t->set_var( "user_id", $userItem->id() );
    $t->set_var( "user_firstname", $userItem->firstName() );
    $t->set_var( "user_lastname", $userItem->lastName() );

    if ( $GetByUserID == $user->id() )
    {
            
        if ( $user->id() == $userItem->id() )
        {
            $t->set_var( "user_is_selected", "selected" );
        }
        else
        {
            $t->set_var( "user_is_selected", "" );
        }
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
    }
    $t->parse( "user_item", "user_item_tpl", true );
}
    
// Todo list
if ( count( $todo_array ) == 0 )
{
    $t->set_var( "todo_item", "" );
    $t->parse( "no_found", "no_found_tpl" );
}

$locale = new eZLocale( $Language );

$i=0;
foreach( $todo_array as $todoItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
    
    $t->set_var( "todo_id", $todoItem->id() );
    $t->set_var( "todo_name", $todoItem->name() );
    $t->set_var( "todo_description", $todoItem->description() );
    $cat = new eZCategory( $todoItem->categoryID() );
    $t->set_var( "todo_category_id", $cat->name() );
    $pri = new eZPriority( $todoItem->priorityID() );
    $t->set_var( "todo_priority_id", $pri->name() );
    $t->set_var( "todo_userid", $todoItem->userID() );

    if ( $todoItem->permission() == "Public" )
    {
        $t->set_var( "todo_permission", $iniLanguage->read_var( "strings", "public" ) );    
    }
    else
    {
        $t->set_var( "todo_permission", $iniLanguage->read_var( "strings", "private" ) );    
    }
    
    $t->set_var( "todo_date", $locale->format( $todoItem->date() ) );

    if ( $todoItem->status() == true )
    {

        $t->set_var( "todo_status", $iniLanguage->read_var( "strings", "completed" ) );
    }
    else
    {
        $t->set_var( "todo_status", $iniLanguage->read_var( "strings", "not_completed" ) );
    }



    $t->set_var( "no_found", "" );

    $t->parse( "todo_item", "todo_item_tpl", true );
    $i++;
}

$t->pparse( "output", "todo_list_page" );

?>

