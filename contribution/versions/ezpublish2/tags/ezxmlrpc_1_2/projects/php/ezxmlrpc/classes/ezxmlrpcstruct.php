<?php
// 
// $Id: ezxmlrpcstruct.php,v 1.7 2001/03/16 11:20:12 bf Exp $
//
// Definition of eZXMLRPCStruct class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Dec-2000 17:31:48 bf>
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
//! eZXMLRPCStruct hadles encoding and decoding of an XML-RPC struct datatype.
/*!
    
*/

class eZXMLRPCStruct
{
    /*!
      Creates a new eZXMLRPCStruct object.

      The default value is true.
    */
    function eZXMLRPCStruct( $struct=array() )
    {
        $this->Struct = $struct;
    }

    /*!
      This function will encode the sting into a valid XML-RPC value.
    */
    function &serialize( )
    {
        $ret .= $this->serializeStruct( $this->Struct );        
        return $ret;
    }

    /*!
      Returns the struct value.
    */
    function value()
    {
        return $this->Struct;
    }

    /*!
      Decodes the a value.
    */
    function decode( $value )
    {
        
    }

    /*!
      \private
    */
    function serializeStruct( $struct )
    {
        $ret .= "<value><struct>";

        reset( $struct );
        
        while ( list( $key, $value ) = each( $struct ) )
        {
            $ret .= "<member><name>" . ${key} . "</name>";

			switch ( gettype($value) )
			{
				case "integer":
                {
					$ret .= "<value><int>$value</int></value>";
                }
                break;
                
				case "object":
                {
					if ( substr( get_class($value),0,8) == "ezxmlrpc" )
					{
						$ret .= $value->serialize( $value );
					}
                }
                break;
                 
                case "array":
                {
                    $ret .= eZXMLRPCArray::serializeArray( $value );
                }
                break;                
                
				default:
                {
					$ret .= "<value><string>$value</string></value>";
                }
                break;
            }
            
            $ret .= "</member>";
        }
        
        $ret .= "</struct></value>";

        return $ret;
    }

    // The struct value
    var $Struct;
}

?>
