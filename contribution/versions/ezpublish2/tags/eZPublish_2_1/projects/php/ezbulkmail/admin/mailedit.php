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
    eZHTTPTool::header( "Location: /bulkmail/mailedit" );
    exit();
}

if( isset( $Preview ) )
{
    $MailID = save_mail();
    if( !isset( $error ) )
    {
        eZHTTPTool::header( "Location: /bulkmail/preview/$MailID" );
        exit();
    }
}

if( isset( $Save ) )
{
    $MailID = save_mail();
}

if( isset( $Send ) )
{
    $MailID = save_mail();
    if( !isset( $error ) )
    {
        eZHTTPTool::header( "Location: /bulkmail/send/$MailID" );
        exit();
    }
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBulkMailMain", "Language" ); 

$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "ezbulkmail/admin/intl/", $Language, "mailedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_edit_page_tpl" => "mailedit.tpl"
    ) );

$t->set_block( "mail_edit_page_tpl", "template_item_tpl", "template_item" );
$t->set_block( "mail_edit_page_tpl", "multiple_value_tpl", "multiple_value" );
$t->set_block( "mail_edit_page_tpl", "error_message_tpl", "error_message" );
$t->set_var( "multiple_value", "" );
$t->set_var( "template_item", "" );
$t->set_var( "error_message", "" );

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
$categoryArrayID = array();
/** We are editing an allready existent mail... lets insert it's values **/
if( $MailID != 0 ) 
{
    $t->set_var( "current_mail_id", $MailID );
    
    $mail = new eZBulkMail( $MailID );

    if( $mail->sender() != "" )
        $t->set_var( "from_value",  $mail->sender() );
    $t->set_var( "subject_value", $mail->subject() );
    $t->set_var( "mail_body", $mail->body() );

    $categoryArrayID = $mail->categories( false );
    $templateID = $mail->template( false );
}

/** Inserting values in the drop down boxes... **/
$categories = eZBulkMailCategory::getAll();
foreach( $categories as $category )
{
    $t->set_var( "category_id", $category->id() );
    $t->set_var( "category_name", $category->name() );

    if( in_array( $category->id(), $categoryArrayID ) )
        $t->set_var( "multiple_selected", "selected" );
    else
        $t->set_var( "multiple_selected", "" );
        
    $t->parse( "multiple_value", "multiple_value_tpl", true );
}
$templates = eZBulkMailTemplate::getAll();
foreach( $templates as $template )
{
    $t->set_var( "template_id", $template->id() );
    $t->set_var( "template_name", $template->name() );
    $t->set_var( "selected", "" );
    if( $templateID == $template->id() )
        $t->set_var( "selected", "selected" );
    else
        $t->set_var( "selected", "" );

    $t->parse( "template_item", "template_item_tpl",  true );
}

/** Lets check for errors and display them to the user **/
if( isset( $error ) )
    $t->parse( "error_message", "error_message_tpl", false );

$t->pparse( "output", "mail_edit_page_tpl" );

/*********************** FUNCTIONS ***************************************/

/*
  Saves the mail and returns the ID of the saved mail.
 */
function save_mail()
{
    global $CategoryArrayID, $TemplateID, $To, $From, $Subject, $MailBody, $MailID, $error;// instead of passing them as arguments..

    if( $MailID == 0 )
    {
        $mail = new eZBulkMail();
        $mail->setOwner( eZUser::currentUser() );
    }
    else
    {
        $mail = new eZBulkMail( $MailID );
    }
    $mail->setSender( $From  ); // from NAME
    $mail->setSubject( $Subject );
    $mail->setBodyText( $MailBody );

    $mail->setIsDraft( true );
    
    $mail->store();
    if( $TemplateID != -1 )
        $mail->useTemplate( $TemplateID );
    
    $mail->addToCategory( false );
    if( count( $CategoryArrayID ) > 0 )
    {
        foreach( $CategoryArrayID as $categoryItemID )
        {
            $mail->addToCategory( $categoryItemID );
        }
    }
    else
        $error = "No categories set";
    
    return $mail->id();
}

?>
