<?
/*!
    $Id: category.php,v 1.12 2000/07/31 21:40:49 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:05 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezforumforum.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezsession.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$session = new eZSession;
$t = new Template( "$DOCROOT/templates" );
    
$t->set_file( array("category" => "category.tpl",
                    "elements" => "category-elements.tpl",
                    "navigation" => "navigation.tpl",
                    "navigation-bottom" => "navigation-bottom.tpl",
                    "no-forums" => "noforums.tpl"
                    )
              );

$t->set_var( "docroot", $DOCROOT);
            
if ( $session->get( $AuthenticatedSession ) == 0 )
{
   $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
}
else
{
   $t->set_var( "user", "Anonym" );
}
$t->parse( "navigation-bar", "navigation", true);

$forums = eZforumForum::getAllForums( $category_id );

for ($i = 0; $i < count( $forums ); $i++)
{
    arrayTemplate( $t, $forums[$i], Array( Array( "Id", "forum_id" ),
                                           Array( "Name", "name" ),
                                           Array( "Description", "description" ),
                                           Array( "Id", "messages" )
                                                  )
                   );

    $t->set_var( "messages", eZforumMessage::countMessages( $t->get_var( "forum_id" ) ) );
    $t->set_var( "color", switchColor( $i, "#eeeeee", "#bbbbbb" ) );

    $t->parse("forums","elements",true);
}

if ( count( $forums ) == 0 )
    $t->set_var( "forums", "noforums", true);

$t->set_var( "link1-url", "main.php");
$t->set_var( "link1-caption", "Gå til topp");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "main.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);

$t->pparse( "output", "category" );
?>
