<?
/*!
    $Id: editmessage.php,v 1.3 2000/07/25 20:07:50 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <25-Jul-2000 15:13:15 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$t = new Template( "." );
$t->set_file(Array( "edit" => "$DOCROOT/admin/templates/editmessage.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl" ) );

$msg = new eZforumMessage;
$msg->get( $message_id );

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$t->parse( "navigation-bar", "navigation", true);

// rest
$t->set_var( "user", eZUser::resolveUser( $msg->userId() ) );
$t->set_var( "topic", $msg->topic() );
$t->set_var( "body", $msg->body() );

if ( $msg->emailNotice() == "Y" )
{
    $t->set_var( "email-notice", "checked");
}
else
{
    $t->set_var( "email-notice", "");
}

$t->set_var( "link1-url", "admin/category.php");
$t->set_var( "link1-caption", "Gå til topp");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "admin/message.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);
$t->pparse( "output", "edit" );
?>
