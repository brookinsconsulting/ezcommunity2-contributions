<?
// 
// $Id: ezform.php,v 1.1 2001/06/15 15:40:34 pkej Exp $
//
// ezform class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <11-Jun-2001 12:07:57 pkej>
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

//!! ezform
//! ezform documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezform/classes/ezformelement.php" );

class eZForm
{

    /*!
      Constructs a new eZForm object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZForm( $id=-1, $fetch=true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores a eZForm object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $name =& addslashes( $this->Name );
        $receiver =& addslashes( $this->Receiver );
        $cc =& addslashes( $this->CC );
        $completedPage =& addslashes( $this->CompletedPage );
        $instructionPage =& addslashes( $this->InstructionPage );
        $sender =& addslashes( $this->Sender );
        $sendAsUser =& $this->SendAsUser;
        $counter =& $this->Counter;
        
        $setValues = "
            Name='$name',
            Receiver='$receiver',
            CompletedPage='$completedPage',
            CC='$cc',
            InstructionPage='$instructionPage',
            Counter='$counter',
            SendAsUser='$sendAsUser',
            Sender='$sender'
        ";
        if ( empty( $this->ID ) )
        {
            $db->query( "INSERT INTO eZForm_Form SET $setValues" );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZForm_Form SET $setValues WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZForm object from the database.
    */
    function delete( $formID=-1 )
    {
        if ( $formID == -1 )
            $formID = $this->ID;

        $db =& eZDB::globalDatabase();

        $formElements =& $this->formElements();
        if ( is_array ( $formElements ) )
        {
            foreach( $formElements as $element )
            {
                $element->delete();
            }
        }
        
        $db->query( "DELETE FROM eZForm_Form WHERE ID=$formID" );
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $formArray, "SELECT * FROM eZForm_Form WHERE ID='$id'",
                              0, 1 );
            if( count( $formArray ) == 1 )
            {
                $this->fill( &$formArray[0] );
                $ret = true;
            }
            elseif( count( $formArray ) != 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$formArray )
    {
        $this->ID =& $formArray[ "ID" ];
        $this->Name =& $formArray[ "Name" ];
        $this->CC =& $formArray[ "CC" ];
        $this->Receiver =& $formArray[ "Receiver" ];
        $this->CompletedPage =& $formArray[ "CompletedPage" ];
        $this->InstructionPage =& $formArray[ "InstructionPage" ];
        $this->Counter =& $formArray[ "Counter" ];
        $this->SendAsUser =& $formArray[ "SendAsUser" ];
        $this->Sender =& $formArray[ "Sender" ];
    }

    /*!
      Returns all the objects found in the database.

      The objects are returned as an array of eZForm objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $formArray = array();

        if ( $limit == false )
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_Form
                                           ORDER BY Name DESC
                                           " );

        }
        else
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZForm_Form
                                           ORDER BY Name DESC
                                           LIMIT $offset, $limit" );
        }

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZForm( $formArray[$i]["ID"] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count of objects in the database.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZForm_Form" );
        $ret = $result["Count"];
        return $ret;
    }

    /*!
      Returns the object ID. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the object.
    */
    function &name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the receiver of the object.
    */
    function &receiver()
    {
        return htmlspecialchars( $this->Receiver );
    }

    /*!
      Returns the cc of the object.
    */
    function &cc()
    {
        return htmlspecialchars( $this->CC );
    }

    /*!
      Returns the completed page of the object.
    */
    function &completedPage()
    {
        return $this->CompletedPage;
    }

    /*!
      Returns the instruction page of the object.
    */
    function &instructionPage()
    {
        return $this->InstructionPage;
    }

    /*!
      Returns the counter of the object.
    */
    function &counter()
    {
        return $this->Counter;
    }

    /*!
      Returns the sender of the object.
    */
    function &sender()
    {
        return $this->Sender;
    }

     /*!
      Returns true if one should use the user name of the user for the sending.
    */
    function &isSendAsUser()
    {
        $ret = true;
        
        if( $this->SendAsUser == 0 )
        {
            $ret = false;
        }
        
        return $ret;
    }

   /*!
      Sets the name of the object.
    */
    function setName( &$value )
    {
       $this->Name = $value;
    }

   /*!
      Sets the sender of the object.
    */
    function setSender( &$value )
    {
       $this->Sender = $value;
    }

    /*!
      Sets the sender of the object.
    */
    function setSendAsUser( $value = true )
    {
        if( $value == false )
        {
            $this->SendAsUser = 0;
        }
        else
        {
            $this->SendAsUser = 1;
        }
    }

    /*!
      Sets the receiver.
    */
    function setReceiver( &$value )
    {
        $this->Receiver = $value;
    }

    /*!
      Sets the cc.
    */
    function setCC( &$value )
    {
        $this->CC = $value;
    }

    /*!
      Sets the completed page for the object.
    */
    function setCompletedPage( &$value )
    {
        $this->CompletedPage = $value;
    }

    /*!
      Sets the instruction page for the object.
    */
    function setInstructionPage( &$value )
    {
        $this->InstructionPage = $value;
    }

    /*!
      Increases the counter of the object.
    */
    function counterAdd()
    {
        $this->Counter++;
    }

