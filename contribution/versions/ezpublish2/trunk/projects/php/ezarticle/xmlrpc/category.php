<?
// eZ article complete data
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

$category = new eZArticleCategory( $ID );
$ReturnData = new eZXMLRPCStruct( array( "ID" => $category->id(),
                                         "Name" => $category->name( false ),
                                         "ParentID" => $category->parent( false ),
                                         "Description" => $category->description( false ),
                                         "ExcludeFromSearch" => $category->excludeFromSearch(),
                                         "SortMode" => $category->sortMode( true ),
                                         "OwnerID" => $category->owner( false ),
                                         "SectionID" => $category->sectionIDStatic( $ID ),
                                         "ImageID" => $category->image( false )
                                         )
                                  );

?>
