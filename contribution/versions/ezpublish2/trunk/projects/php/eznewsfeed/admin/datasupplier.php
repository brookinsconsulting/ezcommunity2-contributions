
<?php

switch ( $url_array[2] )
{
    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "eznewsfeed/admin/newsarchive.php" );
    }
    break;

    case "news":
    {
        if ( $url_array[3]  == "new" )
        {
            $Action == "New";
        }
        else
        {
            $arg = $url_array[3];
            
            setType( $arg, "integer" );
            if ( $arg != 0 )
            {
                $Action == "Edit";
                $NewsID = $arg;
                print( "" );
            }
        }
        
        include( "eznewsfeed/admin/newsedit.php" );
    }
    break;
    
    case "category":
    {
        if ( $url_array[2]  == "new" )
        {
            $Action == "New";
        }
            
        include( "eznewsfeed/admin/categoryedit.php" );
    }
    break;
    
}

?>
