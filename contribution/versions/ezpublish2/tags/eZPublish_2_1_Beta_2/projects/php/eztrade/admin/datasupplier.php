<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "orderlist" :
        include( "eztrade/admin/orderlist.php" );
        break;

    case "orderedit" :
        $OrderID = $url_array[3];
        $Action = $url_array[4];
        include( "eztrade/admin/orderedit.php" );
        break;

    case "categorylist" :
        if ( ( $url_array[3] == "parent") && ( $url_array[4] != "" ) )
        {
            $ParentID = $url_array[4];
            $Offset = $url_array[5];
            include( "eztrade/admin/categorylist.php" );
        }
        else
        {
            include( "eztrade/admin/categorylist.php" );
        }
        break;

    case "typelist" :
    {
        include( "eztrade/admin/typelist.php" );
    }
    break;

    case "typeedit" :
    {
        if ( $url_array[3] == "edit" )
        {
            $TypeID = $url_array[4];
            $Action = "Edit";
        }
        if ( $url_array[3] == "delete" )
        {
            $TypeID = $url_array[4];
            $Action = "Delete";
        }
        if ( $url_array[3] == "up" )
        {
            $TypeID = $url_array[4];
            $AttributeID = $url_array[5];
            $Action = "up";
        }
        if ( $url_array[3] == "down" )
        {
            $TypeID = $url_array[4];
            $AttributeID = $url_array[5];
            $Action = "down";
        }

        include( "eztrade/admin/typeedit.php" );
    }
    break;

    case "categoryedit" :
        if ( ( $url_array[3] == "insert") )
        {
            $Action = "Insert";
            include( "eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "edit") )
        {
            $Action = "Edit";
            $CategoryID = $url_array[4];            
            include( "eztrade/admin/categoryedit.php" );
        }
        else if ( ( $url_array[3] == "update") )
        {
            $Action = "Update";
            include( "eztrade/admin/categoryedit.php" );
        }        
        else if ( ( $url_array[3] == "delete") )
        {
            $Action = "Delete";
            $CategoryID = $url_array[4];
            include( "eztrade/admin/categoryedit.php" );
        }        
        else
        {
            include( "eztrade/admin/categoryedit.php" );
        }        
        break;
        
    case "productedit" :
        switch ( $url_array[3] )
        {
            // preview
            case "productpreview" :
                $ProductID = $url_array[4];
                include( "eztrade/admin/productpreview.php" );
                break;
            
            // Images
            case "imagelist" :
                $ProductID = $url_array[4];
                include( "eztrade/admin/imagelist.php" );
                break;

            case "imageedit" :
                if ( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $ImageID = $url_array[5];
                    $ProductID = $url_array[6];
                    include( "eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $ImageID = $url_array[5];
                    $ProductID = $url_array[6];                    
                    include( "eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "new" )
                {
                    $ProductID = $url_array[5];
                    include( "eztrade/admin/imageedit.php" );
                }
                else if ( $url_array[4] == "storedef" )
                {
                    $Action = "StoreDef";
                    if ( isset( $DeleteSelected ) )
                        $Action = "Delete";
                    $ProductID = $url_array[5];
                    include( "eztrade/admin/imageedit.php" );
                }
                else
                {
                    include( "eztrade/admin/imageedit.php" );                    
                }                
                
                break;
                
            // Options
            case "optionlist" :
                $ProductID = $url_array[4];
                include( "eztrade/admin/optionlist.php" );
                break;

            case "attributeedit" :
            {
                $ProductID = $url_array[4];
                include( "eztrade/admin/attributeedit.php" );
            }
            break;
                
            case "link" :
            {
                $ProductID = $url_array[5];
                switch( $url_array[4] )
                {
                    case "list":
                    {
                        include( "eztrade/admin/linklist.php" );
                        break;
                    }
                    case "select":
                    {
                        if ( isset( $url_array[6] ) )
                            $ModuleName = $url_array[6];
                        if ( isset( $url_array[7] ) )
                            $Type = $url_array[7];
                        if ( isset( $url_array[8] ) )
                            $SectionID = $url_array[8];
                        if ( isset( $url_array[9] ) )
                            $Category = $url_array[9];
                        if ( isset( $url_array[10] ) )
                            $Offset = $url_array[10];
                        include( "eztrade/admin/linkselect.php" );
                        break;
                    }
                }
                break;
            }

            case "optionedit" :
                if ( $url_array[4] == "edit" )
                {
                    $Action = "Edit";
                    $OptionID = $url_array[5];
                    $ProductID = $url_array[6];
                    include( "eztrade/admin/optionedit.php" );
                }
                else if ( $url_array[4] == "delete" )
                {
                    $Action = "Delete";
                    $OptionID = $url_array[5];
                    $ProductID = $url_array[6];                    
                    include( "eztrade/admin/optionedit.php" );
                }
                else if ( $url_array[4] == "new" )
                {
                    $Action = "New";
                    $ProductID = $url_array[5];
                    include( "eztrade/admin/optionedit.php" );
                }
                else
                {
                    include( "eztrade/admin/optionedit.php" );                    
                }                
                
                break;
                
            case "insert" :
                $Action = "Insert";
                include( "eztrade/admin/productedit.php" );
                break;
            case "edit" :
                $Action = "Edit";
                $ProductID = $url_array[4];            
                include( "eztrade/admin/productedit.php" );
                break;
            case "update" :
                $Action = "Update";
                include( "eztrade/admin/productedit.php" );
                break;

            case "cancel" :
            {
                $Action = "Cancel";
                include( "eztrade/admin/productedit.php" );                
            }
            break;

            case "delete" :
                $Action = "Delete";
                $ProductID = $url_array[4];
                include( "eztrade/admin/productedit.php" );
                break;

            default:
                include( "eztrade/admin/productedit.php" );
                break;
        }
        break;

    case "vattypes" :
    {
        if ( isset( $Add ) )
            $Action = "Add";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "Delete";
        
        include( "eztrade/admin/vattypes.php" );        
    }        
    break;

    case "shippingtypes" :
    {
        if ( isset( $AddType ) )
            $Action = "AddType";

        if ( isset( $AddGroup ) )
            $Action = "AddGroup";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "DeleteSelected";
        
        
        include( "eztrade/admin/shippingtypes.php" );
        break;
    }        

    case "pricegroups":
    {
        $Action = $url_array[3];
        switch( $Action )
        {
            case "list":
            {
                include( "eztrade/admin/pricegroups.php" );
                break;
            }
            case "new":
            case "edit":
            {
                if ( !isset( $PriceID ) )
                    $PriceID = $url_array[4];
                include( "eztrade/admin/pricegroupedit.php" );
                break;
            }
        }
        break;
    }

    case "search":
    {
        $Offset = $url_array[3];
        if ( isset( $Query ) )
            $Search = $Query;
        else
            $Search = $url_array[4];
        include( "eztrade/admin/productsearch.php" );
        break;
    }

    case "currency" :
    {
        if ( isset( $AddCurrency ) )
            $Action = "AddCurrency";

        if ( isset( $Store ) )
            $Action = "Store";

        if ( isset( $Delete ) )
            $Action = "DeleteSelected";
        
        
        include( "eztrade/admin/currency.php" );
        break;
    }
    
    default :
        print( "<h1>Sorry, Your PRODUCT page could not be found. </h1>" );
        break;
}

?>
