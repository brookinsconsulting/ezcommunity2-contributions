<?
// 
// $Id: ezmailaccount.php,v 1.22 2001/04/27 13:29:51 fh Exp $
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

/* ATTENTION:
   Lots of this code is build upon imap specific features. If you want to understand how this stuff works
   you can find info here www.faqs.org about the protocols:
    RFC821: Simple Mail Transfer Protocol (SMTP). 
    RFC822: Standard for ARPA internet text messages. 
    RFC2060: Internet Message Access Protocol (IMAP) Version 4rev1. 
    RFC1939: Post Office Protocol Version 3 (POP3). 
    RFC977: Network News Transfer Protocol (NNTP). 
    RFC2076: Common Internet Message Headers. 
    RFC2045 , RFC2046 , RFC2047 , RFC2048 & RFC2049: Multipurpose Internet Mail Extensions (MIME). 

    To understand the return types from the imap functions take a look at the mail.h file in the mime c-klient library
    found here: ftp://ftp.cac.washington.edu/imap
 */

include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfunctions.php" );
include_once( "ezmail/classes/ezmailfilterrule.php" );
include_once( "classes/ezhttptool.php" );

class eZMailAccount
{
/************* CONSTRUCTORS DESTRUCTORS (virtual) ************************/    
    /*!
      constructor
    */
    function eZMailAccount( $id="", $fetch=true )
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
      Deletes a eZMailAccount object from the database.
    */
    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();

        if ( $id == -1 )
        {
            $db->query( "DELETE FROM eZMail_Account WHERE ID='$this->ID'" );
        }
        else
        {
            $db->query( "DELETE FROM eZMail_Account WHERE ID='$id'" );
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

        $name = addslashes( $this->Name );
        $loginname = addslashes( $this->LoginName );
        $password = addslashes( $this->Password );
        $server = addslashes( $this->Server );
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZMail_Account SET
		                         UserID='$this->UserID',
                                 Name='$name',
                                 LoginName='$loginname',
                                 Password='$password',
                                 Server='$server',
                                 DeleteFromServer='$this->DeleteFromServer',
                                 IsActive='$this->IsActive',
                                 ServerType='$this->ServerType',
                                 ServerPort='$this->ServerPort'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZMail_Account SET
		                         UserID='$this->UserID',
                                 Name='$name',
                                 LoginName='$loginname',
                                 Password='$password',
                                 Server='$server',
                                 DeleteFromServer='$this->DeleteFromServer',
                                 IsActive='$this->IsActive',
                                 ServerType='$this->ServerType',
                                 ServerPort='$this->ServerPort'
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

                $this->ID =& $account_array[0][ "ID" ];
                $this->UserID =& $account_array[0][ "UserID" ];
                $this->Name =& $account_array[0][ "Name" ];
                $this->LoginName =& $account_array[0][ "LoginName" ];
                $this->Password =& $account_array[0][ "Password" ];
                $this->Server =& $account_array[0][ "Server" ];
                $this->DeleteFromServer =& $account_array[0][ "DeleteFromServer" ];
                $this->IsActive =& $account_array[0][ "IsActive" ];
                $this->ServerType =& $account_array[0][ "ServerType" ];
                $this->ServerPort =& $account_array[0][ "ServerPort" ];

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

    /*
      Returns the ID of this object.
     */
    function id()
    {
        return $this->ID;
    }
    
  /*!
    Returns the ID of the account owner
    */
    function userID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->UserID;
    }

    /*!
      Sets the account owner
    */
    function setUserID( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->UserID = $value;
    }

    /*!
      Sets the account owner with a user.
     */
    function setUser( $user )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $user ) == "ezuser" )
            $this->UserID = $user->id();
    }
    
  /*!
    Returns the name of the account.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Name;
    }

    /*!
      Sets the name of the account.
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Name = $value;
    }

  /*!
    Returns the login name for the account.
    */
    function loginName()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->LoginName;
    }

    /*!
      Sets the login name for the account.
    */
    function setLoginName( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->LoginName = $value;
    }


  /*!
    Returns the password for the account.
   */
    function password()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Password;
    }

    /*!
    Sets the password for the account.
    TODO: encrypt it.
    */
    function setPassword( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Password = $value;
    }    

  /*!
    Returns the server for the account. 
    */
    function server()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Server;
    }

    /*!
      Sets the server for this account.
    */
    function setServer( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Server = $value;
    }

    /*!
      Returns the server port for this account.
     */
    function serverPort()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ServerPort;
    }

    /*!
      Sets the server port for this account.
     */
    function setServerPort( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ServerPort = $value;
    }
    
  /*!
    Returns 1 if mail gets deleted from server after download. 0 If not.
    */
    function deleteFromServer()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->DeleteFromServer;
    }

  /*!
    1- Mail gets deleted from server after download
    0-Leaves the mail alone.
    */
    function setDeleteFromServer( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->DeleteFromServer = $value;
    }

  /*!
    Returns 1 if the account is active. Inactive accounts should not be checked.
    */
    function isActive()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->IsActive;
    }

  /*!
    Sets the account active. Inactive accounts will not be checked.
   */
    function setIsActive( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->IsActive = $value;
    }

    /*!
      Returns the server type..Not used at the moment.
    */
    function serverType()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ServerType;
    }

  /*!
    Sets the server type. Not used at the moment.
    */
    function setServerType( $value )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ServerType = $value;
    }

