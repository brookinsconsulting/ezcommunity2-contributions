<h1>This is an eZ publish admin page</h1>

<form action="/example/page/" method="post">

<input type="text" name="Value" value="" />

<input type="submit" value="send" />

</form>


<?
if ( isset( $Value ) )
{
    print( "You entered: " . $Value );
}


?>
