<?
/*
    Edit a certificate type
 */


include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$Language = $ini->read_var( "eZCVMain", "Language" );
include_once( "classes/eztemplate.php" );
include_once( "ezcv/classes/ezcertificatetype.php" );
include_once( "ezcv/classes/ezcertificatecategory.php" );


$error = false;

if( empty( $TypeID ) )
{
    $TypeID = 0;
}

$type = new eZCertificateType();
$type->get( $TypeID );

$t = new eZTemplate( "ezcv/admin/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/admin/intl", $Language, "certificatetype.php" );
$intl = new INIFile( "ezcv/admin/intl/" . $Language . "/certificatetype.php.ini", false );
$t->set_file( array(                    
    "type_page" => "certificatetypeview.tpl"
    ) );

$t->set_block( "type_page", "current_type_tpl", "current_type" );
$t->set_block( "type_page", "path_tpl", "path" );
$t->set_block( "path_tpl", "path_item_tpl", "path_item" );
$t->set_block( "path_tpl", "current_path_item_tpl", "current_path_item" );
$t->set_block( "current_type_tpl", "parent_item_tpl", "parent_item" );

$t->set_var( "page_args", $args );
$t->set_var( "path_item", "" );
$t->set_var( "current_path_item", "" );

$t->set_var( "current_id", $id );
$t->set_var( "current_name", $name );
$t->set_var( "current_description", $Description );
$t->set_var( "parent_id", $ParentID );

if( empty( $TypeID ) || $TypeID == 0 )
{
  $t->parse( "path", "path_tpl" );
}
else
{
  $paths = $type->path( $TypeID );
  $countingPaths = count( $path );

  $t->set_var( "path_item", "" );
  foreach( $paths as $path )
  {
      $t->set_var( "parent_id", $path[0] );
      if( $path[0] == $type->id() )
      {
          $t->parse( "current_path_item", "current_path_item_tpl" );
      }
      else
      {
          $t->set_var( "parent_name", $path[1] );
          $t->parse( "path_item", "path_item_tpl", true );
      }
  }

  $t->parse( "path", "path_tpl" );
}

function byParent( $inStartID, $indent, $inParentID, $maxLevel = 3 )
{
    global $t;
    global $CategoryArray;
    
    $type = new eZCertificateCategory();
    $typeArray = $type->getByParentID( $inStartID );
    
    $count = count( $typeArray );
    
    if( $indent > $maxLevel )
    {
        $indent == $maxLevel;
    }
    $indentLine = str_pad( $indentLine, $indent * 2, "_" );
    
    foreach( $typeArray as $ct )
    {
        $CategoryID = $ct->id();
        $t->set_var( "select_parent_id", $CategoryID );
        $t->set_var( "select_parent_name", $indentLine . $ct->name() );
        $t->set_var( "selected", "" );
        
        if( $CategoryID == $inParentID )
        {
            $t->set_var( "selected", "selected" );
        }
        
        $t->parse( "parent_item", "parent_item_tpl", true );
        byParent( $ct->id(), $indent + 1, $inParentID );
    }
}

if( $Action == "view" || $Action == "list" )
{
    if( $Action == "edit" )
    {
        $t->set_var( "action_value", "update" );
    }
    else
    {
        $t->set_var( "action_value", "insert" );
    }

    $id = $type->id();
    $name = $type->name();
    $desc = $type->description();
    $parentid = $type->certificateCategoryID();

    if( $parentid > 0 )
    {
        $selected = true;
    }
    else
    {
        $selected = false;
    }

    $t->set_var( "current_id", $id );
    $t->set_var( "current_name", $name );
    $t->set_var( "current_description", $desc );
    $t->set_var( "parent_id", $parentid );

    $category = new eZCertificateCategory();
    $categories = $category->getAll();


    byParent( 0, 0, $parentid );

    if( count( $categories ) == 0 )
    {
        $t->set_var( "parent_item", "" );
    }

    if( $selected == false )
    {
        $t->set_var( "root_selected", "selected" );
    }
    else
    {
        $t->set_var( "root_selected", "" );
    }

    $t->parse( "current_type", "current_type_tpl" );
}


$t->setAllStrings();
$t->set_var( "action_value", $ActionValue );
$t->pparse( "output", "type_page"  );


?>
