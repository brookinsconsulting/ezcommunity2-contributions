<?
switch ( $url_array[2] )
{
    case "page":
    {
        include( "ezexample/admin/page.php" );
    }
    break;
   
    case "page2":
    {
        include( "ezexample/admin/page2.php" );
    }
    break;

    case "page3":
    {
        include( "ezexample/admin/page3.php" );
    }
    break;
    
    case "page4":
    {
        include( "ezexample/admin/page4.php" );
    }
    break;

    default :
    {
        // go to default module page or show an error message
        print( "Error: your page request was not found" );
    }
}
?>
