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
            $PageNumber= 0;

        include( "ezarticle/admin/articlepreview.php" );
    }
    break;
    
    case "articleedit":
    {
        if ( $url_array[3] == "insert" )
            $Action = "Insert";

        if ( $url_array[3] == "new" )
            $Action = "New";

        if ( $url_array[3] == "edit" )
        {
            $Action = "Edit";
            $ArticleID = $url_array[4];
        }

        if ( $url_array[3] == "delete" )
        {
            $Action = "Delete";
            $ArticleID = $url_array[4];
        }
        
        

        include( "ezarticle/admin/articleedit.php" );
    }
    break;
}

?>
