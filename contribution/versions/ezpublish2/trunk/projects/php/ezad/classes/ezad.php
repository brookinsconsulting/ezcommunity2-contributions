<?php
// 
// $Id: ezad.php,v 1.7 2000/12/23 15:10:04 bf Exp $
//
// Definition of eZAd class
//
// B�rd Farstad <bf@ez.no>
// Created on: <22-Nov-2000 20:01:05 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! eZAd
//! eZAd handles banner ads.
/*!

  \sa eZAdCategory  
*/

/*!TODO

 */

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZAd
{
    /*!
      Constructs a new eZAd object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZAd( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        
        if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
            {                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
            
        }
    }

    /*!
      Stores a product to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZAd_Ad SET
		                         Name='$this->Name',
		                         Description='$this->Description',
                                 ImageID='$this->ImageID',
                                 IsActive='$this->IsActive',
                                 URL='$this->URL',
                                 ViewStartDate='$this->ViewStartDate',
                                 ViewStopDate='$this->ViewStopDate',
                                 ClickPrice='$this->ClickPrice',
                                 ViewPrice='$this->ViewPrice',
                                 ViewRule='$this->ViewRule'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZAd_Ad SET
		                         Name='$this->Name',
		                         Description='$this->Description',
                                 ImageID='$this->ImageID',
                                 IsActive='$this->IsActive',
                                 URL='$this->URL',
                                 ViewStartDate='$this->ViewStartDate',
                                 ViewStopDate='$this->ViewStopDate',
                                 ClickPrice='$this->ClickPrice',
                                 ViewPrice='$this->ViewPrice',
                                 ViewRule='$this->ViewRule'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $ad_array, "SELECT * FROM eZAd_Ad WHERE ID='$id'" );
            if ( count( $ad_array ) > 1 )
            {
                die( "Error: Ad's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $ad_array ) == 1 )
            {
                $this->ID =& $ad_array[0][ "ID" ];
                $this->Name =& $ad_array[0][ "Name" ];
                $this->Description =& $ad_array[0][ "Description" ];
                $this->IsActive =& $ad_array[0][ "IsActive" ];
                $this->URL =& $ad_array[0][ "URL" ];
                $this->ImageID =& $ad_array[0][ "ImageID" ];
                $this->ViewStartDate =& $ad_array[0][ "ViewStartDate" ];
                $this->ViewStopDate =& $ad_array[0][ "ViewStopDate" ];
                $this->ViewPrice =& $ad_array[0][ "ViewPrice" ];
                $this->ClickPrice =& $ad_array[0][ "ClickPrice" ];
                $this->ViewRule =& $ad_array[0][ "ViewRule" ];

                $this->State_ = "Coherent";
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Deletes a eZAd object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZAd_AdCategoryLink WHERE AdID='$this->ID'" );            
            $this->Database->query( "DELETE FROM eZAd_Ad WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the ad's name.
    */
    function &name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the ad's description.
    */
    function &description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Description );
    }

    /*!
      Returns the ad's url.
    */
    function &url()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->URL );
    }

    /*!
      Returns the ad's click price.
    */
    function &clickPrice()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ClickPrice;
    }

    /*!
      Returns the ad's view price.
    */
    function &viewPrice()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->View;
    }

    /*!
      Returns the ad's url.
    */
    function &url()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->URL );
    }
    
    /*!
      Returns true if the ad is active false if not.
    */
    function isActive()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsActive == "true" )
       {
           $ret = true;
       }
       return $ret;
    }
    
    
    /*!
      Returns the view start date.
    */
    function &viewStartDate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->ViewStartDate );
       
       return $dateTime;
    }    

    /*!
      Returns the view stop date.
    */
    function &viewStopDate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->ViewStopDate );
       
       return $dateTime;
    }    
    
    /*!
      Sets the ad's name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;
    }

    /*!
      Sets the ad's description.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Description = $value;
    }

    /*!
      Sets the URL which the ad should link to.
    */
    function setURL( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->URL = $value;
    }

    /*!
      Sets the click price.
    */
    function setClickPrice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ClickPrice = $value;
       setType( $this->ClickPrice, "double" );
    }

    /*!
      Sets the view price.
    */
    function setViewPrice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ViewPrice = $value;
       setType( $this->ViewPrice, "double" );
    }
    
    /*!
     Sets the ad to active or not. 
    */
    function setIsActive( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsActive = "true";
       }
       else
       {
           $this->IsActive = "false";           
       }
    }
    
    /*!
      Returns the categrories an ad is assigned to.

      The categories are returned as an array of eZAdCategory objects.
    */
    function categories()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $ret = array();
       $this->Database->array_query( $category_array, "SELECT * FROM
                                                       eZAd_AdCategoryLink
                                                       WHERE AdID='$this->ID'" );

       foreach ( $category_array as $category )
       {           
           $ret[] = new eZAdCategory( $category["CategoryID"] );
       }

       return $ret;
    }
    
    /*!
      Removes every category assignments from the current ad.
    */
    function removeFromCategories()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZAd_AdCategoryLink
                                WHERE AdID='$this->ID'" );        
    }

    /*!
      Sets the banner ad image.
    */
    function setImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezimage" )
        {
            $this->dbInit();

            $this->ImageID = $value->id();
        }
    }

    /*!
      Deletes the banner ad image.

      NOTE: the image also gets deleted from the image catalogue.
    */
    function deleteImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $value->id();

            $image = new eZImage( $imageID );
            $image->delete();
        }
    }
    
    /*!
      Returns the banner ad image.
    */
    function image()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $ret = false;
       $img = new eZImage( );
       
       if ( $img->get( $this->ImageID ) )
       {           
           $ret = $img;
       }
       
       return $ret;
    }

    /*!
      Returns the total number of times the banner has been viewed.
    */
    function viewCount( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $view_result, "SELECT count(*) AS Count FROM
                                                       eZAd_View
                                                       WHERE AdID='$this->ID'" );

       return $view_result[0]["Count"];
    }
  
    /*!
      Returns the total number of times the banner has been clicked.
    */
    function clickCount( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $click_result, "SELECT count(*) AS Count FROM
                                                       eZAd_Click
                                                       WHERE AdID='$this->ID'" );

       return $click_result[0]["Count"];
    }
    

    /*!
      Returns the banner's total view revenue.
    */
    function totalViewRevenue( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $view_result, "SELECT SUM(ViewPrice) AS Revenue FROM eZAd_View WHERE AdID='$this->ID'" );

       return $view_result[0]["Revenue"];
    }

    /*!
      Returns the banner's total click revenue.
    */
    function totalClickRevenue( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $click_result, "SELECT SUM(ClickPrice) AS Revenue FROM eZAd_Click WHERE AdID='$this->ID'" );

       return $click_result[0]["Revenue"];
    }

    /*!
      \private
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $Name;
    var $Description;
    var $ImageID;
    var $URL;
    var $ViewStartDate;
    var $ClickPrice;
    var $ViewPrice;

    /// Indicates if the banner is active or not
    var $IsActive;

    /// Indicates if the banner should be viewed by date or by clicks.
    var $ViewRule;

    /// The URL to go to when the banner is clicked
    var $URL;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}


?>
