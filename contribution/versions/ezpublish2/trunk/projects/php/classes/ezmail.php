<?
/*!
    $Id: ezmail.php,v 1.1 2000/09/08 13:10:05 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <17-Jul-2000 15:11:50 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
/*!
  Quite selfexplaining class. Just a wrapper for PHPs mail() function
 */

//!! eZCommon
//!
/*!
  
*/

class eZMail
{
    var $To;
    var $From;
    var $Subject;
    var $Body;

    // constructor
    function eZMail()
    {

    }

    function to()
    {
        return $this->To;
    }

    function setTo( $newTo )
    {
        $this->To = $newTo;
    }

    function from()
    {
        return $this->From;
    }

    function setFrom( $newFrom )
    {
        $this->From = $newFrom;
    }

    function subject()
    {
        return $this->Subject;
    }

    function setSubject( $newSubject )
    {
        $this->Subject = $newSubject;
    }

    function body()
    {
        return $this->Body;
    }

    function setBody( $newBody )
    {
        $this->Body = $newBody;
    }
        
    function send()
    {
        mail( $this->To, $this->Subject, $this->Body, "From: " . $this->From);
    }
}
?>