    /*!
      Returns every form element of this form.
      The form elements are returned as an array of eZFormElement objects.
    */
    function &formElements()
    {
        $returnArray = array();
        $formArray = array();
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $formArray, "SELECT ElementID FROM eZForm_FormElementDict WHERE FormID='$this->ID' ORDER BY Placement" );

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZFormElement( $formArray[$i]["ElementID"], true );
        }
        return $returnArray;
    }

    /*!
      Returns a specific form element based on the placement (number)
      The element is returned as eZFormElement objects.
    */
    function &formElement( $placement )
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $element, "SELECT ElementID FROM eZForm_FormElementDict WHERE FormID='$this->ID' AND Placement='$placement'" );

        $return = new eZFormElement( $element["ElementID"], true );
           
        return $return;
    }
    
    

    /*!
      Returns the number of form elements to this form
    */
    function &numberOfElements()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ElementID) as Count
                                     FROM eZForm_FormElementDict WHERE FormID='$this->ID'" );
        $ret = $result["Count"];
        return $ret;
    }

    /*!
      Returns the number of types which exists
    */
    function &numberOfTypes()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZForm_FormElementType" );
        $ret = $result["Count"];
        
        return $ret;
    }
    
    /*!
        This function adds an eZFormElement to this form.
     */
    function addElement( &$object )
    {
        if( get_class( $object ) == "ezformelement" )
        {
            $elementID = $object->id();
            $elementName = $object->name();
            $formID = $this->ID;

            $db =& eZDB::globalDatabase();

            $db->query_single( $result, "SELECT MAX(Placement) as Placement
                                     FROM eZForm_FormElementDict WHERE FormID='$formID'" );
            
            $placement = $result["Placement"];
            $placement++;
            
            $db->query( "INSERT INTO eZForm_FormElementDict SET Placement='$placement', ElementID='$elementID', FormID='$formID', Name='$elementName'" );

            return true;
        }
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */
    function moveUp( $object )
    {
        if( get_class( $object ) == "ezformelement" )
        {
            if ( $this->State_ == "Dirty" )
                $this->get( $this->ID );
            $db = eZDB::globalDatabase();

            $formID = $this->id();
            $elementID = $object->id();
            
            $db->query_single( $qry, "SELECT Placement FROM eZForm_FormElementDict
                                      WHERE ElementID = '$elementID' AND FormID = '$formID' ORDER BY Placement DESC LIMIT 1" );

            $elementPlacement = $qry["Placement"];

            $db->query_single( $qry, "SELECT min(Placement) as Placement FROM eZForm_FormElementDict WHERE FormID='$formID'" );
            $min =& $qry[ "Placement" ];

            if( $min == $elementPlacement )
            {
                $db->query_single( $qry, "SELECT max(Placement) as Placement FROM eZForm_FormElementDict WHERE FormID='$formID'" );
                $newOrder =& $qry[ "Placement" ];
                $db->query_single( $qry, "SELECT ElementID FROM eZForm_FormElementDict WHERE FormID='$formID' AND Placement='$newOrder'" );
                $oldElementID =& $qry[ "ElementID" ];
            }
            else
            {
                $db->query_single( $qry, "SELECT FormID, ElementID, Placement FROM eZForm_FormElementDict
                                          WHERE Placement < '$elementPlacement' AND FormID='$formID' ORDER BY Placement DESC LIMIT 1" );

                $newOrder = $qry["Placement"];
                $oldElementID = $qry["ElementID"];
            }

            $db->query( "UPDATE eZForm_FormElementDict SET Placement='$newOrder' WHERE ElementID='$elementID' AND FormID='$formID'" );
            $db->query( "UPDATE eZForm_FormElementDict SET Placement='$elementPlacement' WHERE ElementID='$oldElementID' AND FormID='$formID'" );
        }
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */
    function moveDown( $object )
    {
        if( get_class( $object ) == "ezformelement" )
        {
            if ( $this->State_ == "Dirty" )
                $this->get( $this->ID );
            $db = eZDB::globalDatabase();

            $formID = $this->id();
            $elementID = $object->id();
            
            $db->query_single( $qry, "SELECT Placement FROM eZForm_FormElementDict
                                      WHERE ElementID = '$elementID' AND FormID = '$formID' ORDER BY Placement DESC LIMIT 1" );

            $elementPlacement = $qry["Placement"];

            $db->query_single( $qry, "SELECT max(Placement) as Placement FROM eZForm_FormElementDict WHERE FormID='$formID'" );
            $max =& $qry[ "Placement" ];
            echo "$max, $elementPlacement<br>";
            if( $max == $elementPlacement )
            {
                $db->query_single( $qry, "SELECT min(Placement) as Placement FROM eZForm_FormElementDict WHERE FormID='$formID'" );
                $newOrder =& $qry[ "Placement" ];
                $db->query_single( $qry, "SELECT ElementID FROM eZForm_FormElementDict WHERE FormID='$formID' AND Placement='$newOrder'" );
                $oldElementID =& $qry[ "ElementID" ];
            }
            else
            {
                $db->query_single( $qry, "SELECT FormID, ElementID, Placement FROM eZForm_FormElementDict
                                          WHERE Placement > '$elementPlacement' AND FormID='$formID' ORDER BY Placement LIMIT 1" );

                $newOrder = $qry["Placement"];
                $oldElementID = $qry["ElementID"];
            }

            $db->query( "UPDATE eZForm_FormElementDict SET Placement='$newOrder' WHERE ElementID='$elementID' AND FormID='$formID'" );
            $db->query( "UPDATE eZForm_FormElementDict SET Placement='$elementPlacement' WHERE ElementID='$oldElementID' AND FormID='$formID'" );
        }
    }


    var $ID;
    var $Name;
    var $Receiver;
    var $CC;
    var $CompletedPage;
    var $InstructionPage;
    var $Counter;
    var $SendAsUser;
    var $Sender;
}

?>
