<?php
//
// $Id: ezmygoldimport.php,v 1.1.2.2 2002/04/16 10:30:40 ce Exp $
//
// Definition of ||| class
//
// <real-name> <<mail-name>>
// Created on: <27-Nov-2001 15:55:13 ce>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!!
//! The class ||| does
/*!

*/

class eZMygoldImport
{
    function eZMygoldImport( $host="www.mygold.com", $user=false, $password=false, $option="daily", $limit=0 )
    {
        $this->Client = new eZXMLRPCClient( $host, "/trade/xmlrpcimport/" );
        if ( $user and $password )
        {
            $this->Client->setLogin( $user );
            $this->Client->setPassword( $password );
        }
        $this->Option = $option;
        $this->db = eZDB::globalDatabase();
        $this->Count = 0;
        $this->ProductCount = 0;
        $this->MaxLimit = $limit;
    }

    function getUnavailableProducts()
    {
        $call = new eZXMLRPCCall( );
        $call->setMethodName( "getUnavailableProducts" );

        $response = $this->Client->send( $call );

        return $response->result();
    }

    function productExists( $remoteID )
    {
        $call = new eZXMLRPCCall( );
        $call->setMethodName( "productExists" );

        $paramenter = new eZXMLRPCString( $remoteID );

        $call->addParameter( $paramenter );

        $response = $this->Client->send( $call );

        $result = $response->result();

        if ( $response->isFault() )
        {
            eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
            return false;
        }
        else
        {
            if ( $result->value() == true )
                return true;
            else
                return false;
        }
    }

    function updateImages()
    {
        $fd = fopen ( "/home/upload/export/picfilelist.ez", "r" );

        $i = 0;
        while (!feof ($fd))
        {
            // get a new line
            $buffer = fgets($fd, 4096);

            $buffer = trim( $buffer );
            $this->updateImage( $buffer );

            $i++;
        }
        fclose( $fd );
    }


