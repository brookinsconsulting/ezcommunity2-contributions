<?
$ini =& $GlobalSiteIni;
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ), $DOC_ROOT . "admin/intl", $Language, "error.php" );
$t->setAllStrings();

$page_path = "/contact/error";
$item_error = true;

if( empty( $BackUrl ) )
{
    $back_command = "/";
}
else
{
    $back_command = $BackUrl;
}

header( "Error $Type: " );

$t->set_file( array(
    "error_page" =>  "error.tpl",
    ) );
$t->set_block( "error_page", "uri_item_tpl", "uri_item" );

$t->set_var( "uri_item", "" );    

if( !empty( $Uri ) )
{
    $t->set_var( "uri_data", "http://" . $HOSTNAME . $Uri );
    $t->parse( "uri_item","uri_item_tpl" );
}

$t->pparse( "output", "error_page" );
?>
