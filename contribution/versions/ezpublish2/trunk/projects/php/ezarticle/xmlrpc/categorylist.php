<?
// eZ article classes
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

$category = new eZArticleCategory();

$categoryList =& $category->getByParent( $category, true, "placement" );

$cat = array();
foreach ( $categoryList as $catItem )
{
    $cat[] = new eZXMLRPCStruct( array( "ID" => new eZXMLRPCInt( $catItem->id() ),
                                        "Name" => new eZXMLRPCString( $catItem->name() )
                                        )
                                 );
    
}

$ReturnData = new eZXMLRPCStruct( array( "Catalogues" => $cat,
                                         "Elements" => array() ) );

?>
