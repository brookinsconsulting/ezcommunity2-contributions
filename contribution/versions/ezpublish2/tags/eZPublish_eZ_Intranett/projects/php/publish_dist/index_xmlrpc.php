<?php
// 
// $Id: index_xmlrpc.php,v 1.20 2001/07/31 19:55:51 kaid Exp $
//
// Created on: <09-Nov-2000 14:52:40 ce>
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

// Tell PHP where it can find our files.
if ( file_exists( "sitedir.ini" ) )
{
    include_once( "sitedir.ini" );
}

// TODO: This needs a better analysis
if ( isset( $siteDir ) and $siteDir != "" )
{
    $includePath = ini_get( "include_path" );
    $includePath .= ":" . $siteDir;
    ini_set( "include_path", $includePath );
 
    // For non-virtualhost, non-rewrite setup
    if ( ereg( "(.*/)([^\/]+\.php)$", $SCRIPT_NAME, $regs ) )
    {
        $wwwDir = $regs[1];
        $index = $regs[2];
    }
 
    // Remove url parameters
    if ( ereg( "^$wwwDir$index(.+)", $REQUEST_URI, $req ) )
    {
        $REQUEST_URI = $req[1];
    }
    else
    {
        $REQUEST_URI = "/";
    }
}
else
{
    // Remove url parameters
    ereg( "([^?]+)", $REQUEST_URI, $regs );
    $REQUEST_URI = $regs[1];
 
    $wwwDir = "";
    $index = "";
}
	
ob_end_clean();
ob_start();

define( "EZPUBLISH_SERVER_VERSION", 0.2 );

// Error codes
define( "EZERROR_BAD_LOGIN", 1 );
define( "EZERROR_INVALID_FUNCTION", 2 );
define( "EZERROR_CUSTOM", 3 );
define( "EZERROR_NO_RETURN_DATA", 4 );
define( "EZERROR_NONEXISTING_OBJECT", 5 );
define( "EZERROR_PHP_ERROR", 6 );
define( "EZERROR_BAD_REQUEST_DATA", 7 );

// include the server
include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );

// include the datatype(s) we need
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );

// site information
include_once( "classes/INIFile.php" );
$ini =& INIFile::globalINI();
$GlobalSiteIni =& $ini;

// File functions
include_once( "classes/ezfile.php" );

include_once( "classes/ezlog.php" );
error_reporting(0);
$old_error_handler = set_error_handler("xmlrpcErrorHandler");

include_once( "classes/ezlocale.php" );



// eZ user
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezuser/classes/ezpermission.php" );



//  eZLog::writeNotice( "XML-RPC connect." );

$server = new eZXMLRPCServer( );


// Register server function
$server->registerFunction( "Call", array( new eZXMLRPCStruct( ) ) );

// process the server requests
$server->processRequest();

