<?
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
include_once( "ezbulkmail/classes/ezbulkmailtemplate.php" );
include_once( "ezbulkmail/classes/ezbulkmail.php" );

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /bulkmail/folder/$inboxid" );
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
    eZHTTPTool::header( "Location: /mail/folder/$sentid" );
    exit();
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" ); 

$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "ezbulkmail/admin/intl/", $Language, "mailedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_edit_page_tpl" => "mailedit.tpl"
    ) );

$t->set_block( "mail_edit_page_tpl", "category_item_tpl", "category_item" );
$t->set_block( "mail_edit_page_tpl", "template_item_tpl", "template_item" );
$t->set_var( "category_item", "" );
$t->set_var( "template_item", "" );

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "from_value", "" );
$t->set_var( "subject_value", "" );
$t->set_var( "mail_body", "" );
$t->set_var( "current_mail_id", "" );

/** New mail, lets insert some default values **/
if( $MailID == 0 )
{
    // put signature stuff here...
}
$user = eZUser::currentUser();
$t->set_var( "from_value", $user->email() );

/** We are editing an allready existant mail... lets insert it's values **/
if( $MailID != 0 ) // load values from disk!, check that this is really current users mail
{
    $t->set_var( "current_mail_id", $MailID );
    
    $mail = new eZBulkMail( $MailID );

    if( $mail->sender() != "" )
        $t->set_var( "from_value",  $mail->sender() );
    $t->set_var( "subject_value", $mail->subject() );
    $t->set_var( "mail_body", $mail->body() );

}

/** Inserting values in the drop down boxes... **/
$categories = eZBulkMailCategory::getAll();
foreach( $categories as $category )
{
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );
    $t->set_var( "selected", "" );
    $t->parse( "category_item", "category_item_tpl",  true );
}
$templates = eZBulkMailTemplate::getAll();
foreach( $templates as $template )
{
    $t->set_var( "template_id", $template->id() );
    $t->set_var( "template_name", $template->name() );
    $t->set_var( "selected", "" );
    $t->parse( "template_item", "template_item_tpl",  true );
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
        $mail = new eZBulkMail();
        $mail->setOwner( eZUser::currentUser() );
    }
    else
    {
        $mail = new eZBulkMail( $MailID );
    }
    $mail->setTo( $To );
    $mail->setFrom( $From  ); // from NAME
    $mail->setCc( $Cc );
    $mail->setBcc( $Bcc );
//    $mail->setReferences( );
//    $mail->setReplyTo( $ );
    $mail->setSubject( $Subject );
    $mail->setBodyText( $MailBody );
    $mail->calculateSize();
    
    $mail->store();

    return $mail->id();
}

?>
