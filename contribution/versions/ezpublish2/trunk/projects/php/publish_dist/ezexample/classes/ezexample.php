<?php
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



include_once( "classes/ezdb.php" );

class eZExample
{
    /*!

     */
    function eZExample( $id=-1 )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Fetches the Text from the database.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != -1  )
        {
            $db->array_query( $text_array, "SELECT * FROM eZExample_Test WHERE ID='$id'" );
            
            if ( count( $text_array ) > 1 )
            {
                die( "Error: Text with the same ID was found in the database. This shouldn't happen." );
            }
            else if ( count( $text_array ) == 1 )
            {
                $this->ID =& $text_array[0][ "ID" ];
                $this->Text =& $text_array[0][ "Text" ];
                $this->Created =& $text_array[0][ "Created" ];
            }
        }
        return true;
    }

    /*!
      Retrieves all Text fields from the database.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $return_array = array();
        $url_array = array();
        
        $db->array_query( $url_array, "SELECT ID FROM eZExample_Test ORDER BY Created DESC"  );
        
        for ( $i=0; $i<count($url_array); $i++ )
        {
            $return_array[$i] = new eZExample( $url_array[$i]["ID"], 0 );
        }

        return $return_array;
    }


    /*!
      Stores/updates the Text.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZExample_Test SET
                         Created=now(),
		                 Text='$this->Text'
                          " );
			$this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZExample_Test SET
		                 Text='$this->Text',
                         Created=Created
                         WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    
    /*!
      Deletes a Text from the database.
    */
    function delete( $id )
    {
        if ( is_numeric( $id ) )
             $this->ID = $id;
        
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZExample_Test WHERE ID='$this->ID'" );
    }

    /*!
      Returns the object ID to the option. This is the unique ID stored in the database.
    */
    function id()
    {
       return $this->ID;
    }

    /*!
      Returns the Text From the database.
    */
    function text()
    {
        return $this->Text;
    }

    /*!
      Sets the text in the database.
    */
    function setText( $value )
    {
        $this->Text = $value;
    }

    var $ID;
    var $Text;
    var $Created;
}
?>
