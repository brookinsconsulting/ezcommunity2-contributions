<?
// 
// $Id: login.php,v 1.9 2000/11/03 10:39:35 ce-cvs Exp $
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
include_once( "classes/ezlog.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezsession/classes/ezsession.php" );

if ( isSet( $Forgot ) )
{
    Header( "Location: /user/forgot/" );
    exit();
}

if ( isSet( $Register ) )
{
    print( "wg!" );
    Header( "Location: /user/userwithaddress/new/" );
    exit();
}

// Template
$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     "ezuser/user/intl", $Language, "login.php" );
$t->setAllStrings();

$t->set_file( array(
    "login" => "login.tpl"
    ) );

if ( $Action == "login" )
{
    $user = new eZUser();
    $user = $user->validateUser( $Username, $Password );

    if ( $user )
    {
        eZLog::writeNotice( "User login: $Username from IP: $REMOTE_ADDR" );
        
        eZUser::loginUser( $user );
        if ( isSet( $RedirectURL ) )
        {
            $stringTmp = split( "/", $RedirectURL );
            if ( $stringTmp[2] == "norights" )
            {
                Header( "Location: /" );
            }
            else
            {
                Header( "Location: $RedirectURL" );
            }
        }
        else
        {
            Header( "Location: /" );
        }
        exit();
    }
    else
    {
        eZLog::writeNotice( "Bad login: $Username from IP: $REMOTE_ADDR" );
        
        Header( "Location: /user/norights/?Error=WrongPassword" );
        exit();
    }
}

if ( $Action == "logout" )
{
    eZUser::logout();
    Header( "Location: /" );
}

$t->set_var( "action_value", "login" );
$t->pparse( "output", "login" );

?>
