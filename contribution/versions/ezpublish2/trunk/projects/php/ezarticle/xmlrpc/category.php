<?
// eZ article complete data
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

// TODO: check permissions!!

if( $Action == "category" ) // Dump category info!
{
    $writeGroups = eZObjectPermission::getGroups( $ID, "article_category", 'w', false );
    $readGroups = eZObjectPermission::getGroups( $ID, "article_category", 'r', false );
    $category = new eZArticleCategory( $ID );
    $ReturnData = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $category->id() ),
                                             "Name" => new eZXMLRPCString( $category->name( false ) ),
                                             "ParentID" => new eZXMLRPCInt( $category->parent( false ) ),
                                             "Description" => new eZXMLRPCString( $category->description( false ) ),
                                             "ExcludeFromSearch" => new eZXMLRPCBool( $category->excludeFromSearch() ),
                                             "SortMode" => new eZXMLRPCInt( $category->sortMode( true ) ),
                                             "OwnerID" => new eZXMLRPCInt( $category->owner( false ) ),
                                             "SectionID" => new eZXMLRPCInt( $category->sectionIDStatic( $ID ) ),
                                             "ImageID" => new eZXMLRPCInt( $category->image( false ) ),
                                             "ReadGroups" => new eZXMLRPCArray( $readGroups ),
                                             "WriteGroups" => new eZXMLRPCArray( $writeGroups )
                                             )
                                      );
}
else if( $Action == "storecategory" ) // save the category data!
{
    if( $Data["ID"] == 0 )
        $category = new eZArticleCategory();
    else
        $category = new eZArticleCategory( $Data["ID"] );

    $category->setName( $Data["Name"]->value() );
    $category->setDescription( $Data["Description"]->value() );
    $category->setParent( $Data["ParentID"]->value() );
    $category->setExcludeFromSearch( $Data["ExcludeFromSearch"]->value() );
    $category->setSortMode( $Data["SortMode"]->value() );
    $category->setOwnerID( $Data["OwnerID"]->value() );
    $category->setSectionID( $Data["SectionID"]->value() );
    $category->setImage( $Data["ImageID"]->value() );
    $category->store();
    $ID = $category->id();

    eZObjectPermisson::removePermissions( $ID, "article_category", 'r' );
    foreach( $Data["ReadGroups"]->value() as $readGroup )
        eZObjectPermission::setPermission( $readGroup->value(), $ID, "article_category", 'r' );

    eZObjectPermisson::removePermissions( $ID, "article_category", 'w' );
    foreach( $Data["WriteGroups"]->value() as $writeGroup )
        eZObjectPermission::setPermission( $writeGroup->value(), $ID, "article_category", 'w' );
    
    $ReturnData = new eZXMLRPCStruct( array( "ErrorID" => new eZXMLRPCInt( 0 ),
                                             "ErrorString" => new eZXMLRPCString( "" )
                                             )
                                      );
}

?>
