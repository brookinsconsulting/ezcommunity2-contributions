<?
include_once( "classes/INIFile.php" );
include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproductattribute.php" );
include_once( "classes/ezfile.php" );
include_once( "classes/ezlog.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcclient.php" );
include_once( "ezxmlrpc/classes/ezxmlrpccall.php" );

include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcstruct.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "classes/ezmail.php" );

$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;
$db = eZDB::globalDatabase();
set_time_limit( 0 );

$bigLog = array();

function assignToCategoies()
{
return true;
//    $client = new eZXMLRPCClient( "www.mygold.com", "/trade/xmlrpcimport/" );
//    $client = new eZXMLRPCClient( "mygold.nox.ez.no", "/trade/xmlrpcimport/" );
    $call = new eZXMLRPCCall( );
    $call->setMethodName( "assignToCategoies" );

    $response = $client->send( $call );

    $result = $response->result();
    if ( $response->isFault() )
    {
        eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
        return false;
    }
    else
    {
        return true;
    }
}

$db->array_query( $logArray, "SELECT * FROM Log" );

function canAdd( $id=false )
{
    global $logArray;
    if ( is_string ( $id ) )
    {
        if ( in_array( $logArray, $id ) )
        {
            return false;
        }
        $db->query( "INSERT INTO Log set RemoteID='$id'" );
        return true;
    }
}

/*
 * Send the product to the xmlrpc server.
 */
function addProduct( $client, $data = array(), $options = array(), $showPrice = true )
{
//    $client = new eZXMLRPCClient( "mygold.nox.ez.no", "/trade/xmlrpcimport/" );
//   $client = new eZXMLRPCClient( "www.mygold.com", "/trade/xmlrpcimport/" );
    $client = new eZXMLRPCClient( "mygoldtest.ez.no", "/trade/xmlrpcimport/" );  
    $client->setLogin( "test" );
    $client->setPassword( "mygold" );
    $remoteID =& $data["RemoteID"];
    $productNumber =& $data["ProductNumber"];
    $isHotDeal =& $data["Werkstattinfo"];
    $imageName =& $data["Bildnummer"];
    $productName =& $data["Beschreibung"];
    $price =& $data["VKbrutto"];
    $alloy =& $data["Legierung"];
    $goldColor =& $data["Farbe"];
    $weight =& $data["Gesamtgewicht"];
    $reference =& $data["Referenz"];
    $category =& $data["Wg"];
    $groesse =& $data["Groesse"];

    $design =& $data["Design"];
    $imageName =& $data["Bildnummer"];

    $descArray = explode( ":-:", $data["Anmerkung"] );

    $shortDescription =& $descArray[0];

    $description =& $descArray[1];

    $dia1Carat =& $data["Dia1Karat"];
    $dia1Cut =& $data["Dia1Schliff"];
    $dia1Color =& $data["Dia1Farbe"];
    $dia1Clearity =& $data["Dia1Reinheit"];
    
    $dia2Carat =& $data["Dia2Karat"];
    $dia2Cut =& $data["Dia2Schliff"];
    $dia2Color =& $data["Dia2Farbe"];
    $dia2Clearity =& $data["Dia2Reinheit"];
    
    $gem1Description =& $data["Fst1Bezeichnung"];
    $gem2Description =& $data["Fst2Bezeichnung"];
    $gem3Description =& $data["Fst3Bezeichnung"];

    $productTotalQuantity = 1;

    $oldDesign = $design;
    
    // Set the gold color
    include( "goldcolor.php" );

    // Set the design
    include( "design.php" );

    // Set the diamond color
    include( "diamondcolor.php" );

    // Set the diamond cut
    include( "diamondcut.php" );

    $call = new eZXMLRPCCall( );
    $call->setMethodName( "insert" );

    // Add a image to the product, if exists.
    $imageFile = new eZFile();

    unset ( $content );
    if ( $imageFile->getFile( "/home/upload/bilder/" . $imageName . ".JPG" ) )
    {
        $filePath = "/home/upload/bilder/" . $imageName . ".JPG";
        $fp = fopen( $filePath, "r" );
        $fileSize = filesize( $filePath );
        $content =& fread( $fp, $fileSize );
        fclose( $fp );
    }        
    if ( !$content )
        return false;
    if ( $imageName )
        $imageName .= ".jpg";

    $paramenter = "";
    $paramenter = new eZXMLRPCStruct( array(
                                             //  product type for setting the correct attributes
                                             "productID" => new eZXMLRPCInt( $ID ),
                                             "productNumber" => new eZXMLRPCInt( $productNumber ),
                                             "productIsHotDeal" => new eZXMLRPCBool( $isHotDeal ),
                                             
                                             "productPicture" => new eZXMLRPCBase64( $content ),
                                             "productPictureName" => new eZXMLRPCString( $imageName ),

                                             "productRemoteID" => new eZXMLRPCString( $remoteID ),
                                             "productCategory" => new eZXMLRPCString( $category ),
                                             "productNumber" => new eZXMLRPCString( $productNumber ),
                                             "productTotalQuantity" => new eZXMLRPCInt( $productTotalQuantity ),
                                             "productGroesse" => new eZXMLRPCInt( $groesse ),

                                             "productName" => new eZXMLRPCString( $productName ),
                                             
                                             "productShortDescription" => new eZXMLRPCString( $shortDescription ),
                                             "productDescription" => new eZXMLRPCString( $description ),
                                             "productPrice" => new eZXMLRPCDouble( $price ),
                                             "productShowPrice" => new eZXMLRPCBool( $showPrice ),

                                             "attributeDesign" => new eZXMLRPCString( $design ),
                                             "attributeAlloy" => new eZXMLRPCInt( $alloy ),
                                             "attributeGoldColor" => new eZXMLRPCString( $goldColor ),
                                             "attributeTotalWeight" => new eZXMLRPCString( $weight ),
                                             "attributeSize" => new eZXMLRPCInt( $size ),
                                             
                                             "attributeDia1Schliff" => new eZXMLRPCString( $dia1Cut ),
                                             "attributeDia1Karat" => new eZXMLRPCString( $dia1Carat ),
                                             "attributeDia1Farbe" => new eZXMLRPCString( $dia1Color ),
                                             "attributeDia1Reinheit" => new eZXMLRPCString( $dia1Clearity ),

                                             "attributeDia2Schliff" => new eZXMLRPCString( $dia2Cut ),
                                             "attributeDia2Karat" => new eZXMLRPCString( $dia2Carat ),
                                             "attributeDia2Farbe" => new eZXMLRPCString( $dia2Color ),
                                             "attributeDia2Reinheit" => new eZXMLRPCString( $dia2Clearity ),
                                             
                                             "attributeFst1Bezeichnung" => new eZXMLRPCString( $gem1Description ),
                                             "attributeFst2Bezeichnung" => new eZXMLRPCString( $gem2Description ),
                                             "attributeFst3Bezeichnung" => new eZXMLRPCString( $gem3Description ),

                                             "oldDesign" => new eZXMLRPCString( $oldDesign ),                                             

                                             "productOptions" => new eZXMLRPCArray( $options )
                                             ));

    
    $call->addParameter( $paramenter );

    $response = $client->send( $call );

    $result = $response->result();
    if ( $response->isFault() )
    {
        eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
        return false;
    }
    else
    {
        return true;
    }
}

