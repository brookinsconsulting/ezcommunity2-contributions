<?
ob_end_clean();
ob_start();

chdir( "/var/www/mygold/" );
include_once( "classes/ezcachefile.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbool.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcbase64.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezvattype.php" );
include_once( "eztrade/classes/ezpricegroup.php" );
include_once( "eztrade/classes/ezshippinggroup.php" );

include_once( "classes/ezfile.php" );
include_once( "classes/ezimagefile.php" );
include_once( "classes/ezlog.php" );
include_once( "classes/INIFile.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

// must have to generate XML
include_once( "ezarticle/classes/ezarticlegenerator.php" );

set_time_limit( 0 );
$server = new eZXMLRPCServer( );

$server->registerFunction( "insert", array( new eZXMLRPCStruct() ) );
$server->registerFunction( "assignToCategoies" );
$server->registerFunction( "passiv", array( new eZXMLRPCStruct() ) );
// $server->registerFunction( "passiv", new eZXMLRPCArray() );
$server->registerFunction( "unavailable", array( new eZXMLRPCArray() ) );
$server->registerFunction( "clearcache" );
$server->registerFunction( "uploadImage", array( new eZXMLRPCStruct() ) ); 
$server->registerFunction( "productExists", array( new eZXMLRPCString() ) );     

$server->processRequest();

function productExists( $id )
{
    $product = eZProduct::getByRemoteID( $id[0]->value() );

    if ( get_class ( $product ) == "ezproduct" )
    {
        return new eZXMLRPCBool( true );
    }
    else
    {
        return new eZXMLRPCBool( false );
    }
}



function uploadImage( $args )
{
    $data =&$args[0];
    $struct = $data->value();
    $remoteID =& $struct["productRemoteID"]->value();
    $productPictureName =& $struct["productPictureName"]->value();
    $productPicture =& $struct["productPicture"]->value();

    $product = eZProduct::getByRemoteID ( $remoteID );
   
    if ( !$productPictureName or !$remoteID or !$productPicture )
        return new eZXMLRPCInt( 0 );

    $product = eZProduct::getByRemoteID ( $remoteID );

    if ( !$product )
        return new eZXMLRPCInt( 0 );

    $productID = $product->id();

    if ( $productPictureName )
    {
        if ( is_file( "tmp/" . $productPictureName ) )
            unlink( "tmp/" . $productPictureName );
        
        $images =& $product->images();
        
        if ( count ( $images ) > 0 )
        {
            foreach( $images as $image )
            {
                $product->deleteImage( $image );
                $image["Image"]->delete();
            }
        }

        if ( is_file( "tmp/" . $productPictureName ) == false )
        {
            $filePath = "tmp/" . $productPictureName;
            $fp = fopen( $filePath, "w+" );
            fwrite( $fp, $productPicture );
            fclose( $fp );

            if ( is_file( "tmp/" . $productPictureName ) && ( filesize( "tmp/". $productPictureName ) != 0 ) && ( $productPictureName != ".jpg" ) )
            {
                $file = new eZImageFile();
                if ( $file->getFile( "tmp/" . $productPictureName ) )
                {
                    $image = new eZImage();
                    $image->setImage( &$file );
                    $image->setName( $productName );
                    $image->store();

                    addImage( $image, $product );
                }
            }
        }
    }
    return new eZXMLRPCInt( $productID );
}

/*
function unavailable( $value )
{
    $value = $value[0];
    $remoteIDObjects = $value->value();
    $count = 0;
    foreach( $remoteIDObjects as $remoteObj )
    {
        $remoteID = $remoteObj->value();
        $product = new eZProduct( );
        $product = $product->getByRemoteID( $remoteID );
        if ( is_object ( $product ) )
        {
            $product->setTotalQuantity( 0 );
            $product->store();
            $count++;
        }
    }
    return new eZXMLRPCInt( $count );    
}
*/

function unavailable( $value )
{
    $value = $value[0];
    $remoteIDObjects = $value->value();
    $productCount = 0;
    $optionCount = 0;
    foreach( $remoteIDObjects as $remoteObj )
    {
        $option = false;
        $product = false;
        $remoteID = $remoteObj->value();
        $newProduct = new eZProduct( );
        $product = $newProduct->getByRemoteID( $remoteID );
        if ( is_object ( $product ) )
        {
            $product->setTotalQuantity( 0 );
            $product->store();
            $productCount++;
        }
    }
    return new eZXMLRPCInt( $productCount );
}

function clearcache(  )
{
    system( "./clearcache.sh" );
    return new eZXMLRPCInt( 1 );   
}

// Add a product to the rigth categories.
function &addToGroup( $groupName, $product, $parentName, $design, $material, $parentCheck )
{
    $db =& eZDB::globalDatabase();
    $db->array_query( $checkArray, "SELECT ID FROM eZTrade_Category WHERE RemoteID='$groupName'" );

    // Make the top level
    $matArray = createIfNotExists( "Material", 0 );
    $mat = $matArray[0];
    
    $placeArray = createIfNotExists( "Produkt", 0 );
    $place = $placeArray[0];

    // Create the parent
    $parentArray = createIfNotExists( $parentName, $place->id() );
    $parent = $parentArray[0];
    $parentID = $parent->id();
    
    if ( $parentCheck )
    {
        $db->array_query( $checkArray, "SELECT ID FROM eZTrade_Category WHERE Parent='$parentID' AND RemoteID='$groupName'" );
    }

    $categoryID = $checkArray[0]["ID"];

    if ( count ( $checkArray ) == 0 )
    {
        $category = new eZProductCategory();
        $category = $category->getByRemoteID( $groupName, $parentID );

        if ( ( get_class( $category ) != "ezproductcategory" ) || ( $category->id() == 0 ) )
        {
            $category = new eZProductCategory();
            $category->setRemoteID( $groupName );
        }

        include( "translategroup.php" );
        
        $category->setName( $groupName );
        $category->setParent( $parent );
        $category->store();

        unset( $categoryID );
        $categoryID = $category->id();

        $categoryCreated = true;
    }
    elseif ( count ( $checkArray == 1 ) )
    {
        include( "translategroup.php" );
        $productCategory = new eZProductCategory( $categoryID );
        $productCategory->setName ( $groupName );
        $productCategory->store();
    }

    $productCategory = new eZProductCategory( $categoryID );

    if ( $product )
    {
        $product->setCategoryDefinition( $productCategory );

        addProductToGroup( $productCategory, $product, true );

        // Add the product to the material groups
        $matCategoryArray = array();
        $material = translateCategory( $material );
        switch( $material )
        {
            // Gold
            case "B08":
            case "R07":
            case "K01":
            case "A04":
            case "A07":
            case "H01":
            case "H06":
            case "O01":
            case "O04":
            case "O07":
            case "N01":
            case "K02":
            case "R05":
            case "B01":
            case "B02":
            case "S02":
            case "B03":
            case "B05":
            case "B06":
            case "B07":
            case "B04":
            {
                // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Gold" );
                $matCatArray = createIfNotExists( "Gold", $mat->id() );
                $matCat = $matCatArray[0];
                $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
            }
            break;

            // Diamond
            case "D01":
            case "D02":
            case "D03":
            case "R06":
            case "A02":
            case "H02":
            case "H07":
            case "O02":
            case "D08":
            case "N02":
            case "K02":
            case "R01":
            case "R02":
            case "R03":
            case "D04":
            case "A05":
            case "D05":
            case "D06":
            case "D07":
            case "O05":
            case "D09":
            {
                // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Diamond" );
                $matCatArray = createIfNotExists( "Diamant", $mat->id() );
                $matCat = $matCatArray[0];
                $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
            }
            break;

            // Ketten
            case "A01":
            case "H04":
            case "H05":
            case "S05":
            case "S01":
            case "S04":
            case "S03":
            {
                // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Ketten" );
                $matCatArray = createIfNotExists( "Ketten", $place->id() );
                $matCat = $matCatArray[0];
                $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
            }
            break;


            // Others
            case "T08":
            case "R04":
            case "N03":
            case "T04":
            case "H03":
            case "T02":
            case "T02":
            case "H08":
            case "T03":
            case "T01":
            case "H09":
            case "H10":
            case "O03":
            case "T05":
            case "O06":
            case "T06":
            case "T07":
            case "K02":
            {
                $others = true;
            }
        }

        switch( $design )
        {
            // Saphire
            case "SA":
            {
                // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Saphire" );
                $matCatArray = createIfNotExists( "Safir", $mat->id() );
                $matCat = $matCatArray[0];
                $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
            }
            break;

            // Bluetopas
            case "BT":
            {
                // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Bluetopas" );
                $matCatArray = createIfNotExists( "Blautopas", $mat->id() );
                $matCat = $matCatArray[0];
                $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
            }
            break;

            // Zirkonia
            case "ZI":
            {
                // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Zirkonia" );
                $matCatArray = createIfNotExists( "Zirkonia", $mat->id() );
                $matCat = $matCatArray[0];
                $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
                
            }
            break;

            // Pearls
            case "AY":
            case "BW":
            case "TA":
            {
                // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Pearls" );
                $matCatArray = createIfNotExists( "Perlen", $mat->id() );
                $matCat = $matCatArray[0];
                $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
            }
            break;
            

            default:
            {
                if ( $others )
                {
                    // eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Others" );
                    $matCatArray = createIfNotExists( "Weitere", $mat->id() );
                    $matCat = $matCatArray[0];
                    $matCategoryArray[] = array( $matCat, $material, $matCatArray[1] );
                }
            }
        }


        // Add the product in the right material category
        foreach( $matCategoryArray as $matCategory )
        {
            if ( get_class( $matCategory[0] ) == "ezproductcategory" )
            {

                $tmp = $product->categoryDefinition();
                $name = $tmp->name();


                $parentMaterial = createIfNotExists( $name, $matCategory[0]->id(), $matCategory[1], false, true );

                $parentMaterial = $parentMaterial[0];

                $parent = $parentMaterial->parent();
                
                addProductToGroup( $parentMaterial, $product );

                if ( $matCategory[2] == true )
                {
//                    addProductToGroup( $parent, $product );
                }
            }
        }
    }
    
    return $productCategory;
}

// Translate a string from a .ini file
function translate( $name, $file )
{
    $ini = new INIFile( "translate/" . $file );

    $ret = false;
    
    $ret = $ini->read_var( "strings", $name );

    // eZLog::writeNotice( "Translate: from " . $name . " to " . $ret );

    return $ret;
}

// Insert a product to the a category.
function addProductToGroup( $group, $product, $checkProduct=false )
{
    if ( ( get_class( $group ) == "ezproductcategory" ) && ( get_class( $product ) == "ezproduct" ) )
    {
        $groupID = $group->id();
        $productID = $product->id();
        $db =& eZDB::globalDatabase();
        
        $db->array_query( $checkArray, "SELECT CategoryID FROM eZTrade_ProductCategoryLink WHERE ProductID='$productID' AND CategoryID='$groupID'" );

        if ( count ( $checkArray ) > 0 )
        {
            return new eZProductCategory( $checkArray[0]["CategoryID"] );
        }
        else
        {
            // eZLog::writeNotice( "Category: Added product to category " . $group->name() );
            $group->addProduct( $product );
            return true;
        }
    }
}

// Add a product to a category. Only insert the product if there is no other product that have the same category.
function addToCategoryIfNotExists( $category, $product )
{
    $categoryID = $category->id();
    $productID = $product->id();

    $db =& eZDB::globalDatabase();

    $db->array_query( $productList, "SELECT ProductID FROM eZTrade_ProductCategoryLink WHERE CategoryID='$categoryID'" );

    if ( count ( $productList ) > 0 )
    {
        foreach( $productList as $productItem )
        {
            $categories = $product->categories();
            
            foreach ( $categories as $categoryItem )
            {
                if ( $categoryID != $categoryItem->id() )
                {
                    $category->addProduct( $product );
                }
            }
        }
    }
    else
    {
        $category->addProduct( $product );
    }

}

// Add a image to the product.
function addImage( $image, $product )
{
    if ( ( get_class( $image ) == "ezimage" ) && ( get_class( $product ) == "ezproduct" ) )
    {
        $imageID = $image->id();
        $productID = $product->id();
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $checkArray, "SELECT ImageID FROM eZTrade_ProductImageLink WHERE ProductID='$productID' AND ImageID='$imageID'" );

        if ( count ( $checkArray ) > 0 )
        {
            return new eZImage( $checkArray[0]["ImageID"] );
        }
        else
        {
            $product->addImage( $image );
            $product->setThumbnailImage( $image );
            $product->setMainImage( $image );
        }
    }
   
}

