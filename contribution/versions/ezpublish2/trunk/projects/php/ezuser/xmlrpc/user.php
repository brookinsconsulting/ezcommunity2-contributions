<?
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
if( $Action == "userlist" ) // Return a list of users and their ID's 
{
    $userList = eZUser::getAll( "Login", true );
    $users = array();
    foreach( $userList as $user )
    {
        $users[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $user->id() ),
                                              "Login" => new eZXMLRPCString( $user->login( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCArray( $users );
}
?>




