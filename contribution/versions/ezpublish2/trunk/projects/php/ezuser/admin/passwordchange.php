<?
// 
// $Id: passwordchange.php,v 1.5 2000/10/26 13:13:46 ce-cvs Exp $
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

require( "ezuser/admin/admincheck.php" );

// Template
$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                     $DOC_ROOT . "/admin/" . "/intl", $Language, "passwordchange.php" );
$t->setAllStrings();

$t->set_file( array(
    "change" => "passwordchange.tpl"
    ) );

$user = eZUser::currentUser();
if ( !$user ) 
{
    print( "Du må logge inn" );
    Header( "Location: /user/login/" );
    exit();
}

if ( $Action == "update" )
{
    $checkuser = $user->validateUser( $user->login(), $OldPassword );
    
    if ( !$checkuser )
    {
        print( "Gammelt passord var feil." );
        
    }
    else if ( $NewPassword != $VerifyPassword )
    {
        print( "Passordene machet ikke." );

    }
    else if ( $OldPassord == $NewPassord )
    {
        $user->setPassword( $NewPassword );
        $user->store();
        print( "Passordet ble oppgradert!" );

    }
}

if ( $Action == "change" )
{
    
    
}

$t->set_var( "first_name", $user->firstName() );
$t->set_var( "last_name", $user->lastName() );

$t->set_var( "action_value", "update" );
$t->pparse( "output", "change" );

?>