    function assignToCategoies()
    {
        $call = new eZXMLRPCCall( );
        $call->setMethodName( "assignToCategoies" );

        $response = $this->Client->send( $call );

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

    function unavailable( $products )
    {
        if ( is_array ( $products ) )
        {
            $call = new eZXMLRPCCall( );
            $call->setMethodName( "unavailable" );

            $paramenter = new eZXMLRPCArray( $products );

            $call->addParameter( $paramenter );

            $response = $this->Client->send( $call );

            $result = $response->result();
            if ( $response->isFault() )
            {
                eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
                return false;
            }
            else
            {
                foreach( $products as $product )
                {
                    $this->db->query( "DELETE FROM AddedProducts WHERE RemoteID='$product'" );
                }
                print( "    Made " . $result->value() . " products unavailable\n" );
            }
        }
    }

    function unavailableOptions( $options )
    {
        if ( is_array ( $options ) )
        {
            $call = new eZXMLRPCCall( );
            $call->setMethodName( "unavailableOptions" );

            $paramenter = new eZXMLRPCArray( $options );

            $call->addParameter( $paramenter );

            $response = $this->Client->send( $call );

            $result = $response->result();
            if ( $response->isFault() )
            {
                eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
                return false;
            }
            else
            {
                foreach( $options as $option )
                {
                    $this->db->query( "DELETE FROM AddedOptions WHERE RemoteID='$option'" );
                }
                print( "    Made " . $result->value() . " options unavailable\n" );
            }
        }
    }

    function updateImage( $image )
    {
        $value = explode( ".", $image );

        $imageName = $value[0];
        if ( is_string ( $imageName ) )
        {
            $this->db->query_single( $product, "SELECT *, Global AS RemoteID  FROM Aktiv WHERE Bildnummer='$imageName'" );

            $productRemoteID = $product["RemoteID"];

            if ( $productRemoteID == "" )
                return false;

            $imageFile = new eZFile();
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

            $call = new eZXMLRPCCall( );
            $call->setMethodName( "uploadImage" );

            $paramenter = new eZXMLRPCStruct( array( "productPicture" => new eZXMLRPCBase64( $content ),
                                                     "productPictureName" => new eZXMLRPCString( $imageName ),
                                                     "productRemoteID" => new eZXMLRPCString( $productRemoteID ) ) );
            $call->addParameter( $paramenter );
            $response = $this->Client->send( $call );
            $result = $response->result();
            if ( $response->isFault() )
            {
                eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
                return false;
            }
            else
            {
                print( $result->value() . " ");
            }
        }
    }

    function clearcache()
    {
        $call = new eZXMLRPCCall( );
        $call->setMethodName( "clearcache" );

        $response = $this->Client->send( $call );
    }

    function canAdd( $id=false )
    {
        if ( ( $this->Option == "noupdate" ) or ( $this->Option == "unavailable" ) )
        {
            return false;
        }
        else if ( $this->Option == "daily" or $this->Option == "all" )
        {
            $this->db->query_single( $checkArray, "SELECT COUNT(*) as Count FROM AddedProducts WHERE RemoteID='$id'" );

            if ( $checkArray["Count"] > 0 )
            {
                if ( $this->Option == "daily" )
                    return false;
                else
                    return true;
            }
            else
            {
                $this->db->query( "INSERT INTO AddedProducts set RemoteID='$id', Added=now()" );
                return true;
            }
        }
    }

    function addOption( $id=false )
    {
        $this->db->query_single( $checkArray, "SELECT COUNT(*) as Count FROM AddedOptions WHERE RemoteID='$id'" );
        if ( $checkArray["Count"] > 0 )
        {
            return false;
        }
        else
        {
            $this->db->query( "INSERT INTO AddedOptions set RemoteID='$id', Added=now()" );
            return true;
        }
    }



/*
 * Send the product to the xmlrpc server.
 */
    function addProduct( $data = array(), $options = array(), $showPrice = true )
    {

        if ( ( $this->MaxLimit != 0 ) and ( $this->Count == $this->MaxLimit ) )
        {
            exit();
        }
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

        if ( $this->productExists( $remoteID ) == false )
        {
            $update = false;
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
        }
        else
        {
            $update = true;
            $imageName = "NoUpdate";
        }
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
        $response = $this->Client->send( $call );
        $this->Count++;
        $result = $response->result();
        if ( $response->isFault() )
        {
            eZLog::writeError( "Error: " . $response->faultCode() . "): ". $response->faultString() );
            return false;
        }
        else
        {
            if ( $update )
                print( "   Updated product: " .  $result->value() );
            else
                print( "   Added product: " .  $result->value() );
            $this->ProductCount++;
        }
    }

    function importProducts()
    {
/*
 * Importing the rings
 * Fetching rings with defined size
 * get all the unique rings
 */
        $ret_array = array();
        $this->db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, Global AS RemoteID FROM Aktiv
                               WHERE (Wg='B08' OR Wg='R05' OR Wg='D01' OR Wg='R01'
                               OR Wg='D02' OR Wg='R02' OR Wg='D03' OR Wg='R03'
                               OR Wg='T08' OR Wg='R04' OR Wg='R06' OR Wg='R07'
                               OR Wg='K01') AND Groesse != '0' AND Referenz != ''
                               GROUP BY Legierung, Farbe, VKbrutto, Referenz
                               ORDER BY Referenz" );

        $k=0;
        foreach ( $ret_array as $item )
        {
            // Fetch variations of this ring
            $rnr = $item["Referenz"];
            $this->db->array_query( $variation_array, "SELECT *,
                                         CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber,
                                         Global AS RemoteID
                                         FROM Aktiv WHERE Referenz = '$rnr' ORDER BY Groesse " );

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
                        $addedProductsArray[] = $variation["RemoteID"];
                        if ( $this->canAddOption( $variation["RemoteID"] ) )
                        {
                            $options[] = new eZXMLRPCStruct( array ( "ID" => $variation["ProductNumber"],
                                                                     "Groesse" => $variation["Groesse"],
                                                                     "RemoteID" => $variation["RemoteID"],
                                                                     "TotalQuentity" => 1,
                                                                     "VKbrutto" => $variation["VKbrutto"] ) );
                        }
                        $valueCount++;
                    }
                    $ringVariationArray[] = $j;
                    if ( $j != 0 )
                        $show = false;
                    $j++;
                }
            }
            $k++;

            $addedProductsArray[] = $item["RemoteID"];

            if ( $this->canAdd( $item["RemoteID"] ) )
            {
                $this->addProduct( $item, $options, $show );
            }
        }

/*

*
Fetching rings without defined options
*/
        $ret_array = array();
        $this->db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, Global AS RemoteID FROM Aktiv WHERE
                               (Wg='B08' OR Wg='R05' OR Wg='D01' OR Wg='R01' OR Wg='D02' OR Wg='R02' OR Wg='D03' OR Wg='R03'
                               OR Wg='T08' OR Wg='R04' OR Wg='R06' OR Wg='R07' OR Wg='K01') AND Groesse = '0' AND Referenz != ''
                               GROUP BY Legierung, Farbe, VKbrutto, Referenz ORDER BY Referenz" );

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
                                                     "RemoteID" => $item["RemoteID"],
                                                     "TotalQuentity" => "NULL",
                                                     "VKbrutto" => 0 ) );

            $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                                     "Groesse" => "54",
                                                     "RemoteID" => $item["RemoteID"],
                                                     "TotalQuentity" => "NULL",
                                                     "VKbrutto" => 0 ) );

            $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                                     "Groesse" => "56",
                                                     "RemoteID" => $item["RemoteID"],
                                                     "TotalQuentity" => "NULL",
                                                     "VKbrutto" => 0 ) );

            $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                                     "Groesse" => "58",
                                                     "RemoteID" => $item["RemoteID"],
                                                     "TotalQuentity" => "NULL",
                                                     "VKbrutto" => 0 ) );

            $options[] = new eZXMLRPCStruct( array ( "ID" => $item["ProductNumber"],
                                                     "Groesse" => "60",
                                                     "RemoteID" => $item["RemoteID"],
                                                     "TotalQuentity" => "NULL",
                                                     "VKbrutto" => 0 ) );


            $addedProductsArray = $item["RemoteID"];
            if ( $this->canAdd( $item["RemoteID"] ) )
            {
                $this->addProduct( $item, $options, true );
            }
            $k++;
        }
        /*
         * import chains(halsschmuck and armschmuck) with sizes
         * Fetching the chains with stored sizes
         */
        $ret_array = array();
        $this->db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, Global AS RemoteID, SUBSTRING_INDEX( Referenz, '/', -1 ) AS SubString,  CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/', -1 )
                               FROM Referenz ) ) AS Ref , LOCATE( '/', Referenz) AS Pos from Aktiv WHERE ( Wg='A01' OR Wg='S01' OR Wg='A02' OR Wg='D04' OR Wg='A03' OR
                               Wg='T01' OR Wg='A07' OR Wg='B02' OR Wg='H01' OR Wg='S02' OR Wg='H02' OR Wg='D05' OR Wg='H03' OR Wg='T02' OR Wg='H04' OR Wg='S04'
                              OR Wg='H05' OR Wg='S03' OR Wg='S05' OR Wg='H09' OR Wg='10' OR Wg='A05' OR Wg='A04' OR Wg='B01' )  AND Groesse != '0' GROUP BY Legierung, Farbe, VKbrutto, Ref, Wg HAVING Pos!=0  ORDER BY Ref, Groesse");

        $k=0;
        foreach ( $ret_array as $item )
        {

//    print( $item["Wg"] . "\n" );
            // Getting variations
            $rnr = $item["Ref"];

            $wg = $item["Wg"];
            $variation_array = array();

            $this->db->array_query( $variation_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber,
                                         Global AS RemoteID,
                                         CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/', -1 ) FROM Referenz ) ) AS Ref
                                         FROM Aktiv
                                         WHERE  Wg='$wg' AND Groesse != '0' HAVING Ref = '$rnr' ORDER BY Groesse " );

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
                        $addedProductsArray[] = $variation["RemoteID"];
                        if ( $this->canAddOption( $variation["RemoteID"] ) )
                        {
                            $options[] = new eZXMLRPCStruct( array ( "ID" => $variation["ProductNumber"],
                                                                     "Groesse" => $variation["Groesse"],
                                                                     "RemoteID" => $variation["RemoteID"],
                                                                     "TotalQuentity" => 1,
                                                                     "VKbrutto" => $variation["VKbrutto"] ) );
                        }
                    }
                    if ( $j != 0 )
                        $show = false;

                    $chainVariationArray[] = $j;
                    $j++;
                }
            }

            $addedProductsArray[] = $item["RemoteID"];
            if ( $this->canAdd( $item["RemoteID"] ) )
            {
                $this->addProduct( $item, $options, $show );
            }
            $k++;
        }
