<?php
/*!
    $Id: user3.php,v 1.1 2000/08/16 11:32:15 bf-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: 
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

function goBack( $message )
{
    global $t, $ezpublish_add, $ezpublish_edit, $ezpublish_editall, $ezlink_add, $ezlink_edit, $ezlink_delete, $grantuser, $preferences, $name, $username, $id;
    $t->set_file( "main", "userform.tpl" );
        
    if( $ezpublish_add == "on" )
        $t->set_var( "ezpublish_add", "checked" );
    else
        $t->set_var( "ezpublish_add", "" );

    if( $ezpublish_edit == "on" )
        $t->set_var( "ezpublish_edit", "checked" );
    else
        $t->set_var( "ezpublish_edit", "" );
        
    if( $ezpublish_preferences == "on" )
        $t->set_var( "ezpublish_preferences", "checked" );
    else
        $t->set_var( "ezpublish_preferences", "" );
        
    if( $ezlink_add == "on" )
        $t->set_var( "ezlink_add", "checked" );
    else
        $t->set_var( "ezlink_add", "" );

    if( $ezlink_edit == "on" )
        $t->set_var( "ezlink_edit", "checked" );
    else
        $t->set_var( "ezlink_edit", "" );
        
    if( $ezlink_delete == "on" )
        $t->set_var( "ezlink_delete", "checked" );
    else
        $t->set_var( "ezlink_delete", "" );
        
    if( $grantuser == "on" )
        $t->set_var( "grantuser", "checked" );
    else
        $t->set_var( "grantuser", "" );

    $t->set_var( "name", $name );
    $t->set_var( "username", $username );
    $t->set_var( "email", $email );
    $t->set_var( "id" , $id );
    print( "<font color=red>$message</font><br>" );
    $t->pparse( "output", "main" );
}

$t = new Template( "templates/" );

// kontrollerer passord 
if( $password1 != $password2 )
{
    goBack( "Du skrev ikke to like passord, prøv igjen!" );
    exit();
}

// sjekk om alle felter er fylt ut
if( $name == "" || $username == "" )
{
    goBack( "Du har ikke fylt ut alle feltene!" );
    exit();
}

if( $id == 0 && $password1 == "" )
{
    goBack( "Du må velge et passord for brukeren!" );
    exit();
}

// setter databaseverdier
if( $ezpublish_add == "on" )
{
    $ezpublish_add = "Y";
}
else
{
    $ezpublish_add = "N";
}

if( $ezpublish_edit == "on" )
{
    $ezpublish_edit = "Y";
}
else
{
    $ezpublish_edit = "N";
}

if( $ezpublish_editall == "on" )
{
    $ezpublish_editall = "Y";
}
else
{
    $ezpublish_editall = "N";
}

if( $ezpublish_preferences == "on" )
{
    $ezpublish_preferences = "Y";
}
else
{
    $ezpublish_preferences = "N";
}

if( $ezlink_add == "on" )
{
    $ezlink_add = "Y";
}
else
{
    $ezlink_add = "N";
}

if( $ezlink_edit == "on" )
{
    $ezlink_edit = "Y";
}
else
{
    $ezlink_edit = "N";
}

if( $ezlink_delete == "on" )
{
    $ezlink_delete = "Y";
}
else
{
    $ezlink_delete = "N";
}

if( $grantuser == "on" )
{
    $grantuser = "Y";
}
else
{
    $grantuser = "N";
}

// sjekk om brukeren kan endre brukere
include_once( "../classes/ezusergroup.php" );

$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 1 )
{
    include( "index.php" );
    exit();
}


if( !eZUserGroup::verifyCommand( $session->userID(), "GrantUser" ) )
{
    print( $User["GrantUser"] );
    print( "Du har ikke rettigheter til å gjøre dette<br>" );
    print( "<a href=index.php>Tilbake</a>" );
    exit

function goBack( $message )
{
    global $t, $ezpublish_add, $ezpublish_edit, $ezpublish_editall, $ezlink_add, $ezlink_edit, $ezlink_delete, $grantuser, $preferences, $name, $username, $id;
    $t->set_file( "main", "userform.tpl" );
        
    if( $ezpublish_add == "on" )
        $t->set_var( "ezpublish_add", "checked" );
    else
        $t->set_var( "ezpublish_add", "" );

    if( $ezpublish_edit == "on" )
        $t->set_var( "ezpublish_edit", "checked" );
    else
        $t->set_var( "ezpublish_edit", "" );
        
    if( $ezpublish_preferences == "on" )
        $t->set_var( "ezpublish_preferences", "checked" );
    else
        $t->set_var( "ezpublish_preferences", "" );
        
    if( $ezlink_add == "on" )
        $t->set_var( "ezlink_add", "checked" );
    else
        $t->set_var( "ezlink_add", "" );

    if( $ezlink_edit == "on" )
        $t->set_var( "ezlink_edit", "checked" );
    else
        $t->set_var( "ezlink_edit"();
}

// sjekk om det ekisterer noen med dette brukernavnet fra før
array_query( $other, "SELECT * FROM AdminTable WHERE Username='$username'" );
// er det noen i databasen med samme brukernavn?
if( count( $other ) != 0 )
{
    // hvis ja, er det noen andre enn meg?
    if( $other[0]["Id"] != $id )
    {
        print( "id: $id - other: " . $other[0]["Id"] );
        goBack( "Det eksisterer allerede noen med dette brukernavnet!" );
        exit();
    }
}

// setter inn ny bruker
if( $id == 0 )
{
    mysql_query( "INSERT INTO AdminTable SET Name='$name', Username='$username', Password=PASSWORD('$password1'), eZPublish_Add='$ezpublish_add', eZPublish_Edit='$ezpublish_edit', eZPublish_EditAll='$ezpublish_editall', eZLink_Add='$ezlink_add', eZLink_Edit='$ezlink_edit', eZLink_Delete='$ezlink_delete', GrantUser='$grantuser', eZPublish_Preferences='$ezpublish_preferences', Email='$email'" ) or die( "Feil ved insetting i database!" );
}
// oppdaterer bruker
else
{
    // oppdater uten nytt passord
    if( $password1 == "" )
    {
        mysql_query( "UPDATE AdminTable SET Name='$name', Username='$username', eZPublish_Add='$ezpublish_add', eZPublish_Edit='$ezpublish_edit', eZPublish_EditAll='$ezpublish_editall', eZLink_Add='$ezlink_add', eZLink_Edit='$ezlink_edit', eZLink_Delete='$ezlink_delete', GrantUser='$grantuser', eZPublish_Preferences='$ezpublish_preferences', Email='$email' WHERE Id='$id'" ) or die( "Feil ved oppdatering i database!" );
    }
    // oppdater med nytt passord
    else
    {
    mysql_query( "UPDATE AdminTable SET Name='$name', Username='$username', Password=PASSWORD('$password1'), eZPublish_Add='$ezpublish_add', eZPublish_Edit='$ezpublish_edit', eZPublish_EditAll='$ezpublish_editall', eZLink_Add='$ezlink_add', eZLink_Edit='$ezlink_edit', eZLink_Delete='$ezlink_delete', GrantUser='$grantuser', eZPublish_Preferences='$ezpublish_preferences', Email='$email' WHERE Id='$id'" ) or die( "Feil ved oppdatering i database!" );
    }
}

// gå til index siden
Header( "Location: index.php?page=user.php" );

?>
