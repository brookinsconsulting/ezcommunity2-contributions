<?
/*
    Edit a certificate
 */
include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$Language = $ini->read_var( "eZCVMain", "Language" );
include_once( "classes/eztemplate.php" );
include_once( "ezcv/classes/ezcertificatecategory.php" );


$error = false;

if( empty( $TypeID ) )
{
    $TypeID = 0;
}

$type = new eZCertificateCategory();
$type->get( $TypeID );

$Permission = false;

switch( $Action )
{
    case "edit":
    {
        $PermissionCommand = "CVTypeModify";
        if( eZPermission::checkPermission( $user, "eZCV", $PermissionCommand ) )
        {
            $Permission = true;
        }
        break;
    }
    case "delete":
    {
        $PermissionCommand = "CVTypeDelete";
        if( eZPermission::checkPermission( $user, "eZCV", $PermissionCommand ) )
        {
            $Permission = true;
        }
        break;
    }
    case "new":
    {
        $PermissionCommand = "CVTypeAdd";
        if( eZPermission::checkPermission( $user, "eZCV", $PermissionCommand ) )
        {
            $Permission = true;
        }
        break;
    }
    case "insert":
    {
        $PermissionCommand = "CVTypeAdd";
        if( eZPermission::checkPermission( $user, "eZCV", $PermissionCommand ) )
        {
            $Permission = true;
        }
        break;
    }
    case "update":
    {
        $PermissionCommand = "CVTypeModify";
        if( eZPermission::checkPermission( $user, "eZCV", $PermissionCommand ) )
        {
            $Permission = true;
        }
        break;
    }
}

if( $Permission == false )
{
    header( "Location: /cv/error/?type=500&reason=missingpermission&permission=$PermissionCommand&tried=/cv/certificatecategory/$Action&module=ezcv" );
    exit();
}

$DontExist = true;

if( !$type->id() )
{
    if( $Action != "new" )
    {
        $DontExist = false;
    }
}
if( $DontExist == false )
{
    header( "Location: /cv/error/?404&reason=missingpage&module=ezcv&hint=/cv/certificatecategory/list/0" );
    exit();
}
if( $Action == "insert" || $Action == "update" )
{
    $type->setName( $CategoryName );
    $type->setDescription( $Description );
    $type->setParentID( $ParentID );
    $type->setInstitution( $Institution ); 
    $type->store();
    $TypeID = $type->id();
    if( $TypeID > 0 )
    {
        header( "Location: /cv/certificatecategory/list/$TypeID" );
    }

}

if ( $Action == "delete" )
{
    $ParentID = $type->parentID();
    $type->delete( );

    header( "Location: /cv/certificatecategory/view/$ParentID" );
}

$t = new eZTemplate( "ezcv/admin/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/admin/intl", $Language, "certificatecategory.php" );
$intl = new INIFile( "ezcv/admin/intl/" . $Language . "/certificatecategory.php.ini", false );
$t->set_file( array(                    
    "type_page" => "certificatecategoryedit.tpl"
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

if( $Action == "edit" || $Action == "new" )
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

    $t->parse( "current_type", "current_type_tpl" );
}

$t->setAllStrings();
$t->set_var( "action_value", $ActionValue );
$t->pparse( "output", "type_page"  );


?>
