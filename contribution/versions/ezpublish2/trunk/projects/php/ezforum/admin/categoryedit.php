<?
/*!
    $Id: categoryedit.php,v 1.4 2000/10/20 15:28:43 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:41:35 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );
$error = new INIFIle( "ezforum/admin/intl/" . $Language . "/categoryedit.php.ini", false );

include_once( "classes/ezdb.php" );
include_once( "classes/eztemplate.php" );

include_once( "ezforum/classes/ezforumcategory.php" );

require( "ezuser/admin/admincheck.php" );

$cat = new eZForumCategory();

$t->set_var( "docroot", $DOC_ROOT );

if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "CategoryAdd" ) )
    {
        if ( $Name != "" &&
        $Description != "" )
        {
            $cat = new eZForumCategory();
            $cat->setName( $Name );
            $cat->setDescription( $Description );
   
            $cat->store();
            Header( "Location: /forum/categorylist/" );
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        Header( "Location: /forum/norights" );
        exit();
    }
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "CategoryDelete" ) )
    {
        if ( $CategoryID != "" )
        {
            $cat = new eZForumCategory();
            $cat->get( $CategoryID );
            $cat->delete( );
            Header( "Location: /forum/categorylist/" );
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }

    }
    else
    {
        Header( "Location: /forum/norights" );
        exit();
    }
}


if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "CategoryModify" ) )
    {
        if ( $Name != "" &&
        $Description != "" )
        {
            $cat = new eZForumCategory();
            $cat->get( $CategoryID );
            $cat->setName( $Name );
            $cat->setDescription( $Description );
            $cat->store();
            
            Header( "Location: /forum/categorylist/" );
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        Header( "Location: /forum/norights" );
        exit();
    }
}

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
"ezforum/admin/" . "/intl", $Language, "categoryedit.php" );
$t->setAllStrings();

$t->set_file( array( "category_page" => "categoryedit.tpl"
                    ) );

$t->set_block( "category_page", "category_edit_tpl", "category_edit" );

$t->set_var( "category_name", "" );
$t->set_var( "category_description", "" );
$t->set_var( "category_id", $CategoryID );
$action_value = "update";

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZForum", "CategoryAdd" ) )
    {
        Header( "Location: /forum/norights" );
        exit();
    }
 
    $action_value = "insert";
}

$ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/categoryedit.php.ini", false );
$headline =  $ini->read_var( "strings", "head_line_insert" );

if ( $Action == "edit" )
{
    $ini = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/categoryedit.php.ini", false );
    $headline =  $ini->read_var( "strings", "head_line_edit" );

    if ( !eZPermission::checkPermission( $user, "eZForum", "CategoryAdd" ) )
    {
        Header( "Location: /forum/norights" );
        exit();
    }
    else
    {
        $cat = new eZForumCategory();
        $cat->get( $CategoryID );    
        $t->set_var( "category_name", $cat->name() );
        $t->set_var( "category_description", $cat->description() );
        $action_value = "update";
    }
}

$t->set_var( "action_value", $action_value );
$t->set_var( "error_msg", $error_msg );
$t->set_var( "headline", $headline );

$t->pparse( "output", "category_page" );
?>
