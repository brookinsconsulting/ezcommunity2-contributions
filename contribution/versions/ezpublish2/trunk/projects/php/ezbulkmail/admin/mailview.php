<?
include_once( "ezbulkmail/classes/ezbulkmail.php" );
include_once( "ezbulkmail/classes/ezbulkmailtemplate.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );


if( isset( $Edit ) )
{
}

if( isset( $Send ) )
{
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" ); 

$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "ezbulkmail/admin/intl/", $Language, "mailview.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_view_page_tpl" => "mailview.tpl"
    ) );


$t->set_var( "site_style", $SiteStyle );
$t->set_block( "mail_view_page_tpl", "send_button_tpl", "send_button" );
$t->set_block( "mail_view_page_tpl", "edit_button_tpl", "edit_button" );

/** Check if we want the buttons enabled **/
if( $SendButton == true )
    $t->parse( "send_button", "send_button_tpl", false );
if( $EditButton == true )
    $t->parse( "edit_button", "edit_button_tpl", false );

$mail = new eZBulkMail( $MailID );
if( is_object( $mail ) )
{
    $t->set_var( "from", $mail->sender() );
    $t->set_var( "subject", $mail->subject() );
    $t->set_var( "mail_body", $mail->body() );
    $category = $mail->category();
    if( is_object( $category ) )
    {
        $t->set_var( "category", $category->name() );
    }
}
else
{
    eZHTTPTool::header( "Location: /bulkmail/drafts/" );
    exit();
}


$t->pparse( "output", "mail_view_page_tpl" );

?>
