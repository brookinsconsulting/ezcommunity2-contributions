<?
/*!
    $Id: grouplist.php,v 1.1 2000/08/16 11:32:15 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <10-Aug-2000 11:35:39 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "../classes/ezdb.php" );
include_once( "../classes/ezusergroup.php" );


$t = new Template( "templates/" );
$t->set_file( Array( "main" => "group-framework.tpl",
                     "element" => "group-element.tpl" ) );

$t->set_var( "docroot", $DOCROOT );


if ( $modifyGroup )
{
    if ( $group->eZPublish_Add() == "Y" )
        $t->set_var( "eZPublish_Add", "checked");
    else
        $t->set_var( "eZPublish_Add", "" );
    
    if ( $group->eZPublish_Edit() == "Y" )
        $t->set_var( "eZPublish_Edit", "checked");
    else
        $t->set_var( "eZPublish_Edit", "" );
    
    if ( $group->GrantUser() == "Y" )
        $t->set_var( "GrantUser", "checked");
    else
        $t->set_var( "GrantUser", "" );
    
    if ( $group->eZPublish_Preferences() == "Y" )
        $t->set_var( "eZPublish_Preferences", "checked");
    else
        $t->set_var( "eZPublish_Preferences", "" );
    
    if ( $group->eZLink_Add() == "Y" )
        $t->set_var( "eZLink_Add", "checked");
    else
        $t->set_var( "eZLink_Add", "" );
    
    if ( $group->eZLink_Edit() == "Y" )
        $t->set_var( "eZLink_Edit", "checked");
    else
        $t->set_var( "eZLink_Edit", "" );
    
    if ( $group->eZLink_Delete() == "Y" )
        $t->set_var( "eZLink_Delete", "checked");
    else
        $t->set_var( "eZLink_Delete", "" );
    
    if ( $group->eZPublish_EditAll() == "Y" )
        $t->set_var( "eZPublish_EditAll", "checked");
    else
        $t->set_var( "eZPublish_EditAll", "" );
    
    if ( $group->eZForum_AddCategory() == "Y" )
        $t->set_var( "eZForum_AddCategory", "checked");
    else
        $t->set_var( "eZForum_AddCategory", "" );
    
    if ( $group->eZForum_AddForum() == "Y" )
        $t->set_var( "eZForum_AddForum", "checked");
    else
        $t->set_var( "eZForum_AddForum", "" );
    
    if ( $group->eZForum_DeleteCategory() == "Y" )
        $t->set_var( "eZForum_DeleteCategory", "checked");
    else
        $t->set_var( "eZForum_DeleteCategory", "" );
    
    if ( $group->eZForum_DeleteForum() == "Y" )
        $t->set_var( "eZForum_DeleteForum", "checked");
    else
        $t->set_var( "eZForum_DeleteForum", "" );

    if ( $group->eZForum_AddMessage() == "Y" )
        $t->set_var( "eZForum_AddMessage", "checked");
    else
        $t->set_var( "eZForum_AddMessage", "" );

    if ( $group->eZForum_DeleteMessage() == "Y" )
        $t->set_var( "eZForum_DeleteMessage", "checked");
    else
        $t->set_var( "eZForum_DeleteMessage", "" );

    if ( $group->zez_addGroup() == "Y" )
        $t->set_var( "zez_addGroup", "checked");
    else
        $t->set_var( "zez_addGroup", "" );

    if ( $group->zez_DeleteGroup() == "Y" )
        $t->set_var( "zez_DeleteGroup", "checked");
    else
        $t->set_var( "zez_DeleteGroup", "" );

    if ( $group->zez_addUser() == "Y" )
        $t->set_var( "zez_addUser", "checked");
    else
        $t->set_var( "zez_addUser", "" );

    if ( $group->zez_DeleteUser() == "Y" )
        $t->set_var( "zez_DeleteUser", "checked");
    else
        $t->set_var( "zez_DeleteUser", "" );

    if ( $group->zez_Admin() == "Y" )
        $t->set_var( "zez_Admin", "checked");
    else
        $t->set_var( "zez_Admin", "" );

}

$group = new eZUserGroup();
$groups = $group->getAllGroups();

for ( $i = 0; $i < count ( $groups ); $i++ )
{
    $t->set_var( "group_id", $groups[$i]["Id"] );
    $t->set_var( "name", $groups[$i]["Name"] );
    $t->set_var( "description", $groups[$i]["Description"] );

    $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );
    $t->parse( "groups", "element", true );
}

$t->pparse( "output", "main" );
