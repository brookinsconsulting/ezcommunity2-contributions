
    </td>
    <td width="1%" bgcolor="#ffffff">

    <img src="/images/1x1.gif" width="130" height="1" border="0"><br />
	
	<!-- Oppslagstavle kommer her! -->
    
    <?
        
          include( "ezuser/user/userbox.php" );
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

	
	<p class="smallbold" align="center"><a href="http://publish.ez.no"><img src="/images/poweredbyezpublish.gif" width="70" height="70" align="center" border="0"></a></p>

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


?>
    <a href="<? print( $REQUEST_URI . "?Design=1"); ?>">Portal site</a>
    <a href="<? print( $REQUEST_URI . "?Design=2"); ?>">E-commerce</a>
    
    <!-- Oppslagstavle fram til hit! -->

	</td>
  </tr>
</table>
</body>
</html>
