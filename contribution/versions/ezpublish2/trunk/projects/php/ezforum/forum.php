<?
/*!
    $Id: forum.php,v 1.17 2000/07/26 12:45:08 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:57:16 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezsession.php" );


$msg = new eZforumMessage( $forum_id );
$t = new Template(".");

$t->set_file( Array("forum" => "$DOCROOT/templates/forum.tpl",
                    "elements" => "$DOCROOT/templates/forum-elements.tpl",
                    "preview" => "$DOCROOT/templates/forum-preview.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl"
                   )
            );

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

//navbar setup
if ( $AuthenticatedSession )
{
    $session = new eZSession();
    $session->get( $AuthenticatedSession );
    $UserID = $session->UserID();

    $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
}
else
{
    $UserID = 0;
    $t->set_var( "user", "Anonym" );
}
$t->parse( "navigation-bar", "navigation", true);


// new posting
if ( $post )
{
    $msg->newMessage();
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    if ( $notice )
        $msg->enableEmailNotice();
    else
        $msg->disableEmailNotice();
    $msg->store();
}

// reply
if ( $reply )
{
    $msg->newMessage();    
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    $msg->setParent( $parent );
    if ( $notice )
        $msg->enableEmailNotice();
    else
        $msg->disableEmailNotice();
    $msg->store();
}

// preview message
if ( $preview )
{
    $t->set_var( "topic", $Topic );
    $t->set_var( "body", nl2br( $Body ) );
    $t->set_var( "body-clean", $Body );
    $t->set_var( "userid", $UserID );
    $t->pparse( "output", "preview" );
}
else
{
    $messages = $msg->printHeaderTree( $forum_id, 0, 0, $DOCROOT );
    $t->set_var( "messages", $messages );
    
    $t->set_var( "newmessage", $newmessage);

    $t->set_var( "link1-url", "newmessage.php" );
    $t->set_var( "link1-caption", "Ny Melding" );
    $t->set_var( "link2-url", "search.php" );
    $t->set_var( "link2-caption", "Søk" );

    $t->set_var( "back-url", "category.php");
    $t->parse( "navigation-bar-bottom", "navigation-bottom", true);

    $t->pparse("output","forum");
}

?>

