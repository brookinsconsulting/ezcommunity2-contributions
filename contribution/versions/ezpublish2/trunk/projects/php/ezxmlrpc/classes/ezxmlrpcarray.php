<?php
// 
// $Id: ezxmlrpcarray.php,v 1.5 2001/03/16 10:48:07 bf Exp $
//
// Definition of eZXMLRPCArray class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Dec-2000 14:13:24 bf>
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


//!! eZXMLRPC
//! eZXMLRPCArray hadles encoding and decoding of an XML-RPC array datatype.
/*!
    
*/

class eZXMLRPCArray
{
    /*!
      Creates a new eZXMLRPCArray object.

      The default value is true.
    */
    function eZXMLRPCArray( $array=array() )
    {
        $this->Array = $array;
    }

    /*!
      This function will encode the sting into a valid XML-RPC value.
    */
    function &serialize( )
    {
        $ret .= $this->serializeArray( $this->Array );        
        return $ret;
    }

    /*!
      Returns the array value.
    */
    function value()
    {
        return $this->Array;
    }

    /*!
      Decodes the a value.
    */
    function decode( $value )
    {
        
    }

    /*!
      \private
      \static
    */
    function serializeArray( $array )
    {
        $ret .= "<value><array><data>";
        foreach ( $array as $value )
        {
            switch( gettype($value) )
            {
                case "integer":
                {
                    $ret .= "<value><int>$value</int></value>";
                }
                break;
                
                case "array":
                {
                    $ret .= eZXMLRPCArray::serializeArray( $value );
                }
                break;
                
                case "object":
                {
                    if ( substr( get_class( $value ), 0, 8 ) == "ezxmlrpc" )
                    {
                        $ret .= $value->serialize( $value );
                    }
                }
                break;
                    
                default:
                {
                    $ret .= "<value><string>$value</string></value>";
                }
                break;
            }
        }

        $ret .= "</data></array></value>";
        return $ret;
    }

    // The array value
    var $Array;
}

?>
