<?php
/*!
    $Id: groupedit.php,v 1.1 2000/08/16 11:32:15 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <10-Aug-2000 14:47:21 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "../classes/ezusergroup.php" );
include_once( "template.inc" );

$t = new Template( "templates/" );
$t->set_file( Array( "main" => "groupedit.tpl" ) );

$group = new eZUserGroup();

if ( $Action == "Update" )
{
    $group = new eZUserGroup( $UserGroupID );

    $group->setName( $Name );
    $group->setDescription( $Description );

    if ( $eZPublish_Add == "on" )
        $group->seteZPublish_Add( "Y" );
    else
        $group->seteZPublish_Add( "N" );
    if ( $eZPublish_Edit == "on" )
        $group->seteZPublish_Edit( "Y" );
    else
        $group->seteZPublish_Edit( "N" );    
    if ( $eZPublish_Preferences == "on" )
        $group->seteZPublish_Preferences( "Y" );
    else
        $group->seteZPublish_Preferences( "N" );    
    if ( $eZPublish_EditAll == "on" )
        $group->seteZPublish_EditAll( "Y" );
    else
        $group->seteZPublish_EditAll( "N" );
    if ( $eZLink_Add == "on" )
        $group->seteZLink_Add( "Y" );
    else
        $group->seteZLink_Add( "N" );    
    if ( $eZLink_Edit == "on" )
        $group->seteZLink_Edit( "Y" );
    else
        $group->seteZLink_Edit( "N" );    
    if ( $eZLink_Delete == "on" )
        $group->seteZLink_Delete( "Y" );
    else
        $group->seteZLink_Delete( "N" );
    if ( $eZForum_AddCategory == "on" )
        $group->seteZForum_AddCategory( "Y" );
    else
        $group->seteZForum_AddCategory( "N" );    
    if ( $eZForum_AddForum == "on" )
        $group->seteZForum_AddForum( "Y" );
    else
        $group->seteZForum_AddForum( "N" );    
    if ( $eZForum_DeleteCategory == "on" )
        $group->seteZForum_DeleteCategory( "Y" );
    else
        $group->seteZForum_DeleteCategory( "N" );    
    if ( $eZForum_DeleteForum == "on" )
        $group->seteZForum_DeleteForum( "Y" );
    else
        $group->seteZForum_DeleteForum( "N" );    
    if ( $eZForum_AddMessage == "on" )
        $group->seteZForum_AddMessage( "Y" );
    else
        $group->seteZForum_AddMessage( "N" );    
    if ( $eZForum_DeleteMessage == "on" )
        $group->seteZForum_DeleteMessage( "Y" );
    else
        $group->seteZForum_DeleteMessage( "N" );
    

    if ( $GrantUser == "on" )
        $group->setGrantUser( "Y" );
    else
        $group->setGrantUser( "N" );
    
    
    if ( $zez_AddGroup == "on" )
        $group->setzez_AddGroup( "Y" );
    else
        $group->setzez_AddGroup( "N" );
    if ( $zez_DeleteGroup == "on" )
        $group->setzez_DeleteGroup( "Y" );
    else
        $group->setzez_DeleteGroup( "N" );    
    if ( $zez_AddUser == "on" )
        $group->setzez_AddUser( "Y" );
    else
        $group->setzez_AddUser( "N" );    
    if ( $zez_DeleteUser == "on" )
        $group->setzez_DeleteUser( "Y" );
    else
        $group->setzez_DeleteUser( "N" );        
    if ( $zez_Admin == "on" )
        $group->setzez_Admin( "Y" );
    else
        $group->setzez_Admin( "N" );        

    $group->update();
    
    Header( "Location: index.php?page=grouplist.php" );    
}

if ( $Action == "Insert" )
{
    $group = new eZUserGroup();

    $group->setName( $Name );
    $group->setDescription( $Description );

    if ( $eZPublish_Add == "on" )
        $group->seteZPublish_Add( "Y" );
    if ( $eZPublish_Edit == "on" )
        $group->seteZPublish_Edit( "Y" );
    if ( $eZPublish_Preferences == "on" )
        $group->seteZPublish_Preferences( "Y" );
    if ( $eZPublish_EditAll == "on" )
        $group->seteZPublish_EditAll( "Y" );

    if ( $eZLink_Add == "on" )
        $group->seteZLink_Add( "Y" );
    if ( $eZLink_Edit == "on" )
        $group->seteZLink_Edit( "Y" );
    if ( $eZLink_Delete == "on" )
        $group->seteZLink_Delete( "Y" );

    if ( $eZForum_AddCategory == "on" )
        $group->seteZForum_AddCategory( "Y" );
    if ( $eZForum_AddForum == "on" )
        $group->seteZForum_AddForum( "Y" );
    if ( $eZForum_DeleteCategory == "on" )
        $group->seteZForum_DeleteCategory( "Y" );
    if ( $eZForum_DeleteForum == "on" )
        $group->seteZForum_DeleteForum( "Y" );
    if ( $eZForum_AddMessage == "on" )
        $group->seteZForum_AddMessage( "Y" );
    if ( $eZForum_DeleteMessage == "on" )
        $group->seteZForum_DeleteMessage( "Y" );

    if ( $GrantUser == "on" )
        $group->setGrantUser( "Y" );
    
    if ( $zez_AddGroup == "on" )
        $group->setzez_AddGroup( "Y" );
    if ( $zez_DeleteGroup == "on" )
        $group->setzez_DeleteGroup( "Y" );
    if ( $zez_AddUser == "on" )
        $group->setzez_AddUser( "Y" );
    if ( $zez_DeleteUser == "on" )
        $group->setzez_DeleteUser( "Y" );
    if ( $zez_Admin == "on" )
        $group->setzez_Admin( "Y" );
    
    $group->store();
    
    Header( "Location: index.php?page=grouplist.php" );        
}

if ( $Action == "Delete" )
{
    $group = new eZUserGroup( $UserGroupID );

    $group->delete();
    
    Header( "Location: index.php?page=grouplist.php" );    
}


if ( $Action == "Edit" )    
{
    $group->get( $UserGroupID );
    $t->set_var( "name", $group->Name() );
    $t->set_var( "description", $group->Description() );

    // eZ publish
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

    if ( $group->eZPublish_EditAll() == "Y" )
        $t->set_var( "eZPublish_EditAll", "checked");
    else
        $t->set_var( "eZPublish_EditAll", "" );
        
        // eZ link
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


        // eZ forum
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

        // site (zez)
    if ( $group->zez_AddGroup() == "Y" )
        $t->set_var( "zez_AddGroup", "checked");
    else
        $t->set_var( "zez_AddGroup", "" );

    if ( $group->zez_DeleteGroup() == "Y" )
        $t->set_var( "zez_DeleteGroup", "checked");
    else
        $t->set_var( "zez_DeleteGroup", "" );

    if ( $group->zez_AddUser() == "Y" )
        $t->set_var( "zez_AddUser", "checked");
    else
        $t->set_var( "zez_AddUser", "" );

    if ( $group->zez_DeleteUser() == "Y" )
        $t->set_var( "zez_DeleteUser", "checked");
    else
        $t->set_var( "zez_DeleteUser", "" );

    if ( $group->zez_Admin() == "Y" )
        $t->set_var( "zez_Admin", "checked");
    else
        $t->set_var( "zez_Admin", "" );

    
    $t->set_var( "user_group_id", $UserGroupID );
    $t->set_var( "action_value", "Update" ); 
    
    $t->pparse( "output", "main" );
}

if ( $Action == "New" )    
{    
    $t->set_var( "name", "" );
    $t->set_var( "description", "" );
    $t->set_var( "eZPublish_Add", "" );
    $t->set_var( "eZPublish_Edit", "" );
    $t->set_var( "GrantUser", "" );
    $t->set_var( "eZPublish_Preferences", "" );
    $t->set_var( "eZLink_Add", "" );
    $t->set_var( "eZLink_Edit", "" );
    $t->set_var( "eZLink_Delete", "" );
    $t->set_var( "eZPublish_EditAll", "" );
    $t->set_var( "eZForum_AddCategory", "" );
    $t->set_var( "eZForum_AddForum", "" );
    $t->set_var( "eZForum_DeleteCategory", "" );
    $t->set_var( "eZForum_DeleteForum", "" );
    $t->set_var( "eZForum_AddMessage", "" );
    $t->set_var( "eZForum_DeleteMessage", "" );
    $t->set_var( "zez_AddGroup", "" );
    $t->set_var( "zez_DeleteGroup", "" );
    $t->set_var( "zez_AddUser", "" );
    $t->set_var( "zez_DeleteUser", "" );
    $t->set_var( "zez_Admin", "" );

    $t->set_var( "action_value", "Insert" );

    $t->pparse( "output", "main" );
}


?>

