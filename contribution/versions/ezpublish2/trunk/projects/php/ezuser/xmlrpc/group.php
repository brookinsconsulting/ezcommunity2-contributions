<?
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
if( $Command == "list" ) // Return a list of users and their ID's 
{
    $groupList = eZUserGroup::getAll();
    $groups = array();
    foreach( $groupList as $group )
    {
        $groups[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezuser", "group", $group->id() ),
                                               "Name" => new eZXMLRPCString( $group->name( false ) )
                                              )
                                       );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => new eZXMLRPCArray(),
                                             "Elements" => $groups,
                                             "Path" => new eZXMLRPCArray() ) ); // array starting with top level catalogue, ending with parent.

}

?>
