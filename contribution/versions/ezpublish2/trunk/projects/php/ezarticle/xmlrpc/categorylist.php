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

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => $cat,
                                         "Elements" => $art,
                                         "Path" => $par ) ); // array starting with top level catalogue, ending with parent.
?>
