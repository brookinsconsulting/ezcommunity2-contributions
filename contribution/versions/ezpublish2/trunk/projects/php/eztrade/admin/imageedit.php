<?
// 
// $Id: imageedit.php,v 1.1 2000/09/21 12:42:24 bf-cvs Exp $
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
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

// include_once( "classes/ezfile.php" );
include_once( "classes/ezimagefile.php" );

include_once( "ezimagecatalogue/ezimage.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZTradeMain", "DocumentRoot" );

include_once( $DOC_ROOT . "/classes/ezproductcategory.php" );
include_once( $DOC_ROOT . "/classes/ezproduct.php" );
include_once( $DOC_ROOT . "/classes/ezoption.php" );

if ( $Action == "Insert" )
{
    $file = new eZImageFile();

    if ( $file->getFile( $HTTP_POST_FILES['userfile'] ) )
    { 
        $file->scaleCopy( "tmp/" . $file->name(), 320, 200 );
        
        
        print( "\n<img src=\"/tmp/" . $file->name() . "\" border=\"2\" alt=\"image\" nosave />\n" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }
}

$t = new eZTemplate( $DOC_ROOT . "/admin/" . $ini->read_var( "eZTradeMain", "TemplateDir" ) . "/imageedit/",
                     $DOC_ROOT . "/admin/intl/", $Language, "imageedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "image_edit_page" => "imageedit.tpl",
    ) );

//default values
$t->set_var( "name_value", "" );
$t->set_var( "caption_value", "" );
$t->set_var( "action_value", "Insert" );
$t->set_var( "option_id", "" );

$product = new eZProduct( $ProductID );
    
$t->set_var( "product_name", $product->name() );


$t->pparse( "output", "image_edit_page" );

?>