/*
 * Importing the rings
 * Fetching rings with defined size
 * get all the unique rings
 */
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID FROM Aktiv
                               WHERE (Wg='B08' OR Wg='R05' OR Wg='D01' OR Wg='R01'
                               OR Wg='D02' OR Wg='R02' OR Wg='D03' OR Wg='R03'
                               OR Wg='T08' OR Wg='R04' OR Wg='R06' OR Wg='R07'
                               OR Wg='K01') AND Groesse != '0' AND Referenz != ''
                               GROUP BY Legierung, Farbe, VKbrutto, Referenz
                               ORDER BY Referenz" );

$ringSQLCount = count( $ret_array );

$k=0;
foreach ( $ret_array as $item )
{
    // Fetch variations of this ring
    $rnr = $item["Referenz"];
    $db->array_query( $variation_array, "SELECT *,
                                         CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber,
                                         CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID
                                         FROM Aktiv WHERE Referenz = '$rnr' ORDER BY Groesse " );

    $ringVariationSQLCount = count( $variation_array );

    while ( list($i) = each ( $item ) )
    {
        $key = key( $item );

        if ( $item[$key] == "0.00" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == ".0" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == "0.0" )
        {
            $item[$key] = "";
        }
    }

    unset( $options );

    $show = true;
    
    $j=0;
    $valueCount=1;
    foreach ( $variation_array as $variation )
    {
        $insertOption = true;
        if ( count ( $options ) > 0 )
        {
            foreach ( $options as $optionItem )
            {
                $optionValueStruct = $optionItem->value();
                if ( $variation["Groesse"] == $optionValueStruct["Groesse"] )
                {
                    $insertOption = false;
                }
            }
        }
        if ( $insertOption )
        {
            if ( $variation["Groesse"] != 0 )
            {
             	$options[] = new eZXMLRPCStruct( array ( "ID" => $variation["ProductNumber"],
                	                                     "Groesse" => $variation["Groesse"],
                        	                             "RemoteID" => $variation["RemoteID"] . "-" . $valueCount,
                                	                     "TotalQuentity" => 1,
                                        	             "VKbrutto" => $variation["VKbrutto"] ) );
		$valueCount++;
            }
            $ringVariationArray[] = $j;
            if ( $j != 0 )
                $show = false;
            $j++;
        }
    }
    $ringArray[] = $k;
    $k++;

    if ( canAdd( $item["RemoteID"] ) )
    {
        $addedItemsArray[] = $item["RemoteID"];
        addProduct( $client, $item, $options, $show );
    }
}
exit();

/*

 *
 Fetching rings without defined options
 */
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID FROM Aktiv WHERE
                               (Wg='B08' OR Wg='R05' OR Wg='D01' OR Wg='R01' OR Wg='D02' OR Wg='R02' OR Wg='D03' OR Wg='R03'
                               OR Wg='T08' OR Wg='R04' OR Wg='R06' OR Wg='R07' OR Wg='K01') AND Groesse = '0' AND Referenz != ''
                               GROUP BY Legierung, Farbe, VKbrutto, Referenz ORDER BY Referenz" );

$ringStandardSQLCount = count( $ret_array );

$k=0;
foreach ( $ret_array as $item )
{
    // Add the standard options
    while ( list($i) = each ( $item ) )
    {
        $key = key( $item );

        if ( $item[$key] == "0.00" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == ".0" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == "0.0" )
        {
            $item[$key] = "";
        }
    }

    unset( $options );

    $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                             "Groesse" => "52",
                                             "RemoteID" => $item["RemoteID"] . "-1",
                                             "TotalQuentity" => "NULL",
                                             "VKbrutto" => 0 ) );

    $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                             "Groesse" => "54",
                                             "RemoteID" => $item["RemoteID"] . "-2",
                                             "TotalQuentity" => "NULL",
                                             "VKbrutto" => 0 ) );

    $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                             "Groesse" => "56",
                                             "RemoteID" => $item["RemoteID"] . "-3",
                                             "TotalQuentity" => "NULL",
                                             "VKbrutto" => 0 ) );

    $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                             "Groesse" => "58",
                                             "RemoteID" => $item["RemoteID"] . "-4",
                                             "TotalQuentity" => "NULL",
                                             "VKbrutto" => 0 ) );

    $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                             "Groesse" => "60",
                                             "RemoteID" => $item["RemoteID"] . "-5",
                                             "TotalQuentity" => "NULL",
                                             "VKbrutto" => 0 ) );


    addProduct( $client, $item, $options, true );
    $addLog[] = array( $item, $options ); 

    $ringStandardArray[] = $k;
    
    $k++;
}