/*!
  Function to handle requests.
*/
function Call( $args )
{
    $call = $args[0]->value();

    $login = $call["User"]->value();
    $GLOBALS["login"] =& $login;
    $password = $call["Password"]->value();
    $GLOBALS["password"] =& $Password;

    $User = new eZUser();
    $User = $User->validateUser( $login, $password );
    $GLOBALS["User"] =& $User;
        
    if ( ( get_class( $User ) == "ezuser" ) and eZPermission::checkPermission( $User, "eZUser", "AdminLogin" ) )
    {
//          eZLog::writeNotice( "XML-RPC logged in." );
        $version = $call["Version"]->value();
        $GLOBALS["version"] =& $version;

        // Get caller ID if any
        $caller = false;
        if ( isset( $call["Caller" ] ) and is_object( $call["Caller"] ) )
            $caller = $call["Caller"];
        $GLOBALS["caller"] =& $caller;

        $RefID = false;
        if ( isset( $call["RefID" ] ) and is_object( $call["RefID"] ) )
            $RefID = $call["RefID"];
        $GLOBALS["RefID"] =& $RefID;

        // decode URL
        $REQUEST_URI = $call["URL"]->value();
        $Module = $REQUEST_URI["Module"]->value();
        $GLOBALS["Module"] =& $Module;
        $RequestType = $REQUEST_URI["Type"]->value();
        $GLOBALS["RequestType"] =& $RequestType;
        if( isset( $REQUEST_URI["ID"] ) and is_object( $REQUEST_URI["ID"] ) )
            $ID = $REQUEST_URI["ID"]->value();
        else
            $ID = 0;
        $GLOBALS["ID"] =& $ID;

        $Data = $call["Data"]->value();
        $GLOBALS["Data"] =& $Data;
        $Command = $call["Command"]->value();
        $GLOBALS["Command"] =& $Command;

        $ReturnData = array();
        $GLOBALS["ReturnData"] =& $ReturnData;

        $Error = false;
        $GLOBALS["XMLRPC_Error"] =& $Error;
        $ret = "";
        $GLOBALS["ret"] =& $ret;
        $datasupplier = $Module . "/xmlrpc/datasupplier.php";
        if ( ( $Command == "search" && $Module == "" && $RequestType == "" ) ||
             eZFile::file_exists( $datasupplier )  ||
             ( $Module == "ezpublish" && $RequestType == "modules" ) )
        {
            // check for module implementation
            if ( $Command == "search" && $Module == "" && $RequestType == "" )
            {
                // We handle global search ourselves
                $modules = array( "ezarticle", "ezimagecatalogue" );
                foreach( $modules as $module )
                {
                    $search_file = $module . "/xmlrpc/search.php";
                    if ( eZFile::file_exists( $search_file ) )
                    {
                        include( $search_file );
                    }
                }
            }
            else if ( $Module == "ezpublish" && $RequestType == "modules" )
            {
                // return the modules in the system
                $dir = eZFile::dir( "." );
                $modules = array();
                while ( $entry = $dir->read() )
                {
                    if ( preg_match( "/ez.*/", $entry ) )
                    {
                        if ( eZFile::file_exists( $entry . "/xmlrpc/datasupplier.php" ) )
                        {
                            $ReturnCatalogues = true;
                            include( $entry . "/xmlrpc/datasupplier.php" );
                            
                            $modules[] = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $entry ),
                                                                    "Catalogues" => new eZXMLRPCArray( $Catalogues )
                                                                    ) );
                            
                        }
                    }
                }

//                  eZLog::writeNotice( "XML-RPC returning modules." );
                $ReturnData = $modules;

            }
            else
            {

//                  eZLog::writeNotice( "XML-RPC returning standard data." );
                include( $datasupplier );

            }

            if ( !is_object( $RefID ) )
            {
                $RefID = new eZXMLRPCString( md5( microtime() ) );
            }

            if ( $Error and !is_object( $Error ) )
            {
                $ret =& createErrorMessage( EZERROR_INVALID_FUNCTION );
            }
            else if ( $Error and is_object( $Error ) )
            {
            }
            else if ( isset( $ReturnData ) and is_object( $ReturnData ) )
            {
                // create the return struct...
                $ret_arr = array( "Version" => new eZXMLRPCDouble( EZPUBLISH_SERVER_VERSION ),
                                  "URL" => createURLStruct( $Module, $RequestType, $ID ),
                                  "Command" => new eZXMLRPCString( $Command ),
                                  "RefID" => $RefID,
                                  "Data" => $ReturnData
                                  );
                if ( is_object( $caller ) )
                    $ret_arr["Caller"] = $caller;
                $ret = new eZXMLRPCStruct( $ret_arr );
            }
            else
            {
                $ret =& createErrorMessage( EZERROR_NO_RETURN_DATA );
            }

            if ( get_class( $Error ) == "ezxmlrpcresponse" )
            {                
                $ret = $Error;
            }            
        }
        else
        {
            $ret =& createErrorMessage( EZERROR_INVALID_FUNCTION );
        }

