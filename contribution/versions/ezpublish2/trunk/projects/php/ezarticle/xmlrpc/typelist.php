<?
// eZ article classes
include_once( "ezarticle/classes/ezarticletype.php" );

$types = array();

$typeList =& eZArticleType::getAll();
foreach ( $typeList as $type )
{
    $types[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle",
                                                                    "type",
                                                                    $type->id() ),
                                          "Name" => new eZXMLRPCString( $type->name() )
//                                                 "Description" => new eZXMLRPCString( $type->description() )
                                               ) );
}

$part_arr = array( "Offset" => new eZXMLRPCInt( 0 ),
                   "Total" => new eZXMLRPCInt( count( $typeList ) ),
                   "Begin" => new eZXMLRPCBool( true ),
                   "End" => new eZXMLRPCBool( true ) );
$part = new eZXMLRPCStruct( $part_arr );

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => array(),
                                         "Elements" => $types,
                                         "Part" => $part ) ); // array starting with top level catalogue, ending with parent.
?>