/*
 * import chains(halsschmuck and armschmuck) with sizes
 * Fetching the chains with stored sizes
 */
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID, SUBSTRING_INDEX( Referenz, '/', -1 ) AS SubString,  CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/', -1 )
                               FROM Referenz ) ) AS Ref , LOCATE( '/', Referenz) AS Pos from Aktiv WHERE ( Wg='A01' OR Wg='S01' OR Wg='A02' OR Wg='D04' OR Wg='A03' OR
                               Wg='T01' OR Wg='A07' OR Wg='B02' OR Wg='H01' OR Wg='S02' OR Wg='H02' OR Wg='D05' OR Wg='H03' OR Wg='T02' OR Wg='H04' OR Wg='S04'
                              OR Wg='H05' OR Wg='S03' OR Wg='S05' OR Wg='H09' OR Wg='10' OR Wg='A05' OR Wg='A04' OR Wg='B01' )  AND Groesse != '0' GROUP BY Legierung, Farbe, VKbrutto, Ref, Wg HAVING Pos!=0  ORDER BY Ref, Groesse");

$chainSQLCount = count( $ret_array );

$k=0;
foreach ( $ret_array as $item )
{

//    print( $item["Wg"] . "\n" );
    // Getting variations
    $rnr = $item["Ref"];

    $wg = $item["Wg"];
    $variation_array = array();

    $db->array_query( $variation_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber,
                                         CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID,
                                         CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/', -1 ) FROM Referenz ) ) AS Ref
                                         FROM Aktiv
                                         WHERE  Wg='$wg' AND Groesse != '0' HAVING Ref = '$rnr' ORDER BY Groesse " );

    $chainVariationSQLCount = count( $ret_array );
    while ( list($i) = each ( $item ) )
    {
        $key = key( $item );

        if ( $item[$key] == "0.00" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == ".0" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == "0.0" )
        {
            $item[$key] = "";
        }
    }

    $options = array();
    $show = true;
    $j=0;

    foreach ( $variation_array as $variation )
    {
        $insertOption = true;
        if ( count ( $options ) > 0 )
        {
            foreach ( $options as $optionItem )
            {
                $optionValueStruct = $optionItem->value();
                if ( $variation["Groesse"] == $optionValueStruct["Groesse"] )
                {
                    $insertOption = false;
                }
            }
        }
        if ( $insertOption )
        {
            if ( ( $variation["Groesse"] != 0 ) && ( $variation["VKbrutto"] != 0 ) )
            {
	            $options[] = new eZXMLRPCStruct( array ( "ID" => $variation["ProductNumber"],
        	                                             "Groesse" => $variation["Groesse"],
                	                                     "RemoteID" => $variation["RemoteID"],
                        	                             "TotalQuentity" => 1,
                                	                     "VKbrutto" => $variation["VKbrutto"] ) );
           }
            if ( $j != 0 )
                $show = false;
            
            $chainVariationArray[] = $j;
            $j++;
        }
    }

addProduct( $client, $item, $options, $show );
$addLog[] = array( $item, $options ); 

// if ( $item["Nummer"] == "97" )
// {
// addProduct( $client, $item, $options, $show );
// print_r( $item );
// exit();
// }

    $chainArray[] = $k;

    $k++;
}

/*
 * import chains(halsschmuck and armschmuck) without sizes.
 */
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID, SUBSTRING_INDEX( Referenz, '/', -1 ) AS SubString,  CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/', -1 )
                               FROM Referenz ) ) AS Ref , LOCATE( '/', Referenz) AS Pos from Aktiv WHERE ( Wg='A01' OR Wg='S01' OR Wg='A02' OR Wg='D04' OR Wg='A03' OR
                               Wg='T01' OR Wg='A07' OR Wg='B02' OR Wg='H01' OR Wg='S02' OR Wg='H02' OR Wg='D05' OR Wg='H03' OR Wg='T02' OR Wg='H04' OR Wg='S04'
                              OR Wg='H05' OR Wg='S03' OR Wg='S05' OR Wg='H09' OR Wg='10' OR Wg='A05' OR Wg='A04' OR Wg='B01' )  AND Groesse != '0' GROUP BY Legierung, Farbe, VKbrutto, Ref, Wg HAVING Pos = 0  ORDER BY Ref, Groesse");

