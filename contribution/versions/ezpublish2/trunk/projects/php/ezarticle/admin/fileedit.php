<?
// 
// $Id: fileedit.php,v 1.1 2000/12/23 14:25:35 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Dec-2000 18:01:48 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

include_once( "classes/ezfile.php" );

include_once( "ezfilemanager/classes/ezvirtualfile.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

if ( $Action == "Insert" )
{
    $file = new eZFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $article = new eZArticle( $ArticleID );

        $uploadedFile = new eZVirtualFile();
        $uploadedFile->setName( $Name );
        $uploadedFile->setDescription( $Description );

        $uploadedFile->setFile( $file );
        
        $uploadedFile->store();

        $article->addFile( $uploadedFile );

        eZLog::writeNotice( "File added to article $ArticleID  from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    header( "Location: /article/articleedit/filelist/" . $ArticleID . "/" );
    exit();
}

if ( $Action == "Update" )
{
//      $file = new eZFilerFile();
    
//      if ( $file->getUploadedFile( "userfile" ) )
//      {
//          $article = new eZArticle( $ArticleID );

//          $oldFiler = new eZFiler( $FilerID );
//          $article->deleteFiler( $oldFiler );
        
//          $filer = new eZFiler();
//          $filer->setName( $Name );
//          $filer->setCaption( $Caption );

//          $filer->setFiler( $file );
        
//          $filer->store();
        
//          $article->addFiler( $filer );
//      }
//      else
//      {
//          $filer = new eZFiler( $FilerID );
//          $filer->setName( $Name );
//          $filer->setCaption( $Caption );
//          $filer->store();
//      }
    
    header( "Location: /article/articleedit/filelist/" . $ArticleID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $article = new eZArticle( $ArticleID );
    $file = new eZVirtualFile( $FileID );
        
    $article->deleteFiler( $filer );
    
    header( "Location: /article/articleedit/filelist/" . $ArticleID . "/" );
    exit();    
}

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "fileedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "file_edit_page" => "fileedit.tpl",
    ) );


//default values
$t->set_var( "name_value", "" );
$t->set_var( "description_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );
$t->set_var( "file", "" );

if ( $Action == "Edit" )
{
    $article = new eZArticle( $ArticleID );
    $file = new eZVirtualFile( $FileID );

    $t->set_var( "article_name", $article->name() );

    $t->set_var( "file_id", $file->id() );
    $t->set_var( "name_value", $file->name() );
    $t->set_var( "description_value", $file->description() );
    $t->set_var( "action_value", "Update" );
}

$article = new eZArticle( $ArticleID );
    
$t->set_var( "article_name", $article->name() );
$t->set_var( "article_id", $article->id() );



$t->pparse( "output", "file_edit_page" );

?>
