<?php
// 
// $Id: ezpublish.php,v 1.3 2001/05/16 15:20:17 bf Exp $
//
// Definition of eZPublish class
//
// B�rd Farstad <bf@ez.no>
// Created on: <30-Apr-2001 17:11:32 bf>
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

//!! eZCommon
//! The eZPublish class provides global eZ publish variables.
/*!
  Has the version number of eZ publish.
  
*/

class eZPublish
{
    /*!
      \static
      Returns the eZ publish version number.
    */
    function version()
    {
        return "2.1";
    }
}

?>
