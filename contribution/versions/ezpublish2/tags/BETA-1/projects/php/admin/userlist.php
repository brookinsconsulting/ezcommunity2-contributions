<?php

$noOfUsers = 20;

$t = new Template( "templates/" );
$t->set_file( array( "main"    => "user-framework.tpl",
                     "element" => "user-element.tpl" ) );

include_once( "../classes/ezusergroup.php" );

$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 1 )
{
    include( "index.php" );
    exit();
}


if( !eZUserGroup::verifyCommand( $session->userID(), "GrantUser" ) )
{
    print( "Du har ikke lov til å endre på brukerinformasjon<br>" );
    print( "<a href=index.php>Tilbake</a>" );
    exit();
}

if( !isset( $startno ) )
    $startno = 0;

array_query( $userlist, "SELECT id, nick_name, first_name, last_name, group_id FROM UserTable ORDER BY nick_name" );

$users = count( $userlist );

$t->set_var( "bruker", "" );

for( $i = $startno; ( $i < ($noOfUsers + $startno) ) && ( $i < $users ); $i++ )
{
    $group = new eZUserGroup( $userlist[$i]["group_id"] );
    
    $t->set_var( "id", $userlist[$i]["id"] );
    $t->set_var( "username", $userlist[$i]["nick_name"] );
    $t->set_var( "name", $userlist[$i]["first_name"] . " " . $userlist[$i]["last_name"] );
    $t->set_var( "group", $group->name() );
    $t->set_var( "color", switchColor( $i, "#DCDCDC", "#F0F0F0" ) );
    $t->parse( "bruker", "element", true );
}

if( $startno == 0 )
{
    $prev = "";
}
else
{
    $prev = "<a href=\"index.php?page=user.php&startno=" . ($startno - $noOfUsers) . "\">[Forrige]</a> ";
}

if( ( $startno + $noOfUsers ) >= $users )
{
    $next = "";
}
else
{
    $next = "<a href=\"index.php?page=user.php&startno=" . ($startno + $noOfUsers) . "\">[Neste]</a> ";
}

$t->set_var( "prevnext", $prev . $next );

$t->pparse( "output", "main" );

?>
