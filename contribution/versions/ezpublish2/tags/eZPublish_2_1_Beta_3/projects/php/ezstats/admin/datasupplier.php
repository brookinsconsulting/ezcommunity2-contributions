<?
include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );
$user = eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZStats", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

switch ( $url_array[2] )
{
    case "overview" :
    {
        include( "ezstats/admin/overview.php" );
    }
    break;

    case "pageviewlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/pageviewlist.php" );
    }
    break;

    case "visitorlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/visitorlist.php" );
    }
    break;

    case "refererlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];
        if ( !isset( $ExcludeDomain ) )
            $ExcludeDomain = $url_array[6];

        include( "ezstats/admin/refererlist.php" );
    }
    break;

    case "browserlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/browserlist.php" );
    }
    break;

    case "requestpagelist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        $Offset = $url_array[5];

        include( "ezstats/admin/requestpagelist.php" );
    }
    break;
    
    case "yearreport" :
    {
        $Year = $url_array[3];

        include( "ezstats/admin/yearreport.php" );
    }
    break;

    case "monthreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezstats/admin/monthreport.php" );
    }
    break;

    case "dayreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        $Day = $url_array[5];

        include( "ezstats/admin/dayreport.php" );
    }
    break;

    case "productreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        
        include( "ezstats/admin/productreport.php" );
    }
    break;
    
    case "entryexitreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        
        include( "ezstats/admin/entryexitpages.php" );
    }
    break;
    
}

?>
