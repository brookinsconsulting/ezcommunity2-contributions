<?

include_once( "ezarticle/classes/ezarticle.php" );
$PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );
$UserComments = $ini->read_var( "eZArticleMain", "UserComments" );

switch ( $url_array[2] )
{
    case "author":
    {
        $Action = $url_array[3];
        switch( $Action )
        {
            case "list":
            {
                $SortOrder = $url_array[4];
                include( "ezarticle/user/authorlist.php" );
                break;
            }
            case "view":
            {
                $AuthorID = $url_array[4];
                $SortOrder = $url_array[5];
                $Offset = $url_array[6];
                include( "ezarticle/user/authorview.php" );
                break;
            }
        }
        break;
    }

    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        $Offset = $url_array[4];
        if  ( !is_numeric( $Offset ) )
            $Offset = 0;
        
        if ( $PageCaching == "enabled" )
        {
            $CategoryID = $url_array[3];

            include_once( "classes/ezcachefile.php" );
            $file = new eZCacheFile( "ezarticle/cache/", array( "articlelist", $CategoryID, $Offset ),
                                     "cache", "," );
            $cachedFile = $file->filename( true );

            if ( $file->exists() )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articlelist.php" );
            }            
        }
        else
        {
            include( "ezarticle/user/articlelist.php" );
        }
        
    }
    break;


    case "search":
    {
        include( "ezarticle/user/search.php" );
    }
    break;

    case "articleheaderlist":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "ezarticle/user/articleheaderlist.php" );
       
    }
    break;
    
    case "view":
    case "articleview":
    {
        $StaticRendering = false;        
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];

        if ( $PageNumber != -1 )
            if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ))
                $PageNumber= 1;
        
        if ( $PageCaching == "enabled" )
        {
            $CategoryID = $url_array[3];

            $cachedFile = "ezarticle/cache/articleview," . $ArticleID . ",". $PageNumber .".cache";
            if ( file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }
        }
        else
        {
            include( "ezarticle/user/articleview.php" );
            
        }
        
        if  ( ( $PrintableVersion != "enabled" ) && ( $UserComments == "enabled" ) )
        {
            $RedirectURL = "/article/view/$ArticleID/$PageNumber/";
            $article = new eZArticle( $ArticleID );
            $forum = $article->forum();
            $ForumID = $forum->id();
            include( "ezforum/user/messagesimplelist.php" );
        }        
    }
    break;

    case "articleuncached":
    {
        $ViewMode = "static";

        $StaticRendering = true;
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];
        
        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ) )
            $PageNumber= 1;
        
        include( "ezarticle/user/articleview.php" );
    }
    break;

    case "print":
    case "articleprint":
    {
        $StaticRendering = false;
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];

        if ( $PageNumber != -1 )
        {
            if ( !isset( $PageNumber ) || ( $PageNumber == "" ) )
                $PageNumber = -1;
            else if ( $PageNumber < 1 )
                $PageNumber = 1;
        }

        if ( $PageCaching == "enabled" )
        {
            $CategoryID = $url_array[3];

            $cachedFile = "ezarticle/cache/articleprint," . $ArticleID . ",". $PageNumber .".cache";
            if ( file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }
        }
        else
        {
            include( "ezarticle/user/articleview.php" );
        }
    }
    break;

    case "static":
    case "articlestatic":
    {
        $ViewMode = "static";

        $StaticRendering = true;
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];
        
        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ) )
            $PageNumber= 1;
        
        if ( $PageCaching == "enabled" )
        {
            $CategoryID = $url_array[3];

            $cachedFile = "ezarticle/cache/articleview," . $ArticleID . ",". $PageNumber .".cache";
            if ( file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }            
        }
        else
        {
            include( "ezarticle/user/articleview.php" );
        }
    }
    break;

    case "rssheadlines":
    {
        include( "ezarticle/user/articlelistrss.php" );

    }
    break;
    
}

?>
