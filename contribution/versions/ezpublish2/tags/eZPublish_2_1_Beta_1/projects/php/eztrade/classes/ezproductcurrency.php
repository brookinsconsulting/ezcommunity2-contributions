<?
// 
// $Id: ezproductcurrency.php,v 1.3 2001/03/06 19:18:26 jb Exp $
//
// Definition of eZProductCurrency class
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Feb-2001 16:47:27 bf>
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


//!! eZTrade
//! This class handles alternative product currencies.
/*!
  
  \sa eZProduct
*/

include_once( "classes/ezdb.php" );

class eZProductCurrency
{
    /*!
      Constructs a new object.
    */
    function eZProductCurrency( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores/updates the alternative currency in the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZTrade_AlternativeCurrency SET
		                 Name='$this->Name',
		                 Sign='$this->Sign',
		                 Value='$this->Value',
		                 Created=now(),
		                 PrefixSign='$this->PrefixSign'
                          " );
        
            $this->ID = mysql_insert_id();
        }
        else
        {
            $db->query( "UPDATE eZTrade_AlternativeCurrency SET
		                 Name='$this->Name',
		                 Sign='$this->Sign',
		                 Value='$this->Value',
		                 Created=Created,
		                 PrefixSign='$this->PrefixSign'
                        WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Fetches the alternative currency from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != -1  )
        {
            $db->array_query( $currency_array, "SELECT * FROM eZTrade_AlternativeCurrency WHERE ID='$id'" );
            
            if ( count( $currency_array ) > 1 )
            {
                die( "Error: Product currencies with the same ID was found in the database. This shouldn't happen." );
            }
            else if( count( $currency_array ) == 1 )
            {
                $this->ID =& $currency_array[0][ "ID" ];
                $this->Name =& $currency_array[0][ "Name" ];
                $this->Value =& $currency_array[0][ "Value" ];
                $this->Sign =& $currency_array[0][ "Sign" ];
                $this->PrefixSign =& $currency_array[0][ "PrefixSign" ];
            }
        }
    }

    /*!
      Retrieves all the alternative currencies from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $currency_array = array();
        
        $db->array_query( $currency_array, "SELECT ID FROM eZTrade_AlternativeCurrency ORDER BY Created" );
        
        for ( $i=0; $i < count($currency_array); $i++ )
        {
            $return_array[$i] = new eZProductCurrency( $currency_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Deletes a alternative currency from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZTrade_AlternativeCurrency WHERE ID='$this->ID'" );
    }

    /*!
      Returns the object ID to the currency.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the name of the currency.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the value of the currency.
    */
    function value()
    {
        return number_format( $this->Value, 4, ".", "" );
    }

    /*!
      Returns the sign of the currency.
    */
    function sign()
    {
        return $this->Sign;
    }

    /*!
      Returns true if the sign should prefix the value, false if not.
    */
    function prefixSign()
    {
        if ( $this->PrefixSign == 1 )
            return true;
        else
            return false;
    }
    
    /*!
      Sets the name of the currency.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the value of the currency.
    */
    function setValue( $value )
    {
        $newValue = number_format( $value, 4, ".", "" );
        $value = (double) $newValue;
        $this->Value = $value;
    }


    /*!
      Sets the sign of the currency.
    */
    function setSign( $value )
    {
        $this->Sign = $value;
    }

    /*!
      Set to true if the sign should prefix theb value, false if not.
    */
    function setPrefixSign( $value )
    {
        if ( $value == true )            
            $this->PrefixSign = 1;
        else
            $this->PrefixSign = 0;
    }
    
    var $ID;
    var $Name;
    var $Sign;
    var $PrefixSign;
    var $Value;
}

?>
