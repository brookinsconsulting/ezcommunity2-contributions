<?php
// 
// $Id: ezpayboxmessage.php,v 1.1.2.1 2001/11/22 09:52:40 ce Exp $
//
// Definition of eZPayboxMessage class
//
// Jan Borsodi <jb@ez.no>
// Created on: <23-Apr-2001 14:29:38 amos>
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

//!! 
//! The class eZPayboxMessage handles communication with the paybox system.
/*!

  \code
  $message = new eZPayboxMessage( $host, $port );

  $message->setLanguage( "de" );
  $message->setAuthenticationType( "test" );
  $message->setPayerNumber( $payernumber );
  $message->setPayeeNumber( "+4900011161914" );
  $message->setAmount( 1 );
  $message->setCurrency( "DEM" );
  $message->setPaymentDays( 10 );
  $message->setPreOrderID( 200 );

  $message->transfer();
  if ( $message->receive() )
  {
      $message->sendAcknowledge();
  }
  else
  {
      print( "Error #$message->ErrorCode, $message->LongErrorText\n" );
  }

  $message->closeSocket();
  \endcode
*/

include_once( "classes/ezlog.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezsession/classes/ezsession.php" );

class eZPayboxMessage
{
    function eZPayboxMessage( $host = "127.0.0.1", $port = 55001 )
    {
        $this->Host = $host;
        $this->Port = $port;
        if ( !$this->openSocket() )
            print( "<br /><b>Error when opening socket on host $host using port $port</b><br />" );
    }

    /*!
      Returns the error text from the last operation, if long is true the long version is returned
      otherwise the short version.
    */
    function errorText( $long = true )
    {
        return $long ? $this->LongErrorText : $this->ShortErrorText;
    }

    /*!
      Returns the errorcode from the last operation, if it is 0 no error occured.
    */
    function errorCode()
    {
        return $this->ErrorCode;
    }

    /*!
      Returns the language used.
    */
    function language()
    {
        return $this->Language;
    }

    /*!
      Returns the authentication type, usually test or norm.
    */
    function authenticationType()
    {
        return $this->AuthType;
    }

    /*!
      Returns the payernumber.
    */
    function payerNumber()
    {
        return $this->PayerNumber;
    }

    /*!
      Returns the payeenumber.
    */
    function payeeNumber()
    {
        return $this->PayeeNumber;
    }

    /*!
      Returns point of sale id.
    */
    function pointOfSale()
    {
        return $this->PointOfSale;
    }

    /*!
      Returns amount as double.
    */
    function amount()
    {
        $amount = $this->Amount;
        settype( $amount, "double" );
        $amount /= 100.0;
        return $amount;
    }

    /*!
      Returns currency in use.
    */
    function currency()
    {
        return $this->Currency;
    }

    /*!
      Returns date in use.
    */
    function date()
    {
        return $this->Date;
    }

    /*!
      Returns time in use.
    */
    function time()
    {
        return $this->Time;
    }

    /*!
      Returns the payment days.
    */
    function paymentDays()
    {
        return $this->PaymentDays;
    }

    /*!
      Returns the pre order id.
    */
    function preOrderID()
    {
        return $this->PreOrderID;
    }

    /*!
      Returns the transaction number from the reply.
    */
    function transactionNumber()
    {
        return $this->TransactionNumber;
    }
    /*!
      Sets the language of the transaction, for instance: "de"
    */
    function setLanguage( $lang )
    {
        $this->Language = $lang;
    }

    /*!
      Sets the authentication type, use "test" for testing and "norm" for normal usage.
    */
    function setAuthenticationType( $type )
    {
        $this->AuthType = $type;
    }

    /*!
      Sets the payer number.
    */
    function setPayerNumber( $number )
    {
        $this->PayerNumber = $number;
    }

    /*!
      Sets the payee number.
    */
    function setPayeeNumber( $number )
    {
        $this->PayeeNumber = $number;
    }

    /*!
      Sets the point of sale id.
    */
    function setPointOfSale( $pos )
    {
        $this->PointOfSale = $pos;
    }

    /*!
      Sets the amount, input is a double.
    */
    function setAmount( $amount )
    {
        $this->Amount = $amount;
        $this->Amount *= 100;
        settype( $this->Amount, "integer" );
    }

    /*!
      Sets the currency, it is 3 letter (ISO Standard), for instance: "DEM"
    */
    function setCurrency( $currency )
    {
        $this->Currency = $currency;
    }

    /*!
      Sets the number of payment days.
    */
    function setPaymentDays( $days )
    {
        $this->PaymentDays = $days;
    }

    /*!
      Sets the pre order id.
    */
    function setPreOrderID( $id )
    {
        $this->PreOrderID = $id;
    }

