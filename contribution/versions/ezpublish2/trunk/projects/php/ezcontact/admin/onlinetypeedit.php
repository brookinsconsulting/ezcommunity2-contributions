<?

include_once( "ezcontact/classes/ezonlinetype.php" );

$language_file = "onlinetype.php";
$item_type = new eZOnlineType( $OnlineTypeID );
$page_path = "/contact/onlinetype";

$ini =& $GlobalSiteIni;
$Language = $ini->read_var( "eZContactMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZContactMain", "DocumentRoot" );

include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

require( "ezuser/admin/admincheck.php" );

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ), $DOC_ROOT . "admin/intl", $Language, $language_file );
$t->setAllStrings();

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
    // Check to see if the count has changed since the confirmation was done
    $item_id = $item_type->id();
    $reconfirm = "Location: $page_path/confirm/$item_id";
    $count = $item_type->count();
    if ( !isset( $TypeCount ) )
    {
        $Action = "confirm";
        $TypeError = true;
    }
    else if ( $count != $TypeCount )
    {
        $Action = "confirm";
    }
    else
    {
        // The counts are the same as when confirming so we can delete

        $item_type->delete( true );
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: $page_path/list" );
        exit();
    }
}

if( $Action == "up" )
{
    $item_type->moveUp();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if( $Action == "down" )
{
    $item_type->moveDown();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
    exit();
}

if( $Action == "insert" || $Action == "update" )
{
    if ( $Action == "insert" )
        unset( $item_type->ID );
    $item_type->setName( $ItemName );
    $item_type->setURLPrefix( $Prefix );
    $item_type->setPrefixLink( $PrefixLink );
    $item_type->setPrefixVisual( $PrefixVisual );
    $item_type->store();
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $page_path/list" );
}

$t->set_file( array(
    "list_page" =>  "onlineedit.tpl",
    ) );
$t->set_block( "list_page", "type_edit_tpl", "type_edit" );
$t->set_block( "list_page", "type_confirm_tpl", "type_confirm" );

$t->set_block( "type_edit_tpl", "line_item_tpl", "line_item" );
$t->set_block( "type_edit_tpl", "no_line_item_tpl", "no_line_item" );

$t->set_block( "type_confirm_tpl", "errors_tpl", "errors" );
$t->set_block( "errors_tpl", "error_count_change_item_tpl", "error_count_change_item" );
$t->set_block( "errors_tpl", "error_no_confirm_item_tpl", "error_no_confirm_item" );

$t->set_var( "no_line_item", "" );
$t->set_var( "line_item", "" );

$t->set_var( "item_edit_command", "$page_path/edit" );
$t->set_var( "item_delete_command", "$page_path/delete" );
$t->set_var( "item_view_command", "$page_path/view" );
$t->set_var( "item_list_command", "$page_path/list" );
$t->set_var( "item_new_command", "$page_path/new" );
$t->set_var( "item_id", $ItemID );
$t->set_var( "item_name", $ItemName );
$t->set_var( "prefix", $Prefix );
$t->set_var( "prefix_link_checked", $PrefixLink );
$t->set_var( "prefix_visual_checked", $PrefixVisual );
$t->set_var( "back_url", $back_command );
$t->set_var( "item_back_command", $back_command );

if( $Action == "confirm" )
{
    $t->set_var( "error_count_change_item", "" );
    $t->set_var( "error_no_confirm_item", "" );

    if( isset( $TypeCount ) )
    {
        if ( $TypeCount != $item_type->count() )
        {
            $t->parse( "error_count_change_item", "error_count_change_item_tpl" );
            $error = true;
        }
    }
    if ( isset( $TypeError ) )
    {
        $t->parse( "error_no_confirm_item", "error_no_confirm_item_tpl" );
        $error = true;
    }
        
    if( $error == true )
    {
        $t->parse( "errors", "errors_tpl" );
   }
}

if( $error == false )
{
    $t->set_var( "errors", "" );
}

if( is_numeric( $item_type->id() ) )
{
    $t->set_var( "item_id", $item_type->id() );
    $t->set_var( "item_name", $item_type->name() );
    $t->set_var( "prefix", $item_type->urlPrefix() );
    if ( $item_type->prefixLink() )
        $t->set_var( "prefix_link_checked", "checked" );
    else
        $t->set_var( "prefix_link_checked", "" );
    if ( $item_type->prefixVisual() )
        $t->set_var( "prefix_visual_checked", "checked" );
    else
        $t->set_var( "prefix_visual_checked", "" );
}

if( $Action == "edit" )
{
    $action_value = "update";

    if( is_numeric( $item_type->id() ) )
    {
        $item_error = false;
    }
}

if ( $Action == "confirm" )
{
    $action_value = "delete";
    $t->set_var( "confirm_item", $item_type->name() );
    $t->set_var( "item_count", $item_type->count() );
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

if ( $Action == "confirm" )
{
    $t->set_var( "type_edit", "" );
    $t->parse( "type_confirm", "type_confirm_tpl", true );
}
else
{
    $t->parse( "type_edit", "type_edit_tpl", true );
    $t->set_var( "type_confirm", "" );
}

$t->set_var( "form_path", $page_path );
$t->set_var( "action_value", $action_value );
$t->pparse( "output", "list_page" );

?>