$chainSQLCount = count( $ret_array );

$k=0;
foreach ( $ret_array as $item )
{
//    print_r( $variation_array );
    $chainVariationSQLCount = count( $ret_array );
    while ( list($i) = each ( $item ) )
    {
        $key = key( $item );

        if ( $item[$key] == "0.00" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == ".0" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == "0.0" )
        {
            $item[$key] = "";
        }
    }
    $options = array();

    $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                             "Groesse" => $item["Groesse"],
                                             "RemoteID" => $item["RemoteID"],
                                             "TotalQuentity" => 1,
                                             "VKbrutto" => 0 ) );

    addProduct( $client, $item, $options, $show );
$addLog[] = array( $item, $options ); 

    $chainArray[] = $k;
}

/*
 * Fetching ohrschumck
 */

$query = "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID FROM Aktiv WHERE ( Wg='O01' OR Wg='O02' OR Wg='O03' OR Wg='O04' OR Wg='O05' OR Wg='O06' OR Wg='O07' OR Wg='B07' OR Wg='D08' OR Wg='T07' OR Wg='B05' OR Wg='D07' OR Wg='O05' OR Wg='T05' OR Wg='B06' OR Wg='T06' ) AND Groesse = '0' AND Referenz != '' GROUP BY Legierung, Farbe, VKbrutto, Referenz ORDER BY Nummer";


