<?php
// 
// $Id: ezgpg.php,v 1.7 2001/08/24 13:55:56 ce Exp $
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

class eZGPG
{
    /*!
      \static
      Encrypt function
    */
	function eZGPG( $plaintxt, $keyname, $wwwuser)
    {	
		putenv($home);
		$boundary = md5( uniqid( time() ) );
		
		$this->keyname=$keyname;
		if ( sizeof( $this->keyname ) == 0 )
            echo "WARNING: No Keys Specified";

		$this->pcmd = "echo '$plaintxt' | ";
		$this->pcmd .= $this->pathtogpg.$this->encryptcommand;
		$this->pcmd.= " -a -q --no-tty -e -u $wwwuser -r$wwwuser";
		$this->pcmd.= " -o/var/www/" . $boundary;

		$pp = popen( $this->pcmd, "w" );
		fwrite( $pp, $this->body );
		pclose( $pp );


		$fp = eZFile::fopen( $boundary, r );
		$this->body = fread( $fp, eZFile::filesize( $boundary ) );
		fclose( $fp );
		
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
	var $home="HOME=/var/www";

}
?>
