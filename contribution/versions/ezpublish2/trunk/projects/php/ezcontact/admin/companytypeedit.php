<?
/*
  Edit company types
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
//include_once( "");


//  if( !eZPermission::checkPermission( $user, "eZContact", "TypeAdd" ) && $Action == "new" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=TypeAdd&tried=new&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "TypeAdd" ) && $Action == "insert" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=TypeAdd&tried=insert&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "TypeModify" ) && $Action == "update" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=TypeModify&tried=update&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "TypeModify" ) && $Action == "edit" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=TypeModify&tried=edit&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "TypeDelete" ) && $Action == "delete" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=TypeDelete&tried=delete&module=ezcontact" );
//      exit();
//  }

if( empty( $TypeID ) )
{
    $TypeID = 0;
}

$type = new eZCompanyType();
$type->get( $TypeID );

if( $Action == "insert" || $Action == "update" )
{
    $type = new eZCompanyType();

    if( !empty( $TypeID ) )
    {
        $type->get( $TypeID );
    }
    $type->setName( $TypeName );
    $type->setDescription( $TypeDescription );
    $type->setParentID( $SelectParentID ); 

    $file = new eZImageFile();
    if ( $file->getUploadedFile( "ImageFile" ) )
    {
        $image = new eZImage( );
        $image->setName( "Image" );
        $image->setImage( $file );

        $image->store();

        $type->setImageID( $image->id() );
    }
        
    $type->store();
    $TypeID = $type->id();

    header( "Location: /contact/companytype/view/$TypeID" );

}

if( !$type->id() && $Action != "new"  )
{
    header( "Location: /error.php?type=404&reason=missingpage&module=ezcontact&hint=/contact/companytype/list/0" );
    exit();
}


{
    if ( $Action == "delete" )
    {
        if ( eZPermission::checkPermission( $user, "eZContact", "TypeDelete" ) )
        {
            $type = new eZCompanyType();
            $type->get( $TypeID );
            $ParentID = $type->parentID(); 
            $type->delete( );

            header( "Location: /contact/companytype/list/$ParentID" );
        }
        else
        {
            header( "Location: /error.php?type=500&reason=missingpermission&permission=TypeDelete&tried=delete&module=ezcontact" );
        }
    }

    
    $t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                         "ezcontact/admin/intl/", $Language, "companytype.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "type_page" => "companytypeedit.tpl",
        ) );    
    $t->set_block( "type_page", "current_type_tpl", "current_type" );
    $t->set_block( "type_page", "path_tpl", "path" );
    $t->set_block( "path_tpl", "path_item_tpl", "path_item" );
    $t->set_block( "path_tpl", "current_path_item_tpl", "current_path_item" );
    $t->set_block( "current_type_tpl", "parent_item_tpl", "parent_item" );
    $t->set_block( "current_type_tpl", "image_item_tpl", "image_item" );
    $t->set_block( "current_type_tpl", "no_image_item_tpl", "no_image_item" );

    $t->set_var( "page_args", $args );
    $t->set_var( "no_image_item", "" );
    $t->set_var( "image_item", "" );
    $t->set_var( "path_item", "" );
    $t->set_var( "current_path_item", "" );

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

        $type = new eZCompanyType();
        $type->get( $TypeID );

        $id = $type->id();
        $name = $type->name();
        $desc = $type->description();
        $parentid = $type->parentID();
        
        $t->set_var( "current_id", $id );
        $t->set_var( "current_name", $name );
        $t->set_var( "current_description", $desc );
        $t->set_var( "parent_id", $parentid );

        $ImageID = $type->imageID();
        
        if( is_numeric( $ImageID ) && $ImageID != 0 )
        {
            $ini = new INIFile( "site.ini" );
            $imageWidth = $ini->read_var( "eZContactMain", "CategoryImageWidth" );
            $imageHeight = $ini->read_var( "eZContactMain", "CategoryImageHeight" );

            $image = new eZImage( $ImageID );

            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );
            
            $imageURL = "/" . $variation->imagePath();
            $imageWidth = $variation->width();
            $imageHeight = $variation->height();
            $imageCaption = $image->caption();
            
            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            $t->parse( "image_item", "image_item_tpl" );
        }
        else
        {
            $t->parse( "no_image_item", "no_image_item_tpl" );
        }
        
        $categories = $type->getAll();
        
        $selected = false;
        
        foreach( $categories as $category )
        {
            $t->set_var( "select_parent_id", $category->id() );
            $t->set_var( "select_parent_name", $category->name() );
            if( $category->id() == $parentid )
            {
                $t->set_var( "selected", "selected" );
                $selected = true;
            }
            else
            {
                $t->set_var( "selected", "" );
            }            
            $t->parse( "parent_item", "parent_item_tpl", true );
        }

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
            $t->set_var( "root_selected", "" );
        }

        $t->parse( "current_type", "current_type_tpl" );
    }

    $t->pparse( "output", "type_page" );
}

?>
