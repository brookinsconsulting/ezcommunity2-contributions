<?
// 
// $Id: cron.php,v 1.1 2001/06/08 12:25:47 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <08-Jun-2001 13:16:33 ce>
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

include_once( "ezarticle/classes/ezarticle.php" );

$article = new eZArticle();
$articleValidArray =& $article->getAllValid();
$articleUnValid =& $article->getAllUnValid();

if ( count ( $articleValidArray ) > 0 )
{
    foreach ( $articleValidArray as $article )
    {
        $article->setIsPublished( true );
        $article->store();
        print( "Publishing article: " . $article->name() . "\n" );
    }
}

if ( count ( $articleUnValid ) > 0 )
{
    foreach( $articleUnValid as $article )
    {
        $article->setIsPublished( false );
        $article->store();
        print( "UnPublishing article: " . $article->name() . "\n" );
    }
}

?>
