<?php
//
// $Id: formlist.php,v 1.1 2002/01/11 09:13:59 jhe Exp $
//
// Created on: <10-Jan-2002 14:35:46 jhe>
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
include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformrenderer.php" );

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "formlist.php" );

$t->setAllStrings();

$t->set_file( "form_list_page_tpl", "formlist.tpl" );

$t->set_block( "form_list_page_tpl", "no_forms_item_tpl", "no_forms_item" );
$t->set_block( "form_list_page_tpl", "form_list_tpl", "form_list" );
$t->set_block( "form_list_tpl", "form_item_tpl", "form_item" );

$t->set_var( "form_item", "" );
$t->set_var( "form_list", "" );
$t->set_var( "no_forms_item", "" );

$formlist = eZForm::getAll( 0, false );

if ( count( $formlist ) > 0 )
{
    foreach ( $formlist as $form )
    {
        $t->set_var( "form_id", $form->id() );
        $t->set_var( "form_name", $form->name() );
        $t->set_var( "form_receiver", $form->receiver() );

        $t->parse( "form_item", "form_item_tpl", true );
    }
    $t->parse( "form_list", "form_list_tpl" );
}
else
{
    $t->parse( "no_forms_item", "no_forms_item_tpl" );
}

$t->pparse( "output", "form_list_page_tpl" );


?>
