<? include_once("classes/ezmenu.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

 <head>
	<title><? 
          $SiteTitle = $ini->read_var( "site", "SiteTitle" );
           if ( isset( $SiteTitleAppend ) && $SiteTitleAppend != "" )
               print( $SiteTitleAppend . " : " . $SiteTitle);
	   else
	       print( $SiteTitle );
        ?></title>
	<link rel="stylesheet" type="text/css" href="<? print $GlobalSiteIni->WWWDir; ?>/design/<? print ($GlobalSiteDesign); ?>/w3cstyle.css" />
        <? $SiteURL = $ini->read_var( "site", "SiteURL" );
          // http://procurement.north-slope.org/favicon.ico
        ?><link rel="shortcut icon" href="http://<? print $SiteURL ?>/favicon.ico" type="image/x-icon" />
	<meta name="author" content="<?
	    $SiteAuthor = $ini->read_var( "site", "SiteAuthor" );
	    print( $SiteAuthor );
            ?>" />
	<meta name="copyright" content="<?
	    $SiteCopyright = $ini->read_var( "site", "SiteCopyright" );
	    print( $SiteCopyright );
            ?>" />
	<meta name="description" content="<?
	    if ( isset( $SiteDescriptionOverride ) )
	    {
	    print( $SiteDescriptionOverride );
	    }
   	    else
	    {
	    $SiteDescription = $ini->read_var( "site", "SiteDescription" );
	    print( $SiteDescription );
	    }
        ?>" />
	<meta name="keywords" content="<?
	    if ( isset( $SiteKeywordsOverride ) )
	    {
	    print( $SiteKeywordsOverride );
	    }
	    else
	    {
	    $SiteKeywords = $ini->read_var( "site", "SiteKeywords" );
	    print( $SiteKeywords );
	    }
	?>" />
	<meta name="MSSmartTagsPreventParsing" content="TRUE" />
	<? /* search engine / robot prevention 
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META NAME="ROBOTS" CONTENT="NOARCHIVE">
	<META NAME="GOOGLEBOT" CONTENT="NOARCHIVE">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<META NAME="GOOGLEBOT" CONTENT="NOINDEX, NOFOLLOW"> 
	*/ ?>
	<script language="javascript">
	 var preload = new Image(); 
         preload.src='http://<? print $SiteURL ?>/design/nsb/images/menubgover.png';
	</script>
 </head>

	<body onload="">
	<div id="LayerLogo" class="LayerLogo" align="left" style="padding-bottom: 5px;">
		<a href="/"><img src="/design/nsb/images/logo.png" width="150" height="151" alt="North Slope Borough : Logo" border="0" /></a>
	</div>
	
	<div id="LayerMenu" class="LayerMenu" style="">
	 <?
          include_once( "classes/ezdate.php" );
	  include_once( "ezuser/classes/ezuser.php" );
          $bdate = new ezdate();
          $now_date = ucfirst($bdate->monthName()) . " " . $bdate->day() . ", " .  $bdate->year();
         ?>

	 <div class="LayerMenuDate" style=""> <? print($now_date); ?> </div>

	 <? if ( eZUser::currentUser() ) {
	      $NoAddress = true;
	      // user menu
	      include( "ezuser/user/userbox.php" );
	    }else {
	      // login menu
	      $header_menu = array( 
			  '&nbsp;', 
			  array( 'Link' => '/login', 'Name' => 'Login' ), 
			  '&nbsp;', '<br />'
			  );
	   
	      renderSilverMenu($header_menu);
	    }

        // full menus
        $menus = array( 
	 array( 'Link' => '/map', 'Name' => 'Proposals' ),
         array( 'Link' => '/filemanager/map', 'Name' => 'Attachments' ),
         array( 'Link' => '/report', 'Name' => 'Report' ),
	 '&nbsp;',
	 array( 'Link' => '/contact/person/list', 'Name' => 'People' ),
	 array( 'Link' => '/contact/company/list', 'Name' => 'Companies' ),
	 array( 'Link' => '/planholders', 'Name' => 'Planholders' ),
	 '&nbsp;',
	 array( 'Link' => '/feedback', 'Name' => 'Feedback' ),
	 array( 'Link' => '/about/disclaimer', 'Name' => 'Disclaimer' ),
	 '&nbsp;',
	 array( 'Link' => 'http://www.north-slope.org/nsb/default.htm', 'Name' => 'North Slope Borough', 'Target' => '_new' ),
	 '<br>',
	 '<br>',
	 '<br>',
	 '<br />'
	 );

         renderSilverMenu($menus);
         ?>
        </div>

	<div id="LayerMain" class="LayerMain">
	  <span><? print( $MainContents ); ?></span>

	  <div id="LayerCopyright" style="vertical-align: bottom;">
	   <span class="copyrights"><span style="color: black;"> Content & Visual Design &#169; 2003 - <? print date("Y"); ?> </span><br /> North Slope Borough - All Rights Reserved <br /> <span style="color: black;">Web Application released under the <a style="text-decoration:none;" href="/copyleft">GNU/GPL</a> </span>  </span>
	  </div>
        </div> 
<?
if ( $ini->read_var( "eZStatsMain", "StoreStats" ) == "enabled" )
{
  $seed = md5( microtime() );
  $spacer = "\n";
  $imgSrc = $GlobalSiteIni->WWWDir . "index.php/stats/store/rx$seed-" . $REQUEST_URI . "1x1.gif";
  print( "$spacer<span>\n<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />\n</span>\n" ); } ?> 
 </body>
</html>