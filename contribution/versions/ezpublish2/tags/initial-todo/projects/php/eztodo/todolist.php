<?
// $Id: todolist.php,v 1.1 2000/09/07 07:12:25 ce-cvs Exp $
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

include_once( "classes/class.INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTodoMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezsession.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezusergroup.php" );
include_once( "common/ezphputils.php" );

include_once( "eztodo/classes/eztodo.php" );

$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 1 )
{
    print( "ER IKKE LOGGGT INN!!!!!!!!" );
}

    $t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "intl/", $Language, "todolist.php" );
$t->setAllStrings();

$t->set_file( array(
    "todo_list" => "todolist.tpl",
    "todo_item" => "todoitem.tpl"
    ) );


$user = new eZUser();
$user->get( $session->userID() );

$t->set_var( "user", $user->nickname() );

$todo = new eZTodo();
$todo_array = $todo->getByUser( $user->id() );
for ( $i=0; $i<count( $todo_array ); $i++ )
{
    $t->set_var( "todo_id", $todo_array[ $i ][ "ID" ] );
    $t->set_var( "todo_title", $todo_array[ $i ][ "Title" ] );
    $t->set_var( "todo_text", $todo_array[ $i ][ "Text" ] );
    $t->set_var( "todo_category", $todo_array[ $i ][ "Category" ] );
    $t->set_var( "todo_priority", $todo_array[ $i ][ "Priority" ] );
    $t->set_var( "todo_due", $todo_array[ $i ][ "Due" ] );
    $t->set_var( "todo_user", $todo_array[ $i ][ "User" ] );
    $t->set_var( "todo_permission", $todo_array[ $i ][ "Permission" ] );
    $t->set_var( "todo_date", $todo_array[ $i ][ "Date" ] );
    $t->set_var( "todo_status", $todo_array[ $i ][ "Status" ] );

    $t->parse( "todos", "todo_item", true );
}


$t->set_var( "document_root", $DOC_ROOT );

$t->pparse( "output", "todo_list" );

?>
