<?php
// $Id: ezusergroup.php,v 1.1 2000/08/16 11:40:56 bf-cvs Exp $
// Lars Wilhelmsen <lw@ez.no>
// eZ systems <http://www.ez.no/>
//

// Generated with the PHP Class Generator
// (C) 2000 by Lars Wilhelmsen <lw@ez.no>, All rights reserved.

class eZUserGroup
{

    /*!

    */
    function eZUserGroup( $id="" )
    {
        if ( $id != "" )
        {
            $this->get( $id );
        }
    }

    /*!
      Henter ut en brukergruppe fra databasen.
    */
    function get( $Id )
    {
        $this->openDB();
        $q = mysql_query("SELECT * FROM GroupTable WHERE Id='$Id' ")
             or die( "blah. " ); 
        if( mysql_num_rows( $q ) == 0 )
            die( "No groups defined for this user" );
        $this->ID = mysql_result( $q, 0, "Id" );
        $this->Name = mysql_result( $q, 0, "Name" );
        $this->Description = mysql_result( $q, 0, "Description" );
        $this->eZPublish_Add = mysql_result( $q, 0, "eZPublish_Add" );
        $this->eZPublish_Edit = mysql_result( $q, 0, "eZPublish_Edit" );
        $this->GrantUser = mysql_result( $q, 0, "GrantUser" );
        $this->eZPublish_Preferences = mysql_result( $q, 0, "eZPublish_Preferences" );
        $this->eZLink_Add = mysql_result( $q, 0, "eZLink_Add" );
        $this->eZLink_Edit = mysql_result( $q, 0, "eZLink_Edit" );
        $this->eZLink_Delete = mysql_result( $q, 0, "eZLink_Delete" );
        $this->eZPublish_EditAll = mysql_result( $q, 0, "eZPublish_EditAll" );
        $this->eZForum_AddCategory = mysql_result( $q, 0, "eZForum_AddCategory" );
        $this->eZForum_AddForum = mysql_result( $q, 0, "eZForum_AddForum" );
        $this->eZForum_DeleteCategory = mysql_result( $q, 0, "eZForum_DeleteCategory" );
        $this->eZForum_DeleteForum = mysql_result( $q, 0, "eZForum_DeleteForum" );
        $this->eZForum_AddMessage = mysql_result( $q, 0, "eZForum_AddMessage" );
        $this->eZForum_DeleteMessage = mysql_result( $q, 0, "eZForum_DeleteMessage" );
        $this->zez_AddGroup = mysql_result( $q, 0, "zez_AddGroup" );
        $this->zez_DeleteGroup = mysql_result( $q, 0, "zez_DeleteGroup" );
        $this->zez_AddUser = mysql_result( $q, 0, "zez_AddUser" );
        $this->zez_DeleteUser = mysql_result( $q, 0, "zez_DeleteUser" );
        $this->zez_Admin = mysql_result( $q, 0, "zez_Admin" );
    }

