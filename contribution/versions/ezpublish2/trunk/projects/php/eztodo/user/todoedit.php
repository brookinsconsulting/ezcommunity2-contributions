<?
// $Id: todoedit.php,v 1.10 2001/01/16 15:59:54 ce Exp $
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

if ( isSet ( $Delete ) )
{
    $Action = "delete";
}
if ( isSet ( $List ) )
{
    Header( "Location: /todo" );
    exit();
}
if ( isSet ( $Edit ) )
{
    $Action = "edit";
}
if ( isSet ( $Done ) )
{
    $Action = "updateStatus";
    $Status = "on";
}

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );

$iniLanguage = new INIFile( "eztodo/user/intl/" . $Language . "/todoedit.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdatetime.php" );
include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );
include_once( "classes/ezmail.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezusergroup.php" );

$locale = new eZLocale( $Language );

$user = eZUser::currentUser();

// Setup template.
$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl", $Language, "todoedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "todo_edit_page" => "todoedit.tpl",
    ) );

$t->set_block( "todo_edit_page", "category_select_tpl", "category_select" );
$t->set_block( "todo_edit_page", "priority_select_tpl", "priority_select" );
$t->set_block( "todo_edit_page", "user_item_tpl", "user_item" );

$t->set_block( "todo_edit_page", "errors_tpl", "errors" );
$t->set_var( "errors", "&nbsp;" );


$error = false;
$nameCheck = true;
$permissionCheck = true;
$descriptionCheck = false;
$userCheck = true;

$t->set_block( "errors_tpl", "error_name_tpl", "error_name" );
$t->set_var( "error_name", "&nbsp;" );

$t->set_block( "errors_tpl", "error_description_tpl", "error_description" );
$t->set_var( "error_description", "&nbsp;" );

$t->set_block( "errors_tpl", "error_permission_tpl", "error_permission" );
$t->set_var( "error_permission", "&nbsp;" );

$t->set_block( "errors_tpl", "error_user_tpl", "error_user" );
$t->set_var( "error_user", "&nbsp;" );


if ( ( $userCheck ) && ( $Action == "update" ) || ( $Action == "updateStatus" ) )
{
    $todo = new eZTodo( $TodoID );
    
    if ( ( $todo->userID() == $user->id() ) || ( $todo->ownerID() == $user->id() ) )
    {
    }
    else
    {
        $t->parse( "error_user", "error_user_tpl" );
        $error = true;
    }
}


if ( $Action == "insert" || $Action == "update" )
{
    if ( $nameCheck )
    {
        if ( empty ( $Name ) )
        {
            $t->parse( "error_name", "error_name_tpl" );
            $error = true;
        }
    }
    if ( $descriptionCheck )
    {
        if ( empty ( $Description ) )
        {
            $t->parse( "error_description", "error_description_tpl" );
            $error = true;
        }
    }
    if ( $user->id() != $UserID )
    {
        if ( eZPermission::checkPermission( $user, "eZTodo", "AddOthers" ) == false )
        {
            $t->parse( "error_permission", "error_permission_tpl" );
            $error = true;
        }
    }
}

if ( $error )
{
    $t->parse( "errors", "errors_tpl" );
}

// Save a todo in the database.
if ( $Action == "insert" && $error == false )
{
    $todo = new eZTodo();
    $GLOBALS["DEBUG"] = true;
    $todo->setName( $Name );
    $todo->setDescription( $Description );
    $todo->setCategoryID( $CategoryID );
    $todo->setPriorityID( $PriorityID );
    $todo->setUserID( $UserID );
    $todo->setOwnerID( $user->id() );
    $date = new eZDateTime();

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
        $todo->setStatus( true );
    }
    else
    {
        $todo->setStatus( false );
    }

    $todo->store();

    if ( $SendMail == "on" )
    {
        $category = new eZCategory( $CategoryID );
        $priority = new eZPriority( $PriorityID );
        $owner = new eZUser( $user->id() );
        $user = new eZUser( $UserID );

        $iniName = $iniLanguage->read_var( "strings", "mail_name" );
        $iniCategory = $iniLanguage->read_var( "strings", "mail_priority" );
        $iniPriority = $iniLanguage->read_var( "strings", "mail_category" );
        $iniIsPublic = $iniLanguage->read_var( "strings", "mail_is_public" );
        $iniOwner = $iniLanguage->read_var( "strings", "mail_owner" );

        $mail = new eZMail();

        $body = ( $iniName . ": " . $Name . "\n" );
        $body .= ( $iniCategory . ": " . $category->name() . "\n" );
        $body .= ( $iniPriority . ": " . $priority->name() . "\n" );
        $body .= ( $iniIsPublic . ": " . $todo->permission() . "\n" );
        $body .= ( $iniOwner . ": " . ( $owner->firstName() . " " . $owner->lastName() ) . "\n" );
        $body .= "-------------\n";
        $body .= ( $Description );

        $mail->setSubject( "Todo: " . $Name );
        $mail->setFrom( $owner->email() );
        $mail->setTo( $user->email() );
        $mail->setBody( $body );

        $mail->send();
    }
    
    Header( "Location: /todo/todolist" );
    exit();
}

