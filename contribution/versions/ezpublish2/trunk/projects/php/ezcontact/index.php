<?
// brukes for sider som skal redirectes..
if ( file_exists( $prePage ) )
{
  include( $prePage );
  die();
}
?>

<html>
<head>
	<title>
	eZ contact
	</title>
	<link rel="stylesheet" type="text/css" href="ez.css">
<SCRIPT LANGUAGE="JavaScript1.2">
<!--//
                function NewWindow(bredde,hoyde,url) {
                        window.open(url,"_blank","menubars=0,scrollbars=1,resizable=0,height="+hoyde+",width="+bredde);
                }
//-->
</SCRIPT>  
</head>
<body>

<table width="100%" cellspacing="0" cellpadding="3" border="0">
<tr>
<td bgcolor="#000000">
<table width="100%" cellspacing="0" cellpadding="13" border="0">
<tr>
<td bgcolor="#808080">


	<table width="100%" cellspacing="0" cellpadding="3" border="0">
	<tr>
	<td bgcolor="#000000">
	<table width="100%" cellspacing="0" cellpadding="10" border="0">
	<tr>
	<td bgcolor="#ffffff">

<? // hovedinnholdet på siden
print( session_id() );
if ( file_exists( $page ) )
{
  include( $page );
}
else
{
  print( "<h1>Feil: fant ikke filen: $page</h1>" );
}
?>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

</body>
</html>
