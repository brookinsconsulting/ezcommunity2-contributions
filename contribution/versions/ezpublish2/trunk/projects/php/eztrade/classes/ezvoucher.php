<?
// 
// $Id: ezvoucher.php,v 1.6 2001/09/05 09:32:48 ce Exp $
//
// eZVoucher class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <19-Jun-2001 17:41:06 ce>
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

//!! ezquizgame
//! ezquizgame documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdate.php" );
include_once( "ezquiz/classes/ezquizquestion.php" );
	      
class eZVoucher
{

    /*!
      Constructs a new eZVoucher object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZVoucher( $id=-1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a eZVoucher object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
       
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_Voucher" );
            $nextID = $db->nextID( "eZTrade_Voucher", "ID" );            
            $timeStamp =& eZDateTime::timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZTrade_Voucher
                      ( ID, Created, Price, Available, KeyNumber, MailMethod, UserID )
                      VALUES
                      ( '$nextID',
                        '$timeStamp',
                        '$this->Price',
                        '$this->Available',
                        '$this->KeyNumber',
                        '$this->MailMethod',
                        '$this->UserID'

                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZTrade_Voucher SET
                                     Created=Created,
                                     Price='$this->Price',
                                     Available='$this->Available',
                                     MailMethod='$this->MailMethod',
                                     KeyNumber='$this->KeyNumber',
                                     UserID='$this->UserID'
                                     WHERE ID='$this->ID'" );
        }
        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZVoucher object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $res = $db->query( "DELETE FROM eZTrade_Voucher WHERE ID='$this->ID'" );
    
        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $voucherArray, "SELECT * FROM eZTrade_Voucher WHERE ID='$id'",
                              0, 1 );
            if( count( $voucherArray ) == 1 )
            {
                $this->fill( &$voucherArray[0] );
                $ret = true;
            }
            elseif( count( $voucherArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$voucherArray )
    {
        $this->ID =& $voucherArray[ "ID" ];
        $this->Created =& $voucherArray[ "Created" ];
        $this->Price =& $voucherArray[ "Price" ];
        $this->Available =& $voucherArray[ "Available" ];
        $this->KeyNumber =& $voucherArray[ "KeyNumber" ];
        $this->MailMethod =& $voucherArray[ "MailMethod" ];
        $this->UserID =& $voucherArray[ "UserID" ];
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZVoucher objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $voucherArray = array();

        if ( $limit == false )
        {
            $db->array_query( $voucherArray, "SELECT ID
                                           FROM eZTrade_Voucher
                                           " );

        }
        else
        {
            $db->array_query( $voucherArray, "SELECT ID
                                           FROM eZTrade_Voucher
                                           ", array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i=0; $i < count($voucherArray); $i++ )
        {
            $returnArray[$i] = new eZVoucher( $voucherArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZTrade_Voucher" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the creation time of the voucher.
    */
    function &created()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Created );

        return $dateTime;
    }

    /*!
      Sets the login.
    */
    function generateKey( $length=15 )
    {
        $this->KeyNumber = substr( md5( microtime() ), 0, $length );
    }

    /*!
      Returns the keynumber
    */
    function keyNumber( )
    {
        return $this->KeyNumber;
    }

    /*!
      Returns the user
    */
    function &user( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZUser( $this->UserID );
        else
            $ret = $this->UserID;
        
        return $ret;
    }

    /*!
      Sets if the voucher is available or not.
    */
    function setAvailable( $value )
    {
        if ( $value == true )
            $this->Available = 1;
        else
            $this->Available = 0;
    }

    /*!
      Returns true if the voucher is avaiable
    */
    function available()
    {
        if ( $this->Available == 1 )
            return true;
        else
            return false;
    }

    /*!
      Sets the voucher price.
    */
    function setPrice( $value )
    {
       $this->Price = $value;
       setType( $this->Price, "double" );
    }

    /*!
      Sets the voucher mail method.
    */
    function setMailMethod( $value )
    {
       $this->MailMethod = $value;
    }

    /*!
      Sets the user of this object.
    */
    function setUser( &$user )
    {
        if ( get_class ( $user ) == "ezuser" )
            $this->UserID = $user->id();
        elseif ( is_numeric ( $user ) )
            $this->UserID = $user;
    }

    /*!
      Returns the price of the voucher.
    */
    function &price( )
    {
        return $this->Price;
    }

    /*!
      Returns the mail method of the voucher.
    */
    function mailMethod( )
    {
        return $this->MailMethod;
    }

    /*!
      Returns the price of the voucher.
    */
    function sendMail( )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT MailMethod FROM eZTrade_Voucher WHERE ID='$this->ID'" );

        if ( $res[$db->fieldName( "MailMethod" )] == 1 )
            $this->sendEMail();
        elseif ( $res[$db->fieldName( "MailMethod" )] == 2 )
            $this->sendSMail();
        
        return $this->Price;
    }

    /*!
      \private
      Mail the user.
    */
    function sendEMail()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT * FROM eZTrade_VoucherEMail WHERE VoucherID='$this->ID'" );

        $ini =& INIFile::globalINI();
        $fromUser = $this->user();
        
        $Language = $ini->read_var( "eZTradeMain", "Language" );
        
        $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                             "eztrade/user/intl/", $Language, "voucheremail.php" );

        $t->setAllStrings();
        
        $t->set_file( "voucheremail", "voucheremail.tpl" );

        $mail = new eZMail();

        $t->set_var( "description", $res[$db->fieldName( "Description" )] );
        $t->set_var( "from_name", $fromUser->firstName() . " " . $fromUser->lastName() );
        $t->set_var( "key_number", $this->keyNumber() );
        
        $mailAddress = new eZOnline( $res[$db->fieldName( "OnlineID" )] );

        $mail->setTo( $mailAddress->url() );
        $mail->setBody( $t->parse( "dummy", "voucheremail" ) );
        $mail->setFrom( $fromUser->email() );
        $mail->send();
    }

    /*!
      Get a voucher from a key number.
    */
    function getFromKeyNumber( &$key )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        if ( !$key )
            return false;
        
        $db->query_single( $res, "SELECT ID FROM eZTrade_Voucher WHERE KeyNumber='$key'" );

        if ( $res["ID"] )
        {
            $ret = new eZVoucher( $res["ID"] );
        }

        return $ret;
    }
  

    var $ID;
    var $KeyNumber;
    var $Created;
    var $Available;
    var $Price;
    var $UserID;
    var $MailMethod;
}

?>