if ( $Action == "updateStatus" && $error == false )
{
    $todo = new eZTodo( $TodoID );
    if ( $Status == "on" )
    {
        $todo->setStatus( true );
    }
    else
    {
        $todo->setStatus( false );
    }
    $todo->store();

    Header( "Location: /todo/todolist" );
    exit();
}

// Update a todo in the database.
if ( $Action == "update" && $error == false )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    if ( $todo->status() == false )
    {
        $sendMail = true;
    }
    
    $todo->setName( $Name );
    $todo->setDescription( $Description );
    $todo->setCategoryID( $CategoryID );
    $todo->setPriorityID( $PriorityID );
    $todo->setDue( "" );
    $todo->setUserID( $UserID );
    if ( $Status == "on" )
    {
        $todo->setStatus( true );
    }
    else
    {
        $todo->setStatus( false );
    }
    if ( $Permission == "on" )
    {
        $todo->setPermission( "Public" );
    }
    else
    {
        $todo->setPermission( "Private" );
    }
    $todo->store();

    if ( ( $sendMail == true ) && ( $todo->status() == true ) && ( $todo->userID() != $todo->ownerID() ) )
    {
        $mail = new eZMail();
        $owner = new eZUser( $todo->ownerID() );
        $user = new eZUser( $todo->userID() );

        $body = $iniLanguage->read_var( "strings", "mail_completed" );

        $mail->setSubject( $iniLanguage->read_var( "strings", "subject_completed" ) . "Todo: " . $Name );
        $mail->setFrom( $user->email() );
        $mail->setTo( $owner->email() );
        $mail->setBody( $body );

        $mail->send();
    }

    Header( "Location: /todo/todolist/" );
    exit();
}

// Delete a todo in the database.
if ( $Action == "delete" )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    $todo->delete();
    Header( "Location: /todo/todolist/" );
    exit();
}

if ( $Action == "new" || $error )
{
    $action_value = "insert";
    $name = "";
    $description = "";
    $year = "";
    $mnd = "";
    $day = "";
    $hour = "";
    $min = "";
}

// default user
$UserID = $user->id();
$OwnerID = $user->id();

$datetime = new eZDateTime();

if ( $Action == "new" || $error )
{
    $t->set_var( "current_date", $locale->format( $datetime ) );
    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
    
}

// Edit a todo.
if ( $Action == "edit" )
{
    // Return the current time
    
    $todo = new eZTodo();
    $todo->get( $TodoID );

    if ( $todo->status() == true )
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
//    $todo->due(  $Year . $Mnd . $Hour );
    $name = $todo->name();
    $description = $todo->description();
    $categoryID = $todo->categoryID();
    $priorityID = $todo->priorityID();
    $userid = $todo->userID();
    $ownerid = $todo->ownerID();

    // Get the owner
    $owner = new eZUser( $todo->ownerID() );
    $t->set_var( "first_name", $owner->firstName() );
    $t->set_var( "last_name", $owner->lastName() );
    
    $headline = "Rediger todo";
    $submit_description = "Rediger";

    $t->set_var( "todo_id", $TodoID );
    $action_value = "update";

    $PriorityID = $todo->priorityID();
    $CategoryID = $todo->categoryID();
    $UserID = $todo->userID();
}

// Category selector.
$category = new eZCategory();
$category_array = $category->getAll();

for( $i=0; $i<count( $category_array ); $i++ )
{
    $t->set_var( "category_id", $category_array[$i]->id() );
    $t->set_var( "category_name", $category_array[$i]->name() );

    if ( $categoryID == $category_array[$i]->id() )
    {
         $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "category_select", "category_select_tpl", true );
}

// Priority selector.
$priority = new eZPriority();
$priority_array = $priority->getAll();

for( $i=0; $i<count( $priority_array ); $i++ )
{
    $t->set_var( "priority_id", $priority_array[$i]->id() );
    $t->set_var( "priority_name", $priority_array[$i]->name() );
    
    if ( $PriorityID == $priority_array[$i]->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "priority_select", "priority_select_tpl", true );
}

// User selector.

$user = new eZUser();
$user_array = $user->getAll();

foreach( $user_array as $userItem )
{
    $t->set_var( "user_id", $userItem->id() );
    $t->set_var( "user_firstname", $userItem->firstName() );
    $t->set_var( "user_lastname", $userItem->lastName() );

    // User select
    if ( $UserID == $userItem->id() )
    {
        $t->set_var( "user_is_selected", "selected" );
    }
    else
    {
        $t->set_var( "user_is_selected", "" );
    }

    $t->parse( "user_item", "user_item_tpl", true );
}

// Template variables.

$t->set_var( "todo_id", $TodoID );
$t->set_var( "submit_description", $submit_description );
$t->set_var( "head_line", $headline );
$t->set_var( "name", $name );
$t->set_var( "description", $description );
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

$t->pparse( "output", "todo_edit_page" );

?>
