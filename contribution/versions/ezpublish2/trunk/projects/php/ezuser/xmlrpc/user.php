<?
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
if( $Command == "list" ) // Return a list of users and their ID's 
{
    $userList = eZUser::getAll( "Login", true );
    $users = array();
    foreach( $userList as $user )
    {
        $users[] = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezuser", "user", $user->id() ),
                                              "Name" => new eZXMLRPCString( $user->login( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCArray( $users );
}
else if( $Command == "data" || $Command == "currentuser" )
{
    $user = 0;
    if( $Command == "data" )
    {
        $user = new eZUser( $ID );
        $cu = false;
    }
    else
    {
        $user = $User;
        $cu = true;
    }

    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezuser", "user", $user->id() ),
                                             "FirstName" => new eZXMLRPCString( $user->firstName( false ) ),
                                             "LastName" => new eZXMLRPCString( $user->lastName( false ) ),
                                             "Login" => new eZXMLRPCString( $user->login( false ) ),
                                             "EMail" => new eZXMLRPCString( $user->email() ),
                                             "InfoSubscription" => new eZXMLRPCBool( $user->infoSubscription() ),
                                             "Signature" => new eZXMLRPCString( $user->signature() ),
                                             "CookieLogin" => new eZXMLRPCBool( $user->cookieLogin() ),
                                             "SimultaneousLogins" => new eZXMLRPCInt( $user->simultaneousLogins() ),
                                             "IsCurrentUser" => new eZXMLRPCBool( $cu )
                                             )
                                      );
}
?>




