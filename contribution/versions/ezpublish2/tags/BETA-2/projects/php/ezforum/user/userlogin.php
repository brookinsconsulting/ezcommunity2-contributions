<?
// 
// $Id: userlogin.php,v 1.3 2000/10/26 13:23:25 ce-cvs Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <14-Oct-2000 15:41:17 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
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
        Header( "Location: /forum/reply/reply/$MessageID/" );
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
