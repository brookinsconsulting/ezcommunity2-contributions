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
        $category->setOwner( $User );
    }

    $category->setName( $Data["Name"]->value() );
    $category->setDescription( $Data["Description"]->value() );

//    $category->setParent( $Data["ParentID"]->value() );
    $category->setParent( 0 );
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


    // create the path array
    $path =& $category->path();
    if ( $category->id() != 0 )
    {
        $par[] = createURLStruct( "ezarticle", "category", 0 );
    }
    else
    {
        $par[] = createURLStruct( "ezarticle", "" );
    }
    foreach( $path as $item )
    {
        if ( $item[0] != $category->id() )
            $par[] = createURLStruct( "ezarticle", "category", $item[0] );
    }

    
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "category", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";
}
else if( $Command == "delete" )
{
    // create the path array
    $category = new eZArticleCategory( $ID );
    $path =& $category->path();
    if ( $category->id() != 0 )
    {
        $par[] = createURLStruct( "ezarticle", "category", 0 );
    }
    else
    {
        $par[] = createURLStruct( "ezarticle", "" );
    }
    foreach( $path as $item )
    {
        if ( $item[0] != $category->id() )
            $par[] = createURLStruct( "ezarticle", "category", $item[0] );
    }

    
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "category", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";
    eZArticleCategory::delete( $ID ); // finally, delete the articlecategory..
}

?>
