<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezhttptool.php" );

if( isset( $Cancel ) )
{
}

if( isset( $AddAttachment ) )
{
}

if( isset( $Preview ) )
{
}

if( isset( $Save ) )
{
}

if( isset( $Send ) )
{
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "mailedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_edit_page_tpl" => "mailedit.tpl"
    ) );

$t->set_block( "mail_edit_page_tpl", "inserted_attachments_tpl", "inserted_attachments" );
$t->set_block( "inserted_attachments_tpl", "attachment_tpl", "attachment" );
$t->set_var( "inserted_attachments", "" );

$t->set_var( "to_value", "" );
$t->set_var( "from_value", "" );
$t->set_var( "cc_value", "" );
$t->set_var( "bcc_value", "" );
$t->set_var( "subject_value", "" );
$t->set_var( "mail_body", "" );


$t->pparse( "output", "mail_edit_page_tpl" );
?>
