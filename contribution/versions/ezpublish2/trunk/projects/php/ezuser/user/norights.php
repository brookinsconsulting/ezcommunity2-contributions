<?
//
// $Id: norights.php,v 1.6 2001/03/16 11:52:10 sascha Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:56:23 ce>
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

$ini =& INIFile::globalINI();
$DOC_ROOT = $ini->read_var( "eZUserMain", "DocumentRoot" );
$Language = $ini->read_var( "eZUserMain", "Language" );
$errorIni = new INIFIle( "ezuser/user/intl/" . $Language . "/norights.php.ini", false );

include_once( "classes/ezdb.php" );
include_once( "classes/eztemplate.php" );

$t = new eZTemplate( "ezuser/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
"ezuser/user/" . "/intl", $Language, "norights.php" );
$t->setAllStrings();

switch ( $Error )
{
    case "WrongPassword":
    {
        $errorMsg = $errorIni->read_var( "strings", "wrong_password" );
    }
    break;

    default:
    {
        $errorMsg = $errorIni->read_var( "strings", "default_error" );
    }
    break;
}

$t->set_var( "redirect_url", $RedirectURL );
$t->set_var( "error_msg", $errorMsg );
$t->set_file( array( "norights" => "norights.tpl"
                     ) );

$t->pparse( "output", "norights" );
?>
