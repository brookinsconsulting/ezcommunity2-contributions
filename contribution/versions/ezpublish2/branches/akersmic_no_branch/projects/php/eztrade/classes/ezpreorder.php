<?php
// 
// $Id: ezpreorder.php,v 1.10.8.2 2002/01/30 20:47:05 br Exp $
//
// Definition of eZPreOrder class
//
// Created on: <15-Mar-2001 18:11:55 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZTrade
//! eZOrder handles pre-orders.
/*!
  Pre orders is a handler for unique ID's for checkouts. This is needed
  because the orders are not created until the payment is done and
  we need a unique ID for the checkout (VISA clearing etc). This is to prevent
  double charging of the clients.

  \sa eZOrderItem
*/

include_once( "classes/ezdb.php" );


class eZPreOrder
{
    /*!
      Constructs a new eZPreOrder object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZPreOrder( $id="" )
    {
        $this->OrderID = 0;

        if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a order to the database.
    */
    function store()
    {
        $db =& eZDB::globaldatabase();
        $db->begin();
        if ( !isset( $this->ID ) )
        {
            $utref = $db->escapeString( $this->Utref );
            $curry = $db->escapeString( $this->Curry );
            $ertyp = $db->escapeString( $this->Ertyp );
            $ermsg = $db->escapeString( $this->Ermsg );
            
            $db->lock( "eZTrade_PreOrder" );
            $nextID = $db->nextID( "eZTrade_PreOrder", "ID" );
            $timeStamp =& eZDateTime::timeStamp( true );
            $ret[] = $db->query( "INSERT INTO eZTrade_PreOrder
                               ( ID,
		                         OrderID,
		                         Created,
                                 Pnutr,
                                 Utref,
                                 Payco,
                                 Totam,
                                 Curry,
                                 Ttype,
                                 Rtype,
                                 Status,
                                 Ertyp,
                                 Ermsg,
                                 Edate )
                               VALUES
		                       ( '$nextID',
                                 '$this->OrderID',
		                         '$timeStamp',
                                 '$this->Pnutr',
                                 '$this->Utref',
                                 '$this->Payco',
                                 '$this->Totam',
                                 '$curry',
                                 '$this->Ttype',
                                 '$this->Rtype',
                                 '$this->Status',
                                 '$ertyp',
                                 '$ermsg',
                                 '$this->Edate' )" );
            $db->unlock();
			$this->ID = $nextID;
        }
        else
        {
            $ret[] = $db->query( "UPDATE eZTrade_PreOrder SET
		                         Created=Created,
		                         OrderID='$this->OrderID',
                                 Pnutr='$this->Pnutr',
                                 Utref='$this->Utref',
                                 Payco='$this->Payco',
                                 Totam='$this->Totam',
                                 Curry='$curry',
                                 Ttype='$this->Ttype',
                                 Rtype='$this->Rtype',
                                 Status='$this->Status',
                                 Ertyp='$ertyp',
                                 Ermsg='$ermsg',
                                 Edate='$this->Edate'
                                 WHERE ID='$this->ID'
                                 " );
        }
        eZDB::finish( $ret, $db );
        return true;
    }

    /*!
      Deletes a eZOrder object from the database.
    */
    function delete()
    {
        $db =& eZDB::globaldatabase();
        $db->begin();
        
        $ret[] = $db->query( "DELETE FROM eZTrade_PreOrder WHERE ID='$this->ID'" );

        eZDB::finish( $ret, $db );
            
        return true;
    }
    

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globaldatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_PreOrder WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Pre order's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName("ID")];
                $this->OrderID = $cart_array[0][$db->fieldName("OrderID")];
                $this->Created = $cart_array[0][$db->fieldName("Created")];
                $this->Pnutr = $cart_array[0][$db->fieldName("Pnutr")];
                $this->Utref = $cart_array[0][$db->fieldName("Utref")];
                $this->Payco = $cart_array[0][$db->fieldName("Payco")];
                $this->Totam = $cart_array[0][$db->fieldName("Totam")];
                $this->Curry = $cart_array[0][$db->fieldName("Curry")];
                $this->Ttype = $cart_array[0][$db->fieldName("Ttype")];
                $this->Rtype = $cart_array[0][$db->fieldName("Rtype")];
                $this->Status = $cart_array[0][$db->fieldName("Status")];
                $this->ErTyp = $cart_array[0][$db->fieldName("Ertyp")];
                $this->Ermsg = $cart_array[0][$db->fieldName("Ermsg")];
                $this->Edate = $cart_array[0][$db->fieldName("Edate")];
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Fetches the pre order by OrderID.

      False is returned if no the orderID is not found.
    */
    function getByOrderID( $orderID )
    {
        $db =& eZDB::globaldatabase();
        $ret = false;
        
        if ( $orderID != "" )
        {
            $db->array_query( $cart_array, "SELECT * FROM eZTrade_PreOrder WHERE OrderID='$orderID'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Pre order's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][$db->fieldName("ID")];
                $this->OrderID = $cart_array[0][$db->fieldName("OrderID")];
                $this->Created = $cart_array[0][$db->fieldName("Created")];
                $this->Pnutr = $cart_array[0][$db->fieldName("Pnutr")];
                $this->Utref = $cart_array[0][$db->fieldName("Utref")];
                $this->Payco = $cart_array[0][$db->fieldName("Payco")];
                $this->Totam = $cart_array[0][$db->fieldName("Totam")];
                $this->Curry = $cart_array[0][$db->fieldName("Curry")];
                $this->Ttype = $cart_array[0][$db->fieldName("Ttype")];
                $this->Rtype = $cart_array[0][$db->fieldName("Rtype")];
                $this->Status = $cart_array[0][$db->fieldName("Status")];
                $this->ErTyp = $cart_array[0][$db->fieldName("Ertyp")];
                $this->Ermsg = $cart_array[0][$db->fieldName("Ermsg")];
                $this->Edate = $cart_array[0][$db->fieldName("Edate")];

                $ret = true;
            }
        }

        return $ret;
    }

    /*!
      Returns the order date as a eZDateTime object.
    */
    function date()
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->Date );
       
       return $dateTime;
    }    
    
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the order id. If 0 this pre order has not
      resulted in an order.
    */
    function orderID()
    {
        return $this->OrderID;
    }
    
