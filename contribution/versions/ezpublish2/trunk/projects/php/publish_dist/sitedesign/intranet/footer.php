	<br />
    </td>
   	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>

	<td width="1%" bgcolor="#efffef">
	
	<!-- Oppslagstavle kommer her! -->
    
    <?
        include( "ezuser/user/userbox.php" );
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
        include( "eztodo/user/todomenulist.php" );
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
	<h2>Different sitedesigns:</h2>
    <a href="<? print( $REQUEST_URI . "?Design=1"); ?>"><b>Portal</b></a><br />
    <a href="<? print( $REQUEST_URI . "?Design=2"); ?>"><b>E-commerce</b></a><br />
    <a href="<? print( $REQUEST_URI . "?Design=3"); ?>"><b>News</b></a><br />
    
    <!-- Oppslagstavle fram til hit! -->
	
	<img src="/images/1x1.gif" width="130" height="1" border="0"><br />

	</td>
  </tr>
</table>

<div class="credit" align="center" valign="bottom"><br />Powered by <a class="credit" href="http://publish.ez.no">eZ publish</a> made by <img src="/images/ezsystems-symbol-12x12.gif" width="12" height="12" border="0" alt="0" align="absmiddle" /> <a class="credit" href="http://www.ez.no">eZ systems</a></div>

</body>
</html>
