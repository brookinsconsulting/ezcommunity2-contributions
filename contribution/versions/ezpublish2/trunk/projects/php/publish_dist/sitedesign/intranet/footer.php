	<!-- Main content view end -->
	
	<br />
    </td>
   	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>

	<td width="1%" bgcolor="#f4fbf4">
	
   	<!-- Right menu start -->
    
    <?
    include( "ezuser/user/userbox.php" );
    ?>

    <?
    include( "ezmail/user/menubox.php" );
    ?>

    <?
    include( "eztodo/user/menubox.php" );
    ?>

    <?
    include( "eztodo/user/todomenulist.php" );
    ?>

    <?
    include( "ezcalendar/user/menubox.php" );
    ?>

    <?
    // a short list of articles from the given category
    // shows $Limit number starting from offset $Offset    
    $CategoryID=5;
    $Offset=0;
    $Limit=1;
    include( "ezarticle/user/smallarticlelist.php" );
    ?>
    
    <?
    include( "ezpoll/user/votebox.php" );
    ?>

	<?
	include( "ezcontact/user/consultationlist.php" );
	?>
	     
	<hr noshade="noshade" size="4" />

	
    <?
    // change design on the fly
    
$session =& eZSession::globalSession();

if ( $session->fetch() == false )
{
    $session =& eZSession::globalSession();
    $session->store();    
}

if ( $Design == 1 )
{
    $session->setVariable( "SiteDesign", "standard" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) && ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}

if ( $Design == 2 )
{
    $session->setVariable( "SiteDesign", "trade" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) && ( $REQUEST_URI != "" ) )
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
    <a href="<? print( $REQUEST_URI . "?Design=2"); ?>"><b>Trade</b></a><br />
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

</body>
</html>
