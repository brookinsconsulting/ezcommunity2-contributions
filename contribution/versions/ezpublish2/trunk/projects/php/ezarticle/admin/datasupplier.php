<?

include_once( "classes/ezhttptool.php" );

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

    case "unpublished":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "ezarticle/admin/unpublishedlist.php" );
    }
    break;

    case "search" :
    {
        include( "ezarticle/admin/search.php" );
    }
    break;

    case "view":    
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

            case "cancel" :
            {
                $Action = "Cancel";
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

            case "filelist" :
            {
                $ArticleID = $url_array[4];
                include( "ezarticle/admin/filelist.php" );
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

                    case "edit" :
                    {
                        $Action = "Edit";
                        $ArticleID = $url_array[6];
                        $ImageID = $url_array[5];
                        include( "ezarticle/admin/imageedit.php" );
                    }
                    break;

                    case "storedef" :
                    {
                        $Action = "StoreDef";
                        if ( isset( $DeleteSelected ) )
                            $Action = "Delete";
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

            case "fileedit" :
            {
                switch ( $url_array[4] )
                {
                    case "new" :
                    {
                        $Action = "New";
                        $ArticleID = $url_array[5];
                        include( "ezarticle/admin/fileedit.php" );
                    }
                    break;

                    case "edit" :
                    {
                        $Action = "Edit";
                        $ArticleID = $url_array[6];
                        $FileID = $url_array[5];
                        include( "ezarticle/admin/fileedit.php" );
                    }
                    break;

                    case "delete" :
                    {
                        $Action = "Delete";
                        $ArticleID = $url_array[6];
                        $FileID = $url_array[5];
                        include( "ezarticle/admin/fileedit.php" );
                    }
                    break;
                    
                    default :
                    {
                        include( "ezarticle/admin/fileedit.php" );
                    }
                    
                }
            
            }
            break;
            
            
        }
    }
    break;


    case "categoryedit":
    {
        // make switch
        if ( $url_array[3] == "cancel" )        
        {
            $Action = "Cancel";
            $ArticleID = $url_array[4];
            eZHTTPTool::header( "Location: /article/archive/$CategoryID/" );
            exit();
        }        

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
