<?php

include_once( "classes/ezdb.php" );

$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "ezsitemanager/admin/intl", $Language, "sqlquery.php" );

$t->set_file( "sql_query_tpl", "sqlquery.tpl" );

$t->setAllStrings();

$db =& eZDB::globalDatabase();

$QueryRes   = '';
$QueryError = '';

if ( ! $QueryText )
{
    $QueryError = 'No query specified';        
}
else
{
    $db->array_query( $return_array, $QueryText );

    if ($return_array)
    {
	$QueryRes .= "<table width=\"100%\" border=\"1\"";
	
	for ( $i = 0; $i < count( $return_array ); $i++ )
        {
	    $QueryRes .= "<tr>";
	    
	    for ( $j = 0; $j <  count( $return_array[$i]) / 2 ; $j++ )
    	    {
		$QueryRes .= "<td>".$return_array[$i][$j]."</td>";
	    }
	    $QueryRes .= "</tr>";

	}
	
	$QueryRes .= "</table>";
	
    }
    else
    {
	$QueryError = "SQL error occured";
    }

    

}

$t->set_var( "query_text",   $QueryText  );
$t->set_var( "query_result", $QueryRes   );
$t->set_var( "error", 	     $QueryError );

$t->pparse( "output", "sql_query_tpl" );

?>
