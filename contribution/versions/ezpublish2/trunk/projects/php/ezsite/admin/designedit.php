<?
// 
// $Id: designedit.php,v 1.1 2000/11/22 09:12:27 ce-cvs Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <20-Sep-2000 13:32:11 ce>
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

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZUserMain", "Language" );

$error = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

include_once( "classes/ezmail.php" );
include_once( "classes/ezlog.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "classes/ezimagefile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

require( "ezuser/admin/admincheck.php" );

if ( $Action == "update" )
{
    $iniUpdate = new INIFile( "site.ini" );

    $file = new eZImageFile();
    
    if ( $file->getUploadedFile( "logo" ) )
    {
        $oldImage = new eZImage( $ImageID );
                
        $image = new eZImage();
        $image->setName( $Name );
        $image->setCaption( $Caption );

        $image->setImage( $file );
        
        $image->store();

        $ImageID = $image->id();
    }
    else
    {
        $image = new eZImage( $ImageID );
        $image->setName( $Name );
        $image->setCaption( $Caption );
        $image->store();
    }
    
    $hex1R = decHex( $Color1_R );
    $hex1G = decHex( $Color1_G );
    $hex1B = decHex( $Color1_B );

    $hex2R = decHex( $Color2_R );
    $hex2G = decHex( $Color2_G );
    $hex2B = decHex( $Color2_B );

    $hex3R = decHex( $Color3_R );
    $hex3G = decHex( $Color3_G );
    $hex3B = decHex( $Color3_B );
    
    $color1 = ( $hex1R . $hex1G . $hex1B );
    $color2 = ( $hex2R . $hex2G . $hex2B );
    $color3 = ( $hex3R . $hex3G . $hex3B );

    if ( strlen( $color1 ) == 6 )
        $iniUpdate->set_var( "eZSiteMain", "color1", $color1 );
    if ( strlen( $color2 ) == 6 )
        $iniUpdate->set_var( "eZSiteMain", "color2", $color2 );
    if ( strlen( $color3 ) == 6 )
        $iniUpdate->set_var( "eZSiteMain", "color3", $color3 );

    if ( $ImageID != "" )
        $iniUpdate->set_var( "eZSiteMain", "image_id", $ImageID );
    
    $iniUpdate->save_data();
    
    Header( "Location: /site/design/edit/" );
    exit();
}

$t = new eZTemplate( "ezsite/admin/" . $ini->read_var( "eZSiteMain", "AdminTemplateDir" ),
 "ezsite/admin/" . "/intl", $Language, "designedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "design_edit" => "designedit.tpl"
     ) );

$t->set_block( "design_edit", "image_view_tpl", "image_view" );
$t->set_block( "design_edit", "image_insert_tpl", "image_insert" );

if ( $Action == "edit" )
{
    $iniEdit = new INIFile( "site.ini" );

    $color1_hex = $iniEdit->read_var( "eZSiteMain", "color1" );
    $color2_hex = $iniEdit->read_var( "eZSiteMain", "color2" );
    $color3_hex = $iniEdit->read_var( "eZSiteMain", "color3" );

    $image_id = $iniEdit->read_var( "eZSiteMain", "image_id" );

    if ( $image_id != "" )
    {
        $logoImage = new eZImage( $image_id );
        
        $variation = $logoImage->requestImageVariation( 150, 150 );
        
        $t->set_var( "image_src", "/" . $variation->imagePath() );
        $t->set_var( "image_id", $logoImage->id() );
        
        $t->set_var( "image_insert", "" );

        
        $t->parse( "image_view", "image_view_tpl" );
        
    }


    $t->set_var( "color1", $color1_hex );
    $t->set_var( "color2", $color2_hex );
    $t->set_var( "color3", $color3_hex );



    if ( ereg( "([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})", $color1_hex, $valueArray ) )
    {
            $color1_r_hex = ( $valueArray[1] );
            $color1_g_hex = ( $valueArray[2] );
            $color1_b_hex = ( $valueArray[3] );
    }

    if ( ereg( "([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})", $color2_hex, $valueArray ) )
    {
            $color2_r_hex = ( $valueArray[1] );
            $color2_g_hex = ( $valueArray[2] );
            $color2_b_hex = ( $valueArray[3] );
    }    
    
    if ( ereg( "([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})([0-9A-Fa-f]{2})", $color3_hex, $valueArray ) )
    {
            $color3_r_hex = ( $valueArray[1] );
            $color3_g_hex = ( $valueArray[2] );
            $color3_b_hex = ( $valueArray[3] );
    }    

    $color1_r = hexDec( $color1_r_hex );
    $color1_g = hexDec( $color1_g_hex );
    $color1_b = hexDec( $color1_b_hex );

    $color2_r = hexDec( $color2_r_hex );
    $color2_g = hexDec( $color2_g_hex );
    $color2_b = hexDec( $color2_b_hex );

    $color3_r = hexDec( $color3_r_hex );
    $color3_g = hexDec( $color3_g_hex );
    $color3_b = hexDec( $color3_b_hex );

    $t->set_var( "color1_r", $color1_r );
    $t->set_var( "color1_g", $color1_g );
    $t->set_var( "color1_b", $color1_b );

    $t->set_var( "color2_r", $color2_r );
    $t->set_var( "color2_g", $color2_g );
    $t->set_var( "color2_b", $color2_b );

    $t->set_var( "color3_r", $color3_r );
    $t->set_var( "color3_g", $color3_g );
    $t->set_var( "color3_b", $color3_b );
}


$t->pparse( "output", "design_edit" );

?>
