<?
/*!
    $Id: categoryedit.php,v 1.2 2000/10/17 11:40:49 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:41:35 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/ezdb.php" );
include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforumcategory.php" );

$cat = new eZForumCategory();

$t->set_var( "docroot", $DOC_ROOT );

if ( $Action == "insert" )
{
    $cat = new eZForumCategory();
    $cat->setName( $Name );
    $cat->setDescription( $Description );
   
    $cat->store();
    Header( "Location: /forum/categorylist/" );
}
  
if ( $Action == "delete" )
{
    $cat = new eZForumCategory();
    $cat->get( $CategoryID );
    $cat->delete( );
    Header( "Location: /forum/categorylist/" );
}


if ( $Action == "update" )
{
    $cat = new eZForumCategory();
    $cat->get( $CategoryID );
    $cat->setName( $Name );
    $cat->setDescription( $Description );
    
    $cat->store();

    Header( "Location: /forum/categorylist/" );
}

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
"ezforum/admin/" . "/intl", $Language, "categoryedit.php" );
$t->setAllStrings();

$t->set_file( array( "category_page" => "categoryedit.tpl"
                    ) );

$t->set_block( "category_page", "category_edit_tpl", "category_edit" );

$t->set_var( "action_value", "insert" );
$t->set_var( "category_name", "" );
$t->set_var( "category_description", "" );
$t->set_var( "action_value", "update" );


$ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/categoryedit.php.ini", false );
$headline =  $ini->read_var( "strings", "head_line_insert" );

$t->set_var( "category_id", $CategoryID );

if ( $Action == "edit" )
{
    die();
    $cat = new eZForumCategory();
    $cat->get( $CategoryID );    
    $t->set_var( "category_name", $cat->name() );
    $t->set_var( "category_description", $cat->description() );
    $t->set_var( "action_value", "update" );

    $ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/categoryedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_edit" );

}

$t->set_var( "headline", $headline );

$t->pparse( "output", "category_page" );
?>
