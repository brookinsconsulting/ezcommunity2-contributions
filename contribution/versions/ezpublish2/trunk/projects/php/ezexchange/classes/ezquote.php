<?php
// 
// $Id: ezquote.php,v 1.8 2001/02/04 19:31:29 jb Exp $
//
// Definition of eZQuote class
//
// Jan Borsodi <jb@ez.no>
// Created on: <29-Jan-2001 11:29:26 amos>
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

//!! eZExchange
//! The class eZQuote does
/*!

*/

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );

define( "QUOTE_TYPE", "quote" );
define( "RFQ_TYPE", "rfq" );
define( "OFFER_TYPE", "offer" );

define( "QUOTE_ALL_TYPE", 0 );
define( "QUOTE_PARTIAL_TYPE", 1 );

class eZQuote
{
    function eZQuote( $id = false )
    {
        if( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if( !empty( $id ) )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        $date = $this->Date->mySQLDate();
        $price = $this->Price;
        if ( $this->QuoteState == "offer" )
            $price = -$price;
        else if ( $this->QuoteState == "rfq" )
            $price = "NULL";
        if ( is_numeric( $price ) )
            $price = "'$price'";
        $common_set = "eZExchange_Quote set
                       Date='$date',
                       ExpireDate=ADDDATE( CURDATE(), INTERVAL '$this->ExpireDays' DAY),
	                   Quantity='$this->Quantity',
	                   Price=$price,
                       Type='$this->Type'";
        if( !isSet( $this->ID ) )
        {
        
            $db->query( "INSERT INTO $common_set" );
            $this->ID = mysql_insert_id();
            return "insert";
        }
        else
        {
            $db->query( "UPDATE $common_set
                                WHERE ID='$this->ID'" );
            return "update";
        }
    }

    function delete( $id = false )
    {
        $db =& eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        if( isset( $id ) && is_numeric( $id ) )
        {
            $db->query( "DELETE FROM eZExchange_UserProductQuoteDict
                         WHERE QuoteID='$id'" );
            $db->query( "DELETE FROM eZExchange_Quote WHERE ID='$id'" );
        }
        return true;
    }

    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if( $id != "" )
        {
            $db->query_single( $exchange_array, "SELECT ID, Date,
                                                TO_DAYS( ExpireDate ) - TO_DAYS( CURDATE() ) AS ExpireDays,
                                                ExpireDate,
                                                Quantity, Price, Type
                                                FROM eZExchange_Quote WHERE ID='$id'" );
            $this->fill( $exchange_array );
        }
    }

    function fill( $exchange_array )
    {
        $this->ID = $exchange_array[ "ID" ];
        $this->Date = new eZDate();
        $this->Date->setMySQLDate( $exchange_array[ "Date" ] );
        $this->ExpireDays = $exchange_array[ "ExpireDays" ];
        $this->ExpireDate = new eZDate();
        $this->ExpireDate->setMySQLDate( $exchange_array[ "ExpireDate" ] );
        $this->Quantity = $exchange_array[ "Quantity" ];
        $this->Price = $exchange_array[ "Price" ];
        $this->Type = $exchange_array[ "Type" ];
        if ( $this->Price < 0 )
        {
            $this->Price = -$this->Price;
            $this->QuoteState = "offer";
        }
        else if ( $this->Price == "NULL" or $this->Price == 0 )
        {
            $this->QuoteState = "rfq";
        }
        else
        {
            $this->QuoteState = "quote";
        }
    }

    function addToUser( $productid, $quoteid = false )
    {
        $db =& eZDB::globalDatabase();
        $user =& eZUser::currentUser();
        $userid = $user->id();
        $db->query( "INSERT INTO eZExchange_UserProductQuoteDict SET
                                 UserID='$userid', ProductID='$productid', QuoteID='$this->ID'" );
    }

    function id()
    {
        return $this->ID;
    }

    function date()
    {
        return $this->Date;
    }

    function expireDays()
    {
        return $this->ExpireDays;
    }

    function expireDate()
    {
        return $this->ExpireDate;
    }

    function quantity()
    {
        return $this->Quantity;
    }

    function price()
    {
        return $this->Price;
    }

    function type()
    {
        return $this->Type;
    }

    function quoteState()
    {
        return $this->QuoteState;
    }

    function setDate( $value )
    {
        $this->Date = $value;
    }

    function setExpireDays( $value )
    {
        $this->ExpireDays = $value;
    }

    function setExpireDate( $value )
    {
        $this->ExpireDate = $value;
    }

    function setQuantity( $value )
    {
        $this->Quantity = $value;
    }

    function setPrice( $value )
    {
        $this->Price = $value;
    }

    function setType( $value )
    {
        $this->Type = $value;
    }

    function setQuoteState( $value )
    {
        $this->QuoteState = $value;
    }

    function user( $id = false, $as_object = true )
    {
        if ( !$id )
            $id = $this->ID;

        $db =& eZDB::globalDatabase();

        $db->array_query( $qry_array, "SELECT UserID FROM eZExchange_UserProductQuoteDict
                                       WHERE QuoteID='$id'", 0, 1 );
        if ( count( $qry_array ) == 0 )
            return false;
        if ( $as_object )
            return new eZUser( $qry_array[0]["UserID"] );
        else
            return $qry_array[0]["UserID"];
    }

    function getUserQuote( $productid, $as_object = true )
    {
        return eZQuote::getQuote( $productid, "quote", $as_object );
    }

    function getUserOffer( $productid, $as_object = true )
    {
        return eZQuote::getQuote( $productid, "offer", $as_object );
    }

    function getUserRFQ( $productid, $as_object = true )
    {
        return eZQuote::getQuote( $productid, "rfq", $as_object );
    }

    function getQuote( $productid, $type, $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        switch( $type )
        {
            case "offer":
            {
                $cond = "< '0'";
                break;
            }
            case "rfq":
            {
                $cond = "IS NULL";
                break;
            }
            default:
            case "quote":
            {
                $cond = ">= '0'";
                break;
            }
        }

        $user =& eZUser::currentUser();
        $userid = $user->id();

        $db->array_query( $qry_array, "SELECT Q.ID FROM eZExchange_UserProductQuoteDict AS UPQD,
                                                        eZExchange_Quote AS Q
                                       WHERE UPQD.ProductID='$productid' AND UPQD.UserID='$userid'
                                       AND Q.ExpireDate >= CURDATE() AND UPQD.QuoteID=Q.ID AND Q.Price $cond", 0, 1 );
        if ( count( $qry_array ) == 0 )
            return false;
        if ( $as_object )
        {
            return new eZQuote( $qry_array[0]["ID"] );
        }
        else
        {
            return $qry_array[0]["ID"];
        }
    }

    function getAllQuotes( $productid, $as_object = true, $price = false )
    {
        return eZQuote::getQuotes( $productid, "quote", $as_object, $price );
    }

    function getAllOffers( $productid, $as_object = true, $price = false )
    {
        return eZQuote::getQuotes( $productid, "offer", $as_object, $price );
    }

    function getAllRFQs( $productid, $as_object = true, $limit = false, $price = false )
    {
        return eZQuote::getQuotes( $productid, "rfq", $as_object );
    }

    function getQuotes( $productid, $type, $as_object = true, $price = false )
    {
        $db =& eZDB::globalDatabase();

        $minor_sort = "Q.Quantity, Q.Type, Q.ExpireDate";
        switch( $type )
        {
            case "offer":
            {
                $cond = "< '0'";
                $order = "ORDER BY Q.Price DESC, $minor_sort";
                break;
            }
            case "rfq":
            {
                $cond = "IS NULL";
                $order = "ORDER BY $minor_sort";
                break;
            }
            default:
            case "quote":
            {
                $cond = ">= '0'";
                $order = "ORDER BY Q.Price DESC, $minor_sort";
                break;
            }
        }

        if ( is_numeric( $price ) )
        {
            switch( $type )
            {
                case "offer":
                {
                    $price = -$price;
                    $price_text = "AND Q.Price='$price'";
                    break;
                }
                case "rfq":
                case "quote":
                {
                    $price_text = "AND Q.Price='$price'";
                    break;
                }
            }
        }

        $db->array_query( $qry_array, "SELECT Q.ID FROM eZExchange_UserProductQuoteDict AS UPQD,
                                                        eZExchange_Quote AS Q
                                       WHERE UPQD.ProductID='$productid' AND Q.ExpireDate >= CURDATE()
                                         AND UPQD.QuoteID=Q.ID AND Q.Price $cond $price_text
                                       $order" );
        $ret_array = array();
        if ( $as_object )
        {
            foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZQuote( $qry["ID"] );
            }
        }
        else
        {
            foreach ( $qry_array as $qry )
            {
                $ret_array[] = $qry["ID"];
            }
        }
        return $ret_array;
    }

    function bestPricedQuote( $productid, $as_object = true )
    {
        return eZQuote::bestPriced( $productid, "quote", $as_object );
    }

    function bestPricedOffer( $productid, $as_object = true )
    {
        return eZQuote::bestPriced( $productid, "offer", $as_object );
    }

    function bestPriced( $productid, $type, $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        switch( $type )
        {
            case "offer":
            {
                $cond = "< '0'";
                $order = "ORDER BY Q.Price DESC";
                break;
            }
            case "rfq":
            {
                $cond = "IS NULL";
                $order = "ORDER BY Q.Quantity";
                break;
            }
            default:
            case "quote":
            {
                $cond = ">= '0'";
                $order = "ORDER BY Q.Price DESC";
                break;
            }
        }

        $db->array_query( $qry, "SELECT Q.ID FROM eZExchange_UserProductQuoteDict AS UPQD,
                                                  eZExchange_Quote AS Q
                                       WHERE UPQD.ProductID='$productid' AND Q.ExpireDate >= CURDATE()
                                         AND UPQD.QuoteID=Q.ID AND Q.Price $cond
                                       GROUP BY Q.Price $order LIMIT 1", 0, 1 );
        if ( count( $qry ) == 0 )
            return false;
        if ( $as_object )
        {
            $ret = new eZQuote( $qry[0]["ID"] );
        }
        else
        {
            $ret = $qry[0]["ID"];
        }
        return $ret;
    }

    function getTotalQuantity( $productid, $price )
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry_array, "SELECT sum( Q.Quantity ) AS Quantity
                                        FROM eZExchange_UserProductQuoteDict AS UPQD,
                                            eZExchange_Quote AS Q
                                        WHERE UPQD.ProductID='$productid' AND Q.ExpireDate >= CURDATE()
                                          AND UPQD.QuoteID=Q.ID AND Q.Price='$price'" );
        return $qry_array["Quantity"];
    }

    function getTotalUsers( $productid, $price )
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $qry_array, "SELECT count( Q.Quantity ) AS Count
                                        FROM eZExchange_UserProductQuoteDict AS UPQD,
                                            eZExchange_Quote AS Q
                                        WHERE UPQD.ProductID='$productid' AND Q.ExpireDate >= CURDATE()
                                          AND UPQD.QuoteID=Q.ID AND Q.Price='$price'" );
        return $qry_array["Count"];
    }

    // not finished
    function match( $quote )
    {
        $match = false;

        if ( get_class( $quote ) == "ezquote" )
        {
            if ( $this->ID == $quote->ID )
            {
                if ( $this->Type == "QUOTE_TYPE" && $quote->type() == "OFFER_TYPE")
                {
                    if ( $this->Date->isGreater( $quote->date(), true ) )
                    {
                    }
                }
                else if ( $this->Type == "OFFER_TYPE" && $quote->type() == "QUOTE_TYPE")
                {
                }
            }
        }

        return $match;
    }

    var $ID;
    var $Date;
    var $ExpireDays;
    var $ExpireDate;
    var $Quantity;
    var $Price;
    var $Type;
    var $QuoteState;
}

?>
