<?
switch ( $url_array[2] )
{
    case "imageview" :
    {
        $ImageID = $url_array[3];

        include( "ezimagecatalogue/user/imageview.php" );
    }
    break;

    case "image" :
    {
        switch ( $url_array[3] )
        {
            case "list" :
            {
                $CategoryID = $url_array[4];
                include( "ezimagecatalogue/user/imagelist.php" );
            }
            break;

            case "new" :
            {
                $Action = "New";
                include( "ezimagecatalogue/user/imageedit.php" );
            }
            break;
            
            case "Insert" :
            {
                $Action = "Insert";
                include( "ezimagecatalogue/user/imageedit.php" );
            }
            break;

            case "edit" :
            {
                $ImageID = $url_array[4];
                $Action = "Edit";
                include( "ezimagecatalogue/user/imageedit.php" );
            }
            break;

            case "update" :
            {
                $ImageID = $url_array[4];
                $Action = "Update";
                include( "ezimagecatalogue/user/imageedit.php" );
            }
            break;
        }
    }
    break;

    case "download" :
    {
        $ImageID = $url_array[3];
        include( "ezimagecatalogue/user/filedownload.php" );
    }
    break;
    
    case "category" :
    {
        switch( $url_array[3] )
        {
           
            case "new" :
            {
                $CurrentCategoryID = $url_array[4];
                $Action = "New";
                include( "ezimagecatalogue/user/categoryedit.php" );
            }
            break;

            case "insert" :
            {
                $Action = "Insert";
                include( "ezimagecatalogue/user/categoryedit.php" );
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $CategoryID = $url_array[4];
                include( "ezimagecatalogue/user/categoryedit.php" );
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $CategoryID = $url_array[4];
                include( "ezimagecatalogue/user/categoryedit.php" );
            }
            break;


        }
    }
    break;
}
?>
