<?
// 
// $Id: ezbulkmailtemplate.php,v 1.6 2001/06/28 08:14:53 bf Exp $
//
// eZBulkMailTemplate class
//
// Frederik Holljen <fh@ez.no>
// Created on: <17-Apr-2001 14:07:32 fh>
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

//!! eZBulkMail
//! eZBulkMailTemplate documentation.
/*!

  Example code:
  \code
  \endcode

*/
	      
class eZBulkMailTemplate
{
    /*!
    */
    function eZBulkMailTemplate( $id=-1 )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZBulkMail object to the database.
    */
    function store()
    {
        $this->dbInit();
        $name = addslashes( $this->Name );
        $header = addslashes( $this->Header );
        $footer = addslashes( $this->Footer );
        $description = addslashes( $this->Description );
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_Template SET
                                 Header='$header',
                                 Name='$name',
                                 Footer='$footer',
                                 Description='$description'
                                 " );
			$this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_Template SET
                                 Header='$header',
                                 Name='$name',
                                 Footer='$footer',
                                 Description='$description'
                                 WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZBulkMail object from the database.

    */
    function delete( $id = -1)
    {
        $db = eZDB::globalDatabase();

        if( $id == -1 )
            $id = $this->ID;

        $db->query( "DELETE FROM eZBulkMail_Template WHERE ID='$this->ID'" );            
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $template_array, "SELECT * FROM eZBulkMail_Template WHERE ID='$id'" );
            if ( count( $template_array ) > 1 )
            {
                die( "Error: Templates with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $template_array ) == 1 )
            {
                $this->ID = $template_array[0][ "ID" ];
                $this->Name = $template_array[0][ "Name" ];
                $this->Header = $template_array[0][ "Header" ];
                $this->Footer = $template_array[0][ "Footer" ];
                $this->Description = $template_array[0]["Description"];
            }
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the templates found in the database.

      The categories are returned as an array of eZBulkMailTemplate objects.
    */
    function getAll()
    {
        $db = eZDB::globaldatabase();
        $return_array = array();
        $template_array = array();
        
        $db->array_query( $template_array, "SELECT ID FROM eZBulkMail_Template ORDER BY Name" );
        for ( $i=0; $i<count($template_array); $i++ )
        {
            $return_array[$i] = new eZBulkMailTemplate( $template_array[$i]["ID"] );
        }
        
        return $return_array;
    }

    /*!
      Returns the header
     */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the header
     */
    function header()
    {
        return $this->Header;
    }

        /*!
      Returns the footer
     */
    function footer()
    {
        return $this->Footer;
    }

    /*!
      Returns the name of this template
     */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the description of this template
     */
    function description()
    {
        return $this->Description;
    }

    
    /*!
      Sets the header.
     */
    function setHeader( $value )
    {
        $this->Header = $value;
    }

    /*!
      Sets the footer
    */
    function setFooter( $value )
    {
        $this->Footer = $value;
    }

    /*!
      Sets the name
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

        /*!
      Sets the name
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    
    /*!
      Adds the footer and the header to the given message and returns the result.
     */
    function buildMessage( $message )
    {
        return $this->Header . $message . $this->Footer;
    }
    
    /*!
      \private
      
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

    var $Header;
    var $Footer;
    var $Name;
    var $Description;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
