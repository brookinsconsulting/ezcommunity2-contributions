<?
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

if( $Action == "article" ) // return all the data in the category
{
    $writeGroups = eZObjectPermission::getGroups( $ID, "article_article", 'w', false );
    $readGroups = eZObjectPermission::getGroups( $ID, "article_article", 'r', false );
    $article = new eZArticle( $ID );
    
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $article->id() ),
                                             "AuthorID" => new eZXMLRPCInt( $article->author( false ) ),
                                             "Name" => new eZXMLRPCString( $article->name( false ) ), // title
                                             "Contents" => new eZXMLRPCString( $article->contents( false ) ),
                                             "ContentsWriterID" => new eZXMLRPCString( $article->contentsWriter( true ) ),
                                             "LinkText" => new eZXMLRPCString( $article->linkText( false ) ),
                                             "ManualKeyWords" => new eZXMLRPCString( $article->manualKeywords() ),
                                             "Discuss" => new eZXMLRPCBool( $article->discuss() ),
                                             "IsPublished" => new eZXMLRPCBool( $article->isPublished() ),
                                             "PageCount" => new eZXMLRPCInt( $article->pageCount() ),
                                             "Images" => new eZXMLRPCArray( $imageList = $article->images( false ) ),
                                             "ReadGroups" => new eZXMLRPCArray( $readGroups ),
                                             "WriteGroups" => new eZXMLRPCArray( $writeGroups )
//                                             "StartDate" => new eZXMLRPCStruct(),
//                                             "StopDate" => new eZXMLRPCStruct(),
//                                             "PublishedDate" => new eZXMLRPCStruct(),
                                             )
                                      );

}
// TODO, storearticle needs work..
else if( $Action == "storearticle" )
{
    $ID = $Data["ID"]->value();
    if( $ID == 0 )
        $article = new eZArticle();
    else
        $article = new eZArticle( $ID );

    $article->setAuthor( $Data["AuthorID"]->value() );
    $article->setName( $Data["Name"]->value() ); // title
    $article->setContents( $Data["Contents"]->value() );
    $article->setLinkText( $Data["LinkText"]->value() );
    $article->setManualKeywords( $Data["ManualKeywords"] );
    $article->setDiscuss( $Data["Discuss"] );
    $article->setIsPublished( $Data["IsPublished"] );
    $article->setPageCount( $Data["PageCount"] );
    $article->store();
    $ID = $article->id();

    // images
    $images = $Data["Images"]->value();
    foreach( $images as $image )
        $article->addImage( $image );


    // permissions....
    eZObjectPermission::removePermissions( $ID, "article_article", 'r' );
    $readGroups = $Data["ReadGroups"]->value();
    foreach( $readGroups as $readGroup )
        eZObjectPermission::setPermission( $readGroup->value(), $ID, "article_article", 'r' );


    eZObjectPermission::removePermissions( $ID, "article_article", 'w' );
    foreach( $Data["WriteGroups"]->value() as $writeGroup )
        eZObjectPermission::setPermission( $writeGroup->value(), $ID, "article_article", 'w' );
    
    $category = eZArticleCategory::categoryDefinitionStatic( $ID )
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

    
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";

}
else if( $Command == "delete" )
{
    $category = eZArticleCategory::categoryDefinitionStatic( $ID )
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

    
    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";
    
    $article = new eZArticle( $ID );
    $article->delete();
}
?>
