<?php
//
// $Id: viewresult.php,v 1.3 2002/01/28 12:18:07 jhe Exp $
//
// Created on: <10-Jan-2002 13:09:28 jhe>
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
include_once( "ezform/classes/ezformtable.php" );
include_once( "ezform/classes/ezformrenderer.php" );

$Language = $ini->read_var( "eZFormMain", "Language" );

$t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                     "ezform/user/intl/", $Language, "viewresult.php" );

$t->setAllStrings();

$t->set_file( "view_result_tpl", "viewresult.tpl" );

$t->set_var( "form_id", $FormID );
$t->set_var( "result_id", $ResultID );

$form = new eZForm( $FormID );
$renderer =& new eZFormRenderer( $form );

$t->set_var( "form_name", $form->name() );

$output = $renderer->renderResult( $ResultID, true, false, false, false, false, $count );

$t->set_var( "form", $output );

$t->pparse( "output", "view_result_tpl" );

?>
