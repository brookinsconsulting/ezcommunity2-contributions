<?
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticleattribute.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );

if( $Command == "data" ) // return all the data in the category
{
    $article = new eZArticle();
    if ( !$article->get( $ID ) )
    {
        $Error = createErrorMessage( EZERROR_NONEXISTING_OBJECT );
    }
    else
    {
        $writeGroups = eZObjectPermission::getGroups( $ID, "article_article", 'w', false );
        $readGroups = eZObjectPermission::getGroups( $ID, "article_article", 'r', false );
        $contentsWriter =& $article->contentsWriter( true );

        $type_arr = array();
        $types =& $article->types();
        foreach( $types as $type )
        {
            $attributes =& $type->attributes();
            if ( count( $attributes ) > 0 )
            {
                $attr_arr = array();
                foreach( $attributes as $attrib )
                {
                    $attr_arr[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $attrib->id() ),
                                                             "Name" => new eZXMLRPCString( $attrib->name() ),
                                                             "Content" => new eZXMLRPCString( $attrib->value( $article ) ) ) );
                }
                $type_arr[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $type->id() ),
                                                         "Name" => new eZXMLRPCString( $type->name() ),
                                                         "Attributes" => new eZXMLRPCArray( $attr_arr ) ) );
            }
        }

        $ret = array( "Location" => createURLStruct( "ezarticle", "article", $article->id() ),
                      "AuthorID" => new eZXMLRPCInt( $article->author( false ) ),
                      "Name" => new eZXMLRPCString( $article->name( false ) ), // title
                      "Contents" => new eZXMLRPCString( $article->contents( false ) ),
                      "ContentsWriterID" => new eZXMLRPCInt( $contentsWriter->id() ),
                      "LinkText" => new eZXMLRPCString( $article->linkText( false ) ),
                      "ManualKeyWords" => new eZXMLRPCString( $article->manualKeywords() ),
                      "Discuss" => new eZXMLRPCBool( $article->discuss() ),
                      "IsPublished" => new eZXMLRPCBool( $article->isPublished() ),
                      "PageCount" => new eZXMLRPCInt( $article->pageCount() ),
                      "Thumbnail" => new eZXMLRPCInt( $article->thumbnailImage( false ) ),
                      "Images" => new eZXMLRPCArray( $article->images( false ), "integer" ),
                      "Files" => new eZXMLRPCArray( $article->files( false ), "integer" ),
                      "ReadGroups" => new eZXMLRPCArray( $readGroups, "integer" ),
                      "WriteGroups" => new eZXMLRPCArray( $writeGroups, "integer" ),
                      "Types" => new eZXMLRPCArray( $type_arr ),
                      "Topic" => new eZXMLRPCInt( $article->topic( false ) )
//                                             "PublishedDate" => new eZXMLRPCStruct(),
                      );
        $start_date = $article->startDate();
        if ( $start_date->isValid() )
            $ret["StartDate"] = createDateTimeStruct( $start_date );
        $stop_date =& $article->stopDate();
        if ( $stop_date->isValid() )
            $ret["StopDate"] = createDateTimeStruct( $stop_date );
        $published =& $article->published();
        if ( $published->isValid() )
            $ret["PublishDate"] = createDateTimeStruct( $published );
        $ReturnData = new eZXMLRPCStruct( $ret );
    }
}
else if( $Command == "storedata" )
{
// TODO, storearticle needs work..
    eZLog::writeNotice( "article: #1" );
//    $ID = $Data["ID"]->value();
    if( $ID == 0 )
        $article = new eZArticle();
    else
        $article = new eZArticle( $ID );
    eZLog::writeNotice( "article: #2" );

    $article->setAuthor( $Data["AuthorID"]->value() );
    eZLog::writeNotice( "article: #2.1" );
    $article->setName( $Data["Name"]->value() ); // title
    eZLog::writeNotice( "article: #2.2" );
    $article->setContents( $Data["Contents"]->value() );
    eZLog::writeNotice( "article: #2.3" );
    $article->setContentsWriter( $Data["ContentsWriterID"]->value() );
    eZLog::writeNotice( "article: #2.4" );
    $article->setLinkText( $Data["LinkText"]->value() );
    eZLog::writeNotice( "article: #2.5" );
    $article->setManualKeywords( $Data["ManualKeyWords"]->value() );
    eZLog::writeNotice( "article: #2.6" );
    $article->setDiscuss( $Data["Discuss"]->value() );
    eZLog::writeNotice( "article: #2.7" );
    eZLog::writeNotice( "article: " . $Data["IsPublished"] );
    eZLog::writeNotice( "article: " . $Data["IsPublished"]->value() ? "true" : "false" );
    $article->setIsPublished( $Data["IsPublished"]->value() );
//      eZLog::writeNotice( "article: #2.8" );
//      $article->setPageCount( $Data["PageCount"]->value() );
    eZLog::writeNotice( "article: #2.9" );
    $thumbImage = new eZImage( $Data["Thumbnail"]->value() );
    eZLog::writeNotice( "article: #2.10" );
    $article->setThumbnailImage( $thumbImage );
    eZLog::writeNotice( "article: #2.11" );
    $article->setTopic( $Data["Topic"]->value() );

    eZLog::writeNotice( "article: #2.11.2" );
    if ( isset( $Data["StartDate"] ) )
    {
        eZLog::writeNotice( "article: #2.11.3" );
        $startDate = createDateTime( $Data["StartDate"]->value() );
        $article->setStartDate( $startDate );
    }
    if ( isset( $Data["StopDate"] ) )
    {
        eZLog::writeNotice( "article: #2.11.4" );
        $stopDate = createDateTime( $Data["StopDate"]->value() );
        ob_start();
        print_r( $stopDate );
        eZLog::writeNotice( "article: #2.11.4: " . ob_get_contents() );
        ob_end_flush();
        $article->setStopDate( $stopDate );
    }

    eZLog::writeNotice( "article: #2.12" );
    $article->store();
    $ID = $article->id();
    eZLog::writeNotice( "article: #3" );

//      // images
//      $images = $article->images( false );
//      $new_images = $Data["Images"]->value();
//      $old_images = array_diff( $images, $new_images );
//      $added_images = array_diff( $new_images, $images );
//      $changed_images = array_intersect( $new_images, $images );
//      foreach( $old_images as $image )
//          $article->deleteImage( $image );
//      foreach( $added_images as $image )
//          $article->addImage( $image );

//      $files =& $article->files( false );
//      $new_files = $Data["Files"]->value();
//      $old_files = array_diff( $files, $new_files );
//      $added_files = array_diff( $new_files, $files );
//      $changed_files = array_intersect( $new_files, $files );
//      foreach( $old_files as $file )
//          $article->deleteFile( $file );
//      foreach( $added_files as $file )
//          $article->addFile( $file );

    // permissions....
    eZObjectPermission::removePermissions( $ID, "article_article", 'r' );
    $readGroups = $Data["ReadGroups"]->value();
    foreach( $readGroups as $readGroup )
        eZObjectPermission::setPermission( $readGroup->value(), $ID, "article_article", 'r' );

    eZObjectPermission::removePermissions( $ID, "article_article", 'w' );
    $writeGroups = $Data["WriteGroups"]->value();
    foreach( $writeGroups as $writeGroup )
        eZObjectPermission::setPermission( $writeGroup->value(), $ID, "article_article", 'w' );

    eZLog::writeNotice( "article: #4" );

//      $types = $Data["Types"]->value();
//      foreach ( $types as $type )
//      {
//          $typeID = $type["ID"]->value();
//          $attrs = $type["Attributes"]->value();
//          $articleType = new eZArticleType( $type );
//          $attributes = $articleType->attributes( false );

//          $attrArray = array();
//          foreach( $attrs as $attr )
//          {
//              $attrArray[] = $attr["ID"]->value();
//          }
//          $old_attrs = array_diff( $attributes, $attrArray );
        

//          {
//              $attrib->setValue( $ID, htmlspecialchars( "" ) );
//          }
//      }

    $category = new eZArticleCategory( eZArticle::categoryDefinitionStatic( $ID ) );
    eZLog::writeNotice( "article: #4.2" );
    $path =& $category->path();
    eZLog::writeNotice( "article: #4.3" );
    if ( $category->id() != 0 )
    {
        $par[] = createURLStruct( "ezarticle", "category", 0 );
    }
    else
    {
        $par[] = createURLStruct( "ezarticle", "" );
    }
    eZLog::writeNotice( "article: #4.4" );
    foreach( $path as $item )
    {
        if ( $item[0] != $category->id() )
            $par[] = createURLStruct( "ezarticle", "category", $item[0] );
    }

    eZLog::writeNotice( "article: #5" );
//      eZArticleTool::deleteCache( $ID, $CategoryID, $CategoryArray );

    $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
                                             "Path" => new eZXMLRPCArray( $par ),
                                             "UpdateType" => new eZXMLRPCString( $Command )
                                             )
                                      );
    $Command = "update";
    eZLog::writeNotice( "article: #6" );

}
else if( $Command == "delete" )
{
    $category = eZArticle::categoryDefinitionStatic( $ID );
    $category = new eZArticleCategory( $category );
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
