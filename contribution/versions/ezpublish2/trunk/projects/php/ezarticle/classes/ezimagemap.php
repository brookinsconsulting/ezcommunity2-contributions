<?php
// 
// $Id: ezimagemap.php,v 1.3 2001/07/19 12:19:21 jakobn Exp $
//
// Created on: <12-Jun-2001 17:41:10 jhe>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZImageMap
//! eZImageMap hadles image maps
/*!
  Collects data generated by the image map applet, and stores to the database. It can allso generate
  data in form needed by the applet from the data in the database
*/
include_once( "classes/ezdb.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZImageMap
{
    function eZImageMap( $image )
    {
        $this->IsConnected = false;
        
        $this->ID = $image;
    }
    
    function get()
    {
        $this->dbinit();
        
        $elements = array();
        
        $this->Database->array_query( $article_array, "SELECT Link, AltText, Shape, StartPosX, StartPosY, EndPosX, EndPosY FROM eZImageCatalogue_ImageMap WHERE ImageID='$this->ID'" );

        for ( $i = 0; $i < count( $article_array ); $i++ )
        {
            $elements[$i] = $article_array[$i][0] . "|" . $article_array[$i][1] . "|" . $article_array[$i][2] . "|" . $article_array[$i][3] . "|" . $article_array[$i][4] . "|" . $article_array[$i][5] . "|" . $article_array[$i][6];
        }
        return $elements;  
    }	
    
    function store( $elements )
    {
        $this->dbinit();
    	
        $this->Database->query( "DELETE FROM eZImageCatalogue_ImageMap WHERE ImageID='$this->ID'" );
        
        $list = array();
        $element_list = array();
        $element_list = split( "\n", $elements );
        $this->Database->lock( "eZImageCatalogue_ImageMap" );
        
        for ( $i = 0; $i < count( $element_list ); $i++ )
        {
            if ( $element_list[$i] != "" )
            {
                $list = split( "\|", $element_list[$i] );
                $id = $this->Database->nextID( "eZImageCatalogue_ImageMap", "ID" );
                $res[] = $this->Database->query( "INSERT INTO eZImageCatalogue_ImageMap
                                         (ID,
                                          ImageID,
                                          Link,
                                          AltText,
                                          Shape,
                                          StartPosX,
                                          StartPosY,
                                          EndPosX,
                                          EndPosY) VALUES
                                         ('$id',
                                          '$this->ID',
                                          '$list[0]',
                                          '$list[1]',
                                          '$list[2]',
                                          '$list[3]',
                                          '$list[4]',
                                          '$list[5]',
                                          '$list[6]')" );
            }
        }
        if ( in_array( false, $res ) )
            $db->rollback( );
        else
            $db->commit();            
    }
    
    function dbinit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }
}

?>