// Create a category if its not exists.
function createIfNotExists( $categoryName, $parentID, $remoteID=0, $checkParent=false, $checkRemoteID=false )
{
    $db =& eZDB::globalDatabase();

    if ( $checkParent )
        $db->array_query( $categoryArray, "SELECT ID, Name FROM eZTrade_Category WHERE Name='$categoryName' AND Parent='$parentID'" );
    else
        $db->array_query( $categoryArray, "SELECT ID, Name FROM eZTrade_Category WHERE Name='$categoryName'" );

    if ( $checkRemoteID )
    {
        $db->array_query( $categoryArray, "SELECT ID, Name FROM eZTrade_Category WHERE RemoteID='$remoteID' AND Parent='$parentID'" );
        if ( count ( $categoryArray ) > 0 )
            $check = true;
    }

    foreach( $categoryArray as $cat )
    {
        if ( $cat["Name"] == $categoryName )
        {
            $check = true;
        }
    }

    if ( $check )
    {
        $object = new eZProductCategory( $categoryArray[0]["ID"] );
        $object->setName( $categoryName );
        $object->store();
        $ret = array( $object, true );
        return $ret;
    }
    else
    {
        $category = new eZProductCategory();
        $category->setName( $categoryName );
        $category->setParent( $parentID );
        $category->setRemoteID ( $remoteID );
        $category->store();

        // eZLog::writeNotice( "Category: Stored " . $categoryName . " to the database" );

        $ret = array( $category, false );
        return $ret;
    }
}

