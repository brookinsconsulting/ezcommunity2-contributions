<?

switch( $url_array[2] )
{
    case "view" :
    {
        $MessageID = $url_array[3];
        include( "ezmessage/admin/messageview.php" );
    }
    break;    

    case "list" :
    {
        include( "ezmessage/admin/messagelist.php" );
    }
    break;    

    case "edit" :
    {
        include( "ezmessage/admin/messageedit.php" );
    }
    break;    
}

?>
