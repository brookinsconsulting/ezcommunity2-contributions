<?

switch ( $url_array[2] )
{
    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "ezarticle/admin/articlelist.php" );
    }
    break;

    case "articlepreview":
    {
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];
        
        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) )
            $PageNumber= 1;

        include( "ezarticle/admin/articlepreview.php" );
    }
    break;

    
    case "articleedit":
    {
        switch ( $url_array[3] )
        {
            case "insert" :
            {
                $Action = "Insert";
                include( "ezarticle/admin/articleedit.php" );
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $ArticleID = $url_array[4];
                include( "ezarticle/admin/articleedit.php" );
            }
            break;

            case "new" :
            {
                $Action = "New";
                include( "ezarticle/admin/articleedit.php" );
            }
            break;
            
            case "edit" :
            {
                $Action = "Edit";
                $ArticleID = $url_array[4];
                include( "ezarticle/admin/articleedit.php" );
            }
            break;

            case "delete" :
            {
                $Action = "Delete";
                $ArticleID = $url_array[4];
                include( "ezarticle/admin/articleedit.php" );
            }
            break;

            case "imagelist" :
            {
                $ArticleID = $url_array[4];
                include( "ezarticle/admin/imagelist.php" );
            }
            break;

            case "imageedit" :
            {
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $Action = "New";
                        $ArticleID = $url_array[5];
                        include( "ezarticle/admin/imageedit.php" );
                    }
                    break;

                    
                    case "storedef" :
                    {
                        $Action = "StoreDef";
                        $ArticleID = $url_array[5];
                        include( "ezarticle/admin/imageedit.php" );
                    }
                    break;

                    default :
                    {
                        include( "ezarticle/admin/imageedit.php" );
                    }
                    
                }
            
            }
            break;
            
        }
    }
    break;


    case "categoryedit":
    {
        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "ezarticle/admin/groupedit.php" );
        }
        if ( $url_array[3] == "new" )
        {
            $Action = "new";
            include( "ezarticle/admin/groupedit.php" );
        }
        if ( $url_array[3] == "update" )
        {
            $CategoryID = $url_array[4];
            $Action = "update";
            include( "ezarticle/admin/groupedit.php" );
        }
        if ( $url_array[3] == "delete" )
        {
            $CategoryID = $url_array[4];
            $Action = "delete";
            include( "ezarticle/admin/groupedit.php" );
        }
        if ( $url_array[3] == "edit" )
        {
            $CategoryID = $url_array[4];
            $Action = "edit";
            include( "ezarticle/admin/groupedit.php" );
        }

    }
    break;
        
}

?>
