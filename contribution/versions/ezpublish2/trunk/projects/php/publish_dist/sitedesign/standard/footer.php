
    </td>
   	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>

	<td width="1%" bgcolor="#f0f0f0">

   	<!-- Oppslagstavle kommer her! -->
    
    <?
          include( "ezuser/user/userbox.php" );
	?>

    <?
          include( "ezpoll/user/votebox.php" );
    ?>
        
	<!-- Oppslagstavle fram til hit! -->


	<hr noshade="noshade" size="4" />
	
    <?
    $session =& eZSession::globalSession();


if ( $session->fetch() == false )
{
    $session =& eZSession::globalSession();
    $session->store();    
}

if ( $Design == 2 )
{
    $session->setVariable( "SiteDesign", "intranet" );
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $REQUEST_URI" );
    exit();
}

    ?>
    <a href="<? print( $REQUEST_URI . "?Design=2"); ?>"> here</a>
	 <img src="/images/1x1.gif" width="130" height="1" border="0"><br />
	 
	</td>
  </tr>
</table>
<div class="credit" align="center" valign="bottom"><br />Powered by <a class="credit" href="http://publish.ez.no">eZ publish</a> made by <img src="/images/logo-mini.gif" width="16" height="16" border="0" alt="0" align="absmiddle" /> <a class="credit" href="http://publish.ez.no">eZ systems</a></div>

</body>
</html>
