<?php

// $Id: todoinfo.php,v 1.2 2000/09/14 13:05:00 ce-cvs Exp $
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
include_once( "common/ezphputils.php" );
include_once( "classes/ezlocale.php" );

include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );
include_once( "eztodo/classes/ezpriority.php" );

$session = new eZSession();
//  if( $session->get( $AuthenticatedSession ) == 1 )
//  {
//      print( "ER IKKE LOGGGT INN!!!!!!!!" );
//  }

$t = new eZTemplate( $DOC_ROOT . "/" . $ini->read_var( "eZTodoMain", "TemplateDir" ), $DOC_ROOT . "intl/", $Language, "todoinfo.php" );
$t->setAllStrings();
$t->set_file( array(
    "todoinfo" => "todoinfo.tpl"
    ));

$todo = new eZTodo();
$todo->get( $TodoID );

$Title = $todo->title();
$Text = $todo->text();
$locale = new eZLocale( $Language );
$Due =  $locale->format( $todo->due() );
$Date = $locale->format( $todo->date() );

if ( $todo->status() == "N" )
{
    $Status = "No";
}
else
{
    $Status = "Yes";
}
if ( $todo->permission() == "Private" )
{
    $Permission = "Yes";
}
else
{
    $Permission = "No";
}

$user = new eZUser();
$user->get( $todo->userID() );
$UserName = ( $user->firstName() . " " .  $user->lastName() );

$owner = new eZUser();
$owner->get( $todo->ownerID() );
$OwnerName = ( $owner->firstName() . $owner->lastName() );


$pri = new eZPriority();
$pri->get( $todo->priorityID() );
$Priority = $pri->title();

$cat = new eZCategory();
$cat->get( $todo->categoryID() );
$Category = $cat->title();

$t->set_var( "title", $Title );
$t->set_var( "text", $Text );
$t->set_var( "due", $Due );
$t->set_var( "date", $Date );
$t->set_var( "status", $Status );
$t->set_var( "permission", $Permission );
$t->set_var( "user_name", $UserName );
$t->set_var( "owner_name", $OwnerName );
$t->set_var( "priority", $Priority );
$t->set_var( "category", $Category );

$t->pparse( "output", "todoinfo" );

?>
