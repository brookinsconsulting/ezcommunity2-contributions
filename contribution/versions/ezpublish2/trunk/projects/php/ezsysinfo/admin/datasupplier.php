<?

switch( $url_array[2] )
{
    case "sysinfo" :
    {
        include( "ezsysinfo/admin/sysinfo.php" );
    }
    break;

    case "netinfo" :
    {
        include( "ezsysinfo/admin/netinfo.php" );
    }
    break;

    case "hwinfo" :
    {
        include( "ezsysinfo/admin/hwinfo.php" );
    }
    break;

    case "meminfo" :
    {
        include( "ezsysinfo/admin/meminfo.php" );
    }
    break;
    
    case "fileinfo" :
    {
        include( "ezsysinfo/admin/fileinfo.php" );
    }
    break;
    
}

?>
