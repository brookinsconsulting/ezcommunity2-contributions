<?
/*!
    $Id: forum.php,v 1.19 2000/10/17 13:43:58 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );
include_once( "$DOCROOT/classes/ezforumforum.php" );
include_once( "../classes/ezusergroup.php" );
include_once( "../classes/ezsession.php" );

$session = new eZSession;

$t = new Template( "$DOCROOT/admin/templates" );
$t->set_file(Array("forum" => "forum.tpl",
                   "elements" => "forum-elements.tpl",
                   "addbox" => "forum-addbox.tpl",
                   "modifybox" => "forum-modifybox.tpl"
                   )
             );

$t->set_var( "docroot", $DOCROOT );

$t->set_var( "category_id", $category_id );

if ( $session->get( $AuthenticatedSession ) != 0 )
{
    // fail  - reason: user not logged in.
    die( "your not logged in.. (redirect to login page)" );
}

//actions (submit buttons)
if ( $add )
{
    if ( !eZUserGroup::verifyCommand( $session->userID, "eZForum_AddForum" ) )
    {
        die( "Insufficient user rights to add forum, dying..." );
        exit;
    }

    $forum = new eZforumForum;
    $forum->newForum();
    $forum->setCategoryId( $category_id );
    $forum->setName( $name );
    $forum->setDescription( $description );
    
    if ( $moderated )
        $forum->setModerated( "Y" );
    else
        $forum->setModerated( "N" );

    if ( $private )
        $forum->setPrivate( "Y" );
    else
        $forum->setPrivate( "N" );

    $forum->store();
}

if ( $modify )
{
    if ( !eZUserGroup::verifyCommand( $session->userID(), "eZForum_DeleteForum" ) )
    {
        die( "Insufficient user rights to modify forum, dying..." );
        exit;
    }
    
    $forum = new eZforumForum();
    $forum->get( $forum_id );
    $forum->setName( $name );
    $forum->setDescription( $description );
    
    if ( $moderated )
        $forum->setModerated( "Y" );
    else
        $forum->setModerated( "N" );

    if ( $private )
        $forum->setPrivate( "Y" );
    else
        $forum->setPrivate( "N" );

    $forum->store();
}

if ( $delete )
{
    if ( !eZUserGroup::verifyCommand( $session->userID(), "eZForum_DeleteForum" ) )
    {
        die( "Insufficient user rights to delete forum, dying..." );
        exit;
    }

    $forum = new eZforumForum;
    $forum->delete( $forum_id );
}

// boxes
if ( $modifyforum )
{
    $forum = new eZforumForum;
    $forum->get( $forum_id );

    $t->set_var( "name", $forum->name() );
    $t->set_var( "description", $forum->description() );

    if ( $forum->moderated() == "Y")
        $t->set_var( "moderated", "checked");
    if ( $forum->private() == "Y")
        $t->set_var( "private", "checked");

    $t->set_var( "forum_id", $forum_id);
    $t->parse( "box", "modifybox", true);
}
else // default: Add forum box
{
    $t->parse( "box", "addbox", true);
}

// Forum list for current category
$forum = new eZforumForum();

$forums = $forum->getAllForums( $category_id );

for ($i = 0; $i < count( $forums ); $i++)
{
    $t->set_var( "forum_id", $forums[$i]["Id"] );
    $t->set_var( "name", $forums[$i]["Name"] );
    $t->set_var( "description", $forums[$i]["Description"] );
    $t->set_var( "moderated", $forums[$i]["Moderated"] );
    $t->set_var( "private",  $forums[$i]["Private"] );

    $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );

    $t->parse( "forums", "elements", true);
}
$t->pparse( "output", "forum");
?>
