<?
// 	function utime ()
// 	{
// 		$time = explode( " ", microtime());
// 		$usec = (double)$time[0];
// 		$sec = (double)$time[1];
// 		return $sec + $usec;
//     }
    
    #echo "Time start: " . utime() . "<br />\n";
    include_once("eznews/admin/eznewsadmin.php");
    $item=new eZNewsAdmin( "site.ini" );
    $item->doActions();
    #echo "Time stop: " . utime() . "<br />\n";
    
?> 
