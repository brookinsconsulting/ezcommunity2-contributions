<?
// eZ article complete data
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

// TODO: check permissions!!

if( $Command == "data" ) // Dump category info!
{
    $writeGroups = eZObjectPermission::getGroups( $ID, "article_category", 'w', false );
    $readGroups = eZObjectPermission::getGroups( $ID, "article_category", 'r', false );

    foreach( $readGroups as $group )
        $rgp[] = new eZXMLRPCInt( $group );
    foreach( $writeGroups as $group )
        $wgp[] = new eZXMLRPCInt( $group );

    $category = new eZArticleCategory( $ID );
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "category", $category->id() ),
                                             "Name" => new eZXMLRPCString( $category->name( false ) ),
                                             "ParentID" => new eZXMLRPCInt( $category->parent( false ) ),
                                             "Description" => new eZXMLRPCString( $category->description( false ) ),
                                             "ExcludeFromSearch" => new eZXMLRPCBool( $category->excludeFromSearch() ),
                                             "SortMode" => new eZXMLRPCInt( $category->sortMode( true ) ),
                                             "OwnerID" => new eZXMLRPCInt( $category->owner( false ) ),
                                             "SectionID" => new eZXMLRPCInt( $category->sectionIDStatic( $ID ) ),
                                             "ImageID" => new eZXMLRPCInt( $category->image( false ) ),
                                             "BulkMailID" => new eZXMLRPCInt( $category->bulkMailCategory(false ) ),
                                             "ReadGroups" => new eZXMLRPCArray( $rgp ),
                                             "WriteGroups" => new eZXMLRPCArray( $wgp )
                                             )
                                      );
}
else if( $Command == "storedata" ) // save the category data!
{

    $ID = $Data["ID"]->value();
    
    if( $ID == 0 )
        $category = new eZArticleCategory();
    else
    {
        $category = new eZArticleCategory( $ID );
        $category->setOwner( eZUser::currentUser() );
    }

    $category->setName( $Data["Name"]->value() );
    $category->setDescription( $Data["Description"]->value() );

//    $category->setParent( $Data["ParentID"]->value() );
    $category->setExcludeFromSearch( $Data["ExcludeFromSearch"]->value() );
    
    $category->setBulkMailCategory( $Data["BulkMailID"]->value() );
    $category->setSortMode( $Data["SortMode"]->value() );
    $category->setSectionID( $Data["SectionID"]->value() );
//    $category->setImage( $Data["ImageID"]->value() );
    $category->store();
    $ID = $category->id();

    
    eZObjectPermission::removePermissions( $ID, "article_category", 'r' );
    $readGroups = $Data["ReadGroups"]->value();
    foreach( $readGroups as $readGroup )
    {
        eZObjectPermission::setPermission( $readGroup->value(), $ID, "article_category", 'r' );
    }

    eZObjectPermission::removePermissions( $ID, "article_category", 'w' );
    foreach( $Data["WriteGroups"]->value() as $writeGroup )
        eZObjectPermission::setPermission( $writeGroup->value(), $ID, "article_category", 'w' );
    
    $ReturnData = new eZXMLRPCStruct( array( "ErrorID" => new eZXMLRPCInt( 0 ),
                                             "ErrorString" => new eZXMLRPCString( "" )
                                             )
                                      );
}
else if( $Command == "delete" )
{
    eZLog::writeNotice( "Deleting: " . $ID );
//    $category = new eZArticleCategory( $ID );
//    $category->delete();
    eZArticleCategory::delete( $ID );
    eZLog::writeNotice( "Deleted: " . $ID );
    $ReturnData = new eZXMLRPCStruct( array( "ErrorID" => new eZXMLRPCInt( 0 ),
                                             "ErrorString" => new eZXMLRPCString( "" )
                                             )
                                      );
}

?>