// Return the category name for a shortnamed category.
function belongsTo( $category )
{
    $ringArray = array( "B08", "D01", "D02", "D03", "T08", "R06", "R07", "K01", "R05", "R01", "R02", "R03", "R04" );
    $halsArray = array( "H01", "H02", "H03", "H04", "H05", "H06", "H07", "H08", "H09", "H10", "S05", "S02", "D05", "T02", "S04", "S03", "B03", "D06", "T03" );
    $armArray = array( "A01", "A02", "A03", "A04", "A07", "S01", "D04", "T01", "B01", "B02", "A05" );
    $orhArray = array( "O01", "O02", "O03", "O04", "O06", "O07", "D08", "T07", "B05", "D07", "T05", "B06", "T05", "B06", "T06", "B07", "O05" );
    $ansteArray = array( "K02", "N01", "N02", "N03", "B04", "D09", "T04" );
    $ketten = array( "A01", "S01", "H04", "S04", "H05", "S03", "S05" );

    if ( isPartOf( $ringArray, $category ) )
    {
        $ret[] = "Ringe";
    }
    if ( isPartOf( $halsArray, $category ) )
    {
        $ret[] = "Halsschmuck";
    }
    if ( isPartOf( $ketten, $category ) )
    {
        $ret[] = "Ketten";
    }
    if ( isPartOf( $armArray, $category ) )
    {
        $ret[] = "Armschmuck";
    }
    if ( isPartOf( $ansteArray, $category ) )
    {
        $ret[] = "Ansteckschmuck";
    }
    if ( isPartOf( $orhArray, $category ) )
    {
        $ret[] = "Ohrschmuck";
    }

    return $ret;
}

