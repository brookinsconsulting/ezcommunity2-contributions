<?php

include_once( "../classes/ezusergroup.php" );

function goBack()
{
    global $t;
    $t->set_file( array( "main" => "user-framework.tpl",
                         "element" => "user-element.tpl" ) );
    
    array_query( $userlist, "SELECT * FROM AdminTable" );
    
    $t->set_var( "bruker", "" );
    
    for( $i = 0; $i < count( $userlist ); $i++ )
    {
        $t->set_var( "id", $userlist[$i]["Id"] );
        $t->set_var( "username", $userlist[$i]["Username"] );
        $t->set_var( "name", $userlist[$i]["Name"] );
        $t->parse( "bruker", "element", true );
    }
}

$t = new Template( "templates/" );
$t->set_file( Array( "main" =>  "userform.tpl",
                     "element" => "user-choice.tpl" ) );

$groups = eZUserGroup::getAllGroups();


$id = $radiouser;

if( isset( $new ) )
{
    
    $t->set_var( "name", "" );
    $t->set_var( "username", "" );
    $t->set_var( "email", "" );

    $t->set_var( "selected", "" );
    
    for ( $i = 0; $i < count( $groups ); $i++ )
    {
        $t->set_var( "group_id", $groups[$i]["Id"] );
        $t->set_var( "caption", $groups[$i]["Name"] );
        
        $t->parse( "choices", "element", true );
    }
}
else if( isset( $edit ) )
{
    if( !isset( $radiouser ) )
    {
        goBack();
    }
    else
    {
        $user = new eZUser();
        $user->get( $radiouser );

        $t->set_var( "first_name", $user->FirstName() );
        $t->set_var( "last_name", $user->LastName() );
        $t->set_var( "nick_name", $user->NickName() );
        $t->set_var( "email", $user->Email() );
        
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

    }
}
else
{
    if( !isset( $radiouser ) )
    {
        goBack();
    }
    else
    {
        array_query( $userlist, "SELECT * FROM AdminTable WHERE Id='$id'" );
        $t->set_file( "main", "user-delete.tpl" );
        $t->set_var( "username", $userlist[0]["Username"] );
        $t->set_var( "name", $userlist[0]["Name"] );
        $t->set_var( "id", $id );
    }
}

$t->pparse( "output", "main" );

?>
