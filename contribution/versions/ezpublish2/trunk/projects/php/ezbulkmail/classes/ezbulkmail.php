<?
// 
// $Id: ezbulkmail.php,v 1.3 2001/04/19 09:45:22 fh Exp $
//
// eZBulkMail class
//
//  Frederik Holljen<fh@ez.no>
// Created on: <17-Apr-2001 11:53:30 fh>
//
// Copyright (C) .  All rights reserved.
//
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! eZBulkMail
//! eZBulkMail documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "classes/ezdatetime.php" );

class eZBulkMail
{
    /*!
    */
    function eZBulkMail( $id=-1 )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZBulkMail object to the database.
    */
    function store()
    {
        $this->dbInit();
        $bodytext = addslashes( $this->BodyText );
        $subject = addslashes( $this->Subject );
        $replyto = addslashes( $this->ReplyTo );
        $fromname = addslashes( $this->fromname );
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_Mail SET
		                         UserID='$this->UserID',
                                 FromField='$this->FromField',
                                 FromName='$fromname',
                                 ReplyTo='$replyto',
                                 Subject='$subject',
                                 BodyText='$bodytext',
                                 IsDraft='$this->IsDraft'
                                 " );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_Mail SET
		                         UserID='$this->UserID',
                                 FromField='$this->FromField',
                                 FromName='$fromname',
                                 ReplyTo='$replyto',
                                 Subject='$subject',
                                 BodyText='$bodytext',
                                 IsDraft='$this->IsDraft'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBulkMail object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            // delete from BulkMailCategoryLink
            $this->Database->query( "DELETE FROM eZBulkMail_MailCategoryLink WHERE MailID='$this->ID'" );
            // delete actual group entry
            $this->Database->query( "DELETE FROM eZBulkMail_Mail WHERE ID='$this->ID'" );            
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            $this->Database->array_query( $mail_array, "SELECT * FROM eZBulkMail_Mail WHERE ID='$id'" );
            if ( count( $mail_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $mail_array ) == 1 )
            {
                $this->ID = $mail_array[0][ "ID" ];
                $this->UserID = $mail_array[0][ "UserID" ];
                $this->FromField = $mail_array[0][ "FromField" ];
                $this->FromName = $mail_array[0][ "FromName" ];
                $this->ReplyTo = $mail_array[0][ "ReplyTo" ];
                $this->Subject = $mail_array[0][ "Subject" ];
                $this->BodyText = $mail_array[0][ "BodyText" ];
                $this->SentDate = $mail_array[0][ "SentDate" ];
                $this->IsDraft = $mail_array[0][ "IsDraft" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZBulkMail objects.
    */
    function getAll( $draftsOnly = false )
    {
        $db = eZDB::globalDatabase();
        
        $return_array = array();
        $mail_array = array();

        $draftsSQL = "";
        if( $draftsOnly == true )
            $draftsSQL = "WHERE IsDraft='1'";
        
        $db->array_query( $mail_array, "SELECT ID FROM eZBulkMail_Mail $draftsSQL  ORDER BY SentDate" );
        
        for ( $i=0; $i<count($mail_array); $i++ )
        {
            $return_array[$i] = new eZBulkMail( $mail_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }
    
    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the sender address.
    */
    function sender()
    {
        return $this->From;
    }

    /*!
      Sets the sender address.      
    */
    function setSender( $newSender )
    {
        $this->From = $newSender;
    }
    
    /*!
      Returns the subject.
    */
    function subject( $html = true )
    {
        if( $html )
            return htmlspecialchars( $this->Subject );
        return $this->Subject;
    }

    /*!
      Sets the subject of the mail.
    */
    function setSubject( $newSubject )
    {
        $this->Subject = trim( $newSubject );
    }

    /*!
      returns the body.
    */
    function body( $html )
    {
        if( $html )
            return htmlspecialchars( $this->BodyText );
        return $this->BodyText;
    }

    /*!
      Sets the body.
    */
    function setBodyText( $newBody )
    {
        $this->BodyText = $newBody;
    }

    /*!
      Returns the userID of the user that owns this object
    */
    function owner()
    {
        return $this->UserID;;
    }

    /*!
      Sets the owner of this mail
    */
    function setOwner( $newOwner )
    {
        if( get_class( $newOwner ) == "ezuser" )
            $this->UserID = $newOwner->id();
        else
            $this->UserID = $newOwner;
    }

    /*!
      Returns the date that this mail was distributed
     */
    function date( $html = true )
    {
        $dateTime = new eZDateTime( );
        $dateTime->setMySQLTimeStamp( $this->Date );        
        
        if( $html )
            return htmlspecialchars( $dateTime );
        return $dateTime;
    }

    /*!
      Sets the date when this mail was sent.
     */
    function setDate( $value )
    {
        $this->Date = $value;
    }

    /*!
      Returns true if this mail is a draft.
     */
    function isDraft()
    {
        return $this->IsDraft;
    }

    /*!
      Set if this mail is a draft or not.
     */
    function setIsDraft( $value )
    {
        $this->IsDraft = $value;
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
    var $FromName;
    var $FromField;
    var $ReplyTo;
    var $Subject;
    var $BodyText;
    var $SentDate;
    var $IsDraft;
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
    
}

?>