// check if a category is a part of the categoryArray
function isPartOf( $array=array(), $categoryName )
{
    foreach( $array as $arrayItem )
    {
        if ( $arrayItem == $categoryName )
        {
            return true;
        }
    }
    return false;
}

// Rename categoryies.
function translateCategory( $categoryName )
{
    switch( $categoryName )
    {
        case "R05":
            $categoryName = "B08";
        break;
        case "R01":
            $categoryName = "D01";
        break;
        case "R02":
            $categoryName = "D02";
        break;
        case "R03":
            $categoryName = "D03";
        break;
        case "R04":
            $categoryName = "T08";
        break;
        case "S02":
            $categoryName = "H01";
        break;
        case "D05":
            $categoryName = "H02";
        break;
        case "T02":
            $categoryName = "H03";
        break;
        case "S04":
            $categoryName = "H04";
        break;
        case "S03":
            $categoryName = "H05";
        break;

        // new
        case "B03":
            $categoryName = "H06";
        break;
        case "D06":
            $categoryName = "H07";
        break;
        case "T03":
            $categoryName = "H08";
        break;
        case "A05":
            $categoryName = "D04";
        break;
        case "B07":
            $categoryName = "O07";
        break;
        case "O05":
            $categoryName = "O02";
        break;
        // new end
        
        case "S01":
            $categoryName = "A01";
        break;
        case "D04":
            $categoryName = "A02";
        break;
        case "T01":
            $categoryName = "A03";
        break;
        case "B01":
            $categoryName = "A04";
        break;
        case "B02":
            $categoryName = "A07";
        break;
        case "B05":
            $categoryName = "O01";
        break;
        case "D07":
            $categoryName = "O02";
        break;
        case "T05":
            $categoryName = "O03";
        break;
        case "B06":
            $categoryName = "O04";
        break;
        case "T06":
            $categoryName = "O06";
        break;
        case "B04":
            $categoryName = "N01";
        break;
        case "D09":
            $categoryName = "N02";
        break;
        case "T04":
            $categoryName = "N03";
        break;
    }
    
    return $categoryName;
}

