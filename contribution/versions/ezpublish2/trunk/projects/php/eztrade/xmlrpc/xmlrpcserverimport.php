<?
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
include_once( "ezimagecatalogue/classes/ezimage.php" );

$server = new eZXMLRPCServer( );

$server->registerFunction( "insert", array( new eZXMLRPCStruct() ) );

$server->processRequest();

// Check if the the category exists, if not, create the category. And add the product to this current category.
function &addToGroup( $groupName, $product, $parentName, $design, $material )
{
    $db =& eZDB::globalDatabase();
    $db->array_query( $checkArray, "SELECT ID FROM eZTrade_Category WHERE RemoteID='$groupName'" );

    // Make the top level
    $mat = createIfNotExists( "Material", 0 );
    $place = createIfNotExists( "Place", 0 );

    // Create the parent
    $parent = createIfNotExists( $parentName, $place->id() );

    $categoryID = $checkArray[0]["ID"];

    if ( count ( $checkArray ) == 0 )
    {
        $category = new eZProductCategory();
        $category = $category->getByRemoteID( $groupName );

        if ( ( get_class( $category ) != "ezproductcategory" ) || ( $cateogry->id() == 0 ) )
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
    $productCategory = new eZProductCategory( $categoryID );

    if ( $product )
    {
        $product->setCategoryDefinition( $productCategory );

        addProductToGroup( $productCategory, $product );

        if ( $categoryCreated )
        {
            addProductToGroup( $parent, $product );

            $parentTmp = $parent->parent();
            
            addToCategoryIfNotExists( $parentTmp, $product );
        }

        // Add the product to the material groups
        $matCategoryArray = array();
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
                /* Old gold material groups
                   case "B01":
                   case "B02":
                   case "B03":
                   case "B04":
                   case "B05":
                   case "B06":
                   case "B07":
                   case "B08":
                   case "B09":
                */
            {
                eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Gold" );
                $matCat = createIfNotExists( "Gold", $mat->id() );
                $matCategoryArray[] = array( $matCat, $material );
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
                /* Old diamond material groups
                   case "D01":
                   case "D02":
                   case "D03":
                   case "D04":
                   case "D05":
                   case "D06":
                   case "D07":
                   case "D08":
                   case "D09":
                */
            {
                eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Diamond" );
                $matCat = createIfNotExists( "Diamond", $mat->id() );
                $matCategoryArray[] = array( $matCat, $material );
            }
            break;

            // Ketten
            case "A01":
            case "H04":
            case "H05":
            case "S05":
            {
                eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Ketten" );
                $matCat = createIfNotExists( "Ketten", $mat->id() );
                $matCategoryArray[] = array( $matCat, $material );
            }
            break;
            default:
            {
                $noCategory = true;
            }
        }

        switch( $design )
        {
            // Saphire
            case "SA":
            {
                eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Saphire" );
                $matCat = createIfNotExists( "Saphire", $mat->id() );
                $matCategoryArray[] = array( $matCat, $material );
            }
            break;

            // Bluetopas
            case "BT":
            {
                eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Bluetopas" );
                $matCat = createIfNotExists( "Bluetopas", $mat->id() );
                $matCategoryArray[] = array( $matCat, $material );
            }
            break;

            // Zirkoina
            case "ZI":
            {
                eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Zirkonia" );
                $matCat = createIfNotExists( "Zirkonia", $mat->id() );
                $matCategoryArray[] = array( $matCat, $material );
                
            }
            break;

            // Pearls
            case "AY":
            case "BW":
            case "TA":
            {
                eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Pearls" );
                $matCat = createIfNotExists( "Pearls", $mat->id() );
                $matCategoryArray[] = array( $matCat, $material );
            }
            break;
            
            default:
            {
                if ( $noCategory )
                {
                    eZLog::writeNotice( "Material: Added product " . $product->productNumber() . " to Others" );
                    $matCat = createIfNotExists( "Others", $mat->id() );
                    $matCategoryArray[] = array( $matCat, $material );
                }
            }
        }

        foreach( $matCategoryArray as $matCategory )
        {
            if ( get_class( $matCategory[0] ) == "ezproductcategory" )
            {
                $tmp = $product->categoryDefinition();
                $name = $tmp->name();

                $parentMaterial = createIfNotExists( $name, $matCategory[0]->id(), $matCategory[1], true );

                $parent = $parentMaterial->parent();
                
                addProductToGroup( $parentMaterial, $product );
            }
        }
    }
    
    return $productCategory;
}

function translate( $name, $file )
{
    $ini = new INIFile( "translate/" . $file );

    $ret = false;
    
    $ret = $ini->read_var( "strings", $name );

    eZLog::writeNotice( "Translate: from " . $name . " to " . $ret );

    return $ret;
}

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
            eZLog::writeNotice( "Category: Added product to category " . $group->name() );
            $group->addProduct( $product );
            return true;
        }
    }
}

