<?
// 
// $Id: photographerlist.php,v 1.1 2001/06/12 14:32:33 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <12-Jun-2001 16:33:07 ce>
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

include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

include_once( "ezuser/classes/ezphotographer.php" );

if ( isset( $NewPhotographer ) )
{
    $photographer = new eZPhotographer( );
    $photographer->store();    
}

if ( isset( $DeletePhotographer ) )
{
    foreach ( $DeleteIDArray as $id )
    {
        eZPhotographer::delete( $id );
    }
}

if ( ( isset( $Store ) ) || ( isset ( $NewPhotographer ) ) || ( isset ( $DeletePhotographer ) ) )
{
    $i=0;
    foreach ( $IDArray as $id )
    {
        $photographer = new eZPhotographer( $id );
        $photographer->setEMail( $EMail[$i] );
        $photographer->setName( $Name[$i] );
        $photographer->store();

        $i++;
    }
}


$t = new eZTemplate( "ezuser/admin/" . $ini->read_var( "eZUserMain", "AdminTemplateDir" ),
                     "ezuser/admin/intl", $Language, "photographerlist.php" );

$locale = new eZLocale( $Language ); 

$t->set_file( "photographer_page_tpl", "photographerlist.tpl" );

$t->setAllStrings();

$t->set_block( "photographer_page_tpl", "photographer_list_tpl", "photographer_list" );
$t->set_block( "photographer_list_tpl", "photographer_item_tpl", "photographer_item" );

$t->set_var( "photographer_item", "" );

$photographer = new eZPhotographer( );

$photographerArray = $photographer->getAll();

if ( count ( $photographerArray ) > 0 )
{
    foreach ( $photographerArray as $photographer )
    {
        $t->set_var( "id", $photographer->id() );
        $t->set_var( "photographer_name", $photographer->name() );
        $t->set_var( "photographer_email", $photographer->email() );

        $t->parse( "photographer_item", "photographer_item_tpl", true );
    }
}

$t->parse( "photographer_list", "photographer_list_tpl" );

$t->pparse( "output", "photographer_page_tpl" );

?>
