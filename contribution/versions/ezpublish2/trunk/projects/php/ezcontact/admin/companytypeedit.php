<?
/*
  Edit company types
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

if( empty( $TypeID ) )
{
    $TypeID = 0;
}

$type = new eZCompanyType();
$type->get( $TypeID );

if ( $Action == "insert" || $Action == "update" )
{
    if( true || eZPermission::checkPermission( $user, "eZContact", "AdminModify" ) )
    {
        $type = new eZCompanyType();

        if( !empty( $TypeID ) )
        {
            $type->get( $TypeID );
        }
        $type->setName( $TypeName );
        $type->setDescription( $TypeDescription );
        $type->setParentID( $SelectParentID ); 
        $type->store();
        $TypeID = $type->id();

        header( "Location: /contact/companytype/view/$TypeID" );
    }
    else
    {
        header( "Location: /error.php?type=500&reason=missingpermission&tried=update&module=ezcontact" );
   }
}

if( !$type->id() && $Action != "new"  )
{
    header( "HTTP/1.0 404 Not Found" );
    header( "Location: /contact/companytype/list/0" );
    exit();
}
else
{
    if ( $Action == "delete" )
    {
        if ( true || eZPermission::checkPermission( $user, "eZContact", "AdminDelete" ) )
        {
            $type = new eZCompanyType();
            $type->get( $TypeID );
            $ParentID = $type->parentID(); 
            $type->delete( );

            header( "Location: /contact/companytype/list/$ParentID" );
        }
        else
        {
            header( "Location: /error.php?type=500&reason=missingpermission&tried=delete&module=ezcontact" );
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

    $t->set_var( "page_args", $args );

    if( empty( $TypeID ) || $TypeID == 0 )
    {
        $t->set_var( "path_item", "" );
        $t->set_var( "current_path_item", "" );
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
    
    if ( $Action == "edit" || $Action == "new" )
    {
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
