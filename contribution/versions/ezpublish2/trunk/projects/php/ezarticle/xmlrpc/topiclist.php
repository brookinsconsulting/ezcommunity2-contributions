<?
// eZ article classes
include_once( "ezarticle/classes/eztopic.php" );

$topics = array();

$topicList =& eZTopic::getAll();
foreach ( $topicList as $topic )
{
    $topics[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle",
                                                                     "topic",
                                                                     $topic->id() ),
                                           "Name" => new eZXMLRPCString( $topic->name() ),
                                           "Description" => new eZXMLRPCString( $topic->description() )
                                           ) );
}

$part_arr = array( "Offset" => new eZXMLRPCInt( 0 ),
                   "Total" => new eZXMLRPCInt( count( $topicList ) ),
                   "Begin" => new eZXMLRPCBool( true ),
                   "End" => new eZXMLRPCBool( true ) );
$part = new eZXMLRPCStruct( $part_arr );

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => array(),
                                         "Elements" => $topics,
                                         "Part" => $part ) ); // array starting with top level catalogue, ending with parent.
?>