$db->array_query( $ret_array, $query );
$ohnSQLCount = count( $ret_array );
$k=0;
foreach ( $ret_array as $item )
{
    $options = array();
    addProduct( $client, $item, $options, true );
$addLog[] = array( $item, $options ); 
    $ohrArray[] = $k;
    $k++;
}

/*
 * Fetching Halsschmuck with groesse == 0
 */
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber,
                               CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID
                               FROM Aktiv
                               WHERE ( Wg='H06' OR Wg='B03' OR Wg='H07' OR Wg='D06' OR Wg='H08' OR Wg='T03' )
                               AND Groesse = '0' AND Referenz != ''
                               GROUP BY Legierung, Farbe, VKbrutto, Referenz ORDER BY Referenz" );

$halsschmuckWithNoSize = count( $ret_array );
$k=0;
foreach ( $ret_array as $item )
{
    $options = array();
    addProduct( $client, $item, $options, true );
$addLog[] = array( $item, $options ); 
    $noSize[] = $k;
    $k++;
}


/*
 * import chains(halsschmuck and armschmuck) with sizes
 * Fetching the chains with stored sizes
 */
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuem
er, '-', Global ) AS RemoteID, SUBSTRING_INDEX( Referenz, '/', -1 ) AS SubString,  CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/
', -1 )
                               FROM Referenz ) ) AS Ref , LOCATE( '/', Referenz) AS Pos from Aktiv WHERE ( Wg='A01' OR Wg='S01' OR Wg='A02' OR
 Wg='D04' OR| Wg='A03' OR
                               Wg='T01' OR Wg='A07' OR Wg='B02' OR Wg='H01' OR Wg='S02' OR Wg='H02' OR Wg='D05' OR Wg='H03' OR Wg='T02' OR Wg=
'H04' OR Wg='S04'
                              OR Wg='H05' OR Wg='S03' OR Wg='S05' OR Wg='H09' OR Wg='10' OR Wg='A05' OR Wg='A04' OR Wg='B01' )  AND Groesse !=
 '0' GROUP BY Legierung, Farbe, VKbrutto, Ref, Wg HAVING Pos!=0  ORDER BY Ref, Groesse");

$chainSQLCount = count( $ret_array );

