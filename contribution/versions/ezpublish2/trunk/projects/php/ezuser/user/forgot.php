<?
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

include_once( "ezuser/classes/ezforgot.php" );
include_once( "classes/ezmail.php" );

$iniSite = new INIFIle( "site.ini" );
$Language = $iniSite->read_var( "eZUserMain", "Language" );

$ini = new INIFIle( "ezuser/intl/" . $Language . "/forgot.php.ini", false );
// Get the user.
if ( $Login )
{
    $getUser = new eZUser();
    $user = $getUser->getUser( $Login );
} 

// Store the user with a unic hash and mail the hash variable to the user.
if ( $user )
{
    $subjectText = $ini->read_var( "strings", "subject_text" );
    $bodyText = $ini->read_var( "strings", "body_text" );

    $forgot = new eZForgot();
    $forgot->get( $user );
    $forgot->setUserID( $user->id() );
    $userID = $user->id();
    $forgot->store();

    $mailpassword = new eZMail();
    $mailpassword->setTo( $user->email() );
    $mailpassword->setSubject( $subjectText );

    $body = ( $bodyText . "\n");
    $body .= ( "http://zez.nox.ez.no/user/forgot/change/?Action=change&UserID=$userID&Hash=" . $forgot->Hash() );

    $mailpassword->setBody( $body );
    $mailpassword->send();
    Header( "Location: /" );
}

if ( $Action == "change" )
{
    $change = new eZForgot();

    if ( $change->check( $Hash, $UserID ) )
    {
        $change->get( $change->check( $Hash, $UserID ) );
        $subjectNewPassword = $ini->read_var( "strings", "subject_text_password" );
        $bodyNewPassword = $ini->read_var( "strings", "body_text_password" );
        $passwordText = $ini->read_var( "strings", "password" );
        $user = new eZUser();
        $user->get( $UserID );
        $password = substr( md5( microtime() ), 0, 7 );
        $user->setPassword( $password );
        $user->store();
        $mail = new eZMail();
        $mail->setTo( $user->email() );
        $mail->setSubject( $subjectNewPassword );

        $body = ( $bodyNewPassword . "\n" );
        $body .= ( $passwordText . ": "  .  $password );
        $mail->setBody( $body );
        $mail->send();

        // Cleanup
        $change->get( $change->check( $Hash, $UserID ) );
        $change->delete();
        Header( "Location: /" );
    }
}

// Template
$t = new eZTemplate( "ezuser/user/" . $iniSite->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/user/intl", $Language, "forgot.php" );
$t->setAllStrings();

$t->set_file( array(
    "login" => "forgot.tpl"
    ) );

$t->pparse( "output", "login" );

?>
