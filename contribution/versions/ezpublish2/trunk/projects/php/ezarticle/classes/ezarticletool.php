<?php
// 
// $Id: ezarticletool.php,v 1.1 2001/04/27 12:13:25 jb Exp $
//
// Definition of eZArticleTool class
//
// Jan Borsodi <jb@ez.no>
// Created on: <27-Apr-2001 14:08:05 amos>
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

//!! 
//! The class eZArticleTool does
/*!

*/

include_once( "classes/ezcachefile.php" );

class eZArticleTool
{
    /*!
      \static
      Deletes the cache files for a given article and it's categories.
    */
    function deleteCache( $ArticleID, $CategoryID, $CategoryArray )
    {
        $user = eZUser::currentUser();
/*    $groupstr = "";
      if( get_class( $user ) == "ezuser" )
      {
      $groupIDArray = $user->groups( true );
      sort( $groupIDArray );
      $first = true;
      foreach( $groupIDArray as $groupID )
      {
      $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
      $first = false;
      }
      }*/

        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( array( "articleprint", "articleview", "articlestatic", "static", "view", "print"  ),
                                             $ArticleID, NULL, NULL ), "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( array( "articlelist", "list" ),
                                             array_merge( 0, $CategoryID, $CategoryArray ),
                                             NULL, array( "", NULL ) ),
                                      "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }


        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( "articlelinklist",
                                             array_merge( 0, $CategoryID, $CategoryArray ),
                                             $ArticleID,
                                             NULL ),
                                      "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( "articleindex",
                                             NULL ),
                                      "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }
    }

}

?>