function addToCategoryIfNotExists( $category, $product )
{
    $categoryID = $category->id();
    $productID = $product->id();

    $db =& eZDB::globalDatabase();

    $db->array_query( $categoryListFromProduct, "SELECT ProductID FROM eZTrade_ProductCategoryLink WHERE CategoryID='$categoryID'" );

    foreach( $productList as $productItem )
    {
        $categories = $product->categories();

        foreach ( $categories as $categoryItem )
        {
            if ( $categoryID == $categoryItem["ID"] )
            {
                $category->addProduct( $category, $product );
            }
        }
    }
}

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

function createIfNotExists( $categoryName, $parentID, $remoteID=0, $checkParent=false )
{
    $db =& eZDB::globalDatabase();

    if ( $checkParent )
        $db->array_query( $categoryArray, "SELECT ID, Name FROM eZTrade_Category WHERE Name='$categoryName' AND Parent='$parentID'" );
    else
        $db->array_query( $categoryArray, "SELECT ID, Name FROM eZTrade_Category WHERE Name='$categoryName'" );

    foreach( $categoryArray as $cat )
    {
        if ( $cat["Name"] == $categoryName )
        {
            $check = true;
        }
    }

    if ( $check )
    {
        return new eZProductCategory( $categoryArray[0]["ID"] );
    }
    else
    {
        $category = new eZProductCategory();
        $category->setName( $categoryName );
        $category->setParent( $parentID );
        $category->setRemoteID ( $remoteID );
        $category->store();

        eZLog::writeNotice( "Category: Stored " . $categoryName . " to the database" );

        return $category;
    }
}

function belongsTo( $category )
{
    $ringArray = array( "B08", "D01", "D02", "D03", "T08", "R06", "R07", "K01" );
// Gamle ringer
//    $halsArray = array( "H01", "H02", "H03", "H04", "H05", "H06", "H07", "H07", "H08", "H09", "H10", "H11", "H12", "S05" );
    $halsArray = array( "H01", "H02", "H03", "H04", "H05", "H06", "H07", "H07", "H08", "H09", "H10", "S05" );
//    $armArray = array( "A01", "A02", "A03", "A04", "A05", "A06", "A07" );
    $armArray = array( "A01", "A02", "A03", "A04", "A07" );
//    $orhArray = array( "O01", "O02", "O03", "O04", "O05", "O06", "O07", "O08", "B07", "D08", "T07" );
    $orhArray = array( "O01", "O02", "O03", "O04", "O06", "O07", "D08", "T07" );
    $ansteArray = array( "K02", "N01", "N02", "N03" );

    if ( isPartOf( $ringArray, $category ) )
    {
        return "Rings";
    }
    if ( isPartOf( $halsArray, $category ) )
    {
        return "Halsschmuck";
    }
    if ( isPartOf( $armArray, $category ) )
    {
        return "Armschmuck";
    }
    if ( isPartOf( $orhArray, $category ) )
    {
        return "Orhschmuck";
    }
    if ( isPartOf( $ansteArray, $category ) )
    {
        return "Ansteckschmuck";
    }
}

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

    eZLog::writeNotice( "Category: Added product to category " . $category->name() );
    return true;
}