    /*!
      Returns pnutr value (from paynet).
    */
    function pnutr()
    {
        return $this->Pnutr;
    }

    /*!
      Returns utref value (from paynet).
      This is an unique value sent to paynet.
    */
    function utref()
    {
        return $this->Utref;
    }

    /*!
      Returns the payco value (from paynet).
      This is the identification number for the payment provider.
    */
    function payco()
    {
        return $this->Payco;
    }

    /*!
      Returns totam (from paynet receipt).
    */
    function totam()
    {
        return $this->Totam;
    }

    /*!
      Returns the curry value (from paynet).
      This descibes the country (3 characters)
    */
    function curry()
    {
        return $this->Curry;
    }

    /*!
      Returns The ttype used for the transaction (from paynet).
     */
    function ttype()
    {
        return $this->Ttype;
    }

    /*!
      Returns what receipt this is (from paynet).
      0 - payment is reserved on cartholders card.
      1 - transaction submitted.
      9 - error message (ertyp and ermsg is set)
     */
    function rtype()
    {
        return $this->Rtype;
    }

    /*!
      Returns The status for the transaction (from paynet).
     */
    function status()
    {
        return $this->Status;
    }

    /*!
      Returns the error type for the transaction if any (from paynet).
     */
    function ertyp()
    {
        return $this->Ertyp;
    }

    /*!
      returns The error message for the transaction if any (from paynet).
     */
    function ermsg()
    {
        return $this->Ermsg;
    }

    /*!
      returns the edate for the payment.
     */
    function edate()
    {
        $dateTime = new eZDateTime();
        $dateTime->setTimeStamp( $this->Edate );

        return $dateTime;
    }
    
    /*!
      Set the pnutr id (from paynet)
    */
    function setPnutr( $value )
    {
        $this->Pnutr = $value;
    }

    /*!
      Set the utref value (from paynet)
    */
    function setUtref( $value )
    {
        $this->Utref = $value;
    }

    /*!
      Set the Payco value (from paynet)
      This value set the identification number for the payment provider.
    */
    function setPayco( $value )
    {
        $this->Payco = $value;
    }
    
    /*!
      Set the total amount, sent to paynet (from paynet).
    */
    function setTotam( $value )
    {
        $this->Totam = $value;
    }


    
    /*!
      Set the curry used in the payment (from paynet)
    */
    function setCurry( $value )
    {
        $this->Curry = $value;
    }

    /*!
      Set the ttype used in the payment (from paynet)
    */
    function setTtype( $value )
    {
        $this->Ttype = $value;
    }
    
    /*!
      Set the rtype used in the payment (from paynet)
    */
    function setRtype( $value )
    {
        $this->Rtype = $value;
    }

    /*!
      Set the status for the payment (from paynet)
    */
    function setStatus( $value )
    {
        $this->Status = $value;
    }

    /*!
      Set the error type for the payment if any (from paynet)
    */
    function setErtyp( $value )
    {
        $this->Ertyp = $value;
    }

    /*!
      Set the error message for the payment if any (from paynet)
    */
    function setErmsg( $value )
    {
        $this->Curry = $value;
    }

    /*!
      Set the edate for the payment (from paynet)
      This is the date the payment will be paid.
    */
    function setStatus( $value )
    {
        $this->Status = $value;
    }

    /*!
      Sets the order id which corresponds to this pre-order
    */
    function setOrderID( $value )
    {
        $this->OrderID = $value;
    }

    /*!
      Sets the edate for the order.
    */
    function setEdate( $value )
    {
        $this->Edate = $value;
    }

    
    var $ID;
    var $Created;
    var $OrderID;

    // The following is information about paynet variables
    var $Pnutr;
    var $Utref;
    var $Payco;
    var $Totam;
    var $Curry;
    var $Ttype;
    var $Rtype;
    var $Status;
    var $Ertyp;
    var $Ermsg;
    var $Edate;

}

?>
