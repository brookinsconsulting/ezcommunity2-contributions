<?
include_once( "ezclassified/classes/ezclassified.php" );
include_once( "ezclassified/classes/ezposition.php" );

$classified = new eZClassified();

$user = eZUser::currentUser();

print( "dette er en test<br>" );

$pos = new eZPosition( 1 );
//  $pos->setName( "bla" );
//  $pos->setUser( $user );
//  $pos->setDescription( "beskrivelse" );
//  $pos->setPrice( "21412" );

//  $pos->setPay( "123" );
//  $pos->setWorkTime( "FullTime" );
//  $pos->setDuration( "Temp" );
//  $pos->setContactPerson( "oipjwgoijwe" );
//  $pos->setWorkPlace( "Skien" );
//  $pos->store();

print( $pos->contactPerson() );
?>
