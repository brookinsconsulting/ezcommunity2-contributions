<?php
// 
// $Id: ezxmlrpcresponse.php,v 1.11 2001/03/16 14:07:42 bf Exp $
//
// Definition of eZXMLRPCResponse class
//
// Bård Farstad <bf@ez.no>
// Created on: <16-Dec-2000 15:57:06 bf>
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
//! eZXMLRPCResponse hadles a XML-RPC server response.
/*!
  
*/

// datatypes
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbase64.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdatetime.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );


class eZXMLRPCResponse
{
    /*!
      Creates a new eZXMLRPCResponse object. 
    */
    function eZXMLRPCResponse( )
    {
        $this->Result = 0;
        $this->Error = 0;
        $this->IsFault = false;        
    }

    /*!
      Decodes the XML-RPC response stream and stores the result.

      You can get the result by calling the result() function.
    */
    function decodeStream( $stream )
    {
        // create a new decoder object
        $decoder = new eZXMLRPCDataTypeDecoder( );
        
//        print( nl2br( htmlspecialchars( $stream ) ) );

        $stream = $this->stripHTTPHeader( $stream );

        $domTree =& qdom_tree( $stream );

        foreach ( $domTree->children as $response )
        {
            if ( $response->name == "methodResponse" )
            {                
                foreach ( $response->children as $params )
                {
                    if ( $params->name == "params" )
                    {

                        foreach ( $params->children as $param )
                        {
                            if ( $param->name == "param" )
                            {
                        
                                foreach ( $param->children as $value )
                                {
                                    if ( $value->name == "value" )
                                    {
                                        $this->Result =& $decoder->decodeDataTypes( $value );
                                    }
                                }
                            }
                        }
                    }

                    if ( $params->name == "fault" )
                    {
                        foreach ( $params->children as $param )
                        {
                            if ( $param->name == "value" )
                            {
                                $this->IsFault = true;
                                $this->Error =& $decoder->decodeDataTypes( $param );
                            }
                        }
                    }
                }
            }
        }

        // could not decode the stream
        if ( $this->Result == 0 and $this->Error == 0 )
        {
            $this->setError( 3, "Could not decode stream. Server error." );
        }        
    }

    /*!
      Sets the result value.

      The argument must be a valid
      XML-RPC datatype object. eZXMLRPCInt, eZXMLRPCString ...
    */
    function setResult( $result )
    {
        $this->Result = $result;
    }

    /*!
      Sets an error message.
    */
    function setError( $faultCode, $faultString )
    {
        $this->IsFault = true;
        $this->Error = new eZXMLRPCStruct( array( "faultCode" => new eZXMLRPCInt( $faultCode ),
                                                  "faultString" => new eZXMLRPCString( $faultString ),
                                                  )
                                           );
    }
    
    /*!
      Returns the result of the response

      The result is a valid
      XML-RPC datatype object. eZXMLRPCInt, eZXMLRPCString ...

      If not false is returned
    */
    function result( )
    {
        return $this->Result;
    }

    /*!
      Returns the response payload. This is the response encoded
      as an XML-RPC call.
    */
    function &payload( )
    {
        $payload = "<?xml version=\"1.0\"?>";

        if ( $this->Error == "" )
        {
            $payload .= "<methodResponse><params><param>";
            $payload .= $this->Result->serialize();
            $payload .= "</param></params></methodResponse>";
        }
        else
        {
            $payload .= "<methodResponse><fault>";
            $payload .= $this->Error->serialize();
            $payload .= "</fault></methodResponse>";
        }

        return $payload;
    }

    /*!
      \private
      Strips the header information from the HTTP raw response.
    */
    function &stripHTTPHeader( $data )
    {
        $start = strpos( $data, "<?xml version=\"1.0\"?>" );
        $data = substr( $data, $start, strlen( $data ) - $start );
            
        return $data;
    }

    /*!
      Returns true if the response is a fault.
    */
    function isFault()
    {
        return $this->IsFault;
    }

    /*!
      Returns the fault code if there was an error. False if not.
    */
    function faultCode()
    {
        $ret = false;

        if ( $this->IsFault and ( get_class( $this->Error ) == "ezxmlrpcstruct" ) )
        {
            
//            $error = $this->Result->value();
            $error = $this->Error->value();

            
            $ret = $error["faultCode"]->value();
        }
        
        return $ret;
    }

    /*!
      Returns the fault string if there was an error. False if not.
    */
    function faultString()
    {
        $ret = false;

        if ( $this->IsFault and ( get_class( $this->Error ) == "ezxmlrpcstruct" )  )
        {
//            $error = $this->Result->value();
            $error = $this->Error->value();
            
            $ret = $error["faultString"]->value();
        }
        
        return $ret;
    }
    
    
    /// Contains the result
    var $Result;

    /// Contains the error struct
    var $Error;

    /// Is true if the response is a fault
    var $IsFault;
}

?>
