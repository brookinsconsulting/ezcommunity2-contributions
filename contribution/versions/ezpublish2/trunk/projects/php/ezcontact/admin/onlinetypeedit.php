<?

include_once( "ezcontact/classes/ezonlinetype.php" );

$language_file = "onlinetype.php";
$item_type = new eZOnlineType( $OnlineTypeID );
$page_path = "/contact/onlinetype";

include( "ezcontact/admin/typeedit.php" );

?>
