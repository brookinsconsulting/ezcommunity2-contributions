
<?php

switch ( $url_array[2] )
{
    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        $ShowUnPublished = "no";
        
        include( "eznewsfeed/admin/newsarchive.php" );
    }
    break;

    case "unpublished":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        $ShowUnPublished = "only";
        include( "eznewsfeed/admin/newsarchive.php" );
    }
    break;
    
    case "sourcesite":
    {
        if ( $url_array[3]  == "edit" )
        {
            $Action = "Edit";
        }
        
        include( "eznewsfeed/admin/sourcesiteedit.php" );
    }
    break;
    
    case "news":
    {
        if ( $url_array[3]  == "new" )
        {
            $Action = "New";
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
        if ( $url_array[3]  == "new" )
        {
            $Action = "New";
        }
            
        include( "eznewsfeed/admin/categoryedit.php" );
    }
    break;

    case "importnews":
    {
        if ( $url_array[3]  == "fetch" )
        {
            $Action = "Fetch";
            $SourceSiteID = $url_array[4]; 
        }
        
        include( "eznewsfeed/admin/importnews.php" );
    }
    break;
    
}

?>
