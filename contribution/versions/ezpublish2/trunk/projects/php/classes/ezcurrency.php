<?
// 
// $Id: ezcurrency.php,v 1.1 2000/09/08 13:10:33 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <06-Sep-2000 16:20:20 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


//!! eZCommon
//! The eCurrency class provides currency functions.
/*!

*/

class eZCurrency
{
    /*!
      Constructs a new eZCurrency object.
    */
    function eZCurrency( $value=0 )
    {
        $this->Value = $value;
        settype( $this->Value, double );
    }

    /*!
      Returns the value for the currency.
    */
    function value()
    {
        return $this->Value;
    }

    /*!
      Sets the currency value.
    */
    function setValue( $value  )
    {
        $this->Value = $value;
        settype( $this->Value, double );
    }

    /*!
      Returns true if the currency is negative, false if not.
    */
    function isNegative( )
    {
        if ( $Value < 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    var $Value;
}
?>
