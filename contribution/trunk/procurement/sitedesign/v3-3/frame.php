<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
	<title><?php
	$SiteTitle = $ini->read_var( "site", "SiteTitle" );
	if ( isset( $SiteTitleAppend ) )
	    print( $SiteTitle . " - " . $SiteTitleAppend );
	else
	    print( $SiteTitle );
?></title>
	<link rel="stylesheet" type="text/css" href="http://www.ladivaloca.org<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/w3cstyle.css" />
	<link rel="shortcut icon" href="http://www.ladivaloca.org/favicon.ico" type="image/x-icon" />
	<meta name="author" content="<?php
	    $SiteAuthor = $ini->read_var( "site", "SiteAuthor" );
	    print( $SiteAuthor );
?>" />
	<meta name="copyright" content="<?php
	    $SiteCopyright = $ini->read_var( "site", "SiteCopyright" );
	    print( $SiteCopyright );
?>" />
	<meta name="description" content="<?php
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
	<meta name="keywords" content="<?php
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
</head>

<body>
	<div id="LayerLogo" class="LayerLogo" align="center" style="">
		<a href="/"><img src="http://ladivaloca.org/sitedesign/nsb_rfp/images/logo.gif" width="137" height="136" alt="North Slope Borough Logo" border="0" /></a>
	</div>
	
	<div id="LayerMenu" class="LayerMenu" style="">
	 <?
          include_once( "classes/ezdate.php" );
	  include_once( "ezuser/classes/ezuser.php" );
          $bdate = new ezdate();
          $now_date = ucfirst($bdate->monthName()) . " " . $bdate->day() . ", " .  $bdate->year();
         ?>
	 <div class="LayerMenuDate" style=""> <? print($now_date); ?> </div>
	
		<?
        	 if ( eZUser::currentUser() ) {
		    $NoAddress = true;
       		    include( "ezuser/user/userbox.php" );
        	 }else {
		?>
			<span class="LayerMenuItem">&nbsp;</span>
		        <span class="LayerMenuItem"><a href="/login">Login</a><br /></span>
		<?
        	}
		?> 
		<span>
	         <span class="LayerMenuItem"><a href="/proposals">Proposals</a></span>
                 <span class="LayerMenuItem"><a href="/map">Proposals Map</a></span>
	         <span class="LayerMenuItem"><a href="/areas">Areas</a></span>

		 <span class="LayerMenuItem"><a href="/authors">Authors</a></span>
	         <span class="LayerMenuItem"><a href="/report">Report</a></span>

		 <span class="LayerMenuItem">&nbsp;</span>

                 <span class="LayerMenuItem"><a href="/feedback">Feedback</a></span>

		 <span class="LayerMenuItem">&nbsp;</span>

		 <span class="LayerMenuItem"><a target="_new" href="http://www.north-slope.org/nsb/default.htm">Borough Home</a></span>
		<br /><br /><br />
        </div>

	<div id="LayerMain" class="LayerMain" style="">

		<span><? print( $MainContents ); ?> </span>

		<div id="LayerCopyright" style="">
		<span class="copyrights">Content & Visual Design &#169; <? print date("Y"); ?> <br /> North Slope Borough All Rights Reserved.<br /> Web Application released under the <a style="text-decoration:none;" href="http://ladivaloca.org/copyleft">GNU/GPL</a>  </span>
	  	</div>
        </div>

<?
if ( $ini->read_var( "eZStatsMain", "StoreStats" ) == "enabled" )
{
    $seed = md5( microtime() );
    $spacer = "		";

    $imgSrc = $GlobalSiteIni->WWWDir . "index.php/stats/store/rx$seed-" . $REQUEST_URI . "1x1.gif";
    print( "$spacer<span>\n$spacer<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />\n$spacer</span>\n" );
}
?>
</body>
</html>