<?
include_once( "ezuser/classes/ezauthor.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
if( $Command == "list" ) // Return a list of authors and their ID's 
{
    $authorList = eZAuthor::getAll();
    $authors = array();
    foreach( $authorList as $author )
    {
        $authors[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezuser", "author", $author->id() ),
                                                "Name" => new eZXMLRPCString( $author->name( false ) )
                                                )
                                         );
    }
    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => array(),
                                             "Elements" => $authors ) );
}
else
{
    $Error = true;
}

?>
