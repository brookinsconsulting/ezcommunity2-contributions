<?
/*!
    $Id: forum.php,v 1.6 2000/07/21 11:35:52 lw Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 08:56:19 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezforumcategory.php" );
include_once( "$DOCROOT/classes/ezforumforum.php" );

$t = new Template( "." );
$t->set_file(Array("forum" => "$DOCROOT/admin/templates/forum.tpl",
                   "elements" => "$DOCROOT/admin/templates/forum-elements.tpl",
                   "addbox" => "$DOCROOT/admin/templates/forum-addbox.tpl",
                   "modifybox" => "$DOCROOT/admin/templates/forum-modifybox.tpl"
                   )
             );

$t->set_var( "docroot", $DOCROOT);
$t->set_var( "category_id", $category_id);

//actions
if ( $add )
{
    $forum = new eZforumForum;
    $forum->new();
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
    $forum = new eZforumForum;
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

    if ( ($i % 2) != 0)
        $t->set_var( "color", "#eeeeee" );
    else
        $t->set_var( "color", "#bbbbbb" );
            
    $t->parse( "forums", "elements", true);
}
$t->pparse( "output", "forum");
?>
