<?php
//
// Created on: <22-May-2002 13:32:38 jhe>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

$user =& eZUser::currentUser();

if ( !$user )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /user/login" );
    exit();
}

switch ( $url_array[2] )
{
    case "":
    {
        $workspaceUser = eZUser::currentUser();
        include( "ezworkspace/user/workspace.php" );
    }
    break;

    case "user":
    {
        if ( is_numeric( $url_array[3] ) )
            $workspaceUser = new eZUser( $url_array[3] );
        else
            $workspaceUser = eZUser::getUser( $url_array[3] );

        if ( !( $workspaceUser->id() > 0 ) )
            $workspaceUser = eZUser::currentUser();

        include( "ezworkspace/user/workspace.php" );
    }
    break;
}

?>
