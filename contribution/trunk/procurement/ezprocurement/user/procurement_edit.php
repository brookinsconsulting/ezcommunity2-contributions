<?php
//
// $Id: procurement_edit.php,v 1.116.2.10 2004/11/09 06:52:21 ghb Exp $
//
// Created on: <09-Nov-2004 15:04:39 ghb>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 2003-2006 Brookins Consulting.  All rights reserved.
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );

include_once( "ezuser/classes/ezauthor.php" );

include_once( "classes/ezhttptool.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/eztopic.php" );

include_once( "ezrfp/classes/ezrfpgenerator.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );

//include_once( "ezbulkmail/classes/ezbulkmail.php" );
//include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

include_once( "ezrfp/classes/ezrfptool.php" );
include_once( "ezxml/classes/ezxml.php" );


include_once( "ezrfp/classes/fnc_viewArray.php" );

$ini =& INIFile::globalINI();
//$AnonymousUserGroup = $ini->read_var( "eZRfpMain", "AnonymousUserGroup" );


//##########################################################################


$user =& eZUser::currentUser();

if ($user) {
  $user_id = $user->id();
  $procurement = new eZRfp($ProcurementID);
 
  if ($Action == "join") {
    $procurement->addPlanholder($user);
  }
  elseif ($Action == "remove") {
    $procurement->removePlanholder($user);
  }

  exec("bin/shell/clearcache.sh");
  //  break;

  include_once( "classes/ezhttptool.php" );
  eZHTTPTool::header( "Location: /procurement/view/$ProcurementID" );
  exit;
} else {
  print("No User . . . Redirecting to Login (Besure to set this RequestURI as a session variable)");
  // Session RequestURI();

  include_once( "classes/ezhttptool.php" );
  eZHTTPTool::header( "Location: /user/login" );
  exit;

}

//print("<br /> END OF LINE");

?>

