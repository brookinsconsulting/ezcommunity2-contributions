<?
// 
// $Id: userbox.php,v 1.5 2000/10/28 11:54:14 ce-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
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

$Language = $ini->read_var( "eZUserMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZUserMain", "DocumentRoot" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );


$user = eZUser::currentUser();
if ( !$user ) 
{
    $t = new eZTemplate( "ezuser/user/" .  $ini->read_var( "eZUserMain", "TemplateDir" ),
    "ezuser/user/intl", $Language, "userbox.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "login" => "login.tpl"
        ) );

    $t->set_var( "redirect_url", $REQUEST_URI );
   
    $t->set_var( "action_value", "login" );
    $t->pparse( "output", "login" );
    
}
else
{
    $t = new eZTemplate( "ezuser/user/" .  $ini->read_var( "eZUserMain", "TemplateDir" ),
    "ezuser/user/intl", $Language, "userbox.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "userbox" => "userbox.tpl"
        ) );

    $t->set_var( "first_name", $user->firstName() );
    $t->set_var( "last_name", $user->lastName() );
    $t->set_var( "user_id", $user->id() );
    $t->set_var( "style", $SiteStyle );

    $t->pparse( "output", "userbox" );
} 

?>
