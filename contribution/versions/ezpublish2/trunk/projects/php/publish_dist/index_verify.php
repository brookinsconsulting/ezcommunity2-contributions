<?
ob_end_clean();
// script to check if the site is alive
// this script will return 42 if the server is alive
// it will return 13 if not

include_once( "classes/ezdb.php" );

$db =& eZDB::globalDatabase();
$db->query_single( $session_array, "SELECT count( ID ) AS Count FROM eZSession_Session" );
$db->query_single( $user_array, "SELECT count( ID ) AS Count FROM eZUser_User" );
if ( $user_array["Count"] > 0 )
    print( "42" );
else
    print( "13" );

exit();
?>
