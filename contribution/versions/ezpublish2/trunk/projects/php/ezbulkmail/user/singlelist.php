<?
include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
include_once( "ezbulkmail/classes/ezbulkmailforgot.php" );
include_once( "ezuser/classes/ezuser.php" );

// check hash from mail, validate the correct email address...
$languageIni = new INIFIle( "ezbulkmail/user/intl/" . $Language . "/subscriptionlogin.php.ini", false );
$ini =& $GLOBALS["GlobalSiteIni"];
$categoryName = $ini->read_var( "eZBulkMailMain", "SingleListLogon" );
$category = eZBulkMailCategory::getByName( $categoryName );

if( isset( $Hash ) && is_object( $category ) )
{
    $change = new eZBulkMailForgot();

    if ( $change->check( $Hash ) )
    {
        $change->get( $change->check( $Hash ) );
        if( eZBulkMailSubscriptionAddress::addressExists( $change->mail() ) && isset( $UnSubscribe ) )
        {
            $subscriptionaddress = eZBulkMailSubscriptionAddress::getByEmail( $change->mail() );
            if( $subscriptionaddress != false )
            {
                $subscriptionaddress->delete();
                $change->delete();

                $unsubscribed="";
                include( "ezbulkmail/user/usermessages.php" );
            }
        }
        else if( isset( $Subscribe ) )
        {
            $subscriptionaddress = new eZBulkMailSubscriptionAddress();
            $subscriptionaddress->setEMail( $change->mail() );
            $subscriptionaddress->store();
            $subscriptionaddress->subscribe( $category );
            
            // Cleanup
            $change->delete();

            $subscribed="";
            include( "ezbulkmail/user/usermessages.php" );
        }
    }
}
else
{
    // config error... single list specified but list not found
}

if( isset( $Subscribe ) )
{
        $subscriptionaddress = new eZBulkMailSubscriptionAddress();
        if( $subscriptionaddress->setEMail( $Email ) && !$subscriptionaddress->addressExists( $Email ) )
        {
            $headersInfo = getallheaders();
            $subjectText = ( $languageIni->read_var( "strings", "subject_text" ) . " " . $headersInfo["Host"] );
            $bodyText = $languageIni->read_var( "strings", "body_text" );

            $forgot = new eZBulkMailForgot();
            $forgot->get( $Email );
            $forgot->setMail( $Email );
            $forgot->store();

            $mailconfirmation = new eZMail();
            $mailconfirmation->setTo( $Email );
            $mailconfirmation->setSubject( $subjectText );

            $body = ( $bodyText . "\n");
            $body .= ( "http://" . $headersInfo["Host"] . "/bulkmail/singlelistsubscribe/" . $forgot->Hash() );

            $mailconfirmation->setBodyText( $body );
            $mailconfirmation->send();

            eZHTTPTool::header( "Location: /bulkmail/successfull/" );
            exit();
        }
        else
        {
            // Not a valid email address
        }
}

if( isset( $UnSubscribe ) )
{
    $subscriptionaddress = new eZBulkMailSubscriptionAddress();
    if( $subscriptionaddress->addressExists( $Email ) )
    {
        $headersInfo = getallheaders();
        $subjectText = ( $languageIni->read_var( "strings", "unsubscribe_subject_text" ) . " " . $headersInfo["Host"] );
        $bodyText = $languageIni->read_var( "strings", "unsubscribe_body_text" );

        $forgot = new eZBulkMailForgot();
        $forgot->get( $Email );
        $forgot->setMail( $Email );
        $forgot->store();

        $mailconfirmation = new eZMail();
        $mailconfirmation->setTo( $Email );
        $mailconfirmation->setSubject( $subjectText );

        $body = ( $bodyText . "\n");
        $body .= ( "http://" . $headersInfo["Host"] . "/bulkmail/singlelistunsubscribe/" . $forgot->Hash() );

        $mailconfirmation->setBodyText( $body );
        $mailconfirmation->send();

        $unsubscribemail = "";
        include( "ezbulkmail/user/usermessages.php" );
    }
}







?>