/********** INTERESTING FUNCTIONS *********************/
    /*!
      \static
      
      Returns true if the given account belongs to the given user.
     */
    function isOwner( $user, $accountID )
    {
        if( get_class( $user ) == "ezuser" )
            $user = $user->id();
        
        $database =& eZDB::globalDatabase();
        $database->query_single( $res, "SELECT UserID from eZMail_Account WHERE ID='$accountID'" );
        if( $res["UserID"] == $user )
            return true;
        
        return false;
    }

    /*!
      \static
      
      Returns all mail accounts for a selected user as an array of eZMailAccount objects.
     */
    function getByUser( $user )
    {
        if( get_class( $user ) == "ezuser" )
            $user = $user->id();
        
        $database =& eZDB::globalDatabase();

        $return_array = array();
        $account_array = array();
 
        $database->array_query( $account_array, "SELECT ID FROM eZMail_Account WHERE UserID='$user'" );
 
        for ( $i=0; $i < count($account_array); $i++ )
        {
            $return_array[$i] = new eZMailAccount( $account_array[$i]["ID"] );
        }
 
        return $return_array; 
    }

    /*
      Checks if there is any new mail in this accounts. Downloads according to the setup, and filters it
      to the correct folder if filters are enabled. Currently this function only works for pop3.
     */
    function checkMail()
    {
        $user = eZUser::currentUser();
        $server = "{" . $this->Server . "/pop3:" .$this->ServerPort ."}";
        $mbox = imap_open( $server, $this->LoginName, $this->Password, OP_HALFOPEN);
        if( $mbox == false )
        {
            $errorMsg = rawurlencode( imap_last_error() );
            eZHTTPTool::header( "Location: /error/error?Info=$errorMsg" );
            exit();
        }

//debug!!!!        
//        $struct = imap_fetchstructure( $mbox, 1 );
//        echo "<pre>"; print_r( $struct ); echo "</pre>";
//        exit;

        // get the inbox... we will be adding mail to this.
        $inbox = eZMailFolder::getSpecialFolder( INBOX );
        $filters = new eZMailFilter();
        
        $num = imap_num_msg( $mbox );         // fetch numbers of all new mails
        for( $i=1; $i <= $num; $i++ )  // go through each mail in inbox
        {
            $headerinfo = imap_header( $mbox, $i );           // fetch mail headers
            if( !eZMail::isDownloaded( $headerinfo->message_id, $user->id() ) )             // check if allready downloaded
            {
                $mail = new eZMail();
                $mail->setOwner( $user );
                $mail->setStatus( UNREAD );
                $mail->setUDate( $headerinfo->udate );
                
                getHeaders( $mail, $mbox, $i ); // fetch header information
                $mail->store(); // to get ID
                
                $mailstructure = imap_fetchstructure( $mbox, $i );
                disectThisPart( $mailstructure, "1", $mbox, $i, $mail );
                $mail->setSize( $mailstructure->bytes );

                $mail->store();
                $mail->markAsDownloaded();
                $inbox->addMail( $mail ); // safety for now while we debug the filters 
                $filters->runFilters( $mail );

                if( $this->DeleteFromServer == true )
                    imap_delete( $mbox, $i );
            }
        }
        
//        $headers = imap_headers( $mbox );
//        print("<pre>"); print_r( $headers ); print("</pre>" );
        imap_close( $mbox, CL_EXPUNGE );
    }
    
    
    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    
    var $ID;
    var $UserID;
    var $Name;
    var $LoginName;
    var $Password;
    var $Server;
    var $ServerPort;
    var $DeleteFromServer;
    var $IsActive;
    var $ServerType;
    
    var $Database;
    var $IsConnected;
    var $State_;
}

?>
