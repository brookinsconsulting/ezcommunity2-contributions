<?
// $Id: todomenulist.php,v 1.2 2001/01/15 14:57:27 ce Exp $
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

include_once( "ezuser/classes/ezuser.php" );

include_once( "eztodo/classes/eztodo.php" );

$user = eZUser::currentUser();

$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl/", $Language, "todomenulist.php" );
$t->setAllStrings();
$t->set_file( array(
    "todo_list_page" => "todomenulist.tpl"
    ) );

$t->set_block( "todo_list_page", "todo_item_tpl", "todo_item" );
$t->set_block( "todo_list_page", "no_found_tpl", "no_found" );

$todo = new eZTodo();

$todo_array =& $todo->getByLimit( $user->id(), 5, 0 );

$i=0;
foreach( $todo_array as $todoItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
    
    $t->set_var( "todo_id", $todoItem->id() );
    $t->set_var( "todo_name", $todoItem->name() );

    $t->set_var( "no_found", "" );

    $t->parse( "todo_item", "todo_item_tpl", true );
    $i++;
}

$t->pparse( "output", "todo_list_page" );

?>

