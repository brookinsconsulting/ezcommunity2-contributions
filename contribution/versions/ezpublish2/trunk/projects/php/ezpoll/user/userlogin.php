<?
// 
// $Id: userlogin.php,v 1.1 2000/10/28 12:08:17 ce-cvs Exp $
//
// B�rd Farstad <bf@ez.no>
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

$Language = $ini->read_var( "eZPollMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );

if ( eZUser::currentUser() )
{
    Header( "Location: /poll/vote/$VoteID/" );
}
else
{
    $t = new eZTemplate( "ezpoll/user/" . $ini->read_var( "eZPollMain", "TemplateDir" ),
                         "ezpoll/user/intl/", $Language, "userlogin.php" );

    $t->setAllStrings();

    $t->set_file( array(        
        "user_login_tpl" => "userlogin.tpl"
        ) );

    $t->set_var( "redirect_url", "/poll/vote/$VoteID/" );
    
    $t->pparse( "output", "user_login_tpl" );
}

?>
