<?php
//
// $Id: formedit.php,v 1.3 2002/01/28 19:28:22 jhe Exp $
//
// Created on: <15-Jan-2002 11:30:20 jhe>
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

include_once( "classes/eztemplate.php");
include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformrenderer.php" );

$user =& eZUser::currentUser();

if ( $user && $user->hasRootAccess() )
{
    if ( $Action == "delete" )
    {
        $form = new eZForm( $FormID );
        $form->deleteResults( $DeleteArrayID );

        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /form/results/$FormID/" );
        exit();
    }
    else if ( $Action == "edit" )
    {
        $Language = $ini->read_var( "eZFormMain", "Language" );

        $t = new eZTemplate( "ezform/user/" . $ini->read_var( "eZFormMain", "TemplateDir" ),
                             "ezform/user/intl/", $Language, "formedit.php" );
        
        $t->setAllStrings();
        
        $t->set_file( "form_edit_tpl", "formedit.tpl" );

        $form = new eZForm( $FormID );
        $render = new eZFormRenderer( $form );
        $output = $render->renderResult( $ResultID, false, false, false, false, false, $count );
        $t->set_var( "form", $output );
        $t->pparse( "output", "form_edit_tpl" );
    }
    else if ( $Action == "store" )
    {
        $form = new eZForm( $FormID );
        $render = new eZFormRenderer( $form );
        $pages = $form->pageList( false );

        foreach ( $pages as $page )
        {
            $render->storePage( $page, $ResultID );
        }

        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /form/results/$FormID/" );
        exit();
    }
}

?>
