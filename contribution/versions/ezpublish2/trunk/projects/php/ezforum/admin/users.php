<?
/*!
    $Id: users.php,v 1.3 2000/09/01 13:29:00 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <28-Jul-2000 14:37:15 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "classes/template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$t = new Template( "$DOCROOT/admin/templates/" );

$t->set_file( Array("userlist" => "users.tpl",
                    "elements" => "users-elements.tpl",
                    "box" => "users-box.tpl"
                    )
              );

$t->set_var( "docroot", $DOCROOT);

if ( $modifyuser )
{
    $usr = new eZUser( $UserId );
    $usr->setNickName( $NickName );
    $usr->setFirstName( $FirstName );
    $usr->setLastName( $LastName );
    $usr->setEmail( $Email );
    $usr->store();
}

if ( $adduser )
{
    $usr = new eZUser;
    $usr->newUser();
    $usr->setNickName( $NickName );
    $usr->setFirstName( $FirstName );
    $usr->setLastName( $LastName );
    $usr->setEmail( $Email );
    $usr->store();
}

if ( $action == "delete" )
{
    eZUser::delete( $UserId );
}

if ( $action == "modify" )
{
    $usr = new eZUser;
    $usr->get( $UserId );

    
    $t->set_var( "nick-name", $usr->nickName() );
    $t->set_var( "first-name", $usr->firstName() );
    $t->set_var( "last-name", $usr->lastName() );
    $t->set_var( "email", $usr->email() );
    $t->set_var( "userid", $UserId );

    $t->set_var( "action", "modifyuser" );
    $t->set_var( "caption", "Endre" );

}
else  // default to add user box
{
    $t->set_var( "nick-name", "" );
    $t->set_var( "first-name", "" );
    $t->set_var( "last-name","" );
    $t->set_var( "email", "" );
    $t->set_var( "userid", "" );

    $t->set_var( "action", "adduser");
    $t->set_var( "caption", "Legg til" );
}
$t->parse( "addmodify-box", "box", true);



$users = eZUser::getAllUsers();

for ($i = 0; $i < count( $users ); $i++)
{
    //for ($j = 0; $j < count( $users[$i] ); $j++)
             echo $users[$i]["FirstName"];
    $t->set_var( "user-id", $users[$i]["id"]);
    $t->set_var( "nick-name", $users[$i]["nick_name"]);
    $t->set_var( "first-name", $users[$i]["first_name"]);
    $t->set_var( "last-name", $users[$i]["last_name"]);
    $t->set_var( "email", $users[$i]["email"]);

    if ( ($i % 2) != 0)
        $t->set_var( "color", "#f0f0f0" );
    else
        $t->set_var( "color", "#dcdcdc" );

    $t->parse( "user-list", "elements", true );
}

$t->pparse( "output", "userlist" );
?>
