<?
// 
// $Id: imageedit.php,v 1.3 2000/10/21 11:08:58 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 10:32:36 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

// include_once( "classes/ezfile.php" );
include_once( "classes/ezimagefile.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZArticleMain", "Language" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

print( "ArtikkelID: " . $ArticleID . " " . $Action  );

if ( $Action == "Insert" )
{
    $file = new eZImageFile();

    if ( $file->getUploadedFile( "userfile" ) )
    { 
        $article = new eZArticle( $ArticleID );
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
        
        $article->addImage( $image );

        eZLog::writeNotice( "Picture added to article: $ArticleID  from IP: $REMOTE_ADDR" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }

    header( "Location: /article/articleedit/imagelist/" . $ArticleID . "/" );
    exit();
}

if ( $Action == "Update" )
{
    $file = new eZImageFile();
    
    if ( $file->getUploadedFile( "userfile" ) )
    {
        $article = new eZArticle( $ArticleID );

        $oldImage = new eZImage( $ImageID );
        $article->deleteImage( $oldImage );
        
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();
        
        $article->addImage( $image );
    }
    else
    {
        $image = new eZImage( $ImageID );
        $image->setName( $Name );
        $image->setCaption( $Caption );
        $image->store();
    }
    
    header( "Location: /article/articleedit/imagelist/" . $ArticleID . "/" );
    exit();
}


if ( $Action == "Delete" )
{
    $article = new eZArticle( $ArticleID );
    $image = new eZImage( $ImageID );
        
    $article->deleteImage( $image );
    
    header( "Location: /article/articleedit/imagelist/" . $ArticleID . "/" );
}

// store the image definition
if ( $Action == "StoreDef" )
{
    $article = new eZArticle( $ArticleID );

    $thumbnail = new eZImage( $ThumbnailImageID );
    $main = new eZImage( $MainImageID );

    $article->setThumbnailImage( $thumbnail );

    if ( isset( $NewImage ) )
    {
        print( "new image" );
        header( "Location: /article/articleedit/imageedit/new/$ArticleID/" );
    }
//  <form action="/article/articleedit/imageedit/new/{article_id}/" method="post">
     
    header( "Location: /article/articleedit/edit/" . $ArticleID . "/" );
}

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "caption_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );

if ( $Action == "Edit" )
{
    $article = new eZArticle( $ArticleID );
    $image = new eZImage( $ImageID );

    $t->set_var( "image_id", $image->id() );
    $t->set_var( "name_value", $image->name() );
    $t->set_var( "caption_value", $image->caption() );
    $t->set_var( "action_value", "Update" );
}

$article = new eZArticle( $ArticleID );
    
$t->set_var( "article_name", $article->name() );
$t->set_var( "article_id", $article->id() );



$t->pparse( "output", "image_edit_page" );

?>
