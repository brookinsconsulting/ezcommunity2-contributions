<?

switch ( $url_array[2] )
{
    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "ezarticle/user/articlelist.php" );
    }
    break;

    case "articleview":
    {
        $StaticRendering = false;        
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];
        
        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ))
            $PageNumber= 1;
        
        if ( $PageCaching == "enabled" )
        {
            print( "cached version<br>" );
        
            $CategoryID = $url_array[3];

            $cachedFile = "ezarticle/cache/articleview," . $ArticleID . ",". $PageNumber .".cache";
            if ( file_exists( $cachedFile ) )
            {
                print( "pure static<br>" );
                
                include( $cachedFile );
            }
            else
            {
                print( "first time generated<br>" );                
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }            
        }
        else
        {
            print( "uncached version" );
            

            include( "ezarticle/user/articleview.php" );
        }
    }
    break;
    

    case "articlestatic":
    {
        $StaticRendering = true;
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];
        
        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ))
            $PageNumber= 1;
        
        if ( $PageCaching == "enabled" )
        {
            print( "cached version<br>" );
        
            $CategoryID = $url_array[3];

            $cachedFile = "ezarticle/cache/articleview," . $ArticleID . ",". $PageNumber .".cache";
            if ( file_exists( $cachedFile ) )
            {
                print( "pure static<br>" );
                
                include( $cachedFile );
            }
            else
            {
                print( "first time generated<br>" );                
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }            
        }
        else
        {
            print( "uncached version" );
            

            include( "ezarticle/user/articleview.php" );
        }
    }
    break;
    
}

?>