function insert( $args )
{
//          print_r( $args );
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

    $options =& $struct["productOptions"]->value();

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

    // Create the product and store it.
    $product->setName( $productName );
    $product->setBrief( $productShortDescription );
    $product->setDescription( $productDescription );
    $product->setProductNumber( $productNumber );
    $product->setPrice( 0 );
    $product->setRemoteID( $productRemoteID );
    $vatType = new eZVatType( 1 );
    $product->setVATType( $vatType );
    $ship = new eZShippingGroup( 1 );
    $product->setShippingGroup( $ship );
    $product->store();
    $productID = $product->id();

    // set hot deal
    if ( $productIsHotDeal )
    {
        $nummer = $product->productNumber();

        eZLog::writeNotice( "HotDeal: Added product " . $nummer . " to hotdeal" );
        $product->setIsHotDeal( true );
    }
    else
    {
        $product->setIsHotDeal( false );
    }
    
    $product->setTotalQuantity( $productTotalQuantity );

    // If the product has options, dont show the price.
    if ( ( $struct["productShowPrice"]->value() == true ) || ( count ( $options ) == 1 ) )
    {
        $product->setPrice( $productPrice );
        $product->store();
    }

    // Add options for this product
    if ( count ( $options ) > 1 )
    {
        $productOptions =& $product->options();

        if ( get_class ( $productOptions[0] ) == "ezoption" )
        {
            $option =& $productOptions[0];
        }
        else
        {
            $option = new eZOption();
            $option->setName( "Groesse" );
            $option->setDescription( $productDescription );
            $option->store();
            $product->addOption( $option );
        }

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

            if ( $optionStruct["RemoteID"]->value() != false )
            {
                $remoteValue = $value->getByRemoteID( $optionStruct["RemoteID"]->value() );
            }
            
            if ( get_class ( $remoteValue ) == "ezoptionvalue" )
            {
                $value = $remoteValue;
            }
            else
            {
               $value->setRemoteID( $optionStruct["RemoteID"]->value() ); 
            }
            if ( $struct["productShowPrice"]->value() == false )
            {
                if ( $optionShowPrice )
                    $value->setPrice( $optionStruct["VKbrutto"]->value() );
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
                $value->setTotalQuantity( $optionStruct["TotalQuentity"]->value() );
        }
    }
    else
    {
        $setSizeAttribute = true;
    }

    // Find out what category this product belongs to.
    $parent = belongsTo( $productCategory );

    // Add the product to the group.
    $category = addToGroup( $productCategory, $product, $parent, $oldDesign, $oldCategoryName );

//      if ( $update == true )
//      {
//          $images = $product->images();

//          foreach( $images as $deleteImage )
//          {
//              $product->deleteImage( $deleteImage );
//              $deleteImage->delete();
//          }
//      }
    if ( $productPictureName && $update == false )
    {
        if ( is_file( "tmp/" . $productPictureName ) )
            unlink( "tmp/" . $productPictureName );


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

    // Set the product type
    $type = new eZProductType( 1 );
    $product->setType( $type );

    // Set all the attributs.
    $attribue = new eZProductAttribute();
    
    $attribue->get( 1 );
    $attribue->setValue( $product, $attributeTotalWeight . " g");
    
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

    if ( $update )
        eZLog::writeNotice( "Product: Updated product " . $product->productNumber() . " to the database" );
    else
        eZLog::writeNotice( "Product: Added product " . $product->productNumber() . " to the database" );
    
    return new eZXMLRPCInt( $productID );
}

?>

