<?
include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );
include_once( "ezbulkmail/classes/ezbulkmailforgot.php" );
include_once( "classes/ezmail.php" );
include_once( "ezuser/classes/ezuser.php" );

// check hash from mail, validate the correct email address...
$languageIni = new INIFIle( "ezbulkmail/user/intl/" . $Language . "/subscriptionlogin.php.ini", false );

if( isset( $Hash ) )
{
    $change = new eZBulkMailForgot();

    if ( $change->check( $Hash ) )
    {
        $change->get( $change->check( $Hash ) );
        $subscriptionaddress = new eZBulkMailSubscriptionAddress();
        $subscriptionaddress->setEMail( $change->mail() );
        $subscriptionaddress->setEncryptetPassword( $change->password() );
        $subscriptionaddress->store();

      // Cleanup
        $change->delete();
        $session->setVariable( "BulkMailAddress", $change->mail() );
        eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
        exit();
    }

}

if( isset( $Ok ) )
{
    if( $Action == "login" )
    {
        // check if password and email is correct.. if so, let the user continue..
        if( eZBulkMailSubscriptionAddress::validate( $Email, $Password ) )
        {
            $session->setVariable( "BulkMailAddress", $Email );
            eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
            exit();
        }
    }
    else if( $Action == "create" )
    {
        // TODO:check if address allready exists!!
        $subscriptionaddress = new eZBulkMailSubscriptionAddress();
        if( $subscriptionaddress->setEMail( $Email ) && $Password != "" && $Password == $Password2 ) // check if passwords are alike and that we have a valid email address...
        {
            $headersInfo = getallheaders();
            // send an email to the new address asking to confirm it..
            $subjectText = ( $languageIni->read_var( "strings", "subject_text" ) . " " . $headersInfo["Host"] );
            $bodyText = $languageIni->read_var( "strings", "body_text" );

            $forgot = new eZBulkMailForgot();
            $forgot->get( $Email );
            $forgot->setMail( $Email );
            $forgot->setPassword( $Password );
            $forgot->store();

            $mailpassword = new eZMail();
            $mailpassword->setTo( $Email );
            $mailpassword->setSubject( $subjectText );

            $body = ( $bodyText . "\n");
            $body .= ( "http://" . $headersInfo["Host"] . "/bulkmail/confirmsubscription/" . $forgot->Hash() );

            $mailpassword->setBody( $body );
            $mailpassword->send();

            eZHTTPTool::header( "Location: /bulkmail/successfull/" );
            exit();
        }
        else // we have some sort of error... find out what it is, and present it to the user..
        {
        }

        // send confirmation mail to that address
    }
}

$t = new eZTemplate( "ezbulkmail/user/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "ezbulkmail/user/intl", $Language, "subscriptionlogin.php" );

$t->set_file( array(
    "subscription_login_tpl" => "subscriptionlogin.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteStyle );

$t->set_block( "subscription_login_tpl", "second_password_tpl", "second_password" );
$t->set_block( "subscription_login_tpl", "new_tpl", "new" );
$t->set_block( "subscription_login_tpl", "login_tpl", "login" );
$t->set_var( "new", "" );
$t->set_var( "login", "" );
$t->set_var( "second_password", "" );
$t->set_var( "action_value", "login" );

if( isset( $New ) )
{
    $t->parse( "second_password", "second_password_tpl" );
    $t->set_var( "action_value", "create" );
    $t->parse( "login", "login_tpl" );
}
else
{
    $t->parse( "new", "new_tpl" );
}

$t->pparse( "output", "subscription_login_tpl" );
?>
