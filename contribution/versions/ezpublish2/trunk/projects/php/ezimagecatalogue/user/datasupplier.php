<?
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezhttptool.php" );
include_once( "ezuser/classes/ezuser.php" );

function writeAtAll()
{
    $user = eZUser::currentUser();
    if( eZObjectPermission::getObjects( "imagecatalogue_category", 'w', true ) < 1
        && !eZPermission::checkPermission( $user, "eZImageCatalogue", "WriteToRoot" ) )
    {
        $text = "You do not have write permission to any categories";
        $info = urlencode( $text );
        eZHTTPTool::header( "Location: /error/403?Info=$info" );
        exit();
    }
    return true;
}

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
                writeAtAll();
                $Action = "New";
                include( "ezimagecatalogue/user/imageedit.php" );
            }
            break;
            
            case "Insert" :
            {
                writeAtAll();
                $Action = "Insert";
                include( "ezimagecatalogue/user/imageedit.php" );
            }
            break;

            case "edit" :
            {
                $ImageID = $url_array[4];
                $Action = "Edit";
                if( eZObjectPermission::hasPermission( $ImageID, "imagecatalogue_image", 'w' ) && writeAtAll() )
                {
                    include( "ezimagecatalogue/user/imageedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
            }
            break;

            case "update" :
            {
                writeAtAll();
                $ImageID = $url_array[4];
                $Action = "Update";
                include( "ezimagecatalogue/user/imageedit.php" );
            }
            break;
            default :
            {
                eZHTTPTool::header( "Location: /error/404" );
                exit();
            }
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
                writeAtAll();
                $CurrentCategoryID = $url_array[4];
                $Action = "New";
                include( "ezimagecatalogue/user/categoryedit.php" );
            }
            break;

            case "insert" :
            {
                writeAtAll();
                $Action = "Insert";
                include( "ezimagecatalogue/user/categoryedit.php" );
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $CategoryID = $url_array[4];
                if( eZObjectPermission::hasPermission( $CategoryID, "imagecatalogue_category", 'w' ) && writeAtAll() )
                {
                    include( "ezimagecatalogue/user/categoryedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $CategoryID = $url_array[4];
                if( eZObjectPermission::hasPermission( $CategoryID, "imagecatalogue_category", 'w' ) && writeAtAll() )
                {
                    include( "ezimagecatalogue/user/categoryedit.php" );
                }
                else
                {
                    eZHTTPTool::header( "Location: /error/403" );
                    exit();
                }

            }
            break;


        }
    }
    break;
}
?>
