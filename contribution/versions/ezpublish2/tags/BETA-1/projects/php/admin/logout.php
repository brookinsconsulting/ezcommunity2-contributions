<?php

$session = new eZSession();
$session->delete( $AuthenticatedSession );
Header( "Location: index.php" );

?>
