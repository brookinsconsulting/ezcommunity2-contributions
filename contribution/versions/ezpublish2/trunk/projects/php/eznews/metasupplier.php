<?php
    include_once("eznews/user/eznewsuser.php");
    $item=new eZNewsUser( "site.ini" );
    $item->doActions( true );
?> 
