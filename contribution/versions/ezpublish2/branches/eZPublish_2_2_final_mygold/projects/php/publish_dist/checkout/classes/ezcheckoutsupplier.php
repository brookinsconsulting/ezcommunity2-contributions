<?
// 
// $Id: ezcheckoutsupplier.php,v 1.1.2.2 2001/11/22 09:58:30 ce Exp $
//
// Definition of eZCheckoutSupplier class
//
// Bård Farstad <bf@ez.no>
// Created on: <02-Feb-2001 15:22:14 bf>
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
//! eZCheckoutSupplier handles checkout instances for the current checkout method.
/*!
  
*/

class eZCheckoutSupplier
{
    /*!
      
    */
    function eZCheckoutSupplier()
    {
        $this->PaymentMethods = array( array( "ID" => 1, "Text" => "VISA", "RequireSSL" => "disabled", "WorkWithVoucher" => true ),
                                       array( "ID" => 2, "Text" => "Euro- / Mastercard", "RequireSSL" => "disabled", "WorkWithVoucher" => true ),
                                       array( "ID" => 3, "Text" => "Bankeinzug", "RequireSSL" => "disabled", "WorkWithVoucher" => true ),
                                       array( "ID" => 4, "Text" => "Nachnahme", "RequireSSL" => "disabled", "WorkWithVoucher" => true ),
                                       array( "ID" => 5, "Text" => "Paybox", "RequireSSL" => "disabled", "WorkWithVoucher" => true ),
                                       array( "ID" => 6, "Text" => "Voucher", "RequireSSL" => "disabled", "WorkWithVoucher" => true ) );
        
//        $this->PaymentMethods = array( array( "ID" => 4, "Text" => "Nachnahme" )
//                                       );
    }

    /*!
      Returns the payment types supported by this checkout method.

      The types are returned as an
      array(
           array( "ID" => $id, "Text" => $text, "RequireSSL" => "enabled" ),
           array( "ID" => $id, "Text" => $text, "RequireSSL" => "disabled" )
           );
    */
    function &paymentMethods( $useVoucher=false )
    {
        for( $i=0; $i < count ( $this->PaymentMethods ); $i++ )
        {
            if ( ( $GLOBALS["SERVER_PORT"] != "443" ) and ( $this->PaymentMethods[$i]["RequireSSL"] == "disabled" )  )
            {
                if ( $useVoucher == true and $this->PaymentMethods[$i]["WorkWithVoucher"] == true )
                    $tmp[] = $this->PaymentMethods[$i];
                else if ( $useVoucher == false )
                    $tmp[] = $this->PaymentMethods[$i];
            }
        }
        $this->PaymentMethods = $tmp;
        
        return $this->PaymentMethods;
    }

    /*!
      Returns the payment type with the given id.
    */
    function &paymentName( $id )
    {
        $id = ereg_replace( "voucher_done", "6", $id );
        $tmp = explode( ",", $id );

        $ret = "unknown";
        foreach ( $this->PaymentMethods as $paymentMethod )
        {
            if ( count ( $tmp ) > 1 )
            {
                $i = 0;
                foreach( $tmp as $id )
                {
                    if ( $paymentMethod["ID"] == $id )
                    {
                        if ( $i == 0 )
                            $ret = $paymentMethod["Text"];
                        else
                        {
                            $ret .= ", " . $paymentMethod["Text"];
                        }
                    }
                    $i++;
                }

            }
            elseif ( $paymentMethod["ID"] == $id )
            {
                $ret = $paymentMethod["Text"];
            }
        }
        return $ret;
    }
    

    /*!
      Returns the file to include for the given payment type.
    */
    function &paymentFile( $id )
    {
        $ret = "checkout/user/visa.php";
        switch( $id )
        {
            case 1 :
            {
                $ret = "checkout/user/visa.php";
            } break;

            case 2 :
            {
                $ret = "checkout/user/mastercard.php";
            } break;

            case 3 :
            {
                $ret = "checkout/user/elv.php";
            } break;

            case 4 :
            {
                $ret = "checkout/user/cod.php";
            } break;
            case 5 :
            {
                $ret = "checkout/user/paybox.php";
            } break;
            case 6 :
            {
                $ret = "eztrade/user/voucher.php";
            } break;

            
        }
        return $ret;
    }

    var $PaymentMethods;
}

?>
