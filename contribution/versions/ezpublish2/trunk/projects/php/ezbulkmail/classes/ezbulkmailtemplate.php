<?
// 
// $Id: ezbulkmailtemplate.php,v 1.1 2001/04/17 12:30:12 fh Exp $
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

//!! eZBulkMailTemplate
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
    function eZBulkMail( $id=-1 )
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
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBulkMail_Template SET
                                 Header='$header',
                                 Name='$name',
                                 Footer='$footer'
                                 " );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBulkMail_Template SET
                                 Header='$header',
                                 Name='$name',
                                 Footer='$footer'
                                 WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZBulkMail object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            // delete actual group entry
            $this->Database->query( "DELETE FROM eZBulkMail_Template WHERE ID='$this->ID'" );            
        }
        
        return true;
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
            else if( count( $mail_array ) == 1 )
            {
                $this->ID = $mail_array[0][ "ID" ];
                $this->Name = $mail_array[0][ "Name" ];
                $this->Header = $mail_array[0][ "Header" ];
                $this->Footer = $mail_array[0][ "Footer" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
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
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
