<?php
// 
// $Id: ezcctool.php,v 1.2 2001/02/08 13:07:12 ce Exp $
//
// Definition of eZCCTool class
//
// Christoffer A. Elo <ce@ez.ez.no>
// Created on: <07-Feb-2001 10:59:41 ce>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//! Provied utility functions for CreditCards.
/*!
*/

class eZCCTool
{
    function checkCC( $CCNumber,$month,$year )
    {
        $ret = false;
        
        if ( $CCNumber != false )
        {
            // Set the current date
            $date = time();
            $currentYear = date( "Y", $date );
            $currentMonth = date( "m", $date );

            if ( $month < $currentMonth && $year == $currentYear )
            {
                return false;
            }
            else
            {
                $ret = true;
            }

            $CCNumberLenght = strlen ( $CCNumber );

            if ( $CCNumberLenght != 13 && $CCNumberLenght != 15 && $CCNumberLenght != 16 )
            {
                return false;
            }
            else
            {
                $ret = true;
            }


            if ( ( $CCNumberLenght % 2 ) == 0 )
            {
                $pair = 0;
            }
            else
            {
                $pair = 2;
            }

            // Get each number of the card and put it in an array
            for ( $i=0; $i != $CCNumberLenght; $i++ )
            {
                $numberArray[$i] = substr( $CCNumber, $i, 1 );
            }

            // Algorithm Mod10 modificated (by me :-)
            for ( $nume = ($p/2); $nume != $CCNumberLenght-$pair; $nume = $nume + 2)
            {
                $numberArray[$nume] = $numberArray[$nume] * 2;
                if ( $numberArray[$nume] >= 10 )
                {
                    $X1 = substr( $numberArray[$nume],0,1 );
                    $X2 = substr( $numberArray[$nume],1,1 );
                    $numberArray[$nume] = substr( $numberArray[$nume],0,1 ) + substr( $numberArray[$nume],1,1 );
                }
            }
        
            // Sums each value of the modificated array
            // Check the result of the algorithm
            for( $i=0; $i != $CCNumberLenght; $i++ )
            {
                $val = $val + $numberArray[$i];
            }

            // Please note: if the second number of the result of algorithm is not 0
            // then the card is NOT valid
            if ( $val == 0 )
            {
                return false;
            }

            if ( substr( $val,1,1 ) != 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }
}
?>
