<?

$ModuleName = "eZ article";
$DetailedSearchPath = "/article/search/";
$DetailedSearchVariable = "SearchText";
$DetailViewPath = "/article/view/";
$IconPath = "/admin/images/document.gif";

include_once( "ezarticle/classes/ezarticle.php" );

$article = new eZArticle();
$SearchResult = $article->search( $SearchText, "time", false, 0, $Limit );
$SearchCount = $article->searchCount( $SearchText, "time", false );



?>
