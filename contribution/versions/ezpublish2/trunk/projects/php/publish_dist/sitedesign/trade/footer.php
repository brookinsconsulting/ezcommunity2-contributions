	<br />
    </td>
 	<td valign="top" rowspan="2" width="1%" bgcolor="#eeeeee">

    <? include( "ezuser/user/userbox.php" ); ?>

    <? include( "eztrade/user/smallcart.php" );    ?>
    
	<img src="/images/1x1.gif" width="100" height="1" border="0"><br />

	</td>
  </tr>
  <tr> 
	<td align="center" valign="bottom" bgcolor="#eeeeee">
	<a href="http://trade.ez.no"><img src="/images/poweredbyeztrade.gif" width="62" height="75" border="0"></a><br />
	<img src="/images/1x1.gif" width="80" height="10" border="0"><br />


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
    
	</td>
	</tr>
</table>

<center>
eZ systems - <a href="http://ez.no">ez.no</a>
</center>

</body>
</html>

