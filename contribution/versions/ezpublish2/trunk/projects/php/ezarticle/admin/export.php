<?php
// 
// $Id: export.php,v 1.4 2001/11/07 16:36:08 bf Exp $
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

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );


$Language = $ini->read_var( "eZArticleMain", "Language" ); 
$TemplateDir = $ini->read_var( "eZArticleMain", "TemplateDir" );

$t = new eZTemplate( "ezarticle/user/" . $TemplateDir,                     
                     "ezarticle/user/intl/", $Language, "articleview.php" );


$t->set_file( "article_view_tex_tpl", "tex.tpl"  );

$t->setAllStrings();

$ArticleID = 20;
$ArticleIDArray = array( 2, 1, 14, 16 );
//$ArticleIDArray = array( 2, 14, 16, 3, 19, 20, 45, 54, 52, 49, 86, 87, 88, 89, 90, 91, 92 );

$article = new eZArticle( );


foreach ( $ArticleIDArray as $ArticleID )
{
    if ( $article->get( $ArticleID ) )
    {
        print( "<b>Convering article:</b> $ArticleID" . $article->name() . " <br />" );
        if ( $article->isPublished() )
        {
            // published article.
        }
        else
        {
            eZHTTPTool::header( "Location: /error/404" );
            exit();
        }

        // convert images to eps

        $images =& $article->images();

        foreach ( $images as $image )
        {
            $image = $image["Image"];
            $fileName = $image->fileName();
        
            print( system( "convert ezimagecatalogue/catalogue/$fileName ezimagecatalogue/catalogue/" . $fileName . ".eps  " ) );

        
            print( $image->fileName() . "<br>" );
        }
    
        $template = "tex";
        $renderer = new eZArticleRenderer( $article, $template );

        $articleContents = $renderer->renderPage( -1 );
    
        $t->set_var( "article_name", $article->name() );
        $t->set_var( "author_name", $article->authorText() );

        $t->set_var( "article_intro", $articleContents[0] );
        $t->set_var( "article_body", $articleContents[1] );

        $doc .= "\chapter{" . $article->name() . "}";

        $doc .= $t->parse( "output", "article_view_tex_tpl" );
    }    
}

$doc = "\documentclass[dvips,12pt]{book}
\usepackage[dvips]{graphics}
\usepackage[T1]{fontenc}
\usepackage{a4,graphics,palatino,fancyhdr}

% header
\lhead{}
\chead{}
\\rhead{Copyright 2001 eZ systems as}
\lfoot{eZ publish user manual}
\cfoot{}
\\rfoot{\\thepage}

\addtolength{\evensidemargin}{-1cm}
\addtolength{\oddsidemargin}{-1cm}
\addtolength{\\textwidth}{2cm}

\begin{document}

\\title{ eZ article user manual}
\author{ eZ systems as}
\maketitle
% \\thispagestyle{empty}

% \pagestyle{empty}
\\tableofcontents
\listoffigures
\pagestyle{fancy}

\\renewcommand{\familydefault}{\sfdefault}


"
. $doc .
"\end{document}";

$doc = str_replace( "#", "\#", $doc );
$doc = str_replace( "&", "\&", $doc );
print( "<pre>" );

$outputFile = "ezarticle/cache/article.tex";

$fp = eZFile::fopen( $outputFile, "w+");

fwrite ( $fp, $doc );
fclose( $fp );

// convert to dvi
//print( system( "cd ezarticle/cache/ && latex -interaction=batchmode $outputFile && cd .." ) );

//print( htmlspecialchars( $doc ) );
print( "</pre>" );

?>
