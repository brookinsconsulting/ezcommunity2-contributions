<?php

$t = new Template( "templates" );
$t->set_file( "feil", "error.tpl" );

$standardMessage = "Du har ikke rettigheter til å gjøre dette";

if( !isset( $message ) )
    $message = $standardMessage;

$t->set_var( "error", $message );

$t->pparse( "output", "feil" );

?>
