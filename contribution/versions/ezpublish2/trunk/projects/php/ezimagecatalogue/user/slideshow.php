<?php

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezimagecatalogue/classes/ezslideshow.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZImageCatalogueMain", "Language" );

$t = new eZTemplate( "ezimagecatalogue/user/" . $ini->read_var( "eZImageCatalogueMain", "TemplateDir" ),
                     "ezimagecatalogue/user/intl/", $Language, "slideshow.php" );

$t->setAllStrings();

$t->set_file( "slideshow_tpl", "slideshow.tpl" );

$t->set_block( "slideshow_tpl", "image_tpl", "image" );
$t->set_block( "slideshow_tpl", "previous_tpl", "previous" );
$t->set_block( "slideshow_tpl", "next_tpl", "next" );

if ( $Position == "" )
    $Position = 0;

$slideshow = new eZSlideshow( $CategoryID, eZUser::currentUser(), $Position );
$image = $slideshow->image();

if ( !$image )
{
    $t->set_var( "image", "" );
}
else
{
    $variation =& $image->requestImageVariation( $ini->read_var( "eZImageCatalogueMain", "ImageViewWidth" ),
    $ini->read_var( "eZImageCatalogueMain", "ImageViewHeight" ) );

    $t->set_var( "image_uri", "/" . $variation->imagePath() );
    $t->set_var( "image_width", $variation->width() );
    $t->set_var( "image_height", $variation->height() );
    
    $t->parse( "image", "image_tpl" );
}

$current = $slideshow->currentPosition();
$t->set_var( "category", $CategoryID );

if ( $current > 0 )
{
    $t->set_var( "prev_image", $current - 1 );
    $t->parse( "previous", "previous_tpl" );
}
else
{
    $t->set_var( "previous", "" );
} 

if ( $current < ( $slideshow->size() - 1 ) )
{
    $t->set_var( "next_image", $current + 1 );
    $t->parse( "next", "next_tpl" );
}
else
{
    $t->set_var( "next", "" );
}

$t->pparse( "output", "slideshow_tpl" );

?>
