<?
// 
// $Id: ezsection.php,v 1.4 2001/05/25 13:19:13 ce Exp $
//
// ezsection class
//
// Christoffer A. Elo <jb@ez.no>
// Created on: <10-May-2001 15:13:08 ce>
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

//!! eZSection
//! eZSections handles sections..
/*!

  Example code:
  \code
  \endcode

*/
	      
class eZSection
{

    /*!
      Constructs a new eZSection object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZSection( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        $this->ExcludeFromSearch = "false";
        if ( $id != -1 )
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
      Stores a eZSection object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name = addslashes( $this->Name );
        $description = addslashes( $this->Description );
        $sitedesign = addslashes( $this->SiteDesign );
        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZSiteManager_Section SET
                                     Name='$name',
                                     SiteDesign='$sitedesign',
                                     Description='$description'" );

			$this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZSiteManager_Section SET
		                             Name='$name',
		                             SiteDesign='$sitedesign',
                                     Description='$description'
                                     WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZSection object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZSiteManager_Section WHERE ID='$this->ID'" );
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $section_array, "SELECT * FROM eZSiteManager_Section WHERE ID='$id'" );
            if ( count( $section_array ) > 1 )
            {
                die( "Error: Section's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $section_array ) == 1 )
            {
                $this->ID = $section_array[0][ "ID" ];
                $this->Name = $section_array[0][ "Name" ];
                $this->SiteDesign = $section_array[0][ "SiteDesign" ];
                $this->Description = $section_array[0][ "Description" ];
                $this->Created = $section_array[0][ "Created" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZSection objects.
    */
    function getAll( $offset=0, $limit=40)
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $section_array = array();
        
        $db->array_query( $section_array, "SELECT ID
                                           FROM eZSiteManager_Section
                                           ORDER BY Created DESC
                                           LIMIT $offset, $limit" );
        
        for ( $i=0; $i < count($section_array); $i++ )
        {
            $return_array[$i] = new eZSection( $section_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the total count.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZQuiz_Game" );
        $ret = $result["Count"];
        return $ret;
    }


    /*!
      Returns the object ID to the section. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the section.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Name );
    }
    
    /*!
      \static
      Returns the SiteDesign of the section.

      If $sectionID is a number, the function will return the sitedesign for that section ID.
    */
    function siteDesign( $sectionID=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( is_numeric ( $sectionID ) )
       {
           $db =& eZDB::globalDatabase();
           $db->query_single( $siteDesign, "SELECT SiteDesign FROM eZSiteManager_Section WHERE ID='$sectionID'" );
           return $siteDesign["SiteDesign"];
       }
       else
           return htmlspecialchars( $this->SiteDesign );
    }
    
    /*!
      Returns the section description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Description );
    }
    
    /*!
      Returns the created date of this section.
    */
    function created()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }

    /*!
      Sets the name of the section.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the SiteDesign of the section.
    */
    function setSiteDesign( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->SiteDesign = $value;
    }

    /*!
      Sets the description of the section.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      \private
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $SiteDesign;
    var $Description;
    var $Created;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
