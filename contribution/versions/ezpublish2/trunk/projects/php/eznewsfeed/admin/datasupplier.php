
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
        else if ( $url_array[3]  == "edit" )
        {
            $arg = $url_array[4];
            
            setType( $arg, "integer" );
            if ( $arg != 0 )
            {
                $Action = "Edit";
                $NewsID = $arg;
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

    case "importnews":
    {
        include( "eznewsfeed/admin/importnews.php" );
    }
    break;
    
}

?>
