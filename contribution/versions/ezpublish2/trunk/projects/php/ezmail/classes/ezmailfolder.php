<?
// 
// $Id: ezmailfolder.php,v 1.1 2001/03/20 20:51:03 fh Exp $
//
// eZMailFolder class
//
// Frederik Holljen <fh@ez.no>
// Created on: <20-Mar-2001 18:29:11 fh>
//
// Copyright (C) .  All rights reserved.
//
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZMailFolder
//! eZMailFolder documentation.
/*!

  Example code:
  \code
  \endcode

*/
	      
class eZMailFolder
{
/************* CONSTRUCTORS DESTRUCTORS (virtual) ************************/    
    /*!
      constructor
    */
    function eZMailFolder( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        // default value
        $this->IsPublished = "false";
        
        if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
            {                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
            
        }
    }

    /*!
      Deletes a eZMailFolder object from the database.
    */
    function delete( $id = -1 )
    {
        $this->dbInit();

        if ( isset( $this->ID ) && $id == -1 )
        {
            $this->Database->query( "DELETE FROM eZMail_Folder WHERE ID='$this->ID'" );
        }
        else
        {
            $this->Database->query( "DELETE FROM eZMail_Folder WHERE ID='$id'" );
        }
        return true;
    }

/***************** Get / fetch from database *******************************/
    /*!
      Stores a mail to the database.
    */
    function store()
    {
        $this->dbInit();

        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZMail_Folder SET
		                         UserID='$this->UserID',
		                         ParentID='$this->ParentID',
                                 Name='$this->Name',
                                 FolderType='$this->FolderType'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZMail_Folder SET
		                         UserID='$this->UserID',
		                         ParentID='$this->ParentID',
                                 Name='$this->Name',
                                 FolderType='$this->FolderType'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $account_array, "SELECT * FROM eZMail_Folder WHERE ID='$id'" );
            if ( count( $account_array ) > 1 )
            {
                die( "Error: Mail folders with the same ID was found in the database. This should not happen." );
            }
            else if( count( $account_array ) == 1 )
            {

                $this->ID = $account_array[0][ "ID" ];
                $this->UserID = $account_array[0][ "UserID" ];
                $this->ParentID = $account_array[0][ "ParentID" ];
                $this->Name = $account_array[0][ "Name" ];
                $this->FolderType = $account_array[0][ "FolderType" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

/****************** BORING SET AND GET FUNCTIONS ***************************/
  /*!
    */
    function userID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->UserID;
    }

    /*!
    */
    function setUserID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->UserID = $value;
    }

  /*!
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Name;
    }

    /*!
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Name = $value;
    }

    /*!
    */
    function parentID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ParentID;
    }

    /*!
    */
    function setParent( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ParentID = $value;
    }

    /*!
     Returns the type of this folder. Valid types are:
     0 - Normal user created folder
     1 - Inbox
     2 - Outbox
     3 - Sent mail
     4 - Drafts
     5 - Trash
    */
    function folderType()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->FolderType;
    }

  /*!
     Sets the type of this folder. Valid types are:
     0 - Normal user created folder
     1 - Inbox
     2 - Outbox
     3 - Sent mail
     4 - Drafts
     5 - Trash
    */
    function setServerType( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->FolderType = $value;
    }

    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $UserID;
    var $ParentID;
    var $Name;
    var $FolderType=0;
    
    var $Database;
    var $IsConnected;
    var $State_;
}

?>
