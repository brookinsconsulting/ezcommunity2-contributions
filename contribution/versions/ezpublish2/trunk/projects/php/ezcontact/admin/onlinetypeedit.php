<?

$ini =& $GlobalSiteIni;
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "ezcontact/classes/ezonlinetype.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ), $DOC_ROOT . "admin/intl", $Language, "onlinetype.php" );
$t->setAllStrings();

$item_type = new eZOnlineType( $OnlineTypeID );
$page_path = "/contact/onlinetype";
$item_error = true;

if( empty( $HTTP_REFERER ) )
{
    if( empty( $BackUrl ) )
    {
        $back_command = "$page_path/list";
    }
    else
    {
        $back_command = $BackUrl;
    }
}
else
{
    $back_command = $HTTP_REFERER;
}

if( $Action == "delete" )
{
    $item_type->delete();
    header( "Location: $back_command" );
}

if( $Action == "insert" )
{
    unset( $item_type->ID );
    $item_type->setName( $ItemName );
    $item_type->store();
    header( "Location: $page_path/list" );
}

if( $Action == "update" )
{
    $item_type->setName( $ItemName );
    $item_type->store();
    header( "Location: $page_path/list" );
}

$t->set_file( array(
    "list_page" =>  "typeedit.tpl",
    ) );
$t->set_block( "list_page", "line_item_tpl", "line_item" );
$t->set_block( "list_page", "no_line_item_tpl", "no_line_item" );

$t->set_var( "no_line_item", "" );    
$t->set_var( "line_item", "" );    

$t->set_var( "item_edit_command", "$page_path/edit" );
$t->set_var( "item_delete_command", "$page_path/delete" );
$t->set_var( "item_view_command", "$page_path/view" );
$t->set_var( "item_list_command", "$page_path/list" );
$t->set_var( "item_new_command", "$page_path/new" );
$t->set_var( "item_id", $ItemID );
$t->set_var( "item_name", $ItemName );
$t->set_var( "back_url", $back_command );
$t->set_var( "item_back_command", $back_command );

if( is_numeric( $item_type->id() ) )
{
    $t->set_var( "item_id", $item_type->id() );
    $t->set_var( "item_name", $item_type->name() );
}

if( $Action == "edit" )
{
    $action_value = "update";
    
    
    if( is_numeric( $item_type->id() ) )
    {
        $item_error = false;
    }
}

if( $Action == "new" )
{
    $action_value = "insert";

    $item_error = false;
}

if( $item_error == true )
{
    $t->parse( "no_line_item", "no_line_item_tpl" );
}
else
{
    $t->parse( "line_item", "line_item_tpl" );
}

$t->set_var( "form_path", $page_path );
$t->set_var( "action_value", $action_value );
$t->pparse( "output", "list_page" );

?>
