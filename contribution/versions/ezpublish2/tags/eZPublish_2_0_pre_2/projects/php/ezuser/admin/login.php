<?
// 
// $Id: login.php,v 1.17 2001/03/08 13:54:50 jb Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "classes/ezlog.php" );
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );


// Template
$t = new eZTemplate( "ezuser/admin/" .  $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/" . "/intl", $Language, "login.php" );
$t->setAllStrings();

$t->set_file( array(
    "login_tpl" => "login.tpl"
    ) );

$t->set_block( "login_tpl", "error_message_tpl", "error_message" );

if ( $Action == "login" )
{
    $user = new eZUser();
    $user = $user->validateUser( $Username, $Password );

    if ( ( $user )  && eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
    {
        eZLog::writeNotice( "Admin login: $Username from IP: $REMOTE_ADDR" );

        eZUser::loginUser( $user );

        if ( !isset( $RefererURL ) )
            $RefererURL = "/";
        eZHTTPTool::header( "Location: $RefererURL" );
        exit();
    }
    else
    {
        eZLog::writeWarning( "Bad admin  login: $Username from IP: $REMOTE_ADDR" );
        
        $error = true;
    }
}

if ( !isset( $RefererURL ) )
    $RefererURL = $REQUEST_URI;

$t->set_var( "referer_url", $RefererURL );

if ( $Action == "logout" )
{
    eZUser::logout();
    eZHTTPTool::header( "Location: /" );
    exit();
}

if ( $error )
{
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}


$t->set_var( "action_value", "login" );
$t->pparse( "output", "login_tpl" );

?>
