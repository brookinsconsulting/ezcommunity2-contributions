<?
// eZ article classes
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

if ( $Command == "list" )
{

    $list_categories = false;
    $list_articles = false;
    if ( is_object( $Data["ListType"] ) )
    {
        $ListType = $Data["ListType"]->value();
        if ( is_object( $ListType["Catalogues"] ) )
            $list_categories = true;
        if ( is_object( $ListType["Elements"] ) )
            $list_articles = true;
    }

    if ( !$list_categories and !$list_articles )
    {
        $list_categories = true;
        $list_articles = true;
    }

    $offset = 0;
    $max = -1;
    $total = 0;

    if ( is_object( $Data["Part"] ) )
    {
        $Part = $Data["Part"]->value();
        $offset = $Part["Offset"]->value();
        $max = $Part["Max"]->value();
//      eZLog::writeNotice( "Article: Offset: $offset, Max: $max" );
    }


    $category = new eZArticleCategory( $ID );

    $loc_max = $max;
    $loc_offset = $offset;

    $cat = array();
    if ( $list_categories )
    {
        $categoryCount = $category->countByParent( $category, true );
        $total += $categoryCount;
        if ( $loc_offset < $categoryCount )
        {
            $categoryList =& $category->getByParent( $category, true, "placement", $loc_offset, $loc_max );
            $loc_max -= count( $categoryList );

            foreach ( $categoryList as $catItem )
            {
                $cat[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle",
                                                                              "category",
                                                                              $catItem->id() ),
                                                    "Name" => new eZXMLRPCString( $catItem->name() )
                                                    )
                                             );
            }
        }
        $loc_offset = max( 0, $loc_offset - $categoryCount );
    }

    $art = array();
    if ( $list_articles )
    {
        $articleCount = $category->articleCount( true, true );
        $total += $articleCount;
        if ( $loc_max > 0 and $loc_offset >= 0 )
        {
            $articleList =& $category->articles( "alpha", true, true, $loc_offset, $loc_max );
            foreach( $articleList as $artItem )
            {
                $art[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle",
                                                                              "article",
                                                                              $artItem->id() ),
                                                    "Name" => new eZXMLRPCString( $artItem->name() )
                                                    )
                                             );
            }
        }
//      if ( $offset > 0 )
//          usleep( 5000000 );
    }

    $par = array();
    if ( $offset == 0 )
    {
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
    }

    $part_arr = array( "Offset" => new eZXMLRPCInt( $offset ),
                       "Total" => new eZXMLRPCInt( $total ) );
    if ( $offset == 0 )
    {
        $part_arr["Begin"] = new eZXMLRPCBool( true );
    }
    if ( $total == $offset + count( $cat ) + count( $art ) )
    {
        $part_arr["End"] = new eZXMLRPCBool( true );
    }
    $part = new eZXMLRPCStruct( $part_arr );

    $ReturnData = new eZXMLRPCStruct( array( "Catalogues" => $cat,
                                             "Elements" => $art,
                                             "Path" => $par,
                                             "Part" => $part ) ); // array starting with top level catalogue, ending with parent.
}
else if ( $Command == "tree" )
{
    $cat = new eZArticleCategory();
    $tree =& categoryTree( $cat );
    $ReturnData = new eZXMLRPCStruct( array( "Tree" => createTreeStruct( $tree, "ezarticle", "category" ) ) );
}

function categoryTree( $cat )
{
}

?>
