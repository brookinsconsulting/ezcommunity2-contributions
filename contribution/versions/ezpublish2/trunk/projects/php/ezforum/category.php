<?
/*!
    $Id: category.php,v 1.5 2000/07/25 09:59:15 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:05 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include( "template.inc" );
include( "$DOCROOT/classes/ezforumforum.php" );
include( "$DOCROOT/classes/ezsession.php" );
include( "$DOCROOT/classes/ezuser.php" );

openDB();

$session = new eZSession;
$forum = new eZforumForum;
$t = new Template(".");
    
$t->set_file( array("category" => "$DOCROOT/templates/category.tpl",
                    "elements" => "$DOCROOT/templates/category-elements.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl"
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

$forums = $forum->getAllForums($category_id);
        
for ($i = 0; $i < count($forums); $i++)
{
    $Id = $forums[$i]["Id"];
    $Name = $forums[$i]["Name"];
    $Description = $forums[$i]["Description"];
    
    $query_id = mysql_query("SELECT COUNT(Id) AS Messages FROM MessageTable WHERE ForumId='$Id' AND Parent IS NULL")
         or die("");
        
    $Messages = mysql_result($query_id,0,"Messages");
    
    $t->set_var( "forum_id", $Id);
    $t->set_var( "category_id", $category_id);
    $t->set_var( "link", $link);
    $t->set_var( "name", $Name);
    $t->set_var( "description", $Description);
    $t->set_var( "messages",$Messages);
 
    if ( ($i % 2) != 0)
        $t->set_var( "color", "#eeeeee");
    else
        $t->set_var( "color", "#bbbbbb");
    
    $t->parse("forums","elements",true);
    }
if ( count( $forums) == 0 )
    $t->set_var( "forums", "<tr><td colspan=\"3\"><b>Ingen tilgjengelige forum</td></tr></b>");

$t->set_var( "back-url", "main.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);

$t->pparse( "output", "category" );
?>
