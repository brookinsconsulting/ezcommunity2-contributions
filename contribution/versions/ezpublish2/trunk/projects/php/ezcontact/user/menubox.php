<?
// 
// $Id: menubox.php,v 1.8 2001/02/19 13:43:01 jb Exp $
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

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

$user = eZUser::currentUser();
if ( get_class( $user ) == "ezuser" and
     ( eZPermission::checkPermission( $user, "eZContact", "CompanyList" ) or 
       eZPermission::checkPermission( $user, "eZContact", "PersonList" ) or
       eZPermission::checkPermission( $user, "eZContact", "Consultation" ) ) )
{
    include_once( "classes/INIFile.php" );
    $ini = new INIFile( "site.ini" );

    $Language = $ini->read_var( "eZContactMain", "Language" );

    include_once( "classes/eztemplate.php" );

    $t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                         "ezcontact/user/intl", $Language, "menubox.php" );
    $t->setAllStrings();
    $t->set_file( "menu_box_tpl", "menubox.tpl" );
    $t->set_block( "menu_box_tpl", "company_item_tpl", "company_item" );
    $t->set_block( "menu_box_tpl", "person_item_tpl", "person_item" );
    $t->set_block( "menu_box_tpl", "consultation_item_tpl", "consultation_item" );

    $t->set_var( "company_item", "" );
    $t->set_var( "person_item", "" );
    $t->set_var( "consultation_item", "" );
    if ( eZPermission::checkPermission( $user, "eZContact", "CompanyList" ) )
        $t->parse( "company_item", "company_item_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "PersonList" ) )
        $t->parse( "person_item", "person_item_tpl" );
    if ( eZPermission::checkPermission( $user, "eZContact", "Consultation" ) )
        $t->parse( "consultation_item", "consultation_item_tpl" );

    $t->pparse( "output", "menu_box_tpl" );
}   

?>
