<?
// 
// $Id: ezvoucherinformation.php,v 1.1 2001/09/21 09:59:05 ce Exp $
//
// eZVoucherInformation class
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

//!! ezquizvoucher smail
//! ezquizvoucher smail documentation.
/*!

  Example code:
  \code
  $voucherInfo = new eZVoucherInformation(); // Create a new object.
  $online = new eZOnline( 7 ); // Get eZOnline object with id 7.
  $voucher = new eZVoucher( 4 ); // Get voucher object with id 4.
  $voucherInfo->setEmail( $online ); // Add the online object.
  $voucherInfo->setPreOrder( 10 ); // Add the pre order id.
  $voucherInfo->setDescription( $Description ); // Add a description.
  $voucherInfo->setVoucher( $voucher ); // Adds the eZVoucher objdect.
  $voucherInfo->store(); // Stores the object to the database
  \endcode

  \sa eZVoucherUsed eZVoucher eZVoucherSMail
*/

include_once( "eztrade/classes/ezvoucher.php" );
include_once( "eztrade/classes/ezpreorder.php" );

include_once( "ezaddress/classes/ezonline.php" );

class eZVoucherInformation
{

    /*!
      Constructs a new eZVoucherInformation object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZVoucherInformation( $id=-1 )
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
      Stores a eZVoucherInformation object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $description =& addslashes( $this->Description );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_VoucherInformation" );
            $nextID = $db->nextID( "eZTrade_VoucherInformation", "ID" );            
            $timeStamp =& eZDateTime::timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZTrade_VoucherInformation
                      ( ID, VoucherID, OnlineID, AddressID, Description, PreOrderID, MailMethod, Price )
                      VALUES
                      ( '$nextID',
                        '$this->VoucherID',
                        '$this->OnlineID',
                        '$this->AddressID',
                        '$description',
                        '$this->PreOrderID',
                        '$this->MailMethod',
                        '$this->Price'
                         )
                     " );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZTrade_VoucherInformation SET
                                     VoucherID='$this->VoucherID',
                                     OnlineID='$this->OnlineID',
                                     AddressID='$this->AddressID',
                                     Description='$this->Description',
                                     PreOrderID='$this->PreOrderID',
                                     MailMethod='$this->MailMethod',
                                     Price='$this->Price'
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
      Deletes a eZVoucherInformation object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $res = $db->query( "DELETE FROM eZTrade_VoucherInformation WHERE ID='$this->ID'" );
    
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
            $db->array_query( $quizArray, "SELECT * FROM eZTrade_VoucherInformation WHERE ID='$id'",
                              0, 1 );
            if( count( $quizArray ) == 1 )
            {
                $this->fill( &$quizArray[0] );
                $ret = true;
            }
            elseif( count( $quizArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$value )
    {
        $this->ID =& $value[ "ID" ];
        $this->Description =& $value[ "Description" ];
        $this->OnlineID =& $value[ "OnlineID" ];
        $this->AddressID =& $value[ "AddressID" ];
        $this->VoucherID =& $value[ "VoucherID" ];
        $this->PreOrderID =& $value[ "PreOrderID" ];
        $this->MailMethod =& $value[ "MailMethod" ];
        $this->Price =& $value[ "Price" ];
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZVoucherInformation objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $quizArray = array();

        if ( $limit == false )
        {
            $db->array_query( $quizArray, "SELECT ID
                                           FROM eZTrade_VoucherInformation
                                           ORDER BY StartDate DESC
                                           " );

        }
        else
        {
            $db->array_query( $quizArray, "SELECT ID
                                           FROM eZTrade_VoucherInformation
                                           ORDER BY StartDate DESC
                                           LIMIT $offset, $limit" );
        }

        for ( $i=0; $i < count($quizArray); $i++ )
        {
            $returnArray[$i] = new eZVoucherInformation( $quizArray[$i][$db->fieldName( "ID" )] );
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
                                     FROM eZTrade_VoucherInformation" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID to the voucher smail. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the description of the voucher smail.
    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Sets the description.
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }

    /*!
      Returns the price of the voucher smail.
    */
    function &price()
    {
        return $this->Price;
    }

