<?
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
if( $Action == "grouplist" ) // Return a list of users and their ID's 
{
    $groupList = eZUserGroup::getAll();
    $groups = array();
    foreach( $groupList as $group )
    {
        $groups[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $group->id() ),
                                               "Name" => new eZXMLRPCString( $group->name( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCArray( $groups );
}

?>
