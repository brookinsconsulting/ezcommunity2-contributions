<?
// 
// $Id: ezvoucherinformation.php,v 1.8.4.9 2001/11/05 08:31:09 ce Exp $
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
include_once( "eztrade/classes/ezproduct.php" );

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
            if ( is_numeric ( $id ) )
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
        
        $description =& $db->escapeString( $this->Description );
        $toName =& $db->escapeString( $this->ToName );
        $fromName =& $db->escapeString( $this->FromName );

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_VoucherInformation" );
            $nextID = $db->nextID( "eZTrade_VoucherInformation", "ID" );            
            $timeStamp =& eZDateTime::timeStamp( true );

            $res = $db->query( "INSERT INTO eZTrade_VoucherInformation
                      ( ID, VoucherID, ToOnlineID, ToAddressID, FromAddressID, Description, PreOrderID, MailMethod, FromName, ToName, FromOnlineID, Price, UserID )
                      VALUES
                      ( '$nextID',
                        '$this->VoucherID',
                        '$this->ToOnlineID',
                        '$this->ToAddressID',
                        '$this->FromAddressID',
                        '$description',
                        '$this->PreOrderID',
                        '$this->MailMethod',
                        '$fromName',
                        '$toName',
                        '$this->FromOnlineID',
                        '$this->Price',
                        '$this->UserID'
                         )
                     " );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZTrade_VoucherInformation SET
                                     VoucherID='$this->VoucherID',
                                     ToOnlineID='$this->ToOnlineID',
                                     ToAddressID='$this->ToAddressID',
                                     FromAddressID='$this->FromAddressID',
                                     Description='$description',
                                     PreOrderID='$this->PreOrderID',
                                     MailMethod='$this->MailMethod',
                                     FromName='$toName',
                                     ToName='$fromName',
                                     FromOnlineID='$this->FromOnlineID',
                                     Price='$this->Price',
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
        $db =& eZDB::globalDatabase();
        $this->ID =& $value[$db->fieldName( "ID" )];
        $this->Description =& $value[$db->fieldName( "Description" )];
        $this->ToOnlineID =& $value[$db->fieldName( "ToOnlineID" )];
        $this->ToAddressID =& $value[$db->fieldName( "ToAddressID" )];
        $this->FromAddressID =& $value[$db->fieldName( "FromAddressID" )];
        $this->VoucherID =& $value[$db->fieldName( "VoucherID" )];
        $this->PreOrderID =& $value[$db->fieldName( "PreOrderID" )];
        $this->MailMethod =& $value[$db->fieldName( "MailMethod" )];
        $this->FromOnlineID =& $value[$db->fieldName( "FromOnlineID" )];
        $this->FromName =& $value[$db->fieldName( "FromName" )];
        $this->ToName =& $value[$db->fieldName( "ToName" )];
        $this->Price =& $value[$db->fieldName( "Price" )];
        $this->UserID =& $value[$db->fieldName( "UserID" )];
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

        $db->array_query( $quizArray, "SELECT ID
                                           FROM eZTrade_VoucherInformation
                                           ORDER BY StartDate DESC",
        array( "Limit" => $limit,
               "Offset" => $offset  ) );

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
      Returns the to name of the voucher smail.
    */
    function &toName()
    {
        return $this->ToName;
    }

    /*!
      Returns the from name of the voucher smail.
    */
    function &fromName()
    {
        return $this->FromName;
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
      Sets the to name.
    */
    function setToName( &$value )
    {
        $this->ToName = $value;
    }

    /*!
      Sets the from name.
    */
    function setFromName( &$value )
    {
        $this->FromName = $value;
    }

    /*!
      Sets the user object that is creating this information.
    */
    function setUser( &$value )
    {
        if ( get_class ( $value ) == "ezuser" )
            $this->UserID = $value->id();
        elseif ( $value )
            $this->UserID = $value;
    }

    /*!
      Sets the email for the voucher smail.
    */
    function setToOnline( &$value )
    {
        if ( get_class ( $value ) == "ezonline" )
            $this->ToOnlineID = $value->id();
        elseif ( $value )
            $this->ToOnlineID = $value;
    }

    /*!
      Sets the email for the voucher smail.
    */
    function setFromOnline( &$value )
    {
        if ( get_class ( $value ) == "ezonline" )
            $this->FromOnlineID = $value->id();
        else
            $this->FromOnlineID = $value;
    }

    /*!
      Sets the email for the voucher smail.
    */
    function setToAddress( &$value )
    {
        if ( get_class ( $value ) == "ezaddress" )
            $this->ToAddressID = $value->id();
        else
            $this->ToAddressID = $value;
    }

    /*!
      Sets the email for the voucher smail.
    */
    function setFromAddress( &$value )
    {
        if ( get_class ( $value ) == "ezaddress" )
            $this->FromAddressID = $value->id();
        else
            $this->FromAddressID = $value;
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
      Returns the user that created this information.
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
      Returns the email
    */
    function &toOnline( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZOnline( $this->ToOnlineID );
        else
            $ret = $this->ToOnlineID;

        return $ret;
    }

    /*!
      Returns the email
    */
    function &fromOnline( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZOnline( $this->FromOnlineID );
        else
            $ret = $this->FromOnlineID;

        return $ret;
    }

    /*!
      Returns the smail
    */
    function &toAddress( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZAddress( $this->ToAddressID );
        else
            $ret = $this->ToAddressID;

        return $ret;
    }

    /*!
      Returns the smail
    */
    function &fromAddress( $asObject=true )
    {
        if ( $asObject )
            $ret = new eZAddress( $this->FromAddressID );
        else
            $ret = $this->FromAddressID;

        return $ret;
    }

    /*!
      Returns the voucher
    */
    function &voucher( $asObject=true )
    {
        if ( $asObject )
        {
            print( $this->VoucherID );
            return new eZVoucher( $this->VoucherID );
        }
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
    function &preOrderID( $asObject=true )
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

        $fromUser =& $this->fromOnline();
        
        $Language = $ini->read_var( "eZTradeMain", "Language" );

        $locale = new eZLocale( $Language );
        
        $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                             "eztrade/user/intl/", $Language, "voucheremail.php" );

        $languageIni = new INIFile( "eztrade/user/" . "intl/" . $Language . "/voucheremail.php.ini", false );  

        $t->setAllStrings();
        
        $t->set_file( "voucheremail", "voucheremail.tpl" );

//        $t->set_block( "voucheremail", "never_expire_tpl", "never_expire" );
        
        $mail = new eZMail();
        
        $t->set_block( "voucheremail", "intro", "bought" );
        $t->set_block( "voucheremail", "intro", "free" );	
        $t->set_block( "voucheremail", "intro_free_tpl", "intro_free" );
        $t->set_block( "voucheremail", "intro_bought_tpl", "intro_bought" );
        
        $t->set_var( "description", $this->description() );
        $t->set_var( "from_name", $this->fromName() );
        $t->set_var( "to_name", $this->toName() );
        $t->set_var( "key_number", $voucher->keyNumber() );

        $currency = new eZCurrency();
        $currency->setValue( $voucher->price() );
        $t->set_var( "voucher_value", $locale->format( $currency, true, false ) );

        $voucherIsBought = true; // Dummy Value
        
        if ( $voucherIsBought == true )
        {
            $t->set_var( "intro_free", "" );
            $t->parse( "intro_bought", "intro_bought_tpl" );
        }
        else
        {
            $t->set_var( "intro_bought", "" );
            $t->parse( "intro_free", "intro_free_tpl" );	    
        }

        $mailAddress = $this->toOnline();

        $mail->setSubject( $GLOBALS["SERVER_NAME"] . ": " . $languageIni->read_var( "strings", "subject" ) );
        $mail->setTo( $mailAddress->url() );
        $mail->setBody( $t->parse( "dummy", "voucheremail" ) );
        $mail->setFrom( $fromUser->url() );
        $mail->send();
    }

    /*!
      \private
      Send a snail mail based on a LaTeX file.
    */
    function sendSMail()
    {
        $ini =& INIFile::globalINI();

        $voucher =& $this->voucher();

        $Language = $ini->read_var( "eZTradeMain", "Language" );
        
        $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                             "eztrade/user/intl/", $Language, "vouchersmail.php" );

        $t->setAllStrings();
        
        $t->set_file( "vouchersmail", "smail.tpl" );
        
        $t->set_var( "description", $this->description() );
        $t->set_var( "from_name", $this->fromName() );
        $t->set_var( "to_name", $this->toName() );
        $t->set_var( "key_number", $voucher->keyNumber() );

        $toAddress =& $this->toAddress();
        $fromAddress =& $this->fromAddress();

        $t->set_var( "to_address_street1", $toAddress->street1() );
        $t->set_var( "to_address_street2", $toAddress->street1() );
        $t->set_var( "to_address_street1", $toAddress->street1() );

        $cmd = "latex < " . $t->parse( "dummy", "vouchersmail" ) ;

    }

    var $ID;
    var $Description;
    var $PreOrderID;
    var $ToOnlineID;
    var $ToAddressID;
    var $FromAddressID;
    var $VoucherID;
    var $MailMethod;
    var $Price;
    var $ToName;
    var $FromName;
    var $FromOnlineID;
    var $UserID;
}

?>
