<?
// 
// $Id: ezmailaccount.php,v 1.1 2001/03/19 18:52:31 fh Exp $
//
// eZMailAccount class
//
// Frederik Holljen <fh@ez.no>
// Created on: <19-Mar-2001 17:58:38 fh>
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
//!! eZMailAccount
//! eZMailAccount documentation.
/*!

  Example code:
  \code
  \endcode

*/
	      
class eZMailAccount
{
/************* CONSTRUCTORS DESTRUCTORS (virtual) ************************/    
    /*!
      constructor
    */
    function eZMailAccount( $id="", $fetch=true )
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
      Deletes a eZMailAccount object from the database.
    */
    function delete( $id = -1 )
    {
        $this->dbInit();

        if ( isset( $this->ID ) && $id == -1 )
        {
            $this->Database->query( "DELETE FROM eZMail_Account WHERE ID='$this->ID'" );
        }
        else
        {
            $this->Database->query( "DELETE FROM eZMail_Account WHERE ID='$id'" );
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
            $this->Database->query( "INSERT INTO eZMail_Account SET
		                         UserID='$this->UserID',
                                 Name='$this->Name',
                                 LoginName='$this->LoginName',
                                 Password='$this->Password',
                                 Server='$this->Server',
                                 DeleteFromServer='$this->DeleteFromServer',
                                 IsActive='$this->IsActive',
                                 ServerType='$this->ServerType'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZMail_Account SET
		                         UserID='$this->UserID',
                                 Name='$this->Name',
                                 LoginName='$this->LoginName',
                                 Password='$this->Password',
                                 Server='$this->Server',
                                 DeleteFromServer='$this->DeleteFromServer',
                                 IsActive='$this->IsActive',
                                 ServerType='$this->ServerType'
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
            $this->Database->array_query( $account_array, "SELECT * FROM eZMail_Account WHERE ID='$id'" );
            if ( count( $account_array ) > 1 )
            {
                die( "Error: Mail accounts with the same ID was found in the database. This should not happen." );
            }
            else if( count( $account_array ) == 1 )
            {

                $this->ID = $account_array[0][ "ID" ];
                $this->UserID = $account_array[0][ "UserID" ];
                $this->Name = $account_array[0][ "Name" ];
                $this->LoginName = $account_array[0][ "LoginName" ];
                $this->Password = $account_array[0][ "Password" ];
                $this->Server = $account_array[0][ "Server" ];
                $this->DeleteFromServer = $account_array[0][ "DeleteFromServer" ];
                $this->IsActive = $account_array[0][ "IsActive" ];
                $this->ServerType = $account_array[0][ "ServerType" ];

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
    function loginName()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->LoginName;
    }

    /*!
    */
    function setLoginName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->LoginName = $value;
    }


  /*!
    */
    function password()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Password;
    }

    /*!
    */
    function setPassword( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Password = $value;
    }    

  /*!
    */
    function server()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Server;
    }

    /*!
    */
    function setServer( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Server = $value;
    }

  /*!
    */
    function deleteFromServer()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->DeleteFromServer;
    }

  /*!
    */
    function setDeleteFromServer( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->DeleteFromServer = $value;
    }

  /*!
    */
    function isActive()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->IsActive;
    }

  /*!
    */
    function setIsActive( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->IsActive = $value;
    }

    /*!
    */
    function server()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->IsActive;
    }

  /*!
    */
    function setServer( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->IsActive = $value;
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

    
    var $ID
    var $UserID;
    var $Name;
    var $LoginName;
    var $Password;
    var $Server;
    var $DeleteFromServer;
    var $IsActive;
    var $ServerType;
    
    var $Database;
    var $IsConnected;
    var $State_;
}

?>
