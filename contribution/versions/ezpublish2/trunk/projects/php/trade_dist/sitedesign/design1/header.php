<body bgcolor="#aaaaaa">

<h1>eZ trade v1.0.5</h1>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
  <tr> 
    <td width="1%" valign="top" bgcolor="#eeeeee">
     <a href="/article/archive/1/">NEWS</a><br />
     <b>Products:</b>
<?
include( "eztrade/user/categorylist.php" );
?>
     <a href="/trade/cart/">YOUR CART</a><br />
     <a href="/trade/wishlist/">YOUR WISHLIST</a><br />
    <?        include( "eztrade/user/hotdealslist.php" ); ?>
     
</td>
<td rowspan="2" bgcolor="#ffffff" width="97%" valign="top">
	
