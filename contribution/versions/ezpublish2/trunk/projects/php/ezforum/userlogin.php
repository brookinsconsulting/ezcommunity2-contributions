<?
// 
// $Id: userlogin.php,v 1.1 2000/10/15 12:38:27 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <14-Oct-2000 15:41:17 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );

print( $ForumID );
if ( eZUser::currentUser() )
{
    if ( $Action == "NewPost" )
    {
        Header( "Location: /forum/newpost/$ForumID/" );
    }

    if ( $Action == "Reply" )
    {
        Header( "Location: /forum/reply/$MessageID/" );
    }    
}
else
{
    $t = new eZTemplate( "ezforum/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                         "ezforum/intl/", $Language, "userlogin.php" );

    $t->setAllStrings();

    $t->set_file( array(        
        "user_login_tpl" => "userlogin.tpl"
        ) );

    if ( $Action == "NewPost" )
    {
        $t->set_var( "redirect_url", "/forum/newpost/$ForumID/" );
    }

    if ( $Action == "Reply" )
    {
        $t->set_var( "redirect_url", "/forum/reply/$MessageID/" );
    }
    
    $t->pparse( "output", "user_login_tpl" );
}

?>
