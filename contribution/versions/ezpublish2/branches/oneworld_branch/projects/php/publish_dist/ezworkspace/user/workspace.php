<?php
//
// Created on: <22-May-2002 13:42:31 jhe>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "classes/INIFile.php" );

include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezforum/classes/ezforummessage.php" );
include_once( "ezuser/classes/ezuser.php" );

$ini = INIFile::globalINI();
$Language = $ini->read_var( "eZWorkspaceMain", "Language" );

$t = new eZTemplate( "ezworkspace/user/" . $ini->read_var( "eZWorkspaceMain", "TemplateDir" ),
                     "ezworkspace/user/intl/", $Language, "workspace.php" );

$t->setAllStrings();
$t->set_file( "workspace", "workspace.tpl" );

$t->set_block( "workspace", "article_tpl", "article" );
$t->set_block( "workspace", "forum_tpl", "forum" );

$t->set_var( "article", "" );
$t->set_var( "forum", "" );

$t->set_var( "name", $workspaceUser->name() );
$t->set_var( "email", $workspaceUser->email() );

$articleList = eZArticle::authorArticleList( $workspaceUser->id(), 0, 10 );
$db =& eZDB::globalDatabase();

foreach ( $articleList as $articleQuery )
{
    $article = new eZArticle( $articleQuery[$db->fieldName( "ID" )] );
    $t->set_var( "article_id", $article->id() );
    $t->set_var( "article_name", $article->name() );

    $t->parse( "article", "article_tpl", true );
}

$forumList = eZForumMessage::messagesByUser( $workspaceUser->id(), 0, 10 );
foreach ( $forumList as $forumMessage )
{
    $t->set_var( "message_id", $forumMessage->id() );
    $t->set_var( "message_name", $forumMessage->name() );

    $t->parse( "forum", "forum_tpl", true );
}

$t->pparse( "output", "workspace" );

?>