//          eZLog::writeNotice( "XML-RPC returning  data." );
        return $ret;
    }
    else
    {
        $ret =& createErrorMessage( EZERROR_BAD_LOGIN );
    }
}

function appendSearchURLS( $urls )
{
    global $ReturnData;
    global $Data;
    if ( is_object( $ReturnData ) )
        $ret =& $ReturnData->value();
    else
        $ret = array( "Elements" => new eZXMLRPCArray( array() ) );
    if ( isset( $ret["NextSearch"] ) )
        $next =& $ret["NextSearch"]->value();
    else
        $next = array();
    foreach( $urls as $url )
    {
        $next[] = $url;
    }
    if ( !isset( $ret["NextSearch"] ) )
        $ret["NextSearch"] = new eZXMLRPCArray( $next );
    if ( !isset( $ret["Keywords"] ) )
        $ret["Keywords"] = $Data["Keywords"];
    if ( isset( $Data["Parameters"] ) && !isset( $ret["Parameters"] ) )
        $ret["Parameters"] = $Data["Parameters"];
    if ( !is_object( $ReturnData ) )
        $ReturnData = new eZXMLRPCStruct( $ret );
}

function handleSearchData( &$ret )
{
    global $Data;
    if ( isset( $Data["NextSearch"] ) )
    {
        $ret["NextSearch"] = $Data["NextSearch"];
        $ret["Keywords"] = $Data["Keywords"];
    }
    if ( isset( $Data["Parameters"] ) )
        $ret["Parameters"] = $Data["Parameters"];
}

function xmlrpcErrorHandler ($errno, $errmsg, $filename, $linenum, $vars)
{
    global $XMLRPC_Error;
// timestamp for the error entry
    $dt = date("Y-m-d H:i:s (T)");

    // define an assoc array of error string
    // in reality the only entries we should
    // consider are 2,8,256,512 and 1024
    $errortype = array (
        1   =>  "Error",
        2   =>  "Warning",
        4   =>  "Parsing Error",
        8   =>  "Notice",
        16  =>  "Core Error",
        32  =>  "Core Warning",
        64  =>  "Compile Error",
        128 =>  "Compile Warning",
        256 =>  "User Error",
        512 =>  "User Warning",
        1024=>  "User Notice"
        );
    // set of errors for which a var trace will be saved
    $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
    
    $err = "<errorentry>\n";
    $err .= "\t<datetime>".$dt."</datetime>\n";
    $err .= "\t<errornum>".$errno."</errnumber>\n";
    $err .= "\t<errortype>".$errortype[$errno]."</errortype>\n";
    $err .= "\t<errormsg>".$errmsg."</errormsg>\n";
    $err .= "\t<scriptname>".$filename."</scriptname>\n";
    $err .= "\t<scriptlinenum>".$linenum."</scriptlinenum>\n";

    if (in_array($errno, $user_errors))
        $err .= "\t<vartrace>".wddx_serialize_value($vars,"Variables")."</vartrace>\n";
    $err .= "</errorentry>\n\n";
    
    // for testing
    // echo $err;

    // save to the error log, and e-mail me if there is a critical user error
//      error_log($err, 3, "/usr/local/php4/error.log");
    if ( $errno != 8 and $errno != 2 )
    {
        eZLog::writeError( $err );
//          $XMLRPC_Error = createErrorMessage( EZ_ERROR_PHP_ERROR, $err );
    }
//      if ($errno == E_USER_ERROR)
//          mail("phpdev@mydomain.com","Critical User Error",$err);
}

