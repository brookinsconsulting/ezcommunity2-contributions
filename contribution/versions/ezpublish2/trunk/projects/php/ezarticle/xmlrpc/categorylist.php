<?
// eZ article classes
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

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

$category = new eZArticleCategory( $ID );

$cat = array();
if ( $list_categories )
{
    $categoryList =& $category->getByParent( $category, true, "placement" );

    foreach ( $categoryList as $catItem )
    {
        $cat[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle", "category", $catItem->id() ),
                                            "Name" => new eZXMLRPCString( $catItem->name() )
                                            )
                                     );
    
    }
}

$art = array();
if ( $list_articles )
{
    $articleList =& $category->articles( "alpha", true, true, 0, 100000 );
    foreach( $articleList as $artItem )
    {
        $art[] = new eZXMLRPCStruct( array( "URL" => createURLStruct( "ezarticle", "article", $artItem->id() ),
                                            "Name" => new eZXMLRPCString( $artItem->name() )
                                            )
                                     );
    }
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
