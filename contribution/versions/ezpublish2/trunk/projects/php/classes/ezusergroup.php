<?php
// $Id: ezusergroup.php,v 1.8 2000/10/02 11:58:14 bf-cvs Exp $
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
        $this->dbInit();
        $query = mysql_query("SELECT * FROM GroupTable WHERE Id='$Id' ")
             or die( "blah. " );
        
        if( mysql_num_rows( $query ) == 0 )
            die( "No groups defined for this user" );
        
        $this->ID = mysql_result( $query, 0, "Id" );
        $this->Name = mysql_result( $query, 0, "Name" );
        $this->Description = mysql_result( $query, 0, "Description" );
        $this->eZPublish_Add = mysql_result( $query, 0, "eZPublish_Add" );
        $this->eZPublish_Edit = mysql_result( $query, 0, "eZPublish_Edit" );
        $this->GrantUser = mysql_result( $query, 0, "GrantUser" );
        $this->eZPublish_Preferences = mysql_result( $query, 0, "eZPublish_Preferences" );
        $this->eZLink_Add = mysql_result( $query, 0, "eZLink_Add" );
        $this->eZLink_Edit = mysql_result( $query, 0, "eZLink_Edit" );
        $this->eZLink_Delete = mysql_result( $query, 0, "eZLink_Delete" );
        $this->eZPublish_EditAll = mysql_result( $query, 0, "eZPublish_EditAll" );
        $this->eZForum_AddCategory = mysql_result( $query, 0, "eZForum_AddCategory" );
        $this->eZForum_AddForum = mysql_result( $query, 0, "eZForum_AddForum" );
        $this->eZForum_DeleteCategory = mysql_result( $query, 0, "eZForum_DeleteCategory" );
        $this->eZForum_DeleteForum = mysql_result( $query, 0, "eZForum_DeleteForum" );
        $this->eZForum_AddMessage = mysql_result( $query, 0, "eZForum_AddMessage" );
        $this->eZForum_DeleteMessage = mysql_result( $query, 0, "eZForum_DeleteMessage" );
        $this->eZContact_Read = mysql_result( $query, 0, "eZContact_Read" );
        $this->eZContact_Add = mysql_result( $query, 0, "eZContact_Add" );
        $this->eZContact_Delete = mysql_result( $query, 0, "eZContact_Delete" );
        $this->eZContact_Edit = mysql_result( $query, 0, "eZContact_Edit" );
        $this->eZContact_AdminAdd = mysql_result( $query, 0, "eZContact_AdminAdd" );
        $this->eZContact_AdminDelete = mysql_result( $query, 0, "eZContact_AdminDelete" );
        $this->eZContact_AdminEdit = mysql_result( $query, 0, "eZContact_AdminEdit" );
        $this->zez_AddGroup = mysql_result( $query, 0, "zez_AddGroup" );
        $this->zez_DeleteGroup = mysql_result( $query, 0, "zez_DeleteGroup" );
        $this->zez_AddUser = mysql_result( $query, 0, "zez_AddUser" );
        $this->zez_DeleteUser = mysql_result( $query, 0, "zez_DeleteUser" );
        $this->zez_Admin = mysql_result( $query, 0, "zez_Admin" );
    }

    /*!4
      Lagrer en ny brukergruppe i databasen.
    */
    function store()
    {
        $this->dbInit();
        
        if ( !isset( $this->ID ) )
        { // insert new record
            query( "INSERT INTO GroupTable SET
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
            eZContact_Read='$this->eZContact_Read',
            eZContact_Add='$this->eZContact_Add',
            eZContact_Delete='$this->eZContact_Delete',
            eZContact_Edit='$this->eZContact_Edit',
            eZContact_AdminAdd='$this->eZContact_AdminAdd',
            eZContact_AdminDelete='$this->eZContact_AdminDelete',
            eZContact_AdminEdit='$this->eZContact_AdminEdit',
            zez_AddGroup='$this->zez_AddGroup',
			zez_DeleteGroup='$this->zez_DeleteGroup',
			zez_AddUser='$this->zez_AddUser',
			zez_DeleteUser='$this->zez_DeleteUser',
			zez_Admin='$this->zez_Admin'" );
            
            return mysql_insert_id(  );
        }
    }
    

    /*!
      Oppdaterer databasen.
    */
    function update()
    {
        $this->dbInit();        
        $query = mysql_query( "UPDATE GroupTable SET
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
                eZContact_Read='$this->eZContact_Read',
                eZContact_Add='$this->eZContact_Add',
                eZContact_Delete='$this->eZContact_Delete',
                eZContact_Edit='$this->eZContact_Edit',
                eZContact_AdminAdd='$this->eZContact_AdminAdd',
                eZContact_AdminDelete='$this->eZContact_AdminDelete',
                eZContact_AdminEdit='$this->eZContact_AdminEdit',
                zez_AddGroup='$this->zez_AddGroup',
                zez_DeleteGroup='$this->zez_DeleteGroup',
                zez_AddUser='$this->zez_AddUser',
                zez_DeleteUser='$this->zez_DeleteUser',
                zez_Admin='$this->zez_Admin' WHERE Id='$this->ID'" )
             or die( "SQL Error: could not update usergroup." );
    }

    function delete( )
    {
        $this->dbInit();
        mysql_query("DELETE FROM GroupTable WHERE Id='$this->ID' " )
             or die( "SQL Error: could not delete usergroup with ID=$this->ID" );
    }

    function getAllGroups()
    {
        $this->dbInit();
        $query = mysql_query("SELECT * FROM GroupTable")
             or die( "eZUserGroup::getAllGroups() failed, dying..." );

        for ( $i = 0; $i < mysql_num_rows( $query ); $i++ )
               $resultArray[$i] = mysql_fetch_array( $query );

        return $resultArray;
    }

    /*!
      Funksjoner for brukergruppen.
    */
    function Id()
    {
        return $this->ID;
    }

    function setId( $value )
    {
        $this->ID = $value;
    }

    function Name()
    {
        return $this->Name;
    }

    function setName( $value )
    {
        $this->Name = $value;
    }

    function Description()
    {
        return $this->Description;
    }

    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Rettigheter til eZ publish.
    */    
    function eZPublish_Add()
    {
        return $this->eZPublish_Add;
    }

    function seteZPublish_Add( $value )
    {
        $this->eZPublish_Add = $value;
    }

    function eZPublish_Edit()
    {
        return $this->eZPublish_Edit;
    }

    function seteZPublish_Edit( $value )
    {
        $this->eZPublish_Edit = $value;
    }

    function eZPublish_Preferences()
    {
        return $this->eZPublish_Preferences;
    }

    function seteZPublish_Preferences( $value )
    {
        $this->eZPublish_Preferences = $value;
    }

    function eZPublish_EditAll()
    {
        return $this->eZPublish_EditAll;
    }

    function seteZPublish_EditAll( $value )
    {
        $this->eZPublish_EditAll = $value;
    }

    /*!
      Rettigheter til eZ link.
    */        
    function eZLink_Add()
    {
        return $this->eZLink_Add;
    }

    function seteZLink_Add( $value )
    {
        $this->eZLink_Add = $value;
    }

    function eZLink_Edit()
    {
        return $this->eZLink_Edit;
    }

    function seteZLink_Edit( $value )
    {
        $this->eZLink_Edit = $value;
    }

    function eZLink_Delete()
    {
        return $this->eZLink_Delete;
    }

    function seteZLink_Delete( $value )
    {
        $this->eZLink_Delete = $value;
    }

    /*!
      Rettigheter til eZ Contact
    */
    function eZContact_Read()
    {
        return $this->eZContact_Read;
    }

    function seteZContact_Read( $value )
    {
        $this->eZContact_Read = $value;
    }
    
    function eZContact_Add()
    {
        return $this->eZContact_Add;
    }

    function seteZContact_Add( $value )
    {
        $this->eZContact_Add = $value;
    }
        
    function eZContact_Delete()
    {
        return $this->eZContact_Delete;
    }

    function seteZContact_Delete( $value )
    {
        $this->eZContact_Delete = $value;
    }

    function eZContact_Edit()
    {
        return $this->eZContact_Edit;
    }

    function seteZContact_Edit( $value )
    {
        $this->eZContact_Edit = $value;
    }

    function eZContact_AdminAdd()
    {
        return $this->eZContact_AdminAdd;
    }

    function seteZContact_AdminAdd( $value )
    {
        $this->eZContact_AdminAdd = $value;
    }
        
    function eZContact_AdminDelete()
    {
        return $this->eZContact_AdminDelete;
    }

    function seteZContact_AdminDelete( $value )
    {
        $this->eZContact_AdminDelete = $value;
    }

    function eZContact_AdminEdit()
    {
        return $this->eZContact_AdminEdit;
    }

    function seteZContact_AdminEdit( $value )
    {
        $this->eZContact_AdminEdit = $value;
    }
    
    /*!
      Rettigheter til eZ forum.
    */        
    function eZForum_AddCategory()
    {
        return $this->eZForum_AddCategory;
    }

    function seteZForum_AddCategory( $value )
    {
        $this->eZForum_AddCategory = $value;
    }

    function eZForum_AddForum()
    {
        return $this->eZForum_AddForum;
    }

    function seteZForum_AddForum( $value )
    {
        $this->eZForum_AddForum = $value;
    }

    function eZForum_DeleteCategory()
    {
        return $this->eZForum_DeleteCategory;
    }

    function seteZForum_DeleteCategory( $value )
    {
        $this->eZForum_DeleteCategory = $value;
    }

    function eZForum_DeleteForum()
    {
        return $this->eZForum_DeleteForum;
    }

    function seteZForum_DeleteForum( $value )
    {
        $this->eZForum_DeleteForum = $value;
    }

    function eZForum_AddMessage()
    {
        return $this->eZForum_AddMessage;
    }

    function seteZForum_AddMessage( $value )
    {
        $this->eZForum_AddMessage = $value;
    }

    function eZForum_DeleteMessage()
    {
        return $this->eZForum_DeleteMessage;
    }

    function seteZForum_DeleteMessage( $value )
    {
        $this->eZForum_DeleteMessage = $value;
    }


    /*!
      Rettigheter til siten.
    */

    function GrantUser()
    {
        return $this->GrantUser;
    }

    function setGrantUser( $value )
    {
        $this->GrantUser = $value;
    }

    function zez_AddGroup()
    {
        return $this->zez_AddGroup;
    }

    function setzez_AddGroup( $value )
    {
        $this->zez_AddGroup = $value;
    }

    function zez_DeleteGroup()
    {
        return $this->zez_DeleteGroup;
    }

    function setzez_DeleteGroup( $value )
    {
        $this->zez_DeleteGroup = $value;
    }

    function zez_AddUser()
    {
        return $this->zez_AddUser;
    }

    function setzez_AddUser( $value )
    {
        $this->zez_AddUser = $value;
    }

    function zez_DeleteUser()
    {
        return $this->zez_DeleteUser;
    }

    function setzez_DeleteUser( $value )
    {
        $this->zez_DeleteUser = $value;
    }

    function zez_Admin()
    {
        return $this->zez_Admin;
    }

    function setzez_Admin( $value )
    {
        $this->zez_Admin = $value;
    }

    // Additional commands

    /*
      $userID : userID from UserTable
      $cmd : string with command to request execute access for
     */
    function verifyCommand( $userID, $cmd )
    {
        include_once( "classes/INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );

       
        $query = mysql_query( "SELECT GroupTable.$cmd As rights
                           FROM
                               GroupTable, UserTable
                           WHERE
                               GroupTable.Id=UserTable.group_id
                           AND
                               UserTable.Id='$userID'
                           GROUP BY
                               GroupTable.Id" )
             
             or die( "ERROR in SQL or command  in eZUserGroup::verifyCommand(), exiting." );
             if ( mysql_result( $query, 0, "rights" ) == "Y" )
                 
            return true;
        else
            return false;
    }

    function resolveGroup( $Id )
    {
        $this->dbInit();

        $query = mysql_query( "SELECT Name FROM GroupTable WHERE Id='$Id'" )
             or die( "eZUserGroup::resolveGroup( $Id ) failed, dying..." );

        if ( mysql_num_rows( $query ) == 1 )
            return mysql_result( $query, 0, "Name" );

        else
            die( "The query returned a bogus number of records (" . mysql_num_rows( $query ) . "), Probably a user with group_id=0, dying..." );
    }

    /*!
      Privat funksjon, skal kun brukes av ezusergroup klassen.
      Funksjon for å åpne databasen.
    */
    function dbInit()
    {
        include_once( "classes/INIFile.php" );

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
    var $eZContact_Read;
    var $eZContact_Add;
    var $eZContact_Delete;
    var $eZContact_Edit;
    var $eZContact_AdminAdd;
    var $eZContact_AdminDelete;
    var $eZContact_AdminEdit;
    var $zez_AddGroup;
    var $zez_DeleteGroup;
    var $zez_AddUser;
    var $zez_DeleteUser;
    var $zez_Admin;
}
?>
