<?

include_once( "ezaddress/classes/ezphonetype.php" );

$language_file = "phonetype.php";
$item_type = new eZPhoneType( $PhoneTypeID );
if ( isset( $ItemArrayID ) and is_array( $ItemArrayID ) )
{
    $item_types = array();
    foreach( $ItemArrayID as $item_id )
    {
        $item_types[] = new eZPhoneType( $item_id );
    }
}

$page_path = "/address/phonetype";

include( "ezaddress/admin/typeedit.php" );

?>
