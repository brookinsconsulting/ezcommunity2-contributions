<?

if ( !isset( $Max ) )
{
    $Max = 10;
}

if ( !isset( $Index ) )
{
    $Index = 0;
}
else if ( !is_numeric( $Index ) )
{
    $Index = 0;
}

if ( !isset( $SearchText ) )
{
    $SearchText = "";
    $search_encoded = "";
}
else
{
    $search_encoded = $SearchText;
    $search_encoded = eZURITool::encode( $search_encoded );
}
?>
