<?
/*!
    $Id: category.php,v 1.1 2000/07/14 12:55:45 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:44:05 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
    include( 'ezforum/dbsettings.php' );
    include( 'template.inc' );
    include( "$DOCROOT/classes/ezforumforum.php" );

    $forum = new eZforumForum;

    openDB();

    $t = new Template(".");
    
    $t->set_file( array("category" => "$DOCROOT/templates/category.tpl",
                        "elements" => "$DOCROOT/templates/category-elements.tpl") );

    $t->set_var( "docroot", $DOCROOT);
            
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
    $t->pparse("output","category");
?>
