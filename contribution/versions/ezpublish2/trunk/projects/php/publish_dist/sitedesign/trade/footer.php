
    </td>
   	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>

	<td width="1%" bgcolor="#f0f0f0">

   	<!-- Oppslagstavle kommer her! -->

    <? include( "ezuser/user/userbox.php" ); ?>

    <? include( "eztrade/user/smallcart.php" );    ?>
    
	<!-- Oppslagstavle fram til hit! -->


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


?>
    <a href="<? print( $REQUEST_URI . "?Design=1"); ?>">Portal site</a>
    <a href="<? print( $REQUEST_URI . "?Design=2"); ?>">Intranet</a>
    
	<img src="/images/1x1.gif" width="130" height="1" border="0"><br />
	</td>
  </tr>
</table>
<div class="credit" align="center" valign="bottom"><br />Powered by <a class="credit" href="http://publish.ez.no">eZ publish</a> made by <img src="/images/ezsystems-symbol-12x12.gif" width="12" height="12" border="0" alt="0" align="absmiddle" /> <a class="credit" href="http://publish.ez.no">eZ systems</a></div>

</body>
</html>
