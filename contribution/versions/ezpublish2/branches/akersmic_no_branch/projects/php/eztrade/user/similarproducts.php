<?
//
// similar products

// check for cache file
include_once( "classes/ezcachefile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

function &similarProducts( $SimilarCategoryID, $twoColums=true )
{
    $SimilarCacheFile = new eZCacheFile( "eztrade/cache/similar/",
                                         array_merge( "sp", $SimilarCategoryID, NULL, NULL ),
                                         "cache", "," );

    if ( $SimilarCacheFile->exists() )
    {
        return $SimilarCacheFile->contents();
    }
    else
    {
        global $ini, $IntlDir, $Language;
        $IntlDir = "no_NO";
        $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                             "eztrade/user/intl/", $Language, "productview.php" );

        $t->setAllStrings();

        $t->set_file( "product_view_tpl", "similarproducts.tpl" );
        $t->set_block( "product_view_tpl", "product_line_tpl", "product_line" );
        $t->set_block( "product_line_tpl", "product_item_tpl", "product_item" );
        $t->set_block( "product_item_tpl", "product_item_button_tpl", "product_item_button" );

        $catID = $SimilarCategoryID;
        $db =& eZDB::globalDatabase();

        $t->set_var(  "product_line", "" );

        $OrderBy = "eZTrade_Product.Published DESC";

        $return_array = array();
        $product_array = array();

        $nonActiveCode = " eZTrade_Product.ShowProduct='1' AND";
        $discontinuedCode = "";

        $db->array_query( $product_array, "SELECT eZTrade_Product.ID AS ID, eZTrade_Product.Name, eZTrade_Product.Price AS Price, eZTrade_Category.ID AS CatID, eZTrade_Category.Name AS CatName
                                           FROM eZTrade_Product, eZTrade_Category, eZTrade_ProductCategoryLink, eZTrade_ProductCategoryDefinition AS Definition
                                           WHERE eZTrade_ProductCategoryLink.CategoryID='$catID'
                                                 AND eZTrade_ProductCategoryLink.ProductID = eZTrade_Product.ID
                                                 AND eZTrade_ProductCategoryLink.ProductID = Definition.ProductID
                                                 AND eZTrade_Category.ID = Definition.CategoryID
                                           ORDER BY $OrderBy" );
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

            $t->parse( "product_item_button", "product_item_button_tpl" );
            $t->parse( "product_item", "product_item_tpl",true );
            $i++;

            if ( ( $i == 2 ) and
                 ( $twoColums == true )
                 )
            {
                $t->parse( "product_line", "product_line_tpl",true );
                $t->set_var( "product_item", "" );
                $t->set_var( "product_item_button", "" );

                $i = 0;
            }
            else if ( $twoColums == false )
            {
                $t->parse( "product_line", "product_line_tpl",true );
                $t->set_var( "product_item", "" );
                $t->set_var( "product_item_button", "" );
            }
        }
        $output =& $t->parse(  "output", "product_view_tpl" );
//        $SimilarCacheFile->store( $output );
        return $output;
    }
}


?>
