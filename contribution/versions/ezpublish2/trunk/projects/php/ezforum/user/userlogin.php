<?
// 
// $Id: userlogin.php,v 1.1 2000/10/20 10:27:02 ce-cvs Exp $
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

print( $Action );
print( $ForumID );
if ( eZUser::currentUser() )
{
    
    if ( $Action == "new" )
    {
        Header( "Location: /forum/messageedit/new/$ForumID/" );
    }

    if ( $Action == "reply" )
    {
        Header( "Location: /forum/reply//reply/$MessageID/" );
    }    
}
else
{
    $t = new eZTemplate( "ezforum/user/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                         "ezforum/user/intl/", $Language, "userlogin.php" );

    $t->setAllStrings();

    $t->set_file( array(        
        "user_login_tpl" => "userlogin.tpl"
        ) );

    if ( $Action == "new" )
    {
        $t->set_var( "redirect_url", "/forum/messageedit/new/$ForumID/" );
    }

    if ( $Action == "reply" )
    {
        $t->set_var( "redirect_url", "/forum/reply/reply/$MessageID/" );
    }
    
    $t->pparse( "output", "user_login_tpl" );
}

?>