function &createErrorMessage( $error_id, $error_msg = false, $error_sub_id = false )
{
    global $ID;
    global $Command;
    global $Module;
    global $RequestType;
    $ret = new eZXMLRPCResponse( );
    switch( $error_id )
    {
        case EZERROR_BAD_LOGIN:
        {
            $error_text = "Login denied, please try again.";
            break;
        }
        case EZERROR_INVALID_FUNCTION:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Server function \"$Command\" for URL \"$Module:/$RequestType/$id_text\" not found.";
            break;
        }
        case EZERROR_NO_RETURN_DATA:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "No return data while processing \"$Command\" for URL \"$Module:/$RequestType/$id_text\".";
            break;
        }
        case EZERROR_NONEXISTING_OBJECT:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Object does not exist, used command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".";
            break;
        }
        case EZERROR_PHP_ERROR:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "PHP error for command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".\n";
            $error_text .= "Error was: $error_msg";
            break;
        }
        case EZERROR_BAD_REQUEST_DATA:
        {
            if ( $ID > 0 )
                $id_text = $ID;
            $error_text = "Bad request data for command \"$Command\" for URL \"$Module:/$RequestType/$id_text\".\n";
            break;
        }
        case EZERROR_CUSTOM:
        {
            $error_text = $error_msg;
            break;
        }
        default:
        {
            $error_text = "Unknown error";
        }
    }
    $ret->setError( $error_id, $error_text, $error_sub_id );
    eZLog::writeError( "ID: $error_id, SubID: $error_sub_id, Text: $error_text" );
    return $ret;
}

function createURLStruct( $module, $type , $id = 0 )
{
    if( $id != 0 )
    {
        $ret = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $id ),
                                          "Type" => new eZXMLRPCString( $type ),
                                          "Module" => new eZXMLRPCString( $module ) )
                            );
    }
    else
    {
        $ret = new eZXMLRPCStruct( array( "Type" => new eZXMLRPCString( $type ),
                                          "Module" => new eZXMLRPCString( $module ) )
                            );
    }
    return $ret;
}

function createSizeStruct( $width, $height )
{
    $ret = new eZXMLRPCStruct( array( "Width" => new eZXMLRPCInt( $width ),
                                      "Height" => new eZXMLRPCInt( $height ) ) );
    return $ret;
}

function createDateStruct( $date )
{
    $ret = new eZXMLRPCStruct( array( "Year" => new eZXMLRPCInt( $date->year() ),
                                      "Month" => new eZXMLRPCInt( $date->month() ),
                                      "Day" => new eZXMLRPCInt( $date->day() ) ) );
    return $ret;
}

function createDateTimeStruct( $datetime )
{
    $ret = new eZXMLRPCStruct( array( "Year" => new eZXMLRPCInt( $datetime->year() ),
                                      "Month" => new eZXMLRPCInt( $datetime->month() ),
                                      "Day" => new eZXMLRPCInt( $datetime->day() ),
                                      "Hour" => new eZXMLRPCInt( $datetime->hour() ),
                                      "Minute" => new eZXMLRPCInt( $datetime->minute() ),
                                      "Second" => new eZXMLRPCInt( $datetime->second() ) ) );
    return $ret;
}

function &createTreeStruct( $tree, $module, $type )
{
    $id = $tree["ID"];
    $name = $tree["Name"];
    $children = $tree["Children"];
    $child_arr = array();
    foreach( $children as $child )
    {
        $child_arr[] =& createTreeStruct( $child, $module, $type );
    }
    $item_arr = array( "Location" => createURLStruct( $module, $type, $id ),
                       "Name" => new eZXMLRPCString( $name ) );
    if ( count( $child_arr ) > 0 )
        $item_arr["Children"] = $child_arr;
    $item = new eZXMLRPCStruct( $item_arr );
    return $item;
}

function createDateTime( $struct )
{
    $datetime = new eZDateTime();
    $datetime->setYear( $struct["Year"]->value() );
    $datetime->setMonth( $struct["Month"]->value() );
    $datetime->setDay( $struct["Day"]->value() );
    $datetime->setHour( $struct["Hour"]->value() );
    $datetime->setMinute( $struct["Minute"]->value() );
    $datetime->setSecond( $struct["Second"]->value() );
    return $datetime;
}

ob_end_flush();
exit();
?>
