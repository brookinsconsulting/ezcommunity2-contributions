<?
// $Id: todoedit.php,v 1.18 2001/04/04 11:59:46 wojciechp Exp $
//
// Definition of todo list.
//
// <real-name> <<mail-name>>
// Created on: <04-Sep-2000 16:53:15 ce>
// Modified on: <28-Mar-2001 21:08:00> by: Wojciech potaczek <Wojciech@Potaczek.pl> for todo status handling
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//
include_once( "classes/ezhttptool.php" );
if ( isSet ( $Delete ) )
{
    $Action = "delete";
}
if ( isSet ( $List ) )
{
    eZHTTPTool::header( "Location: /todo" );
    exit();
}
if ( isSet ( $Edit ) )
{
    $Action = "edit";
}

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /todo" );
    exit();

}

include_once( "classes/INIFile.php" );

//$ini = new INIFIle( "site.ini" );
$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZTodoMain", "Language" );
$NotDoneID = $ini->read_var( "eZTodoMain", "NotDoneID" );

$iniLanguage = new INIFile( "eztodo/user/intl/" . $Language . "/todoedit.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdatetime.php" );
include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );
include_once( "eztodo/classes/ezstatus.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );
include_once( "classes/ezmail.php" );


include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezusergroup.php" );

$locale = new eZLocale( $Language );

$user = eZUser::currentUser();

if ( !$user )
{
    eZHTTPTool::header( "Location: /error/403/" );
    exit();
}

$CategoryID = eZHTTPTool::getVar( "CategoryID", true );
$PriorityID = eZHTTPTool::getVar( "PriorityID", true );
$UserID = eZHTTPTool::getVar( "UserID", true );
$Name = eZHTTPTool::getVar( "Name", true );
$Description = eZHTTPTool::getVar( "Description", true );
$StatusID = eZHTTPTool::getVar( "StatusID", true );



// Setup template.
$t = new eZTemplate( "eztodo/user/" . $ini->read_var( "eZTodoMain", "TemplateDir" ),
                     "eztodo/user/intl", $Language, "todoedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "todo_edit_page" => "todoedit.tpl",
    ) );

$t->set_block( "todo_edit_page", "category_select_tpl", "category_select" );
$t->set_block( "todo_edit_page", "priority_select_tpl", "priority_select" );
$t->set_block( "todo_edit_page", "status_select_tpl", "status_select" );
$t->set_block( "todo_edit_page", "user_item_tpl", "user_item" );

$t->set_block( "todo_edit_page", "errors_tpl", "errors" );
$t->set_var( "errors", "&nbsp;" );

$t->set_var( "name", "$Name" );
$t->set_var( "description", "$Description" );

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
    
    if ( ( $todo->userID() == $user->id() ) || ( $todo->ownerID() == $user->id() ) || ( eZPermission::checkPermission( $user, "eZTodo", "EditOthers" ) == true))
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
    $todo->setStatusID( $StatusID );
    $date = new eZDateTime();

    if ( $Permission == "on" )
    {
        $todo->setPermission( "Public" );
    }
    else
    {
        $todo->setPermission( "Private" );
    }

    $todo->store();

    if ( $SendMail == "on" )
    {
        $category = new eZCategory( $CategoryID );
        $priority = new eZPriority( $PriorityID );
	$status = new ezStatus ( $StatusID );
        $owner = new eZUser( $user->id() );
        $user = new eZUser( $UserID );

        $iniName = $iniLanguage->read_var( "strings", "mail_name" );
        $iniCategory = $iniLanguage->read_var( "strings", "mail_category" );
        $iniPriority = $iniLanguage->read_var( "strings", "mail_priority" );
        $iniStatus = $iniLanguage->read_var( "strings", "mail_status" );
	$iniIsPublic = $iniLanguage->read_var( "strings", "mail_is_public" );
        $iniOwner = $iniLanguage->read_var( "strings", "mail_owner" );

        $mail = new eZMail();

        $body = ( $iniName . ": " . $Name . "\n" );
        $body .= ( $iniCategory . ": " . $category->name() . "\n" );
        $body .= ( $iniPriority . ": " . $priority->name() . "\n" );
        $body .= ( $iniStatus . ": " . $status->name() . "\n" );
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
    
    eZHTTPTool::header( "Location: /todo/todolist" );
    exit();
}


