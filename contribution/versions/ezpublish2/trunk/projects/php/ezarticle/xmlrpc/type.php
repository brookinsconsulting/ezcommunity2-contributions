<?
include_once( "ezarticle/classes/ezarticletype.php" );
include_once( "ezarticle/classes/ezarticleattribute.php" );

if( $Command == "data" ) // return all the data in the category
{
    $type = new eZArticleType();
    if ( $type->get( $ID ) )
    {
        $attrs = $type->attributes();
        $attr_arr = array();
        foreach( $attrs as $attr )
        {
            $attr_arr[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $attr->id() ),
                                                     "Name" => new eZXMLRPCString( $attr->name() ) ) );
        }
        $ReturnData = new eZXMLRPCStruct( array( "Name" => new eZXMLRPCString( $type->name() ),
                                                 "Attributes" => new eZXMLRPCArray( $attr_arr ) ) );
    }
    else
    {
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
