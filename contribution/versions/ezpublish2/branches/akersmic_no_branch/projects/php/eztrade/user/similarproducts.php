<?
//
// similar products

// check for cache file

function similarProducts( $SimilarCategoryID )
{
    global $ini, $IntlDir, $Language;
    $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/user/intl/", $Language, "productview.php" );
    
    $t->setAllStrings();
    
    $t->set_file( "product_view_tpl", "similarproducts.tpl" );
    
    $t->set_block( "product_view_tpl", "product_line_tpl", "product_line" );
    $t->set_block( "product_line_tpl", "product_item_tpl", "product_item" );
    $t->set_block( "product_line_tpl", "product_item_button_tpl", "product_item_button" );

    $catID = $SimilarCategoryID;
    $limit = 12;
    $offset = 0;
    $db =& eZDB::globalDatabase();

    $t->set_var(  "product_line", "" );
    
    $OrderBy = "eZTrade_Product.Published DESC";

    $return_array = array();
    $product_array = array();

    $nonActiveCode = " eZTrade_Product.ShowProduct='1' AND";
    $discontinuedCode = "";

    $db->array_query( $product_array, "
                SELECT eZTrade_Product.ID AS ID, eZTrade_Product.Name, eZTrade_Product.Price AS Price,
                       eZTrade_Category.ID as CatID, eZTrade_Category.Name as CatName
                FROM eZTrade_Product, eZTrade_Category,
                     eZTrade_ProductCategoryLink
                WHERE
                eZTrade_ProductCategoryLink.ProductID = eZTrade_Product.ID
                AND
                $nonActiveCode
                eZTrade_Category.ID = eZTrade_ProductCategoryLink.CategoryID
                AND
                eZTrade_Category.ID='$catID'
                ORDER BY $OrderBy", array( "Limit" => $limit, "Offset" => $offset ) );


    $i=0;
    foreach ( $product_array as $product )
    {
        // get thumbnail image, if exists
        $thumbnailImage = false;
        $db->array_query( $res_array, "SELECT * FROM eZTrade_ProductImageDefinition WHERE ProductID='" . $product["ID"] . "'" );
        
        if ( count( $res_array ) == 1 )
        {
           if ( is_numeric( $res_array[0][$db->fieldName( "ThumbnailImageID" )] ) )
           {
               $thumbnailImage = new eZImage( $res_array[0][$db->fieldName( "ThumbnailImageID" )], false );
           }
        }

        if ( $thumbnailImage )
        {
            $variation =& $thumbnailImage->requestImageVariation( 60, 60 );
    
            $t->set_var( "image_uri", "/" . $variation->imagePath() );
            $t->set_var( "image_width", $variation->width() );
            $t->set_var( "image_height", $variation->height() );
        }
        else
        {
            $t->set_var( "image_uri", "/sitedesign/am/img/a_95x95.gif" );
            $t->set_var( "image_width", "60" );
            $t->set_var( "image_height", "60" );
        }
        
        $t->set_var( "artist_name", $product["CatName"] );
        $t->set_var( "small_product_name", $product["Name"] );
        $t->set_var( "small_product_id", $product["ID"] );
        $t->set_var( "small_product_price", number_format( $product["Price"], 0, " ", " " ) );

        $t->parse( "product_item", "product_item_tpl",true );
        $t->parse( "product_item_button", "product_item_button_tpl",true );

        $i++;

        if ( $i == 2 )
        {
            $t->parse( "product_line", "product_line_tpl",true );
            $t->set_var( "product_item", "" );
            $t->set_var( "product_item_button", "" );

            $i = 0;
        }
        
    }
    
    return $t->parse(  "output", "product_view_tpl" );
}


?>
