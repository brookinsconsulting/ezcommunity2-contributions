<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezmail/classes/ezmailfolder.php" );

if( isset( $Reply ) )
{
    $mail = new eZMail( $MailID );
    $reply = $mail->copyMail();
    $replyid = $reply->id();

    $drafts = eZMailFolder::getSpecialFolder( DRAFTS );
    $drafts->addMail( $mail );
    
    eZHTTPTool::header( "Location: /mail/mailedit/$replyid" );
    exit();
}

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
$t->set_block( "mail_view_page_tpl", "inserted_attachments_tpl", "inserted_attachments" );
$t->set_block( "inserted_attachments_tpl", "attachment_tpl", "attachment" );
$t->set_var( "inserted_attachments", "" );
$t->set_var( "cc_value", "" );
$t->set_var( "bcc_value", "" );

$mail = new eZMail( $MailID );
$t->set_var( "current_mail_id", $MailID );

$t->set_var( "to", htmlspecialchars( $mail->to() ) );
$t->set_var( "from", htmlspecialchars( $mail->from() ) );
$t->set_var( "subject", htmlspecialchars( $mail->subject() ) );
$t->set_var( "mail_body", nl2br( htmlspecialchars( $mail->body() ) ) );
$t->set_var( "date", date("D M d H:i Y ", $mail->uDate() ) );

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

    $files = $mail->files();
    $i = 0;
    foreach( $files as $file )
    {
        $t->set_var( "file_name", "<a href=\"/filemanager/download/" . $file->id() . "/" /*. $file->originalFileName()*/ . "\">" . htmlspecialchars( $file->originalFileName() ) . "</a>" );
        $t->set_var( "file_id", $file->id() );

        $size = $file->siFileSize();
        $t->set_var( "file_size", $size["size-string"] . $size["unit"] );
        
        ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
        
        $t->parse( "attachment", "attachment_tpl", true );
        $i++;
    }
    if( $i > 0 )
        $t->parse( "inserted_attachments", "inserted_attachments_tpl", false );

$t->pparse( "output", "mail_view_page_tpl" );
?>