    /*!
      Transfer information to the LHL with the current payment.
    */
    function transfer()
    {
        if ( !$this->Socket )
            return false;

        $session =& eZSession::globalSession();

        $dateTime = new eZDateTime();

        $date = $dateTime->year() . $dateTime->addZero( $dateTime->month() ) .  $dateTime->addZero( $dateTime->day() );

        $time = $dateTime->addZero( $dateTime->hour() ) . ":" . $dateTime->addZero( $dateTime->minute() ) . ":" .  $dateTime->addZero( $dateTime->second() );
        $this->Date = $date;
        $this->Time = $time;

        $timestamp = $time . "." . $date;

        $tryNr = $session->variable( "PaymentTry" );
        setType( $tryNr, "integer" );
        $ordernr = $this->PreOrderID . "#" . $tryNr;

        if ( $this->AuthType == "" )
            $this->AuthType == "norm";

        $msg = "<?xml version=\"1.0\" standalone=\"no\" ?>" .
             "<!DOCTYPE Transaction SYSTEM \"http://www.paybox.net/pbxtrans.dtd\">" .
             "<Transaction Language=\"$this->Language\" Source=\"cartridge\" AuthorizationType=\"$this->AuthType\"> " .
             " <PaymentRequest  " .
             "PayerPayboxNumber=\"$this->PayerNumber\"  " .
             "PayeePayboxNumber=\"$this->PayeeNumber\"  " .
//             'PointOfSaleID='.'"'.$this->PointOfSale.'" ' .
             "Amount=\"$this->Amount\"  " .
             "Currency=\"$this->Currency\"  " .
             "Timestamp=\"$timestamp\"  " .
             "PaymentDays=\"$this->PaymentDays\"  " .
             "OrderNumber=\"$ordernr\">  " .
             "</PaymentRequest> " .
             "<AttachedData> " .
             "<Checksum code=\"paybox.cartridge\"> " .
             " </Checksum> " .
             "</AttachedData> " .
             "</Transaction> ";

//            if ( $GLOBALS["DEBUG"] )
//                eZLog::writeNotice( "Sent: $msg" );

        fputs( $this->Socket, "$msg\n" );
    }

    /*!
      Tries to receive the reply from the LHL regarding the current transfer.
      Returns false if it fails.
    */
    function receive()
    {
        if ( !$this->Socket )
            return false;
        $msg = "";
        while( !feof( $this->Socket ) )
        {
            $msg .= fgets( $this->Socket,1024 );
            if( ereg(".*</Transaction>*.",$msg) )
            {
                break;
            }
        }

        $xml =& qdom_tree( $msg );
        if ( empty( $msg ) or !$xml )
        {
            $this->ErrorCode = -1;
            $this->ShortErrorText = "";
            $this->LongErrorText = "";
            return false;
        }
        else
        {
//            if ( $GLOBALS["DEBUG"] )
//                eZLog::writeNotice( "Received: $msg" );
            $this->parseResponse( $xml );
            if ( trim( $this->ErrorCode ) != "0" )
                return false;
        }
        return true;
    }

    /*!
      Sends an acknowledge to the LHL saying the reply was received.
    */
    function sendAcknowledge()
    {
        if ( !$this->Socket or $this->TransactionNumber == "" )
            return false;

        $msg ="<"."?"."xml version=\"1.0\" standalone=\"no\""."?".">" .
             "<!DOCTYPE Transaction SYSTEM \"http://www.paybox.net/pbxtrans.dtd\">" .
             "<Transaction Language=\"$this->Language\" Source=\"cartridge\" AuthorizationType=\"$this->AuthType\" >" .
             "<AcknowledgeMessage TransactionNumber=\"$this->TransactionNumber\" >" .
             "</AcknowledgeMessage>" .
             "</Transaction>";

        fputs( $this->Socket, "$msg\n" );
//        if ( $GLOBALS["DEBUG"] )
//            eZLog::writeNotice( "Acknowledge: $msg" );
        return true;
    }

    /*!
      Parses the XML message and extracts the transaction number and error messages.
    */
    function &parseResponse( &$xml )
    {
        $this->XMLMessage =& eZPayboxMessage::parseXML( $xml );
        $pay =& $this->XMLMessage["Transaction"]["children"]["PaymentResponse"];
        $this->TransactionNumber = $pay["TransactionNumber"];
        $this->ErrorCode = $pay["ErrorCode"];
        $this->ShortErrorText = $pay["ShortErrorText"];
        $this->LongErrorText = $pay["LongErrorText"];
    }

    /*!
      \static
      Parses an XML message into a tree array.
    */
    function &parseXML( &$xml )
    {
        $msg = array();
        eZPayboxMessage::parseXMLPart( $xml, $msg );
        return $msg;
    }

    /*!
      \static
      \private
      Helper function for parseXML.
    */
    function parseXMLPart( &$xml, &$msg )
    {
        foreach( $xml->children as $child )
        {
            $part = array();
            if ( isset( $child->attributes ) )
            {
                foreach( $child->attributes as $attr )
                {
                    if ( $attr->type == 2 )
                    {
                        $part[$attr->name] =& $attr->content;
                    }
                }
            }
            if ( isset( $child->children ) )
            {
                $children = array();
                eZPayboxMessage::parseXMLPart( $child, $children );
                $part["children"] =& $children;
            }
            $msg[$child->name] = $part;
        }
    }

    /*!
      Opens a socket connection to a specific host and port.
      Host and port is specified in the constructor.
      Returns false if the socket could not be opened.
    */
    function openSocket()
    {
        if( isset($this->Host) && isset($this->Port) )
        {
            $this->Socket = fsockopen( $this->Host, $this->Port, $errno, $errstr );
            if( !$this->Socket )
            {
                print( "Socket Error #$errno, $errstr\n" );
                return false;
            }
        }
        else
        {
            return false;
        }
        return true;
    }

    /*!
      Closes the open socket if any.
      Returns false if no socket was open.
    */
    function closeSocket()
    {
        if ( !$this->Socket )
            return false;
        fclose( $this->Socket );
        return true;
    }

    var $Language;
    var $AuthType;
    var $PayerNumber;
    var $PayeeNumber;
    var $PointOfSale;
    var $Amount;
    var $Currency;
    var $PaymentDays;
    var $PreOrderID;
    var $Date;
    var $Time;

    var $TransactionNumber;
    var $ErrorCode;
    var $ShortErrorText;
    var $LongErrorText;
    var $XMLMessage;

    var $Host;
    var $Port;
    var $Socket;
}

?>
