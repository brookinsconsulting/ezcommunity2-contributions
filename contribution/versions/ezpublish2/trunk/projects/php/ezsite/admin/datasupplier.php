<?
switch ( $url_array[2] )
{
    case "design" :
    {
        switch( $url_array[3] )
        {
            case "edit" :
            {
                $Action = "edit";
                include( "ezsite/admin/designedit.php" );
            }
            break;

            case "update" :
            {
                $Action = "update";
                include( "ezsite/admin/designedit.php" );
            }
            break;

            case "test" :
            {
                include( "ezsite/admin/test.php" );
            }
            break;

        }
    }
    break;

    default :
    {
        include( "ezuser/admin/login.php" );
    }
    break;

}
?>
