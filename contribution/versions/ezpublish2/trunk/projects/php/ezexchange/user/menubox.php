<?
// 
// $Id: menubox.php,v 1.1 2001/01/30 20:12:10 jb Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <23-Oct-2000 17:53:46 bf>
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

$user = eZUser::currentUser();
if ( $user )
{
    include_once( "classes/INIFile.php" );
    $ini = new INIFile( "site.ini" );

    $Language = $ini->read_var( "eZExchangeMain", "Language" );

    include_once( "classes/eztemplate.php" );

    $t = new eZTemplate( "ezexchange/user/" . $ini->read_var( "eZExchangeMain", "TemplateDir" ),
                         "ezexchange/user/intl", $Language, "menubox.php" );

    $t->setAllStrings();

    $t->set_file( array(
        "menu_box_tpl" => "menubox.tpl"
        ) );

    $t->pparse( "output", "menu_box_tpl" );
}   

?>
