<?php
/*!
    $Id: useredit.php,v 1.1 2000/08/16 11:32:15 bf-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: <15-Aug-2000 10:35:45 bf>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "../classes/ezusergroup.php" );
include_once( "../classes/ezuser.php" );

// javascript
include( "useredit.js" );

if ( $Action == "Update" )
{
    $user = new eZUser( $UserID );

    $user->setFirstName( $FirstName );
    $user->setLastName( $LastName );
    $user->setEmail( $EMail );
    $user->setGroupID( $UserGroup );

    if ( $Password1 != "secret" )
    {
        $user->setPassword( $Password1 );        
    }
    
    $user->update();
    
    Header( "Location: index.php?page=userlist.php" );
}

if ( $Action == "Insert" )
{
    $user = new eZUser( );

    $user->setFirstName( $FirstName );
    $user->setLastName( $LastName );
    $user->setEmail( $EMail );
    $user->setUserName( $UserName );

    $user->setPassword( $Password1 );
    
    $user->setGroupID( $UserGroup );

    $user->store();
    
    Header( "Location: index.php?page=userlist.php" );
}    

if ( $Action == "Delete" )
{
    $user = new eZUser( $UserID );
         
    $user->delete();
    
    Header( "Location: index.php?page=userlist.php" );    
}

$t = new Template( "templates/" );
$t->set_file( Array( "main" =>  "userform.tpl",
                     "element" => "user-choice.tpl" ) );

$group = new eZUserGroup();
$groups = $group->getAllGroups();

if( $Action == "New" )
{
    
    $t->set_var( "name", "" );
    $t->set_var( "username", "" );
    $t->set_var( "email", "" );

    $t->set_var( "selected", "" );

    $t->set_var( "first_name", "" );
    $t->set_var( "last_name", "" );
    $t->set_var( "nick_name", "" );
    $t->set_var( "email", "" );
    
    $t->set_var( "password", "" );    

    // gruppe liste
    for ( $i = 0; $i < count( $groups ); $i++ )
    {
        $t->set_var( "group_id", $groups[$i]["Id"] );
        $t->set_var( "caption", $groups[$i]["Name"] );
        
        $t->parse( "choices", "element", true );
    }
    $t->set_var( "action_value", "Insert" );    
}

if( $Action == "Edit" )
{
    $user = new eZUser();
    $user->get( $UserID );

    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
    $t->set_var( "nick_name", $user->nickName() );
    $t->set_var( "email", $user->email() );
    $t->set_var( "password", "secret" );

    $t->set_var( "user_id", $user->id() );
        
    for ( $i = 0; $i < count( $groups ); $i++ )
    {
        $t->set_var( "group_id", $groups[$i]["Id"] );
        $t->set_var( "caption", $groups[$i]["Name"] );
            
        if ( $groups[$i]["Id"] == $user->GroupID() )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
    
        $t->parse( "choices", "element", true );
    }

    $t->set_var( "action_value", "Update" );
}


$t->pparse( "output", "main" );

?>