/*
 * import chains(halsschmuck and armschmuck) without sizes.
 */
        $ret_array = array();
        $this->db->array_query( $ret_array, "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, Global AS RemoteID, SUBSTRING_INDEX( Referenz, '/', -1 ) AS SubString,  CONCAT( Farbe, TRIM( TRAILING SUBSTRING_INDEX( Referenz, '/', -1 )
                               FROM Referenz ) ) AS Ref , LOCATE( '/', Referenz) AS Pos from Aktiv WHERE ( Wg='A01' OR Wg='S01' OR Wg='A02' OR Wg='D04' OR Wg='A03' OR
                               Wg='T01' OR Wg='A07' OR Wg='B02' OR Wg='H01' OR Wg='S02' OR Wg='H02' OR Wg='D05' OR Wg='H03' OR Wg='T02' OR Wg='H04' OR Wg='S04'
                              OR Wg='H05' OR Wg='S03' OR Wg='S05' OR Wg='H09' OR Wg='10' OR Wg='A05' OR Wg='A04' OR Wg='B01' )  AND Groesse != '0' GROUP BY Legierung, Farbe, VKbrutto, Ref, Wg HAVING Pos = 0  ORDER BY Ref, Groesse");

        $k=0;
        foreach ( $ret_array as $item )
        {
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
            $addedProductsArray[] = $item["RemoteID"];
            if ( $this->CanAdd( $item["RemoteID"] ) )
            {
                $this->addProduct( $item, $options, $show );
            }
        }

