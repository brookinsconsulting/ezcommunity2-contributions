<?
/*
    Edit a certificate
 */
include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$Language = $ini->read_var( "eZCVMain", "Language" );
include_once( "classes/eztemplate.php" );
include_once( "ezcv/classes/ezcertificatecategory.php" );
include_once( "ezcv/classes/ezcertificatetype.php" );


$error = false;

if( empty( $TypeID ) )
{
    $TypeID = 0;
}

$type = new eZCertificateCategory();
$type->get( $TypeID );

$t = new eZTemplate( "ezcv/admin/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/admin/intl", $Language, "certificatecategory.php" );
$intl = new INIFile( "ezcv/admin/intl/" . $Language . "/certificatecategory.php.ini", false );
$t->set_file( array(                    
    "type_page" => "certificatecategoryview.tpl"
    ) );
$t->set_block( "type_page", "current_type_tpl", "current_type" );
$t->set_block( "type_page", "view_headline_tpl", "view_headline" );
$t->set_block( "type_page", "list_headline_tpl", "list_headline" );
$t->set_block( "type_page", "path_tpl", "path" );
$t->set_block( "path_tpl", "path_item_tpl", "path_item" );
$t->set_block( "path_tpl", "current_path_item_tpl", "current_path_item" );
$t->set_block( "current_type_tpl", "parent_item_tpl", "parent_item" );

$t->set_block( "type_page", "list_box_tpl", "list_box" );
$t->set_block( "type_page", "no_list_box_tpl", "no_list_box" );
$t->set_block( "list_box_tpl", "certificate_item_tpl", "certificate_item" );

$t->set_block( "type_page", "category_list_box_tpl", "category_list_box" );
$t->set_block( "type_page", "no_category_list_box_tpl", "no_category_list_box" );
$t->set_block( "category_list_box_tpl", "category_item_tpl", "category_item" );

$t->set_var( "current_type", "" );
$t->set_var( "view_headline", "" );
$t->set_var( "list_headline", "" );
$t->set_var( "list_box", "" );
$t->set_var( "no_list_box", "" );
$t->set_var( "category_list_box", "" );
$t->set_var( "no_category_list_box", "" );
$t->set_var( "page_args", $args );
$t->set_var( "path_item", "" );
$t->set_var( "current_path_item", "" );
$t->set_var( "certificate_item", "" );

$t->set_var( "current_id", $id );
$t->set_var( "current_name", $name );
$t->set_var( "current_description", $Description );
$t->set_var( "current_institution", $CategoryInstitution );
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

if( $Action == "list" || $Action == "view" )
{
    if( $TypeID == 0 )
    {
        $Action = "list";
    }
    $id = $type->id();
    $name = $type->name();
    $desc = $type->description();
    $parentid = $type->parentID();
    $institution = $type->institution();

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
    $t->set_var( "current_institution", $institution );
    $t->set_var( "parent_id", $parentid );

    $categories = $type->getAll();


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
}

if( $Action == "list" )
{
    $typeList = new eZCertificateType();
    $typeList = $typeList->getByCertificateCategoryID( $TypeID );
    $count = count( $typeList );
    
    $i=0;
    foreach( $typeList as $type )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "theme-type_class", "bglight" );
        }
        else
        {
            $t->set_var( "theme-type_class", "bgdark" );
        }
        $i++;
        $t->set_var( "item_name",$type->name() );
        $t->set_var( "item_description",$type->description() );
        $t->set_var( "item_id",$type->id() );
        $t->parse( "certificate_item", "certificate_item_tpl", true );
    }
    
    if( $i > 0 )
    {
        $t->parse( "list_box", "list_box_tpl" );
    }
    else
    {
        $t->parse( "no_list_box", "no_list_box_tpl" );
    }
    
    $t->parse( "list_headline", "list_headline_tpl" );

    $typeList = new eZCertificateCategory();
    $typeList = $typeList->getByParentID( $TypeID );

    $i=0;
    foreach( $typeList as $type )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "theme-type_class", "bglight" );
        }
        else
        {
            $t->set_var( "theme-type_class", "bgdark" );
        }
        $i++;
        $t->set_var( "item_name",$type->name() );
        $t->set_var( "item_description",$type->description() );
        $t->set_var( "item_id",$type->id() );
        $t->set_var( "item_institution",$type->institution() );
        $t->parse( "category_item", "category_item_tpl", true );
    }
    
    if( $i > 0 )
    {
        $t->parse( "category_list_box", "category_list_box_tpl" );
    }
    else
    {
        $t->parse( "no_category_list_box", "no_category_list_box_tpl" );
    }
    
    $t->parse( "list_headline", "list_headline_tpl" );
}
else
{
    $t->parse( "view_headline", "view_headline_tpl" );
    $t->parse( "current_type", "current_type_tpl" );
}

$t->setAllStrings();
$t->set_var( "action_value", $ActionValue );
$t->pparse( "output", "type_page"  );


?>
