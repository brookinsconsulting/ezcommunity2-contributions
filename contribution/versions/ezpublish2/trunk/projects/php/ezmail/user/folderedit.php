<?
include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 


if( isset( $Ok ) && $Name != "" )
{
    if( $FolderID == 0 )
        $folder = new eZMailFolder();
    else
        $folder = new eZMailFolder( $FolderID );

    $folder->setName( $Name );
    $folder->setParent( $ParentID );
    $folder->setUser( eZUser::currentUser() );
    $folder->store();
    $FolderID = $folder->id();
    eZHTTPTool::header( "Location: /mail/folder/$FolderID" );
    exit();
}


$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "folderedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "folder_edit_page_tpl" => "folderedit.tpl"
    ) );

$t->set_block( "folder_edit_page_tpl", "folder_item_tpl", "folder_item" );
$t->set_var( "folder_item", "" );
$t->set_var( "folder_name", "" );
$t->set_var( "current_folder_id", $FolderID );

$folders = eZMailFolder::getByUser();
foreach( $folders as $folderItem )
{
    $t->set_var( "folder_parent_id", $folderItem->id() );
    $t->set_var( "folder_parent_name", $folderItem->name() );
    $t->set_var( "is_selected", "" );
    $t->parse( "folder_item", "folder_item_tpl", true );
}


$t->pparse( "output", "folder_edit_page_tpl" );
?>
