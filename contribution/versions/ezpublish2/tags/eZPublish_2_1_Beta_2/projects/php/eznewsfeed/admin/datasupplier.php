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

        if ( $url_array[4] == "delete" )
        {
            $Action = "Delete";
            $NewsDeleteIDArray = array( $url_array[5] );
        }

        $ShowUnPublished = "only";
        include( "eznewsfeed/admin/unpublished.php" );
    }
    break;
    
    case "sourcesite":
    {
        if ( $url_array[3]  == "edit" )
        {
            $Action = "Edit";
        }
        if ( $url_array[3]  == "insert" )
        {
            $Action = "Insert";
        }

        if ( $url_array[3]  == "update" )
        {
            $Action = "Update";
        }
        if ( $url_array[3]  == "new" )
        {
            $Action = "New";
        }
        if ( $url_array[3]  == "delete" )
        {
            $Action = "Delete";
        }

        $SourceSiteID = $url_array[4];
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
        else if ( $url_array[3]  == "delete" )
        {
            $arg = $url_array[4];
            
            setType( $arg, "integer" );
            if ( $arg != 0 )
            {
                $Action = "Delete";
                $NewsID = $arg;
            }
        }

        
        include( "eznewsfeed/admin/newsedit.php" );
    }
    break;
    
    case "categoryedit":
    case "category":
    {
        if ( $url_array[3]  == "new" )
        {
            $Action = "New";
        }

        if ( $url_array[3]  == "edit" )
        {
            $CategoryID = $url_array[4];
            $Action = "Edit";
        }        

        if ( $url_array[3]  == "delete" )
        {
            $CategoryID = $url_array[4];
            $Action = "Delete";
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

    case "search":
    {
        include( "eznewsfeed/admin/newssearch.php" );
    }
    break;
    
}

?>
