<?
// 
// $Id: ezmailfilterrule.php,v 1.1 2001/03/29 16:40:44 fh Exp $
//
// eZMailFilterRule class
//
//  <fh@ez.no>
// Created on: <29-Mar-2001 14:20:04 fh>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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


//!! eZMailFilterRule
//! eZMailFilterRule documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezmail/classes/ezmail.php" );



class eZMailFilterRule
{
    /*!
      Constructs a new eZMail object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZMailFilterRule( $id="", $fetch=true )
    {
        $this->IsConnected = false;

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
      Deletes a eZMail object from the database.
    */
    function delete( $id = -1 )
    {
        $db = eZDB::globalDatabase();
        if( $id == -1 )
            $id = $this->ID;
        
        $db->query( "DELETE FROM eZMail_MailFilter WHERE ID='$id'" );
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
            $this->Database->query( "INSERT INTO eZMail_FilterRule SET
		                         UserID='$this->UserID',
                                 HeaderType='$this->HeaderType',
                                 CheckType='$this->CheckType',
                                 MatchValue='$this->Match',
                                 FolderID='$this->FolderID',
                                 IsActive='$this->IsActive'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZMail_FilterRule SET
		                         UserID='$this->UserID',
                                 HeaderType='$this->HeaderType',
                                 CheckType='$this->CheckType',
                                 MatchValue='$this->Match',
                                 FolderID='$this->FolderID',
                                 IsActive='$this->IsActive'
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
            $this->Database->array_query( $mail_array, "SELECT * FROM eZMail_FilterRule WHERE ID='$id'" );
            if ( count( $mail_array ) > 1 )
            {
                die( "Error: Mail filters with the same ID where found in the database. This should not happen." );
            }
            else if( count( $mail_array ) == 1 )
            {
                $this->ID =& $mail_array[0][ "ID" ];
                $this->UserID =& $mail_array[0][ "UserID" ];
                $this->HeaderType =& $mail_array[0][ "HeaderType" ];
                $this->CheckType =& $mail_array[0][ "CheckType" ];
                $this->Match =& $mail_array[0][ "MatchValue" ];
                $this->FolderID =& $mail_array[0][ "FolderID" ];
                $this->IsActive =& $mail_array[0][ "IsActive" ];
                
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
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the userID of the user that owns this object
    */
    function owner()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->UserID;;
    }
    
    /*!
      Sets the owner of this mail
    */
    function setOwner( $newOwner )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $newOwner ) == "ezuser" )
            $this->UserID = $newOwner->id();
        else
            $this->UserID = $newOwner;
    }

    /*!
      Returns the header type to check for.
     */
    function headerType()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->HeaderType;
    }

    /*!
      Sets the header type to check for.
     */
    function setHeaderType( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->HeaderType = $value;
    }

    /*!
      Returns the checktype.
     */
    function checkType()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->CheckType;
    }

    /*!
      Set the check type.
     */
    function setCheckType( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->CheckType = $value;
    }

    /*!
      Returns the string that is matched.
     */
    function match()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Match;
    }

    /*!
      Set the string that the filter is matching.
     */
    function setMatch( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Match = $value;
    }

    /*!
    Returns 1 if the filter is active. 
    */
    function isActive()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->IsActive;
    }

  /*!
    Sets the account active. 
   */
    function setIsActive( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->IsActive = $value;
    }

    /*!
      Returns the folder that this
     */
    function folderID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->FolderID;
    }
    
    /*!
      Sets the folderID of the folder that this filter filters mail into.
     */
    function setFolderID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->FolderID = $value;

    }

    /*!
      \static
      
      Returns all mail filters for a selected user as an array of eZMailFilterRules objects.
     */
    function getByUser( $user )
    {
        if( get_class( $user ) == "ezuser" )
            $user = $user->id();
        
        $database =& eZDB::globalDatabase();

        $return_array = array();
        $account_array = array();
 
        $database->array_query( $account_array, "SELECT ID FROM eZMail_FilterRule WHERE UserID='$user'" );
 
        for ( $i=0; $i < count($account_array); $i++ )
        {
            $return_array[$i] = new eZMailFilterRule( $account_array[$i]["ID"] );
        }
 
        return $return_array; 
    }

    /*!
      Applies the filter rule to a mail. Returns true if the filter was successfull.
     */
    function applyFilter( &$mail )
    {
        
        return false;
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

    // the filter
    var $HeaderType;
    var $CheckType;
    var $Match;
    
    var $FolderID;
    var $IsActive;
}

?>
