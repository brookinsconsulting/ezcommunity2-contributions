<?
// $Id: todolist.php,v 1.4 2000/09/14 12:57:26 ce-cvs Exp $
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
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlocale.php" );
include_once( "common/ezphputils.php" );

include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );

$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 1 )
{
    print( "ER IKKE LOGGGT INN!!!!!!!!" );
}

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "intl/", $Language, "todolist.php" );
$t->setAllStrings();
$t->set_file( array(
    "todo_list" => "todolist.tpl",
    "todo_item" => "todoitem.tpl",
    "user_item" => "useritem.tpl"
    ) );

$user = new eZUser();
$user->get( $session->userID() );
$UserID = $session->userID();

$t->set_var( "user", $user->nickname() );


// Check if the user want its own todos or the public todos.
$todo = new eZTodo();

if ( $GetByUserID == $session->userID() )
{
    $GetByUserID = $session->userID();
    $todo_array = $todo->getByOwnerID( $GetByUserID );
}
else
{
    $todo_array = $todo->getByUserID( $GetByUserID );
}
// User selector.
$user = new eZUser();
$user_array = $user->getAll();
for( $i=0; $i<count( $user_array ); $i++ )
{
    if ( !isset( $GetByUserID ) )
    {
        $GetByUserID = $session->userID();
    }
    $t->set_var( "user_id", $user_array[ $i ][ "id" ] );
    $t->set_var( "user_firstname", $user_array[ $i ][ "first_name" ] );
    $t->set_var( "user_lastname", $user_array[ $i ][ "last_name" ] );

    // User select
    // if ( $GetByUserID == $user_array[ $i ][ "id"] )

    if ( $GetByUserID == $session->userID() )
    {
        if ( $session->userID() == $user_array[ $i ][ "id" ] )
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
        if ( $GetByUserID == $user_array[ $i ][ "id" ] )
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

for ( $i=0; $i<count( $todo_array ); $i++ )
{

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "bg_color", "#f0f0f0" );
    }
    else
    {
        $t->set_var( "bg_color", "#dcdcdc" );
    }  
    $t->set_var( "todo_id", $todo_array[ $i ]->id() );
    $t->set_var( "todo_title", $todo_array[ $i ]->title() );
    $t->set_var( "todo_text", $todo_array[ $i ]->text() );
    $cat = new eZCategory( $todo_array[ $i ]->categoryID() );
    $t->set_var( "todo_category_id", $cat->title() );
    $pri = new eZPriority( $todo_array[ $i ]->priorityID() );
    $t->set_var( "todo_priority_id", $pri->title() );
    $t->set_var( "todo_due", $locale->format( $todo_array[ $i ]->due() ) );
    $t->set_var( "todo_userid", $todo_array[ $i ]->userID() );
    $t->set_var( "todo_permission", $todo_array[ $i ]->permission() );
    $t->set_var( "todo_date", $locale->format( $todo_array[ $i ]->date() ) );

    $t->set_var( "todo_status", $todo_array[ $i ]->status() );

    $t->parse( "todos", "todo_item", true );
}

$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "todo_list" );

?>
