<?
/*
  Viser firma typer.
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

if( !$type->id() && $TypeID != 0 )
{
    header( "HTTP/1.0 404 Not Found" );
    header( "Location: /contact/companytype/list/" );
    exit();
}
else
{
    $t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                         "ezcontact/admin/intl/", $Language, "companytype.php" );
    $t->setAllStrings();

    $t->set_file( array(
        "type_page" => "companytypelist.tpl",
        ) );    

    $t->set_block( "type_page", "current_type_tpl", "current_type" );
    $t->set_block( "type_page", "view_tpl", "view" );
    $t->set_block( "type_page", "list_tpl", "list" );
    $t->set_block( "type_page", "not_root_tpl", "not_root" );
    $t->set_block( "type_page", "type_list_tpl", "type_list" );
    $t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );
    $t->set_block( "type_page", "category_list_tpl", "category_list" );
    $t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );
    $t->set_block( "type_page", "no_type_item_tpl", "no_type_item" );
    $t->set_block( "type_page", "no_category_item_tpl", "no_category_item" );
    $t->set_block( "type_page", "path_tpl", "path" );
    $t->set_block( "path_tpl", "path_item_tpl", "path_item" );
    
    if( !empty( $LimitBy ) || !empty( $LimitStart ) )
    {
        $type_array = $type->getByParentID( $TypeID, "Types", $OrderBy, $LimitBy, $LimitStart );

        if( empty( $LimitStart ) )
        {
            $LimitStart = $LimitBy;
        }
        else
        {
            $LimitStart += $LimitStart;
        }
    }
    else
    {
        $type_array = $type->getByParentID( $TypeID, $OrderBy );
    }

    if( !empty( $OrderBy ) )
    {
        if( !empty( $args ) )
        {
            $args = $args . "&";
        }
        $args = $args . "OrderBy=$OrderBy";
    }

    if( !empty( $LimitStart ) )
    {
        if( !empty( $args ) )
        {
            $args = $args . "&";
        }
        $args = $args . "LimitStart=$LimitStart";
    }

    if( !empty( $LimitBy ) )
    {
        if( !empty( $args ) )
        {
            $args = $args . "&";
        }
        $args = $args . "LimitBy=$LimitBy";
    }

    $type_count = count( $type_array );

    $t->set_var( "page_args", $args );

    $pathArray = $type->path( $TypeID );
    
    $t->set_var( "path_item", "" );
    foreach( $pathArray as $path )
    {
        $t->set_var( "parent_id", $path[0] );
        $t->set_var( "parent_name", $path[1] );
        
        $t->parse( "path_item", "path_item_tpl", true );
    }
    $t->parse( "path", "path_tpl" );

    $id = $type->id();
    $name = $type->name();
    $desc = $type->description();


    $t->set_var( "current_id", $id );
    $t->set_var( "current_name", $name );
    $t->set_var( "current_description", $desc );

    $t->parse( "current_type", "current_type_tpl" );
    if( $TypeID != 0 && $Action == "view" )
    {
        $t->parse( "not_root", "not_root_tpl" );
    }
    else
    {
        $t->set_var( "not_root", "" );
    }
    
    if( $Action == "view" )
    {
        $t->parse( "view", "view_tpl" );
        $t->set_var( "list", "" );
    }
    
    if( $Action == "list" )
    {
        $t->set_var( "view", "" );
        $t->parse( "list", "list_tpl" );
    }
    if( $type_count != 0 )
    {
        for( $i = 0; $i < $type_count; $i++ )
        {
            if ( ( $i % 2 ) == 0 )
            {
                $t->set_var( "theme-type_class", "bglight" );
            }
            else
            {
                $t->set_var( "theme-type_class", "bgdark" );
            }  

            $id = $type_array[$i]->id();
            $name = $type_array[$i]->name();
            $desc = $type_array[$i]->description();

            $t->set_var( "type_id", $id );

            if( empty( $name ) )
            {
                $t->set_var( "type_name", "&nbsp;" );
            }
            else
            {
                $t->set_var( "type_name", $name );
            }
            if( empty( $desc ) )
            {
                $t->set_var( "type_description", "&nbsp;" );
            }
            else
            {
                $t->set_var( "type_description", $desc );
            }
            if( !$type_array[$i]->hasChildren( $childrenCount ) )
            {
                $t->parse( "type_item", "type_item_tpl", true );
                $typesDone = true;
            }
            else
            {
                $t->parse( "category_item", "category_item_tpl", true );
                $categoriesDone = true;
            }
        }
    }

    if( $typesDone == true )
    {
        $t->set_var( "no_type_item", "" );    
        $t->parse( "type_list", "type_list_tpl" );
    }
    else
    {
        $t->set_var( "type_list", "" );
        $t->parse( "no_type_item", "no_type_item_tpl" );
    }

    if( $categoriesDone == true )
    {
        $t->set_var( "no_category_item", "" );    
        $t->parse( "category_list", "category_list_tpl" );
    }
    else
    {
        $t->set_var( "category_list", "" );
        $t->parse( "no_category_item", "no_category_item_tpl" );
    }
    $t->pparse( "output", "type_page" );
}
?>
