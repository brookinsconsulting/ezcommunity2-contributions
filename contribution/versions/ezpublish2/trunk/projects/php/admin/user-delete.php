<?php

include_once( "../classes/ezusergroup.php" );

$session = new eZSession();
if( $session->get( $AuthenticatedSession ) == 1 )
{
    include( "index.php" );
    exit();
}


if( !eZUserGroup::verifyCommand( $session->userID(), "GrantUser" ) )
{
    print( "Du har ikke rettigheter til å slette en bruker<br>" );
    print( "<a href=index.php>Tilbake</a>" );
    exit();
}

mysql_query( "DELETE FROM AdminTable WHERE Id='$id'" ) or die( "Det oppstod en feil ved sletting i databasen" );

// gå til index siden
Header( "Location: index.php" );

?>
