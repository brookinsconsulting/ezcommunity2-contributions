<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );

if( isset( $Cancel ) )
{
    // delete existing mail draft if any.
}

if( isset( $AddAttachment ) )
{
    $MailID = save_mail();
    eZHTTPTool::header( "Location: /mail/fileedit/$MailID" );
    exit();
}

if( isset( $DeleteAttachments ) && count( $AttachmentArrayID ) > 0 )
{
    foreach( $AttachmentArrayID as $attachmmentID )
    {
        $mail = new eZMail( $MailID );
        $file = new eZVirtualFile( $attachmentID );
        $mail->deleteFile( $file );
    }
}

if( isset( $Preview ) )
{
}

if( isset( $Save ) )
{
}

if( isset( $Send ) )
{
    $MailID = save_mail();
    $mail = new eZMail( $MailID );
    $mail->send();
//    mail( $mail->to(), $mail->subject(), $mail->body() , "From: $From");
//    eZHTTPTool::header( "Location: /mail/configure/" );
//    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "mailedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_edit_page_tpl" => "mailedit.tpl"
    ) );

$t->set_block( "mail_edit_page_tpl", "attachment_delete_tpl", "attachment_delete" );
$t->set_block( "mail_edit_page_tpl", "inserted_attachments_tpl", "inserted_attachments" );
$t->set_block( "inserted_attachments_tpl", "attachment_tpl", "attachment" );
$t->set_var( "inserted_attachments", "" );
$t->set_var( "attachment_delete", "" );

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "to_value", "" );
$t->set_var( "from_value", "" );
$t->set_var( "cc_value", "" );
$t->set_var( "bcc_value", "" );
$t->set_var( "subject_value", "" );
$t->set_var( "mail_body", "" );
$t->set_var( "current_mail_id", "" );

if( $MailID != 0 && eZMail::isOwner( eZUser::currentUser(), $MailID ) ) // load values from disk!, check that this is really current users mail
{
    $t->set_var( "current_mail_id", $MailID );
    
    $mail = new eZMail( $MailID );
    $t->set_var( "to_value", htmlspecialchars( $mail->to() ) );
    $t->set_var( "from_value", htmlspecialchars( $mail->from() ) );
    $t->set_var( "subject_value", htmlspecialchars( $mail->subject() ) );
    $t->set_var( "mail_body", nl2br( htmlspecialchars( $mail->body() ) ) );
    $t->set_var( "cc_value", htmlspecialchars( $mail->cc() ) );
    $t->set_var( "bcc_value", htmlspecialchars( $mail->bcc() ) );

    $files = $mail->files();
    $i = 0;
    foreach( $files as $file )
    {
        $t->set_var( "file_name", htmlspecialchars( $file->originalFileName() ) );
        $t->set_var( "file_id", $file->id() );

        $size = $file->siFileSize();
        $t->set_var( "file_size", $size["size-string"] . $size["unit"] );

        ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
        
        $t->parse( "attachment", "attachment_tpl", true );
        $i++;
    }
    if( $i > 0 )
    {
        $t->parse( "attachment_delete", "attachment_delete_tpl" );
        $t->parse( "inserted_attachments", "inserted_attachments_tpl", false );
    }
}

$t->pparse( "output", "mail_edit_page_tpl" );

/*********************** FUNCTIONS ***************************************/

/*
  Saves the mail and returns the ID of the saved mail.
 */
function save_mail()
{
    global $To, $From, $Cc, $Bcc, $Subject, $MailBody, $MailID; // instead of passing them as arguments..

    if( $MailID == 0 )
    {
        $mail = new eZMail();
        $mail->setOwner( eZUser::currentUser() );
    }
    else
    {
        $mail = new eZMail( $MailID );
    }
    $mail->setTo( $To );
    $mail->setFrom( $From  ); // from NAME
    $mail->setCc( $Cc );
    $mail->setBcc( $Bcc );
    $mail->setMessageID( );
//    $mail->setReferences( );
//    $mail->setReplyTo( $ );
    $mail->setSubject( $Subject );
    $mail->setBodyText( $MailBody );

    $mail->store();
    $folder = eZMailFolder::getSpecialFolder( DRAFTS );
    $folder->addMail( $mail );

    return $mail->id();
}

?>
