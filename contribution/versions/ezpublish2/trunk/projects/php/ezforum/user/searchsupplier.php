<?

$ModuleName = "eZ forum";
$DetailedSearchPath = "/forum/search/";
$DetailedSearchVariable = "QueryString";
$DetailViewPath = "/forum/message/";
$IconPath = "/images/message.gif";

include_once( "ezforum/classes/ezforum.php" );

$forum = new eZForum();

$article = new eZArticle();
$SearchResult = $forum->search( $SearchText, 0, $Limit );
$SearchCount = $forum->getQueryCount( $SearchText );


?>