// Add to category
function addToCategory ( $categoryID, $product )
{
    $db =& eZDB::globalDatabase();

    $category = new eZProductCategory( $categoryID );

    $category->addProduct( $product );
    $product->setCategoryDefinition( $category );

    // eZLog::writeNotice( "Category: Added product to category " . $category->name() );
    return true;
}


// Insert the product.
function insert( $args )
{
//          print_r ( $args );
//          return new eZXMLRPCInt( $productID );
    $data =&$args[0];

    $struct = $data->value();

    $ID =& $struct["productID"]->value();
    $productNumber =& $struct["productNumber"]->value();
    $productIsHotDeal =& $struct["productIsHotDeal"]->value();

    $productPicture =& $struct["productPicture"]->value();
    $productPictureName =& $struct["productPictureName"]->value();

    $productRemoteID =& $struct["productRemoteID"]->value();
    $productCategory =& $struct["productCategory"]->value();
    $productNumber =& $struct["productNumber"]->value();
    $productGroesse =& $struct["productGroesse"]->value();
    
    $productName =& $struct["productName"]->value();

    $productShortDescription =& $struct["productShortDescription"]->value();
    $productDescription =& $struct["productDescription"]->value();
    $productPrice =& $struct["productPrice"]->value();
    $productShowPrice =& $struct["productShowPrice"]->value();
    $productTotalQuantity =& $struct["productTotalQuantity"]->value();

    $attributeDesign =& $struct["attributeDesign"]->value();
    $attributeAlloy =& $struct["attributeAlloy"]->value();
    $attributeGoldColor =& $struct["attributeGoldColor"]->value();
    $attributeTotalWeight =& $struct["attributeTotalWeight"]->value();
    
    $attributeDia1Schliff =& $struct["attributeDia1Schliff"]->value();
    $attributeDia1Karat =& $struct["attributeDia1Karat"]->value();
    $attributeDia1Farbe =& $struct["attributeDia1Farbe"]->value();
    $attributeDia1Reinheit =& $struct["attributeDia1Reinheit"]->value();

    $attributeDia2Schliff =& $struct["attributeDia2Schliff"]->value();
    $attributeDia2Karat =& $struct["attributeDia2Karat"]->value();
    $attributeDia2Farbe =& $struct["attributeDia2Farbe"]->value();
    $attributeDia2Reinheit =& $struct["attributeDia2Reinheit"]->value();

    $attributeFst1Bezeichnung =& $struct["attributeFst1Bezeichnung"]->value();
    $attributeFst2Bezeichnung =& $struct["attributeFst2Bezeichnung"]->value();
    $attributeFst3Bezeichnung =& $struct["attributeFst3Bezeichnung"]->value();

    $oldDesign =& $struct["oldDesign"]->value();

    $oldCategoryName = $productCategory;
    
    $productCategory = translateCategory( $productCategory );

    if ( $attributeDia1Reinheit )
        $attributeDia1Reinheit = translate( $attributeDia1Reinheit, "diamondclear.ini" );

    if ( $attributeDia2Reinheit )
        $attributeDia2Reinheit = translate( $attributeDia2Reinheit, "diamondclear.ini" );

    $options = $struct["productOptions"]->value();

    unset( $product );
    $product = new eZProduct( );

    unset( $remoteProduct );

    $remoteProduct = $product->getByRemoteID( $productRemoteID );

    $update = false;
    if ( get_class( $remoteProduct ) == "ezproduct" )
    {
        $update = true;
        $product = "";
        $product = $remoteProduct;
    }

    $contents[0] = $productShortDescription;
    $contents[1] = $productDescription;

    $generator = new eZArticleGenerator();
    $contents =& $generator->generateXML( $contents );


    // Create the product and store it.
    $product->setName( $productName );
    $product->setContents( $contents );
    $product->setProductNumber( $productNumber );
    $product->setPrice( 0 );
    $product->setRemoteID( $productRemoteID );
    $vatType = new eZVatType( 1 );
    $product->setVATType( $vatType );
    $ship = new eZShippingGroup( 1 );
    $product->setShippingGroup( $ship );
    $product->setIncludesVAT( true );
    $product->store();
    $productID = $product->id();

    $product->setTotalQuantity( $productTotalQuantity );
    // set hot deal
    if ( $productIsHotDeal )
    {
        $nummer = $product->productNumber();

        // eZLog::writeNotice( "HotDeal: Added product " . $nummer . " to hotdeal" );
        $product->setIsHotDeal( true );
        $product->setTotalQuantity( false );
	$product->setDiscontinued( false );
    }
    else
    {
        $product->setIsHotDeal( false );
    }

    // If the product has options, dont show the price.
    if ( ( $struct["productShowPrice"]->value() == true ) || ( count ( $options ) == 1 ) )
    {
        $product->setPrice( $productPrice );
        $product->store();
    }

    // Add options for this product
    if ( count ( $options ) > 0 )
    {
        $productOptions = $product->options();

        foreach( $productOptions as $option )
        {
            $option->delete();
        }
        unset( $option );
        $option = new eZOption();
        $option->setName( "Groesse" );
        $option->setDescription( $productDescription );
        $option->store();
        $product->addOption( $option );

        $checkOptionPriceStruct = $options[0]->value();
        $checkOptionPrice = $checkOptionPriceStruct["VKbrutto"]->value();
        // Add the options values.
        foreach( $options as $optionItem )
        {
            $optionStruct = $optionItem->value();
            if ( $optionStruct["VKbrutto"]->value() != $checkOptionPrice )
            {
                $optionShowPrice = true;
            }
        }

        $optionItem = "";
        // Add the options values.
        foreach( $options as $optionItem )
        {
            $optionStruct = $optionItem->value();
            
            $value = new eZOptionValue();

            $value->setRemoteID( $optionStruct["RemoteID"]->value() ); 

            if ( $struct["productShowPrice"]->value() == false )
            {
                if ( $optionShowPrice )
                {
                    $value->setPrice( $optionStruct["VKbrutto"]->value() );
                }
                else
                {
                    $product->setPrice( $productPrice );
                    $product->store();

                    $value->setPrice( 0 );
                }
            }
            else
            {
                $value->setPrice( 0 );
            }

            $option->addValue( $value );
            
            $value->addDescription( $optionStruct["Groesse"]->value() );
        
            if ( $optionStruct["TotalQuentity"]->value() > 0 )
            {
                $value->setTotalQuantity( $optionStruct["TotalQuentity"]->value() );
                $product->setTotalQuantity( -1 );
            }
            else
            {
                $value->setTotalQuantity( -1 );
            }
        }
    }
    if( count( $options ) == 1 )
    {
        $product->setPrice( $productPrice );
        $product->store();

        $value->setPrice( 0 );
    }

    // Find out what category this product belongs to.
    $parents = belongsTo( $productCategory );

    // Add the product to the group.

    if ( count ( $parents ) > 1 )
    {
        foreach ( $parents as $parent )
        {
            $category = addToGroup( $productCategory, $product, $parent, $oldDesign, $oldCategoryName, true );
        }
    }
    elseif ( count ( $parents ) == 1 )
        $category = addToGroup( $productCategory, $product, $parents[0], $oldDesign, $oldCategoryName, true );

    if ( $productPicture && $productPicutreName != "NoUpdate" )
    {
        if ( is_file( "tmp/" . $productPictureName ) )
            unlink( "tmp/" . $productPictureName );

        if ( ( is_file( "tmp/" . $productPictureName ) == false ) && !$update )
        {
            $filePath = "tmp/" . $productPictureName;
            $fp = fopen( $filePath, "w+" );
            fwrite( $fp, $productPicture );
            fclose( $fp );

            if ( is_file( "tmp/" . $productPictureName ) && ( filesize( "tmp/". $productPictureName ) != 0 ) && ( $productPictureName != ".jpg" ) )
            {
                $file = new eZImageFile();
                if ( $file->getFile( "tmp/" . $productPictureName ) )
                {
                    $image = new eZImage();
                    $image->setImage( &$file );
                    $image->setName( $productName );
                    $image->store();

                    addImage( $image, $product );
                }
            }
        }
    }


    // Set the product type
    $type = new eZProductType( 1 );
    $product->setType( $type );

    // Set all the attributs.
    $attribue = new eZProductAttribute();
    
    $attribue->get( 1 );
    $attribue->setValue( $product, $attributeTotalWeight );
    
    $attribue->get( 2 );
    $attribue->setValue( $product, $attributeGoldColor );
    
    $attribue->get( 3 );
    $attribue->setValue( $product, $attributeAlloy );
    
//      $attribue->get( 4 );
//      $attribue->setValue( $product, $attributeDesign );

    $attribue->get( 5 );
    $attribue->setValue( $product, $attributeDia1Schliff );

    $attribue->get( 6 );
    $attribue->setValue( $product, $attributeDia1Karat );

    $attribue->get( 7 );
    $attribue->setValue( $product, $attributeDia1Farbe );

    $attribue->get( 8 );
    $attribue->setValue( $product, $attributeDia1Reinheit );
    
    $attribue->get( 9 );
    $attribue->setValue( $product, $attributeDia2Schliff );

    $attribue->get( 10 );
    $attribue->setValue( $product, $attributeDia2Karat );

    $attribue->get( 11 );
    $attribue->setValue( $product, $attributeDia2Farbe );

    $attribue->get( 12 );
    $attribue->setValue( $product, $attributeDia2Reinheit );

    $attribue->get( 13 );
    $attribue->setValue( $product, $attributeFst1Bezeichnung );

    $attribue->get( 14 );
    $attribue->setValue( $product, $attributeFst2Bezeichnung );

    $attribue->get( 15 );
    $attribue->setValue( $product, $attributeFst3Bezeichnung );

    if ( $setSizeAttribute )
    {
        $attribue->get( 16 );
        $attribue->setValue( $product, $productGroesse );
    }

//    if ( $update )
//        eZLog::writeNotice( "Product: Updated product " . $product->productNumber() . " to the database" );
//    else
//        eZLog::writeNotice( "Product: Added product " . $product->productNumber() . " to the database" );

//    system( "./clearcache.sh" );
// deleteCache( $product );
    
    return new eZXMLRPCInt( $productID );
}