$k=0;
foreach ( $ret_array as $item )
{

//    print( $item["Wg"] . "\n" );
    // Getting variations
    $rnr = $item["Ref"];

    $wg = $item["Wg"];

    $db->array_query( $variation_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber,
                                         CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID,
                                         CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/', -1 ) FROM Referenz ) ) AS Ref
                                         FROM Aktiv
                                         WHERE  Wg='$wg' AND Groesse != '0' HAVING Ref = '$rnr' ORDER BY Groesse " );

    $chainVariationSQLCount = count( $ret_array );
    while ( list($i) = each ( $item ) )
    {
        $key = key( $item );

        if ( $item[$key] == "0.00" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == ".0" )
        {
            $item[$key] = "";
        }
        if ( $item[$key] == "0.0" )
        {
            $item[$key] = "";
        }
    }

    $options = array();
    $show = true;
    $j=0;

    foreach ( $variation_array as $variation )
    {
        $insertOption = true;
        if ( count ( $options ) > 0 )
        {
            foreach ( $options as $optionItem )
            {
                $optionValueStruct = $optionItem->value();
                if ( $variation["Groesse"] == $optionValueStruct["Groesse"] )
                {
                    $insertOption = false;
                }
            }
        }
        if ( $insertOption )
        {
            if ( ( $variation["Groesse"] != 0 ) && ( $variation["VKbrutto"] != 0 ) )
            {
                    $options[] = new eZXMLRPCStruct( array ( "ID" => $variation["ProductNumber"],
                                                             "Groesse" => $variation["Groesse"],
                                                             "RemoteID" => $variation["RemoteID"],
                                                             "TotalQuentity" => 1,
                                                             "VKbrutto" => $variation["VKbrutto"] ) );
           }
            if ( $j != 0 )
                $show = false;

            $chainVariationArray[] = $j;
            $j++;
        }
    }

if ( $item["Nummer"] == "541" )
    addProduct( $client, $item, $options, $show );

    $chainArray[] = $k;

    $k++;
}

print( "<pre>" );
print_r( $addLog );

exit();
/*
 * Fetching others.
 */
/*
  $db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID FROM Aktiv WHERE
                               (Wg='O01' OR Wg='O02' OR Wg='O03' OR Wg='O04' OR Wg='O05' OR Wg='O06' OR Wg='O07'
                               OR Wg='B07' OR Wg='D08' OR Wg='T07' ) AND Groesse = '0' AND Referenz != ''
                               GROUP BY Referenz ORDER BY Referenz" );
*/

/*
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID FROM Aktiv WHERE
                               ( Wg='N03' OR Wg='T04' OR Wg='H10' OR Wg='K02' ) AND Groesse = '0' AND Referenz != ''
                               GROUP BY Referenz ORDER BY Referenz" );
*/

// NOTE: Group: N03, T04, H10 and K02 is the only categories that haven't been fetched earlier in the script. This categories don't even have products.
$db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber,
                               CONCAT( Wg, '-', Nummer, '-', Eigentuemer, '-', Global ) AS RemoteID
                               FROM Aktiv
                               WHERE ( Wg='T08' OR Wg='R04' OR Wg='N03' OR Wg='T04' OR Wg='H03' OR Wg='T02' OR Wg='H08'
                               OR Wg='T03' OR Wg='T01' OR Wg='H09' OR Wg='H09' OR Wg='H10' OR Wg='O03' OR Wg='T05' OR Wg='O06' OR Wg='T06' OR Wg='T07' OR Wg='K02' )
                               AND Groesse = '0' AND Referenz != ''
                               GROUP BY Referenz ORDER BY Referenz" );

foreach ( $ret_array as $item )
{
    $options = array();
//    addProduct( $client, $item, $options, true );
}


// fetch the other products
//print( "Fetching the chains with stored sizes\n" );
$db->array_query( $ret_array, "SELECT *, LOCATE( '*', Referenz) AS Pos
                               FROM Aktiv WHERE NOT (
                               Wg='A01' OR Wg='S01' OR Wg='A02' OR Wg='D04' OR Wg='A03' OR
                               Wg='T01' OR Wg='A07' OR Wg='B02' OR Wg='H01' OR Wg='S02' OR
                               Wg='H02' OR Wg='D05' OR Wg='H03' OR Wg='T02' OR Wg='H04' OR
                               Wg='S04' OR Wg='H05' OR Wg='S03' OR Wg='S05' OR Wg='B08' OR
                               Wg='R05' OR Wg='D01' OR Wg='R01' OR Wg='D02' OR Wg='R02' OR
                               Wg='D03' OR Wg='R03' OR Wg='T08' OR Wg='R04' OR Wg='R06' OR
                               Wg='R07' OR Wg='K01' )
                               HAVING Pos != '1'
                               ORDER BY Referenz
                               ");
foreach ( $ret_array as $item )
{
    $options = array();
//    addProduct( $client, $item, $options, true );
}

// Get the total count
$db->array_query( $ret_array, "SELECT ID FROM Aktiv" );

$totalInSQL = count ( $ret_array );

assignToCategoies();

mysql_close();

print( "Total rings: " . count( $ringArray ) . "\n");
print( "Total variation to rings: " . count( $ringVariationArray ) . "\n");

print( "Total standard rings: " . count( $ringStandardArray ) . "\n");

print( "Total chains: " . count( $chainArray ) . "\n" );
print( "Total variation to chains: " . count( $chainVariationArray ) . "\n");

print( "Total Ohr: " . count( $ohrArray ) . "\n");

$total = count( $ohrArray ) + count( $chainVariationArray ) + count( $chainArray ) + count( $ringStandardArray ) + count( $ringVariationArray ) + count( $ringArray );
$totalSQL = $ohnSQLCount + $chainSQLCount + $chainVariationSQLCount + $ringSQLCount + $ringVariationSQLCount + $halsschmuckWithNoSize;

$totalProducts = count( $ohrArray ) + count( $chainArray ) + count( $ringStandardArray ) + count( $ringArray ) + count( $noSize );

print( "Total items in the SQL: ". $totalInSQL . "\n" );
print( "Total SQL: ". $totalSQL . "\n" );
print( "Total insert: ". $total . "\n" );
print( "Total products: ". $totalProducts . "\n" );

$mail = new eZMail();
$mail->setTo( "ce@ez.no" );
// $mail->setCc( "sf@mygold.com" );
$mail->setFrom( "mygold@mygold.com" );
$mail->setSubject( "Aktiv script" );

$body = ( "Mygold aktiv script completed\n" );
$body .= ( "\n" );
$body .= ( "Total products: . " . $totalProducts . "\n" );

$mail->setBody( $body );

$mail->send();

print_r( $mail );
?>
