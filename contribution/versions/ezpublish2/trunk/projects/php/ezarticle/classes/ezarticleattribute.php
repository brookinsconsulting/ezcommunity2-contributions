<?
// 
// $Id: ezarticleattribute.php,v 1.4 2001/06/22 14:47:59 pkej Exp $
//
// Definition of eZArticleAttribute class
//
// Bård Farstad <bf@ez.no>
// Created on: <01-Jun-2001 13:43:02 pkej>
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


//!! eZArticle
//! This class handles different article attributes.
/*!

  \code
  
  $attribute = new eZArticleAttribute();
  $attribute->setType( $type );
  $attribute->setName( "Doors" );
  $attribute->store();

  \endcode  
  \sa eZArticle
*/

include_once( "classes/ezdb.php" );
include_once( "ezarticle/classes/ezarticletype.php" );

class eZArticleAttribute
{
    /*!
      Constructs a new eZArticleAttribute object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZArticleAttribute( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
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
      Stores a eZArticleattribute object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {

            $this->Database->array_query( $attribute_array, "SELECT Placement FROM eZArticle_Attribute" );

            if ( count ( $attribute_array ) > 0 )
            {
                $place = max( $attribute_array );
                $place = $place["Placement"];
                $place++;
            }
            
            $this->Database->query( "INSERT INTO eZArticle_Attribute SET
		                         Name='" . addslashes( $this->Name )  . "',
		                         TypeID='$this->TypeID',
		                         Placement='$place',
		                         Created=now()" );
        
			$this->ID = $this->Database->insertID();
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZArticle_Attribute SET
		                         Name='" . addslashes( $this->Name ) . "',
		                         Created=Created,
		                         TypeID='$this->TypeID' WHERE ID='$this->ID'" );

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Fetches the article attribute object values from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != -1  )
        {
            $this->Database->array_query( $attribute_array, "SELECT * FROM eZArticle_Attribute WHERE ID='$id'" );
            
            if ( count( $attribute_array ) > 1 )
            {
                die( "Error: Article attribute's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $attribute_array ) == 1 )
            {
                $this->ID =& $attribute_array[0][ "ID" ];
                $this->Name =& $attribute_array[0][ "Name" ];
                $this->TypeID =& $attribute_array[0][ "TypeID" ];
                $this->Placement =& $attribute_array[0][ "Placement" ];
                
                $this->State_ = "Coherent";                
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Retrieves every option from the database.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $attribute_array = array();
        
        $this->Database->array_query( $attribute_array, "SELECT ID FROM eZArticle_Attribute ORDER BY Created" );
        
        for ( $i=0; $i<count($attribute_array); $i++ )
        {
            $return_array[$i] = new eZArticleAttribute( $attribute_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
        \static
        Returns the one, and only if one exists, attribute with the name
        
        Returns an object of eZArticleAttribute.
     */
    function getByName( $name )
    {
        $db =& eZDB::globalDatabase();
        
        $topic =& new eZArticleAttribute();
        
        $name = addslashes( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZArticle_Attribute WHERE Name='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic =& new eZArticleAttribute( $author_array[0][ "ID" ] );
            }
        }
        
        return $topic;
    }


    /*!
      Deletes a option from the database.
    */
    function delete()
    {
        $this->dbInit();

        $this->Database->query( "DELETE FROM eZArticle_AttributeValue WHERE AttributeID='$this->ID'" );
        
        $this->Database->query( "DELETE FROM eZArticle_Attribute WHERE ID='$this->ID'" );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       return $this->ID;
    }

    /*!
      Returns the name of the attribute.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
 
        return $this->Name;
    }

    /*!
      Returns the type of the attribute.
    */
    function type()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $type = new eZArticleType( $this->TypeID );
 
       return $type;
    }


    /*!
      Sets the name of the attribute.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the type of the attribute.
    */
    function setType( $type )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $type ) == "ezarticletype" )
       {
           $this->TypeID = $type->id();
       }
    }


    /*!
      Sets the attribute value for the given article.
    */
    function setValue( $article, $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $article ) == "ezarticle" )
       {
           $articleID = $article->id();

           // check if the attribute is already set, if so update
           $this->Database->array_query( $value_array,
           "SELECT ID FROM eZArticle_AttributeValue WHERE ArticleID='$articleID' AND AttributeID='$this->ID'" );

           if ( count( $value_array ) > 0 )
           {
               $valueID = $value_array[0]["ID"];
               
               $this->Database->query( "UPDATE eZArticle_AttributeValue SET
                                 Value='" . addslashes( $value ) . "'
                                 WHERE ID='$valueID'" );
           }
           else
           {
               $this->Database->query( "INSERT INTO eZArticle_AttributeValue SET
		                         ArticleID='$articleID',
                                 AttributeID='$this->ID',
                                 Value='" . addslashes( $value ) . "'" );
           }
       }
    }

    /*!
      Returns the attribute value to the given article.
    */
    function value( $article )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = "";
       if ( get_class( $article ) == "ezarticle" )
       {
           $articleID = $article->id();

           // check if the attribute is already set, if so update
           $this->Database->array_query( $value_array,
           "SELECT Value FROM eZArticle_AttributeValue WHERE ArticleID='$articleID'
           AND AttributeID='$this->ID'" );

           if ( count( $value_array ) > 0 )
           {
               $ret = $value_array[0]["Value"];
           }    
       }
       return $ret;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement<'$this->Placement' AND TypeID = '$this->TypeID' ORDER BY Placement DESC LIMIT 1" );

        $listorder = $qry["Placement"];
        $listid = $qry["ID"];



        $db->query_single( $qry, "SELECT min( Placement ) as Min FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement ASC LIMIT 1" );
        $min = $qry["Min"];
        
        
        if( $min == $this->Placement )
        {
            $db->query_single( $qry, "SELECT max( Placement ) as Max FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement ASC LIMIT 1" );
            
            $max = $qry["Max"];
            
            $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement = '$max' AND TypeID = '$this->TypeID'  ORDER BY Placement ASC LIMIT 1" );
            
            $listorder = $qry["Placement"];
            $listid = $qry["ID"];
        }



        $db->query( "UPDATE eZArticle_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZArticle_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement>'$this->Placement' AND TypeID = '$this->TypeID'  ORDER BY Placement ASC LIMIT 1" );
        $listorder = $qry["Placement"];
        $listid = $qry["ID"];
        
        $db->query_single( $qry, "SELECT max( Placement ) as Max FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement ASC LIMIT 1" );
        $max = $qry["Max"];
        
        
        if( $max == $this->Placement )
        {
            $db->query_single( $qry, "SELECT min( Placement ) as Min FROM eZArticle_Attribute
                                  WHERE TypeID = '$this->TypeID'  ORDER BY Placement ASC LIMIT 1" );
            
            $min = $qry["Min"];
            
            $db->query_single( $qry, "SELECT ID, Placement FROM eZArticle_Attribute
                                  WHERE Placement = '$min' AND TypeID = '$this->TypeID'  ORDER BY Placement ASC LIMIT 1" );
            
            $listorder = $qry["Placement"];
            $listid = $qry["ID"];
        }
        
        $db->query( "UPDATE eZArticle_Attribute SET Placement='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZArticle_Attribute SET Placement='$this->Placement' WHERE ID='$listid'" );
    }

    
    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $TypeID;
    var $Name;
    var $Placement;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
