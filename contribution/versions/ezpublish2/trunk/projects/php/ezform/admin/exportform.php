<?php
//
// $Id: exportform.php,v 1.1 2002/01/07 17:21:23 jhe Exp $
//
// Created on: <07-Jan-2002 12:54:53 jhe>
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

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );
include_once( "ezform/classes/ezform.php" );
include_once( "ezform/classes/ezformelement.php" );

ob_end_clean();

$form = new eZForm( $FormID );

$filename = $form->name() . ".xls";

header( "Cache-Control:" );
header( "Content-disposition: attachment; filename=$filename" );
header( "Content-Type: application/vnd.ms-excel" );

$results = eZFormElement::getAllResults();

$elements = $form->formElements();

if ( count( $results ) > 0 )
{
    foreach ( $elements as $el )
    {
        print $el->name() . "\t";
    }
    print "\r\n";
    
    foreach ( $results as $res )
    {
        foreach ( $elements as $el )
        {
            $element = new eZFormElement( $el );
            print $el->result( $res ) . "\t";
        }
        print "\r\n";
    }
}

exit();

?>
