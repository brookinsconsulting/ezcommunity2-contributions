<?php
// 
// $Id: export.php,v 1.2 2001/09/25 13:48:55 bf Exp $
//
// Created on: <23-Sep-2001 16:55:39 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

include_once( "classes/ezhttptool.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );


$Language = $ini->read_var( "eZRfpMain", "Language" );
$TemplateDir = $ini->read_var( "eZRfpMain", "TemplateDir" );

$t = new eZTemplate( "ezrfp/user/" . $TemplateDir,                     
                     "ezrfp/user/intl/", $Language, "rfpview.php" );


$t->set_file( "rfp_view_tex_tpl", "tex.tpl"  );

$t->setAllStrings();


// xxxx


// get rfplist for array forech loop

$RfpID = 590;

$rfp = new eZRfp( );

if ( $rfp->get( $RfpID ) )
{
    if ( $rfp->isPublished() )
    {
        // published rfp.
    }
    else
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }

    $template = "tex";
    $renderer = new eZRfpRenderer( $rfp, $template );

    $rfpContents = $renderer->renderPage( -1 );
    
    $t->set_var( "rfp_name", $rfp->name() );
    $t->set_var( "author_name", $rfp->authorText() );

    $t->set_var( "rfp_intro", $rfpContents[0] );
    $t->set_var( "rfp_body", $rfpContents[1] );

}

print( "<div>Export Single Only</div>" );

print( "<pre>" );
$doc =& $t->parse( "output", "rfp_view_tex_tpl" );

//$outputFile = "ezrfp/cache/rfp.tex";
$outputFile = "ezrfp/cache/rfp.txt";

$fp = eZFile::fopen( $outputFile, "w+");

fwrite ( $fp, $doc );
fclose( $fp );

// convert to dvi
//print( system( "cd ezrfp/cache/ && latex -interaction=batchmode $outputFile && cd .." ) );

print( htmlspecialchars( $doc ) );
print( "</pre>" );

?>
