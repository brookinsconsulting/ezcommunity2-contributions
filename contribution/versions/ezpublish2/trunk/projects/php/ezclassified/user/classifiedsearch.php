<?php

/*
    Searches for companies.
*/
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezclassified/classes/ezclassified.php" );
include_once( "ezclassified/classes/ezcategory.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$errorIni = new INIFIle( "ezclassified/user/intl/" . $Language . "/search.php.ini", false );

$t = new eZTemplate( "ezclassified/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                     "ezclassified/user/intl/", $Language, "search.php" );
$t->set_file( array(
    "search" => "search.tpl" ) );

$t->set_block( "search", "search_box_tpl", "search_box" );
$t->set_block( "search", "advanced_search_box_tpl", "advanced_search_box" );
$t->set_block( "advanced_search_box_tpl", "category_option_tpl", "category_option" );
$t->set_block( "search", "search_results_tpl", "search_results" );
$t->set_block( "search_results_tpl", "result_item_tpl", "result_item" );
$t->set_block( "result_item_tpl", "result_category_tpl", "result_category" );
$t->set_block( "search", "no_results_tpl", "no_results" );

$t->set_var( "search_box", "" );
$t->set_var( "advanced_search_box", "" );
$t->set_var( "search_results", "" );
$t->set_var( "result_item", "" );
$t->set_var( "category_option", "" );
$t->set_var( "no_results", "" );
$t->set_var( "result_category", "" );

$t->set_var( "search_text", "$SearchText" );

$Action = "new";
$results = "false";

if( $SearchObject == "classified" )
{
    $Action = "search";
}

if( !empty( $SearchText ) )
{
    $Action = "search";
}

if( !empty( $AdvancedSearch ) )
{
    $Action = "advanced";
    $t->set_var( "advanced_search", "true" );
}

if( $Action == "new" )
{
    $t->parse( "search_box", "search_box_tpl" );
}

$classified = new eZClassified();

if( $Action == "search" )
{
    $classifiedArray =& $classified->search( $SearchText );
    
    $count = count( $classifiedArray );
    
    if( $count > 0 )
    {
        $results = true;
    }
    
    $t->parse( "search_box", "search_box_tpl" );
}

function byParent( $inParentID, $indent, $maxLevel = 3 )
{
    global $t;
    global $CategoryArray;
    
    $type = new eZCategory();
    $typeArray = $type->getByParentID( $inParentID );
    
    $count = count( $typeArray );
    
    if( $indent > $maxLevel )
    {
        $indent == $maxLevel;
    }
    $indentLine = str_pad( $indentLine, $indent * 6, "&nbsp;" );
    
    foreach( $typeArray as $ct )
    {
        $CategoryID = $ct->id();
        $t->set_var( "category_id", $CategoryID );
        $t->set_var( "category_value", $indentLine . $ct->name() );
        $t->set_var( "category_selected", "" );
        
        if( is_array( $CategoryArray ) )
        foreach( $CategoryArray as $Category )
        {
            if( $CategoryID == $Category )
            {
                $t->set_var( "category_selected", "selected" );
            }
        }
        
        $t->parse( "category_option", "category_option_tpl", true );
        byParent( $ct->id(), $indent + 1 );
    }
}

if( $Action == "advanced" )
{
    
    byParent( $ParentID, 0 );
    
    $classifiedArray = array();
    $count = count( $CategoryArray );

    if( $count )
    {
        foreach( $CategoryArray as $Category )
        {
            $classifiedArray = array_merge( $classifiedArray, $classified->searchByCategory( $Category, $SearchText ) );
        }
        $classifiedArray = array_unique( $classifiedArray );
        $results = true;
    }
    
    $t->parse( "advanced_search_box", "advanced_search_box_tpl" );
}


if( $results == true )
{
    $count = count( $classifiedArray );
    $t->set_var( "results", $count );
    $i;
    if( $count > 0 )
    foreach( $classifiedArray as $classified )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "item_color", "bglight" );
        else
            $t->set_var( "item_color", "bgdark" );
        $i++;
        $t->set_var( "item_name", $classified->name() );
        $t->set_var( "item_id", $classified->id() );
        $t->set_var( "item_description", $classified->description() );
        $t->set_var( "item_view_path", "/classified/view" );
        $t->set_var( "item_delete_path", "/classified/delete" );
        $t->set_var( "item_edit_path", "/classified/edit" );
        
        $categoryArray = $classified->categories( $classified->id() );
        
        $t->set_var( "result_category", "&nbsp;" );
        
        foreach( $categoryArray as $category )
        {
            $t->set_var( "item_category_id", $category->id() );
            $t->set_var( "item_category_name", $category->name() );
            $t->set_var( "item_category_view_path", "/classified/classifiedlist/list" );
            $t->parse( "result_category", "result_category_tpl", true );
        }
        
        $t->parse( "result_item", "result_item_tpl", true );
    }
    
    if( $count > 0 )
    {
        $t->parse( "search_results", "search_results_tpl" );
    }
    else
    {
        $t->parse( "no_results", "no_results_tpl" );
    }
}

$t->setAllStrings();

$t->pparse( "output", "search");
?>