function deleteCache( $ProductID )
{
    if ( get_class( $ProductID ) == "ezproduct" )
    {
        $CategoryID =& $ProductID->categoryDefinition( false );
        $CategoryArray =& $ProductID->categories( false );
        $Hotdeal = $ProductID->isHotDeal();
        $ProductID = $ProductID->id();
    }

    $files = eZCacheFile::files( "eztrade/cache/", array( array( "productview", "productprint" ),
                                                          $ProductID, $CategoryID ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }
    $files = eZCacheFile::files( "eztrade/cache/", array( "productlist",
                                                          array_merge( $CategoryID, $CategoryArray ) ),
                                 "cache", "," );
    foreach ( $files as $file )
    {
        $file->delete();
    }
    if ( $Hotdeal )
    {
        $files = eZCacheFile::files( "eztrade/cache/", array( "hotdealslist", NULL ),
                                     "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
    }
}


function assignToCategoies( )
{
    $db =& eZDB::globalDatabase();
    $category = new eZProductCategory();
   
    $categories = $category->getTree();
    foreach ( $categories as $categoryItem )
    {
        $level = $categoryItem[1];
        if ( $level == 2 )
        {
            $category = new eZProductCategory( $categoryItem[0]->id() );
            $categoryChildrens = $category->getByParent( $categoryItem[0] );

            // Get the products that we will check on
            $products = $category->products();
            if ( count ( $categoryChildrens ) > 0 )
            {
                foreach ( $categoryChildrens as $categoryChildren )
                {
                    $categoryID = $categoryChildren->id();
                    $createProduct = true;
                    if ( count ( $products ) > 0 )
                    {
                        $createProduct = true;
                        foreach ( $products as $product )
                        {
                            $productID = $product->id();
                            $db->array_query( $checkArray, "SELECT ProductID FROM eZTrade_ProductCategoryLink WHERE ProductID='$productID' AND CategoryID='$categoryID'" );

                            if ( count ( $checkArray ) == 1 )
                            {
                                $createProduct = false;
                            }
                        }
                    }
                    if ( $createProduct )
                    {
                        $products = $categoryChildren->products();
                        $category->addProduct( $products[0] );
                    }
                }
            }
        }
    }
    return new eZXMLRPCInt( $categoryID );
}

function passiv( $bufferArray )
{
    $data =& $bufferArray[0];

    $data = $data->value();
    $db = eZDB::globalDatabase();

    $product = new eZProduct();
    $optionValue = new eZOptionValue();
    
    $db->array_query( $productList, "SELECT ID, RemoteID FROM eZTrade_Product" );
    $db->array_query( $optionValueList, "SELECT ID, RemoteID FROM eZTrade_OptionValue" );
    $i = 0;
    
foreach ( $data as $buffer )
    {
$match = false;
        $buffer = $buffer->value();
        

        foreach( $productList as $productItem )
        {
            $remoteArray = explode( "-", $productItem["RemoteID"] );
            if ( $remoteArray[3] )
            {
                if ( $buffer == $remoteArray[3] )
                {
                    $product_array[] = $buffer;
                    $product->get( $productItem["ID"] );
                    $product->setTotalQuantity( 0 );
			
                    $match = true;
                }
            }
        }

	if ( !$match )
{
        foreach( $optionValueList as $optionItem )
        {
            $remoteArray = explode( "-", $optionItem["RemoteID"] );

            if ( $remoteArray[3] )
            {
                if ( $buffer == $remoteArray[3] )
                {
                    $option_array[] = $buffer;
                
                    $optionValue->get( $optionItem["ID"] );
                    $optionValue->setTotalQuantity( 0 );
                }
            }

        }
}
        $i++;
    }
 system( "./clearcache.sh" );  
   return new eZXMLRPCInt( 1 );
}

ob_end_flush();
exit();


?>

