<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfolder.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "folderlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "folder_list_page_tpl" => "folderlist.tpl"
    ) );

$t->set_block( "folder_list_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_block( "folder_list_page_tpl", "folders_item_tpl", "folders_item" );
$t->set_block( "folders_item_tpl", "folders_item_edit_tpl", "folders_item_edit" );
$t->set_block( "folders_item_tpl", "edit_empty_tpl", "edit_empty" );
$t->set_var( "folders_item_edit", "" );

$t->set_var( "site_style", $SiteStyle );

/** insert the special folders **/
$i=0;
foreach( array( INBOX, SENT, DRAFTS, TRASH ) as $specialfolder )
{
    $folderItem = eZMailFolder::getSpecialFolder( $specialfolder );
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", htmlspecialchars( $folderItem->name() ) );
    $t->set_var( "folder_unread_mail_total", $folderItem->count( true ) );
    $t->set_var( "folder_mail_total", $folderItem->count() );
    $t->set_var( "indent", "");

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "edit_empty", "edit_empty_tpl", false );
    $t->parse( "folders_item", "folders_item_tpl", true );
    $i++;
}
/** insert the user folders **/
$userFolders = eZMailFolder::getTree( 0, -1);
foreach( $userFolders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem[0] );
    $t->set_var( "folder_name", htmlspecialchars( $folderItem[1] ) );
    $t->set_var( "folder_unread_mail_total", eZMailFolder::count( true, $folderItem[0] ) );
    $t->set_var( "folder_mail_total", eZMailFolder::count(false, $folderItem[0] ) );
    $t->set_var( "indent", str_repeat( "&nbsp;", 3 * $folderItem[2] ) );
    $t->set_var( "edit_empty", "" );

    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "folders_item_edit", "folders_item_edit_tpl", false );
    $t->parse( "folders_item", "folders_item_tpl", true );
    $i++;
}

/** insert folders into the move dialog **/
$folders = eZMailFolder::getByUser();
foreach( $folders as $folderItem )
{
    $t->set_var( "folder_id", $folderItem->id() );
    $t->set_var( "folder_name", $folderItem->name() );
    $t->parse( "folder_item", "folder_item_tpl", true );
}


$t->pparse( "output", "folder_list_page_tpl" );
?>
