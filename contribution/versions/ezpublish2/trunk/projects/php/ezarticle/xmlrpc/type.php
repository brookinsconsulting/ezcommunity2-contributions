<?
include_once( "ezarticle/classes/ezarticletype.php" );
include_once( "ezarticle/classes/ezarticleattribute.php" );

if( $Command == "data" ) // return all the data in the category
{
    eZLog::writeNotice( "type: $Module/$RequestType/$ID#1" );
    $type = new eZArticleType();
    eZLog::writeNotice( "type: $Module/$RequestType/$ID#2" );
    if ( $type->get( $ID ) )
    {
    eZLog::writeNotice( "type: $Module/$RequestType/$ID#3" );
        $attrs = $type->attributes();
        $attr_arr = array();
    eZLog::writeNotice( "type: $Module/$RequestType/$ID#4" );
        foreach( $attrs as $attr )
        {
            $attr_arr[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $attr->id() ),
                                                     "Name" => new eZXMLRPCString( $attr->name() ) ) );
        }
        $ReturnData = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $type->name() ),
                                                 "Attributes" => new eZXMLRPCArray( $attr_arr ) ) );
    eZLog::writeNotice( "type: $Module/$RequestType/$ID#5" );
    }
    else
    {
    eZLog::writeNotice( "type: $Module/$RequestType/$ID#2.2" );
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
}
else if( $Command == "delete" )
{
    $type = new eZArticleType();
    if ( $type->get( $ID ) )
    {
        $type->delete();
        $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
                                                 "UpdateType" => new eZXMLRPCString( $Command )
                                                 ) );
        $Command = "update";
    }
    else
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
}
?>
