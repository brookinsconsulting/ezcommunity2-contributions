<?php
// 
// $Id: ezlist.php,v 1.2 2001/02/06 15:55:20 jb Exp $
//
// Definition of eZList class
//
// Jan Borsodi <jb@ez.no>
// Created on: <06-Feb-2001 15:31:37 amos>
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

//!! eZCommon
//! The class eZList helps in handling listings of various types
/*!


  To create a naviator bare at the bottom of your list you can do:
  \code
  $t = new eZTemplate( ... );
  $ItemCount = Items::totalCount();
  $Max = 10;
  $Index = 0;
  eZList::drawNavigator( $t, $ItemCount, $Max, $Index, "my_template" );
  \endcode
*/

/*!TODO
  Add functions for drawing lists too.
*/

class eZList
{

    /*!
      Draws next/prev navigators by using a specified template.
      $total_types is the total number of types a list will give.
      $max_types is the number of types the list should show at a time.
      $index is the index of the type list.
      if $parent is set to a string that string is used as the parent for the template blocks,
      if false all blocks needs to be set by the client.
      $variables is an associative array with various template variables, if a template variable is not specified in the array the default is used.
      It currently supports:
          next: The block containing the next button
          next_inactive: The block containing the inactive next button
          next_index: The index variable for the next button
          previous: The block containing the previous button
          previous_inactive: The block containing the inactive previous button
          previous_index: The index variable for the previous button
          item: The block containing the hotlink items
          item_index: The item index variable used with hotlinks
          item_name: The item name variable used with hotlinks
          type_list: The block containing the whole navigator HTML
    */
    function &drawNavigator( &$t, $total_types, $max_types, $index, $parent = false,
    	                     $variables = false )
    {
        if ( is_array( $variables ) )
        {
            $next = $variables["next"];
            $prev = $variables["previous"];
            $next_inactive = $variables["next_inactive"];
            $prev_inactive = $variables["previous_inactive"];
            $item = $variables["item"];
            $next_index = $variables["next_index"];
            $prev_index = $variables["previous_index"];
            $item_index = $variables["item_index"];
            $item_name = $variables["item_name"];
            $type_list = $variables["type_list"];
        }

        if ( empty( $next ) )
            $next = "type_list_next";
        if ( empty( $prev ) )
            $prev = "type_list_previous";
        if ( empty( $next_inactive ) )
            $next_inactive = "type_list_next_inactive";
        if ( empty( $prev_inactive ) )
            $prev_inactive = "type_list_previous_inactive";
        if ( empty( $item ) )
            $item = "type_list_item";
        if ( empty( $next_index ) )
            $next_index = "item_next_index";
        if ( empty( $prev_index ) )
            $prev_index = "item_previous_index";
        if ( empty( $item_index ) )
            $item_index = "item_index";
        if ( empty( $item_name ) )
            $item_name = "type_item_name";
        if ( empty( $type_list ) )
            $type_list = "type_list";

        if ( is_string( $parent ) )
        {
            $t->set_block( $parent, $type_list . "_tpl", $type_list );
            $t->set_block( $type_list . "_tpl", $prev . "_tpl", $prev );
            $t->set_block( $type_list . "_tpl", $item . "_tpl", $item );
            $t->set_block( $type_list . "_tpl", $next . "_tpl", $next );
            $t->set_block( $type_list . "_tpl", $prev_inactive . "_tpl", $prev_inactive );
            $t->set_block( $type_list . "_tpl", $next_inactive . "_tpl", $next_inactive );
        }

        if ( $total_types > $max_types || $index > 0 )
        {
            $t->set_var( $prev, "" );
            $t->set_var( $item, "" );
            $t->set_var( $next, "" );
            $t->set_var( $prev_inactive, "" );
            $t->set_var( $next_inactive, "" );

            if ( $index > 0 )
            {
                $t->set_var( $prev_index, max( $index - $max_types, 0 ) );
                $t->parse( $prev, $prev . "_tpl" );
            }
            else
            {
                $t->parse( $prev_inactive, $prev_inactive . "_tpl" );
            }
            if ( $index + $max_types < $total_types )
            {
                $t->set_var( $next_index, $index + $max_types );
                $t->parse( $next, $next . "_tpl" );
            }
            else
            {
                $t->parse( $next_inactive, $next_inactive . "_tpl" );
            }

            $total = $total_types;
            $i = 0;
            while ( $total > 0 )
            {
                $t->set_var( $item_index, $i*$max_types );
                $t->set_var( $item_name, $i );
                $t->parse( $item, $item . "_tpl", true );

                $total = $total - $max_types;
                $i++;
            }

            $t->parse( $type_list, $type_list . "_tpl" );
        }
        else
        {
            $t->set_var( $type_list, "" );
        }
    }
}

?>
