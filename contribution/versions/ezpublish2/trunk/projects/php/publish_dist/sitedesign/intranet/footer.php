
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
$session =& eZSession::globalSession();

if ( $session->fetch() == false )
{
    $session =& eZSession::globalSession();
    $session->store();    
}

if ( $Design == 1 )
{
    $session->setVariable( "SiteDesign", "standard" );
    Header( "Location: $REQUEST_URI" );
    exit();
}

    ?>
    <a href="<? print( $REQUEST_URI . "/?Design=1"); ?>"> here</a>
    
    <!-- Oppslagstavle fram til hit! -->

	</td>
  </tr>
</table>
</body>
</html>
