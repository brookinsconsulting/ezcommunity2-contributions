<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/ezhttptool.php" );

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
$t->set_var( "current_mail_id", "" );

if( $MailID != 0 ) // load values from disk!, check that this is really current users mail
{
}

$t->pparse( "output", "mail_edit_page_tpl" );

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
