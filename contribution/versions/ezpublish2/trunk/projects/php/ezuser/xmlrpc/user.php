<?
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
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
?>