    /*!
      Returns the correct price of the product based on the logged in user, and the
      VAT status and use.
    */
    function &correctPrice( $calcVAT, &$product )
    {
        $inUser =& eZUser::currentUser();
        
        $price = $this->Price;

        $vatType =& $product->vatType();
       
        if ( $calcVAT == true )
        {
            if ( $product->excludedVAT() )
            {
                $vatType =& $product->vatType();
                $vat = 0;
       
                if ( $vatType )
                {
                    $vat =& $vatType->value();
                }
                
                $price = ( $price * $vat / 100 ) + $price;
            }
        }
        else
        {
            if ( $product->includesVAT() )
            {
                $vatType =& $product->vatType();
                $vat = 0;
                
                if ( $vatType )
                {
                    $vat =& $vatType->value();
                }
                
                $price = $price - ( $price / ( 100 + $vat ) ) * $vat;
                
            }
        }

        return $price;
    }

    /*!
      Sets the description.
    */
    function setPrice( &$value )
    {
        $this->Price = $value;
    }

    /*!
      Sets the email for the voucher smail.
    */
    function setEmail( &$value )
    {
        if ( get_class ( $value ) == "ezonline" )
            $this->OnlineID = $value->id();
        else
            $this->OnlineID = $value;
    }

    /*!
      Sets the email for the voucher smail.
    */
    function setAddress( &$value )
    {
        if ( get_class ( $value ) == "ezaddress" )
            $this->AddressID = $value->id();
        else
            $this->AddressID = $value;
    }

    /*!
      Sets the voucher for the voucher smail.
    */
    function setVoucher( &$value )
    {
        if ( get_class ( $value ) == "ezvoucher" )
            $this->VoucherID = $value->id();
        else
            $this->VoucherID = $value;
    }

    /*!
      Sets the mail method.
    */
    function setMailMethod( $value )
    {
        $this->MailMethod = $value;
    }

    /*!
      Sets the preorder for the voucher smail.
    */
    function setPreOrder( &$value )
    {
        if ( get_class ( $value ) == "ezpreorder" )
            $this->PreOrderID = $value->id();
        else
            $this->PreOrderID = $value;
    }

    /*!
      Returns the email
    */
    function online( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZOnline( $this->OnlineID );
        else
            $ret = $this->OnlineID;

        return $ret;
    }

    /*!
      Returns the smail
    */
    function address( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZAddress( $this->AddressID );
        else
            $ret = $this->AddressID;

        return $ret;
    }

    /*!
      Returns the voucher
    */
    function voucher( $asObject=true )
    {
        if ( $asObject )
            return new eZVoucher( $this->VoucherID );
        else
            return $this->VoucherID;
    }

    /*!
      Returns the mail method.
    */
    function mailMethod( )
    {
        return $this->MailMethod;
    }

    /*!
      Returns the pre order
    */
    function preOrderID( $asObject=true )
    {
        if ( $asObject )
            return eZPreOrderID( $this->PreOrderID );
        else
            return $this->PreOrderID;
    }

        /*!
      Returns the price of the voucher.
    */
    function sendMail( )
    {
        if ( $this->MailMethod == 1 )
            $this->sendEMail();
        elseif ( $this->MailMethod == 2 )
            $this->sendSMail();
        
        return $this->Price;
    }

    /*!
      \private
      Mail the user.
    */
    function sendEMail()
    {
        $ini =& INIFile::globalINI();

        $voucher =& $this->voucher();

        $fromUser =& $voucher->user();
        
        $Language = $ini->read_var( "eZTradeMain", "Language" );
        
        $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                             "eztrade/user/intl/", $Language, "voucheremail.php" );

        $t->setAllStrings();
        
        $t->set_file( "voucheremail", "voucheremail.tpl" );
        
        $mail = new eZMail();
        
        $t->set_var( "description", $this->description() );
        $t->set_var( "from_name", $fromUser->firstName() . " " . $fromUser->lastName() );
        $t->set_var( "key_number", $voucher->keyNumber() );
        
        $mailAddress = $this->online();
        
        $mail->setTo( $mailAddress->url() );
        $mail->setBody( $t->parse( "dummy", "voucheremail" ) );
        $mail->setFrom( $fromUser->email() );
        $mail->send();
    }

    var $ID;
    var $Description;
    var $PreOrderID;
    var $OnlineID;
    var $AddressID;
    var $VoucherID;
    var $MailMethod;
    var $Price;
}

?>
