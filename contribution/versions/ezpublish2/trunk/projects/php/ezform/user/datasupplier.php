<?
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezmail/classes/ezmail.php" );

$eZFormOperation = $url_array[2];
$eZFormName = $url_array[3];

function &errorPage( $PrimaryName, $PrimaryURL, $type )
{
    $ini =& $GLOBALS["GlobalSiteIni"];

    $t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                         "ezform/user/intl", $ini->read_var( "eZFormMain", "Language" ), "errors.php" );

    $t->set_file( "page", "errormessage.tpl"  );
    $t->set_var( "primary_url", $PrimaryURL  );
    $t->set_var( "primary_url_name", $t->Ini->read_var( "strings", $PrimaryName  ) );

    $t->set_var( "error_header", $t->Ini->read_var( "strings", error_ . $type . _header ) );
    $t->set_var( "error_1", $t->Ini->read_var( "strings", error_ . $type . _1 ) );
    $t->set_var( "error_2", $t->Ini->read_var( "strings", error_ . $type . _2 ) );
    $t->set_var( "error_3", $t->Ini->read_var( "strings", error_ . $type . _3 ) );

    $t->setAllStrings();

    $error = $t->parse( "error", "page" );
    $Info =& stripslashes( $error );
    $error =& urlencode( $Info );
    return $error;
}

$mailSendTo = "";
$mailSendFrom = "";
$mailSubject = "";
$mailMessage = "";
$redirectTo = "";

function formProcess( $value, $key )
{
    global $mailSendTo;
    global $mailSendFrom;
    global $mailSubject;
    global $mailMessage;
    global $redirectTo;
    
    switch( $key )
    {
        case "submit":
        {
        }
        break;
        
        case "redirectTo":
        {
            $redirectTo = $value;
        }
        break;
        
        case "mailSendTo":
        {
            $mailSendTo = $value;
        }
        break;
        
        case "mailSendFrom":
        {
            $mailSendFrom = $value;
        }
        break;
        
        case "mailSubject":
        {
            $mailSubject = $value;
        }
        break;
        
        default:
            $mailMessage = $mailMessage . "$key:\n$value\n\n";
            break;
    }
}

switch( $eZFormOperation )
{
    case "simpleprocess":
    {
        if( $HTTP_POST_VARS )
        {
            array_walk( $HTTP_POST_VARS, "formProcess" );
            
            $mail = new eZMail();
            $mail->setSubject( $mailSubject );
            $mail->setBody( $mailMessage );
            $mail->setFrom( $mailSendFrom );
            $mail->setTo( $mailSendTo );
            $mail->send();
        }
        
        if( !empty( $redirectTo ) )
        {
            eZHTTPTool::header( "Location: $redirectTo" );
        }
        else
        {
            eZHTTPTool::header( "Location: /" );
        }
    }
    break;
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404?Info=" . errorPage( "form_list", "/form/list/", 404 ) );
    }
    break;
}

?>
