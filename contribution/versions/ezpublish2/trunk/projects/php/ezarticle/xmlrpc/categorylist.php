<?
// eZ article classes
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

$category = new eZArticleCategory( $ID );

$categoryList =& $category->getByParent( $category, true, "placement" );

$cat = array();
foreach ( $categoryList as $catItem )
{
    $cat[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $catItem->id() ),
                                        "Name" => new eZXMLRPCString( $catItem->name() )
                                        )
                                 );
    
}

$articleList =& $category->articles( "alpha", true, true, 0, 100000 );
$art = array();
foreach( $articleList as $artItem )
{
    $art[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $artItem->id() ),
                                        "Name" => new eZXMLRPCString( $artItem->name() )
                                        )
                                 );
}

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => $cat,
                                         "Elements" => $art ) );
?>
