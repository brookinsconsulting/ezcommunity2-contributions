<?php
//
// $Id: ezgpg.php,v 1.10 2001/10/11 11:44:30 ce Exp $
//
// Definition of eZGPG class
//
// Created on: <09-Apr-2001 16:36:08 bf>
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

//!! eZCommon
//! GPG Encrytion class
/*!

*/

include_once( "classes/bc/ln2wrn.php" );
include_once( "classes/ezfile.php" );

class eZGPG
{
    /*!
      \static
      Encrypt function
    */
   function eZGPG( $plaintxt, $keyname, $wwwuser, $system, $gnuhome, $home )
   {
      $this->home = $home;
      $this->gnuhome = $gnuhome;
      $this->system = $system;

      // putenv($home);
      $s_home = "HOME=". $this->home;
      putenv($s_home);

      // $boundary = md5( uniqid( time() ) );
      // add full pathing & .gpg to indicate temp file type.
      $boundary = $this->home ."/".  md5( uniqid( time() ) ) .".gpg";

      $this->keyname=$keyname;
      if ( sizeof( $this->keyname ) == 0 )
            echo "WARNING: No Keys Specified";
	
      // --batch --no-tty --no-secmem-warning
      $this->pcmd = "/bin/echo ";
      $this->pcmd .= " '". $plaintxt ."'";
      $this->pcmd .= " | ". $this->pathtogpg.$this->encryptcommand;
      $this->pcmd .= " -a -q --no-secmem-warning --no --no-tty --always-trust --homedir " . $this->gnuhome .  " -u $wwwuser -r $keyname";
      $this->pcmd .= " -o " . $boundary;
 
      // $eresults = array();
      exec( $this->pcmd, $eresults, $resultcode );
      // exec( $this->pcmd );
      // passthru( $this->pcmd, $eresults );
      
      // print_r( $results );
      // print( "<br />\nresult code is " . $resultcode );

      // error_reporting(E_ALL);
      // enable commented out body text return of command.
      //$pp = popen( $this->pcmd, "w" );
      //fwrite( $pp, $this->body );
      /* Add redirection so we can get stderr. */
      //echo "'$pp'; " . gettype($pp) . "\n";
      //$read = fread($pp, 2096);
      //echo $read;    
      //pclose( $pp );

      // touch( '/home/aih/htdocs/nobody/write_test.txt' ); echo "success";

      // distro way to read in a file to a string.
      //$fp = eZFile::fopen( $boundary, r );
      //$this->body = fread( $fp, eZFile::filesize( $boundary ) );
      //fclose( $fp );
     
      // ghb way to read file into array(memory would be faster)?
      // i thought i needed to to retain text formating chars, i know now it is not.
 
      $fp = file($boundary);
      $this->body = ln2wrn($fp, $this->system);

      // Debug Variables #################################
      // $this->body = "it worked";
      // $this->body = $fp;
      // $print_cmd = $plaintxt;
      // $print_cmd = ln2wrn($plaintxt, $this->system);
      // $print_cmd = "'". $this->system ."'"; // ln2wrn($fp, $this->system);
      // $print_cmd = $this->body;
      // $print_cmd = $this->pcmd;

      // system( $print_cmd );
      // print( $print_cmd );
      // exit();
      // Debug Variables #################################

      // trying to find these files to find the directory they 
      // are written to (for permissions fix).

      eZFile::unlink( $boundary );
   }

   var $body;
   var $keyname = array();
   var $pathtogpg = "/usr/bin/";
   var $pp;
   var $fp;
   var $pcmd;
   var $encryptcommand = "gpg --encrypt --batch";
   var $signcommand = "gpg --sign --batch";
   var $home;
   var $gnuhome;
   var $system = "nix";
}
?>
