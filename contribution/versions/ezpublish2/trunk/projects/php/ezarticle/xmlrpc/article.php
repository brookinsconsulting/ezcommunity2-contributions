<?
include_once( "ezarticle/classes/ezarticlecategory.php" );
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
// TODO, storearticle needs work..
//  else if( $Command == "storedata" )
//  {
//      $ID = $Data["ID"]->value();
//      if( $ID == 0 )
//          $article = new eZArticle();
//      else
//          $article = new eZArticle( $ID );

//      $article->setAuthor( $Data["AuthorID"]->value() );
//      $article->setName( $Data["Name"]->value() ); // title
//      $article->setContents( $Data["Contents"]->value() );
//      $article->setLinkText( $Data["LinkText"]->value() );
//      $article->setManualKeywords( $Data["ManualKeywords"] );
//      $article->setDiscuss( $Data["Discuss"] );
//      $article->setIsPublished( $Data["IsPublished"] );
//      $article->setPageCount( $Data["PageCount"] );
//      $article->store();
//      $ID = $article->id();

//      // images
//      $images = $Data["Images"]->value();
//      foreach( $images as $image )
//          $article->addImage( $image );


//      // permissions....
//      eZObjectPermission::removePermissions( $ID, "article_article", 'r' );
//      $readGroups = $Data["ReadGroups"]->value();
//      foreach( $readGroups as $readGroup )
//          eZObjectPermission::setPermission( $readGroup->value(), $ID, "article_article", 'r' );


//      eZObjectPermission::removePermissions( $ID, "article_article", 'w' );
//      foreach( $Data["WriteGroups"]->value() as $writeGroup )
//          eZObjectPermission::setPermission( $writeGroup->value(), $ID, "article_article", 'w' );
    
//      $category = eZArticleCategory::categoryDefinitionStatic( $ID )
//      $path =& $category->path();
//      if ( $category->id() != 0 )
//      {
//          $par[] = createURLStruct( "ezarticle", "category", 0 );
//      }
//      else
//      {
//          $par[] = createURLStruct( "ezarticle", "" );
//      }
//      foreach( $path as $item )
//      {
//          if ( $item[0] != $category->id() )
//              $par[] = createURLStruct( "ezarticle", "category", $item[0] );
//      }

    
//      $ReturnData = new eZXMLRPCStruct( array( "Location" => createURLStruct( "ezarticle", "article", $ID ),
//                                               "Path" => new eZXMLRPCArray( $par ),
//                                               "UpdateType" => new eZXMLRPCString( $Command )
//                                               )
//                                        );
//      $Command = "update";

//  }
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
