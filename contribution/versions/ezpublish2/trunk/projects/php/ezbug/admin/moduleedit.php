<?
/*
  Edit a module type.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugmodule.php" );

if ( $Action == "insert" )
{
    $module = new eZBugModule();
    $module->setName( $Name );
    $parent = new eZBugModule( $ParentID );
    $module->setParent( $parent );
    $module->store();

    Header( "Location: /bug/module/list/" );
    exit();
}

// Updates a module.
if ( $Action == "update" )
{
    $module = new eZBugModule( $ModuleID );
    $module->setName( $Name );
    $parent = new eZBugModule( $ParentID );
    $module->setParent( $parent );
    $module->store();

    Header( "Location: /bug/module/list/" );
    exit();
}

// Delete a module.
if ( $Action == "delete" )
{
    $module = new eZBugModule( $ModuleID );
    $module->delete();

    Header( "Location: /bug/module/list/" );
    exit();
}

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "moduleedit.php" );
$t->setAllStrings();

$t->set_file( array(
    "moduleedit" => "moduleedit.tpl"
    ) );

$t->set_block( "moduleedit", "module_item_tpl", "module_item" );

if ( $Action == "new" )
{
    $t->set_var( "module_name", "" );
    $t->set_var( "action_value", "insert" );
}

// Edit a module.
if ( $Action == "edit" )
{
    $module = new eZBugModule( $ModuleID );

    $parent = $module->parent();
    $t->set_var( "module_name", $module->name() );
    $t->set_var( "module_id", $module->id() );

    $t->set_var( "action_value", "update" );
}

// Category selector

$module = new eZBugModule();

$moduleList = $module->getAll();

foreach( $moduleList as $moduleItem )
{
    $t->set_var( "module_parent_name", $moduleItem->name() );
    $t->set_var( "module_parent_id", $moduleItem->id() );


    if ( $parent )
    {
        if ( $parent->id() == $moduleItem->id() )
        {
            $t->set_var( "is_selected", "selected" );
        }
        else
        {
            $t->set_var( "is_selected", "" );
        }
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }
        

    $t->parse( "module_item", "module_item_tpl", true );
}


$t->pparse( "output", "moduleedit" );
?>