// Update a todo in the database.
if ( $Action == "update" && $error == false )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    $oldstatus = $todo->statusID();
        
    $todo->setName( $Name );
    $todo->setDescription( $Description );
    $todo->setCategoryID( $CategoryID );
    $todo->setPriorityID( $PriorityID );
    $todo->setDue( "" );
    $todo->setUserID( $UserID );
    $todo->setStatusID( $StatusID );

    
    if ( $Permission == "on" )
    {
        $todo->setPermission( "Public" );
    }
    else
    {
        $todo->setPermission( "Private" );
    }
    $todo->store();

    if ( ( $sendMail == true ) && ( $oldstatus != $todo->statusID() ) && ( $todo->userID() != $todo->ownerID() ) )
    {
	$status = new eZStatus();  		 //need for status name in subject
	
        $mail = new eZMail();
        $owner = new eZUser( $todo->ownerID() );
        $user = new eZUser( $todo->userID() );

        $body = $iniLanguage->read_var( "strings", "mail_status_changed" );

        $mail->setSubject( $iniLanguage->read_var( "strings", "subject_status_changed" ) . "Todo: " . $Name . "Status: " . $status->name() );
        $mail->setFrom( $user->email() );
        $mail->setTo( $owner->email() );
        $mail->setBody( $body );

        $mail->send();
    }

    eZHTTPTool::header( "Location: /todo/todolist/" );
    exit();
}

// Delete a todo in the database.
if ( $Action == "delete" )
{
    $todo = new eZTodo();
    $todo->get( $TodoID );
    $todo->delete();
    eZHTTPTool::header( "Location: /todo/todolist/" );
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
    $t->set_var( "text", "" );
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
    $t->set_var( "todo_id", "" );
    $t->set_var( "action_value", "insert" );
    $userID = $user->id();
}

// Edit a todo.
if ( $Action == "edit" )
{
    // Return the current time
    
    $todo = new eZTodo( $TodoID );

    if ( $todo->status() == true )
    {
        $t->set_var( "status", "checked" );
    }
    else
    {
        $t->set_var( "status", "" );
    }

    if ( $todo->permission() == "Public" )
    {
        $t->set_var( "permission", "checked" );
    }
    else
    {
        $t->set_var( "permission", "" );
    }

    $t->set_var( "todo_id", $todo->id() );
    $t->set_var( "name", $todo->name() );
    $t->set_var( "description", $todo->description() );

    $categoryID = $todo->categoryID();
    $priorityID = $todo->priorityID();
    $userID = $todo->userID();
    $ownerID = $todo->ownerID();
    $statusID = $todo->statusID();
    
    // Get the owner
    $owner = new eZUser( $todo->ownerID() );
    $t->set_var( "first_name", $owner->firstName() );
    $t->set_var( "last_name", $owner->lastName() );
    
    $headline = "Rediger todo";
    $submit_description = "Rediger";

    $t->set_var( "action_value", "update" );
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
    
    if ( $priorityID == $priority_array[$i]->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "priority_select", "priority_select_tpl", true );
}

// Status selector
$status = new eZStatus();
$status_array = $status->getAll();

if ( $Action == "new")
    $statusID = 1;
    
for( $i=0; $i<count( $status_array ); $i++ )
{
    $t->set_var( "status_id", $status_array[$i]->id() );
    $t->set_var( "status_name", $status_array[$i]->name() );
    
    if ( $statusID == $status_array[$i]->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $t->parse( "status_select", "status_select_tpl", true );
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
    if ( $userID == $userItem->id() )
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

$t->pparse( "output", "todo_edit_page" );

?>
