<?php

include_once( "classes/ezdb.php" );

$Language = $ini->read_var( "eZSiteManager", "Language" );

$t = new eZTemplate( "ezsitemanager/admin/" . $ini->read_var( "eZSiteManager", "AdminTemplateDir" ),
                     "ezsitemanager/admin/intl", $Language, "sqlquery.php" );

$t->set_file( "sql_query_tpl", "sqlquery.tpl" );

$t->setAllStrings();


$db =& eZDB::globalDatabase();

$res = $db->array_query( $ret_array, $QueryText );

if ( $res == false )
    print( "query error" );

$t->set_var( "query_text", $QueryText );

$t->pparse( "output", "sql_query_tpl" );

?>
