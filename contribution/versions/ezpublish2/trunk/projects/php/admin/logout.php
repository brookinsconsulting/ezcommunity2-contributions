<?php

eZSession::delete( $AuthenticatedSession );
Header( "Location: index.php" );

?>