    /*!
      Lagrer en ny brukergruppe i databasen.
    */
    function store()
    {
        $this->openDB();
        if ( !isset( $this->ID ) )
        { // insert new record
            $q = mysql_query( "INSERT INTO GroupTable SET
			Name='$this->Name',
			Description='$this->Description',
			eZPublish_Add='$this->eZPublish_Add',
			eZPublish_Edit='$this->eZPublish_Edit',
			GrantUser='$this->GrantUser',
			eZPublish_Preferences='$this->eZPublish_Preferences',
			eZLink_Add='$this->eZLink_Add',
			eZLink_Edit='$this->eZLink_Edit',
			eZLink_Delete='$this->eZLink_Delete',
			eZPublish_EditAll='$this->eZPublish_EditAll',
			eZForum_AddCategory='$this->eZForum_AddCategory',
			eZForum_AddForum='$this->eZForum_AddForum',
			eZForum_DeleteCategory='$this->eZForum_DeleteCategory',
			eZForum_DeleteForum='$this->eZForum_DeleteForum',
			eZForum_AddMessage='$this->eZForum_AddMessage',
			eZForum_DeleteMessage='$this->eZForum_DeleteMessage',
			zez_AddGroup='$this->zez_AddGroup',
			zez_DeleteGroup='$this->zez_DeleteGroup',
			zez_AddUser='$this->zez_AddUser',
			zez_DeleteUser='$this->zez_DeleteUser',
			zez_Admin='$this->zez_Admin'" )
             or die( "SQL Error: could not insert usergroup " );

            return mysql_insert_id(  );
        }
    }
    

    /*!
      Oppdaterer databasen.
    */
    function update()
    {
        $this->openDB();        
        $q = mysql_query( "UPDATE GroupTable SET
                Name='$this->Name',
                Description='$this->Description',
                eZPublish_Add='$this->eZPublish_Add',
                eZPublish_Edit='$this->eZPublish_Edit',
                GrantUser='$this->GrantUser',
                eZPublish_Preferences='$this->eZPublish_Preferences',
                eZLink_Add='$this->eZLink_Add',
                eZLink_Edit='$this->eZLink_Edit',
                eZLink_Delete='$this->eZLink_Delete',
                eZPublish_EditAll='$this->eZPublish_EditAll',
                eZForum_AddCategory='$this->eZForum_AddCategory',
                eZForum_AddForum='$this->eZForum_AddForum',
                eZForum_DeleteCategory='$this->eZForum_DeleteCategory',
                eZForum_DeleteForum='$this->eZForum_DeleteForum',
                eZForum_AddMessage='$this->eZForum_AddMessage',
                eZForum_DeleteMessage='$this->eZForum_DeleteMessage',
                zez_AddGroup='$this->zez_AddGroup',
                zez_DeleteGroup='$this->zez_DeleteGroup',
                zez_AddUser='$this->zez_AddUser',
                zez_DeleteUser='$this->zez_DeleteUser',
                zez_Admin='$this->zez_Admin' WHERE Id='$this->ID'" )
             or die( "SQL Error: could not update usergroup." );
    }

    function delete( )
    {
        $this->openDB();
        mysql_query("DELETE FROM GroupTable WHERE Id='$this->ID' " )
             or die( "SQL Error: could not delete usergroup with ID=$this->ID" );
    }

    function getAllGroups()
    {
        $this->openDB();
        $q = mysql_query("SELECT * FROM GroupTable")
             or die( "eZUserGroup::getAllGroups() failed, dying..." );

        for ( $i = 0; $i < mysql_num_rows( $q ); $i++ )
               $resultArray[$i] = mysql_fetch_array( $q );

        return $resultArray;
    }

    /*!
      Funksjoner for brukergruppen.
    */
    function Id()
    {
        return $this->ID;
    }

    function setId( $v )
    {
        $this->ID = $v;
    }

    function Name()
    {
        return $this->Name;
    }

    function setName( $v )
    {
        $this->Name = $v;
    }

    function Description()
    {
        return $this->Description;
    }

    function setDescription( $v )
    {
        $this->Description = $v;
    }

    /*!
      Rettigheter til eZ publish.
    */    
    function eZPublish_Add()
    {
        return $this->eZPublish_Add;
    }

    function seteZPublish_Add( $v )
    {
        $this->eZPublish_Add = $v;
    }

    function eZPublish_Edit()
    {
        return $this->eZPublish_Edit;
    }

    function seteZPublish_Edit( $v )
    {
        $this->eZPublish_Edit = $v;
    }

    function eZPublish_Preferences()
    {
        return $this->eZPublish_Preferences;
    }

    function seteZPublish_Preferences( $v )
    {
        $this->eZPublish_Preferences = $v;
    }

    function eZPublish_EditAll()
    {
        return $this->eZPublish_EditAll;
    }

    function seteZPublish_EditAll( $v )
    {
        $this->eZPublish_EditAll = $v;
    }

    /*!
      Rettigheter til eZ link.
    */        
    function eZLink_Add()
    {
        return $this->eZLink_Add;
    }

    function seteZLink_Add( $v )
    {
        $this->eZLink_Add = $v;
    }

    function eZLink_Edit()
    {
        return $this->eZLink_Edit;
    }

    function seteZLink_Edit( $v )
    {
        $this->eZLink_Edit = $v;
    }

    function eZLink_Delete()
    {
        return $this->eZLink_Delete;
    }

    function seteZLink_Delete( $v )
    {
        $this->eZLink_Delete = $v;
    }

    /*!
      Rettigheter til eZ forum.
    */        
    function eZForum_AddCategory()
    {
        return $this->eZForum_AddCategory;
    }

    function seteZForum_AddCategory( $v )
    {
        $this->eZForum_AddCategory = $v;
    }

    function eZForum_AddForum()
    {
        return $this->eZForum_AddForum;
    }

    function seteZForum_AddForum( $v )
    {
        $this->eZForum_AddForum = $v;
    }

    function eZForum_DeleteCategory()
    {
        return $this->eZForum_DeleteCategory;
    }

    function seteZForum_DeleteCategory( $v )
    {
        $this->eZForum_DeleteCategory = $v;
    }

    function eZForum_DeleteForum()
    {
        return $this->eZForum_DeleteForum;
    }

    function seteZForum_DeleteForum( $v )
    {
        $this->eZForum_DeleteForum = $v;
    }

    function eZForum_AddMessage()
    {
        return $this->eZForum_AddMessage;
    }

    function seteZForum_AddMessage( $v )
    {
        $this->eZForum_AddMessage = $v;
    }

    function eZForum_DeleteMessage()
    {
        return $this->eZForum_DeleteMessage;
    }

    function seteZForum_DeleteMessage( $v )
    {
        $this->eZForum_DeleteMessage = $v;
    }


    /*!
      Rettigheter til siten.
    */

    function GrantUser()
    {
        return $this->GrantUser;
    }

    function setGrantUser( $v )
    {
        $this->GrantUser = $v;
    }

    function zez_AddGroup()
    {
        return $this->zez_AddGroup;
    }

    function setzez_AddGroup( $v )
    {
        $this->zez_AddGroup = $v;
    }

    function zez_DeleteGroup()
    {
        return $this->zez_DeleteGroup;
    }

    function setzez_DeleteGroup( $v )
    {
        $this->zez_DeleteGroup = $v;
    }

    function zez_AddUser()
    {
        return $this->zez_AddUser;
    }

    function setzez_AddUser( $v )
    {
        $this->zez_AddUser = $v;
    }

    function zez_DeleteUser()
    {
        return $this->zez_DeleteUser;
    }

    function setzez_DeleteUser( $v )
    {
        $this->zez_DeleteUser = $v;
    }

    function zez_Admin()
    {
        return $this->zez_Admin;
    }

    function setzez_Admin( $v )
    {
        $this->zez_Admin = $v;
    }

    // Additional commands

    /*
      $userID : userID from UserTable
      $cmd : string with command to request execute access for
     */
    function verifyCommand( $userID, $cmd )
    {
        include_once( "class.INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
        
        $q = mysql_query( "SELECT GroupTable.$cmd As rights
                           FROM
                               GroupTable, UserTable
                           WHERE
                               GroupTable.Id=UserTable.group_id
                           AND
                               UserTable.Id='$userID'
                           GROUP BY
                               GroupTable.Id" )

             or die( "ERROR in SQL or command  in eZUserGroup::verifyCommand(), exiting." );

        if ( mysql_result( $q, 0, "rights" ) == "Y" )
            return true;
        else
            return false;
    }

    function resolveGroup( $Id )
    {
        $this->openDB();

        $q = mysql_query( "SELECT Name FROM GroupTable WHERE Id='$Id'" )
             or die( "eZUserGroup::resolveGroup( $Id ) failed, dying..." );

        if ( mysql_num_rows( $q ) == 1 )
            return mysql_result( $q, 0, "Name" );

        else
            die( "The query returned a bogus number of records (" . mysql_num_rows( $q ) . "), Probably a user with group_id=0, dying..." );
    }

    /*!
      Privat funksjon, skal kun brukes ac ezusergroup klassen.
      Funksjon for å åpne databasen.
    */
    function openDB()
    {
        include_once( "class.INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Name;
    var $Description;
    var $eZPublish_Add;
    var $eZPublish_Edit;
    var $GrantUser;
    var $eZPublish_Preferences;
    var $eZLink_Add;
    var $eZLink_Edit;
    var $eZLink_Delete;
    var $eZPublish_EditAll;
    var $eZForum_AddCategory;
    var $eZForum_AddForum;
    var $eZForum_DeleteCategory;
    var $eZForum_DeleteForum;
    var $eZForum_AddMessage;
    var $eZForum_DeleteMessage;
    var $zez_AddGroup;
    var $zez_DeleteGroup;
    var $zez_AddUser;
    var $zez_DeleteUser;
    var $zez_Admin;
}
?>
