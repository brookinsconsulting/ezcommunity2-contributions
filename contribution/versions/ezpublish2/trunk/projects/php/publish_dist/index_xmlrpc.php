<?
ob_end_clean();
ob_start();


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
$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

define( "EZPUBLISH_SERVER_VERSION", 0.2 );

include_once( "classes/ezlocale.php" );
include_once( "classes/ezlog.php" );



// eZ user
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezuser/classes/ezpermission.php" );



eZLog::writeNotice( "XML-RPC connect." );

$server = new eZXMLRPCServer( );


// Register server function
$server->registerFunction( "Call", new eZXMLRPCStruct( ) );

// process the server requests
$server->processRequest();


/*!
  Function to handle requests.
*/
function Call( $args )
{
    eZLog::writeNotice( "XML-RPC call." );
    $call = $args[0]->value();

    $login = $call["User"]->value();
    $password = $call["Password"]->value();

    $User = new eZUser();
    $User = $User->validateUser( $login, $password );
        
    if ( ( get_class( $User ) == "ezuser" ) and eZPermission::checkPermission( $User, "eZUser", "AdminLogin" ) )
    {
        eZLog::writeNotice( "XML-RPC logged in." );
        $version = $call["Version"]->value();

        // Get caller ID if any
        $caller = false;
        if ( is_object( $call["Caller"] ) )
            $caller = $call["Caller"];

        $RefID = false;
        if ( is_object( $call["RefID"] ) )
            $RefID = $call["RefID"];

        // decode URL
        $REQUEST_URI = $call["URL"]->value();
        $Module = $REQUEST_URI["Module"]->value();
        $RequestType = $REQUEST_URI["Type"]->value();
        if( is_object( $REQUEST_URI["ID"] ) )
            $ID = $REQUEST_URI["ID"]->value();
        else
            $ID = 0;
        
        $Data = $call["Data"]->value();
        $Command = $call["Command"]->value();

        $ReturnData = array();

        $datasupplier = $Module . "/xmlrpc/datasupplier.php";
        // check for module implementation        
        if ( file_exists( $datasupplier )  || ( $Module == "ezpublish" && $RequestType == "modules" ) )
        {
            if ( $Module == "ezpublish" && $RequestType == "modules" )
            {
                // return the modules in the system
                $dir = dir( "." );
                $modules = array();
                while ( $entry = $dir->read() )
                {
                    if ( preg_match( "/ez.*/", $entry ) )
                    {
                        if ( file_exists( $entry . "/xmlrpc/datasupplier.php" ) )
                        {
                            $ReturnCatalogues = true;
                            include( $entry . "/xmlrpc/datasupplier.php" );
                            
                            $modules[] = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $entry ),
                                                                    "Catalogues" => new eZXMLRPCArray( $Catalogues )
                                                                    ) );
                            
                        }
                    }
                }

                eZLog::writeNotice( "XML-RPC returning modules." );
                $ReturnData = $modules;

            }
            else
            {
                eZLog::writeNotice( "XML-RPC returning standard data." );
                include( $datasupplier );

            }

            if ( !is_object( $RefID ) )
            {
                $RefID = new eZXMLRPCString( md5( microtime() ) );
            }

            // create the return struct...
            $ret_arr = array( "Version" => new eZXMLRPCDouble( EZPUBLISH_SERVER_VERSION ),
                              "URL" => createURLStruct( $Module, $RequestType, $ID ),
                              "Command" => new eZXMLRPCString( $Command ),
                              "Data" => $ReturnData
                              );
            if ( is_object( $caller ) )
                $ret_arr["Caller"] = $caller;
            $ret = new eZXMLRPCStruct( $ret_arr );
            
            
            if ( get_class( $Error ) == "ezxmlrpcresponse" )
            {                
                $ret = $Error;
            }            
        }
        else
        {
            $ret = new eZXMLRPCResponse( );
            $ret->setError( 2, "Server function not found." );
        }

        eZLog::writeNotice( "XML-RPC returning  data." );
        return $ret;
    }
    else
    {
        $ret = new eZXMLRPCResponse( );
        $ret->setError( 1, "Login denied, please try again." );
        return $ret;
    }
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

ob_end_flush();
exit();
?>
