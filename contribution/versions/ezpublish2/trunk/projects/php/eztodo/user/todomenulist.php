<?
// $Id: todomenulist.php,v 1.7 2001/04/04 11:59:46 wojciechp Exp $
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

//$ini = new INIFIle( "site.ini");
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTodoMain", "Language" );
$NotDoneID = $ini->read_var( "eZTodoMain", "NotDoneID" );

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
$t->set_block( "todo_list_page", "no_item_tpl", "no_item" );

$todo = new eZTodo();

if ( $user )
{
    $todo_array =& $todo->getByLimit( $user->id(), 5, $NotDoneID, 0 );
}

$i=0;
if ( count( $todo_array ) > 0 )
foreach( $todo_array as $todoItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bgdark" );
    else
        $t->set_var( "td_class", "bglight" );
    
    $t->set_var( "todo_id", $todoItem->id() );
    $t->set_var( "todo_name", $todoItem->name() );

    $t->set_var( "no_item", "" );

    $t->parse( "todo_item", "todo_item_tpl", true );
    $i++;
}

if ( count ( $todo_array ) == 0 ) 
{
    $t->set_var( "todo_item", "" );
    $t->parse( "no_item", "no_item_tpl" );
}

$t->pparse( "output", "todo_list_page" );

?>

