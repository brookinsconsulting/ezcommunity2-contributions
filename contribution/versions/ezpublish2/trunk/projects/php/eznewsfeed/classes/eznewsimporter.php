<?php
// 
// $Id: eznewsimporter.php,v 1.1 2000/11/15 18:14:15 bf-cvs Exp $
//
// Definition of eZNewsImporter class
//
// Bård Farstad <bf@ez.no>
// Created on: <13-Nov-2000 16:56:48 bf>
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

//!! eZNewsFeed
//! eZNewsImporter handles importing of news bullets from other sites.
/*!
  Example code:
  \sa eZNewsCategory

*/

/*!TODO

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezuser/classes/ezuser.php" );

class eZNewsImporter
{
    /*!
      Constructor.
    */
    function eZNewsImporter( $site )
    {
        $this->Site = $site;
    }

    /*!
      Imports news from the given site.
    */
    function importNews( )
    {
        switch ( $this->Site )
        {
            case "nyheter.no" :
            {
                include_once( "eznewsfeed/classes/eznyheternoimporter.php" );
                
                $importer = new eZNyheterNOImporter();
                $importer->news();
            }

            case "freshmeat.net" :
            {
                include_once( "eznewsfeed/classes/ezrdfimporter.php" );
                
                $importer = new eZRDFImporter();
                $importer->news();
            }
            
        }
    }

    var $Site;
    
}
