<?

include_once( "ezcontact/classes/ezconsultationtype.php" );

$language_file = "consultationtype.php";
$item_type = new eZConsultationType( $ConsultationTypeID );
$page_path = "/contact/consultationtype";

include( "ezcontact/admin/typeedit.php" );

?>
