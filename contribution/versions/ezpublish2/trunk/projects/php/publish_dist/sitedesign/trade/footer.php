	<!-- Main content view end -->
	
	<br />
    </td>
   	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>

	<td width="1%" bgcolor="#f6f6fa">

   	<!-- Right menu start -->
	
    <?
	include( "ezuser/user/userbox.php" ); 
	?>
	
	<?
	include( "eztrade/user/menubox.php" );
	?>

    <?
	include( "eztrade/user/smallcart.php" );    
	?>
    
	<hr noshade="noshade" size="4" />

    <?
    // change design on the fly
    include_once( "classes/ezhttptool.php" );
    
$session = new eZSession();

if ( $session->fetch() == false )
{
    $session = new eZSession();    
//   $session =& eZSession::globalSession();
    $session->store();    
}

if ( $Design == 1 )
{
    $session->setVariable( "Bla", "ikkeno" );

    $session->setVariable( "SiteDesign", "standard" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) || ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }

    eZHTTPTool::header( "Location: $redir" );
    exit();
}

if ( $Design == 2 )
{
    $session->setVariable( "SiteDesign", "intranet" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) || ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}

if ( $Design == 3 )
{
    $session->setVariable( "SiteDesign", "news" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) && ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}



?>
	<h2>Alternative sitedesigns:</h2>
    <a href="<? print( $REQUEST_URI . "?Design=1"); ?>"><b>Portal</b></a><br />
    <a href="<? print( $REQUEST_URI . "?Design=2"); ?>"><b>Intranet</b></a><br />
    <a href="<? print( $REQUEST_URI . "?Design=3"); ?>"><b>News</b></a><br />

   	<!-- Right menu end -->
	
	<img src="/images/1x1.gif" width="130" height="20" border="0" alt="" /><br />
	
	<div align="center">
	<a target="_blank" href="http://publish.ez.no"><img src="/images/powered-by-ezpublish-100x35-trans-lgrey.gif" width="100" height="35" border="0" alt="Powered by eZ publish" /></a>
	</div>
	
	<img src="/images/1x1.gif" width="130" height="8" border="0" alt="" /><br />

	</td>
  </tr>
</table>

<?
// Store the statistics with a callback image.
// It will be no overhead with this method for storing stats
//

$StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // callback for storing the stats
    $imgSrc = "/stats/store" . $REQUEST_URI . "1x1.gif";
    print( "<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />" );    
}

?>

</body>
</html>
