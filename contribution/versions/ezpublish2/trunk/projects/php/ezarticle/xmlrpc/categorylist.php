<?
// eZ article classes
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

$category = new eZArticleCategory( $ID );

$categoryList =& $category->getByParent( $category, true, "placement" );

$cat = array();
foreach ( $categoryList as $catItem )
{
    $cat[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle", "category", $catItem->id() ),
                                        "Name" => new eZXMLRPCString( $catItem->name() )
                                        )
                                 );
    
}


$articleList =& $category->articles( "alpha", true, true, 0, 100000 );
$art = array();
foreach( $articleList as $artItem )
{
    $art[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle", "article", $artItem->id() ),
                                        "Name" => new eZXMLRPCString( $artItem->name() )
                                        )
                                 );
}

$path =& $category->path();
$par = array();
foreach( $path as $item )
{
    $par[] = createURLStruct( "ezarticle", "category", $item[0] );
}

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => $cat,
                                         "Elements" => $art,
                                         "Parents" => $par ) );
?>
