<?
include_once( "ezsitemanager/classes/ezsection.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );

if( $Command == "list" ) // Return a list of users and their ID's 
{
    $sectionList = eZSection::getAll();
    $sections = array();

    foreach( $sectionList as $section )
    {
        $sections[] = new eZXMLRPCStruct(
            array( "URL" => createURLStruct( "ezsitemanager", "section", $section->id() ),
                   "Name" => new eZXMLRPCString( $section->name( false ) )
                   )
            );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => new eZXMLRPCArray(),
                                         "Elements" => $sections,
                                         "Path" => new eZXMLRPCArray() ) ); // array starting with top level catalogue, ending with parent.

}

?>
