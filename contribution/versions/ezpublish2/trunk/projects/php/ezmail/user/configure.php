<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmailfolder.php" );

if( isset( $NewAccount ) )
{
    eZHTTPTool::header( "Location: /mail/accountedit" );
    exit();
}

if( isset( $DeleteAccounts ) )
{
}


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "configure.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_configure_page_tpl" => "configure.tpl"
    ) );

$t->set_block( "mail_configure_page_tpl", "account_item_tpl", "account_item" );
$t->set_var( "account_item", "" );


$user = eZUser::currentUser();
$accounts = eZMailAccount::getByUser( $user->id() );
foreach( $accounts as $account )
{
    $t->set_var( "account_id", $account->id() );
    $t->set_var( "account_name", htmlspecialchars( $account->name() ) );
    $t->set_var( "account_type", $account->serverType() );
    $t->set_var( "account_folder", "" );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "account_item", "account_item_tpl", true );
}


$t->pparse( "output", "mail_configure_page_tpl" );
?>
