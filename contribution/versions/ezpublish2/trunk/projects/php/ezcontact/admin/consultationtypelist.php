<?

include_once( "ezcontact/classes/ezconsultationtype.php" );

$language_file = "consultationtype.php";
$page_path = "/contact/consultationtype";
$item_type_array = eZConsultationType::findTypes();
$move_item = true;
$SortPage = "/contact/consultation/type/list";

include( "ezcontact/admin/typelist.php" );

?>
