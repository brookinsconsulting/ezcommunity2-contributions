<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezmail/classes/ezmail.php" );

if( isset( $Delete ) )
{
    $mail = new eZMail( $MailID );
    $folderID = $mail->folder( false );
    eZMail::delete( $MailID );
    eZHTTPTool::header( "Location: /mail/folder/$folderID" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "mailview.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_view_page_tpl" => "mailview.tpl"
    ) );

$t->set_block( "mail_view_page_tpl", "cc_value_tpl", "cc_value" );
$t->set_block( "mail_view_page_tpl", "bcc_value_tpl", "bcc_value" );
$t->set_var( "cc_value", "" );
$t->set_var( "bcc_value", "" );

$mail = new eZMail( $MailID );
$t->set_var( "current_mail_id", $MailID );

$t->set_var( "to", htmlspecialchars( $mail->to() ) );
$t->set_var( "from", htmlspecialchars( $mail->from() ) );
$t->set_var( "subject", htmlspecialchars( $mail->subject() ) );
$t->set_var( "mail_body", nl2br( htmlspecialchars( $mail->body() ) ) );

if( $mail->cc() != "" )
{
    $t->set_var( "cc", htmlspecialchars( $mail->cc() ) );
    $t->parse( "cc_value", "cc_value_tpl", false );
}

if( $mail->bcc() != "" )
{
    $t->set_var( "bcc", htmlspecialchars( $mail->bcc() ) );
    $t->parse( "bcc_value", "bcc_value_tpl", false );
}


$t->pparse( "output", "mail_view_page_tpl" );
?>
