<tr>
    <td><img src="/images/<? echo $SiteStyle; ?>/menu-t.gif" width="150" height="50"></td>
</tr>
<tr> 
    <td background="/images/<? echo $SiteStyle; ?>/menu-m.gif">
	<img src="/images/<? echo $SiteStyle; ?>/menu-arrow.gif" width="20" height="10"><a class="menu" href="/user/">Brukeroversikt</a><br>
	<img src="/images/<? echo $SiteStyle; ?>/menu-arrow.gif" width="20" height="10"><a class="menu" href="index.php?page=useredit.php&Action=New">Ny bruker</a><br>
	<img src="/images/<? echo $SiteStyle; ?>/menu-arrow.gif" width="20" height="10"><a class="menu" href="index.php?page=grouplist.php">Gruppeoversikt</a><br>
	<img src="/images/<? echo $SiteStyle; ?>/menu-arrow.gif" width="20" height="10"><a class="menu" href="index.php?page=groupedit.php&Action=New">Ny gruppe</a><br>
	<img src="/images/<? echo $SiteStyle; ?>/menu-arrow.gif" width="20" height="10"><a class="menu" href="/user/logout/">Logg ut</a><br>

<?
    $user = new eZUser( $session->userID() );
    print( "<img src=\"/images/$SiteStyle/menu-arrow.gif\" width=\"20\" height=\"10\"><b>User: </b>" . $user->firstName() . " " . $user->lastName() . "</p>" );
?>

    </td>
</tr>
<tr>
    <td><img src="/images/<? echo $SiteStyle; ?>/menu-b.gif" width="150" height="30"></td>
</tr>
<tr>
	<td><img src="/images/<? echo $SiteStyle; ?>/1x1.gif" width="10" height="10"></td>
</tr>

