<?
/*
  Edit a category type.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugcategory.php" );

if ( $Action == "insert" )
{
    $category = new eZBugCategory();
    $category->setName( $Name );
    $category->store();

    Header( "Location: /bug/category/list/" );
    exit();
}

// Updates a category.
if ( $Action == "update" )
{
    $category = new eZBugCategory( $CategoryID );
    $category->setName( $Name );
    $category->store();

    Header( "Location: /bug/category/list/" );
    exit();
}

// Delete a category.
if ( $Action == "delete" )
{
    $category = new eZBugCategory( $CategoryID );
    $category->delete();

    Header( "Location: /bug/category/list/" );
    exit();
}

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "categoryedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "categoryedit" => "categoryedit.tpl"
    ) );

if ( $Action == "new" )
{
    $t->set_var( "category_name", "" );
    $t->set_var( "action_value", "insert" );
}

// Edit a category.
if ( $Action == "edit" )
{
    $category = new eZBugCategory( $CategoryID );

    $t->set_var( "category_name", $category->name() );
    $t->set_var( "category_id", $category->id() );

    $t->set_var( "action_value", "update" );
}

$t->pparse( "output", "categoryedit" );
?>
