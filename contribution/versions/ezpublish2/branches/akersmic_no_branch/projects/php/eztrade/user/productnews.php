<?
//
// similar products

// check for cache file
include_once( "classes/ezcachefile.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

function &productNews( $productType, $categoryID=false )
{
    switch ( $productType )
    {
        case "musikk":
        {
            $GlobalSectionID = 2;
            $typeID = 1;
        }
        break;
        case "dvd":
        {
            $GlobalSectionID = 3;
            $typeID = 2;
        }
        break;
        case "playstation":
        {
            $GlobalSectionID = 6;
        }
        break;
        case "nintendo":
        {
            $GlobalSectionID = 8;
        }
        break;
        case "xbox":
        {
            $GlobalSectionID = 9;
        }
        break;
        case "pc":
        {
            $GlobalSectionID = 7;
        }
        break;
        case "hifi":
        {
            $GlobalSectionID = 4;
        }
        break;
    }

    if ( $categoryID == false )
    {
        $staticNewsPage = "sitedesign/am/staticpages/" . $productType . "_news.html";
        if ( file_exists ( $staticNewsPage ) )
        {
            $file = eZFile::fopen( $staticNewsPage, "r" );
            if ( $file )
            {
                $leftContent =& fread( $file, eZFile::filesize( $staticNewsPage ) );
                fclose( $file );
            }
        }
        return $leftContent;
    }
    else
    {
        $productNews = new eZCacheFile( "eztrade/cache/news/",
                                        array_merge( "productnews", $GlobalSectionID, $categoryID ),
                                        "cache", "," );

        $dateTime = new eZDateTime();
        if ( ( $productNews->exists() ) and ( $dateTime->dateEquals( $productNews->lastModified() ) ) )
        {
            return $productNews->contents();
        }
        else
        {
            global $ini, $IntlDir, $Language;
            $IntlDir = "no_NO";
            $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                                 "eztrade/user/intl/", $Language, "productview.php" );

            $t->setAllStrings();
            $twoColums = true;
            // sections
            include_once( "ezsitemanager/classes/ezsection.php" );

            // init the section
            $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
            $sectionObject->setOverrideVariables();

            $sectionOverride = "_sectionoverride_$GlobalSectionID";

            if ( eZFile::file_exists( "eztrade/user/templates/am/productnews" . $sectionOverride  . ".tpl" ) )
            {
                $t->set_file( "product_view_tpl", "productnews" . $sectionOverride  . ".tpl" );
            }
            else
            {
                eZHTTPTool::header( "Location: /" );
                exit();
            }

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

            $query = "SELECT Product.ID,
			     Product.Name,
			     Category.Name as CatName,
			     Product.Price
		      FROM 
			   eZTrade_Product AS Product,
			   eZTrade_Category AS Category,
			   eZTrade_ProductCategoryLink AS CategoryLink
		      WHERE CategoryLink.ProductID=Product.ID AND
			    CategoryLink.CategoryID=Category.ID AND
			    Category.ID='$categoryID'
		      GROUP BY Product.ID ORDER BY Product.ProductNumber DESC LIMIT 20";
            $db->array_query( $product_array, $query );
            $i=0;

            foreach ( $product_array as $product )
            {
                // get thumbnail image, if exists
                $thumbnailImage = false;
                $db->array_query( $res_array, "SELECT * FROM eZTrade_ProductImageDefinition WHERE ProductID='" . $product["ID"] . "'" );

                $db->query_single( $categoryDefArray, "SELECT Category.ID, Category.Name FROM eZTrade_ProductCategoryDefinition AS Def, eZTrade_Category AS Category WHERE Def.ProductID='" . $product["ID"] . "' AND Def.CategoryID = Category.ID" );


                if ( count( $res_array ) == 1 )
                {
                    if ( is_numeric( $res_array[0][$db->fieldName( "ThumbnailImageID" )] ) )
                    {
                        $thumbnailImage = new eZImage( $res_array[0][$db->fieldName( "ThumbnailImageID" )], false );
                    }
                }

                if ( $thumbnailImage )
                {
                    $variation =& $thumbnailImage->requestImageVariation( 60, 120 );

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

                $t->set_var( "artist_name", $categoryDefArray["Name"] );
                $t->set_var( "small_product_name", $product["Name"] );
                $t->set_var( "small_product_id", $product["ID"] );
                $t->set_var( "small_product_price", number_format( $product["Price"], 0, " ", " " ) );

                if ( $product["ProductType"] == 3 )
                {
                    $t->set_var( "buy_button", "bestill" );
                }
                else
                {
                    $t->set_var( "buy_button", "kjop" );
                }

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
            $productNews->store( $output );
            return $output;
        }
    }
}


?>
