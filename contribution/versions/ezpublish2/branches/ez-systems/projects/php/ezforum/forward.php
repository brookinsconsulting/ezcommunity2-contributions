<?
/*!
    $Id: forward.php,v 1.1 2000/07/14 12:55:45 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:50:04 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include('classes/ezforummessage.php');

$t = new Template("/home/lw/public_html/ezforum/templates");
$t->set_file( "forward", "forward.templates" );

if (!$UserId)
    $UserId = 0;

$msg = new eZforumMessage;
$msg->get($Id);
    
if ($forward)
{
    // user email lookup
    if ($email == "")
        $email = "bogus@mailaddr.com";
    
    mail($to, $msg->topic(), $msg->body(),
    "From: $email\nReply-To: nobody@nobody.org\nX-Mailer: PHP/" . phpversion());
    echo "sendt";
}
else
{
    $t->set_var("body", $msg->body() );
    $t->set_var("user", $msg->resolveUser( $UserId) );
    
    $t->pparse( "output", "forward");
}
?>