/*
 * Fetching ohrschumck
 */
        $query = "SELECT *, CONCAT( Wg, '-', Nummer, '-', Eigentuemer ) AS ProductNumber, Global AS RemoteID FROM Aktiv WHERE ( Wg='O01' OR Wg='O02' OR Wg='O03' OR Wg='O04' OR Wg='O05' OR Wg='O06' OR Wg='O07' OR Wg='B07' OR Wg='D08' OR Wg='T07' OR Wg='B05' OR Wg='D07' OR Wg='O05' OR Wg='T05' OR Wg='B06' OR Wg='T06' ) AND Groesse = '0' AND Referenz != '' GROUP BY Legierung, Farbe, VKbrutto, Referenz ORDER BY Nummer";


        $ret_array = array();
        $this->db->array_query( $ret_array, $query );
        $k=0;
        foreach ( $ret_array as $item )
        {
            $addedProductsArray[] = $item["RemoteID"];
            $options = array();
            if ( $this->CanAdd( $item["RemoteID"] ) )
            {
                $this->addProduct( $item, $options, true );
            }
            $k++;
        }

/*
 * Fetching Halsschmuck with groesse == 0
 */
        $ret_array = array();
        $this->db->array_query( $ret_array, "SELECT *, Global AS ProductNumber,
                               Global AS RemoteID
                               FROM Aktiv
                               WHERE ( Wg='H06' OR Wg='B03' OR Wg='H07' OR Wg='D06' OR Wg='H08' OR Wg='T03' )
                               AND Groesse = '0' AND Referenz != ''
                               GROUP BY Legierung, Farbe, VKbrutto, Referenz ORDER BY Referenz" );

        $k=0;
        foreach ( $ret_array as $item )
        {
            $addedProductsArray[] = $item["RemoteID"];
            $options = array();
            if ( $this->canAdd( $item["RemoteID"] ) )
            {
                $this->addProduct( $item, $options, true );
            }
            $k++;
        }

        print( "\nUpdated/added " . $this->ProductCount . " products...\n" );
        return $addedProductsArray;
    }

    function existingProducts()
    {
        $allProducts = array();
        $this->db->array_query( $allProducts, "SELECT * FROM AddedProducts" );

        if ( count ( $allProducts ) > 0 )
        {
            foreach( $allProducts as $product )
            {
                print( "." );
                flush();
                $addedProducts[] = $product["RemoteID"];
            }
        }
        return $addedProducts;
    }

    var $CanAdd;
    var $User;
    var $Password;
    var $Client;
    var $db;
    var $Host;
    var $Count;
    var $ProductCount;
}

?>
