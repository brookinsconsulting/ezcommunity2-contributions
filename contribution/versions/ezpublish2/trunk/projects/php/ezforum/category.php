<?
/*!
    $Id: category.php,v 1.18 2000/08/23 14:46:52 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:05 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

//include( "ezforum/dbsettings.php" );

$ini = new INIFile( "site.ini" ); // get language settings
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );


include_once( "ezphputils.php" );
include_once( "template.inc" );
include_once( $DOC_ROOT . "classes/ezforumforum.php" );
include_once( $DOC_ROOT . "classes/ezforummessage.php" );
include_once( $DOC_ROOT . "classes/ezsession.php" );
include_once( $DOC_ROOT . "classes/ezuser.php" );
include_once( "classes/eztemplate.php" );

$session = new eZSession;

$ini = new INIFile( "site.ini" ); // get language settings
$Language = $ini->read_var( "MAIN", "Language" );

$t = new eZTemplate( "$DOC_ROOT/templates", "$DOC_ROOT/intl", $Language, "category.php" );
$t->setAllStrings();

$t->set_file( array("category" => "category.tpl",
                    "elements" => "category-elements.tpl",
                    "navigation" => "navigation.tpl",
                    "navigation-bottom" => "navigation-bottom.tpl",
                    "no-forums" => "noforums.tpl",
                    "login" => "login.tpl",
                    "logout" => "logout.tpl"
                    )
              );

$t->set_var( "docroot", $DOC_ROOT);
$t->set_var( "category_id", $category_id );

if ( $session->get( $AuthenticatedSession ) == 0 )
{
   $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
   $t->parse( "logout-message", "logout", true );
}
else
{
   $t->set_var( "user", "Anonym" );
   $t->parse( "logout-message", "login", true);
}
$t->parse( "navigation-bar", "navigation", true);

$forum = new eZForumForum();
$forums = $forum->getAllForums( $category_id );

for ($i = 0; $i < count( $forums ); $i++)
{
    arrayTemplate( $t, $forums[$i], Array( Array( "Id", "forum_id" ),
                                           Array( "Name", "name" ),
                                           Array( "Description", "description" ),
                                           Array( "Id", "messages" )
                                                  )
                   );

    $message = new eZForumMessage();
    $t->set_var( "messages", $message->countMessages( $t->get_var( "forum_id" ) ) );
    $t->set_var( "color", switchColor( $i, "#f0f0f0", "#dcdcdc" ) );

    $t->parse("forums","elements",true);
}

if ( count( $forums ) == 0 )
    $t->set_var( "forums", "noforums", true);

$t->set_var( "link1-url", "main.php");
$t->set_var( "link2-url", "search.php");

$t->set_var( "back-url", "main.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);

$t->pparse( "output", "category" );
?>
