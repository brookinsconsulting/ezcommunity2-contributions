<tr>
    <td><img src="/images/standard/menu-t.gif" width="150" height="50"></td>
</tr>
<tr> 
    <td background="/images/standard/menu-m.gif">
	<img src="/images/standard/pil.gif" width="20" height="10"><a class="menu" href="/user/">Brukeroversikt</a><br>
	<img src="/images/standard/pil.gif" width="20" height="10"><a class="menu" href="index.php?page=useredit.php&Action=New">Ny bruker</a><br>
	<img src="/images/standard/pil.gif" width="20" height="10"><a class="menu" href="index.php?page=grouplist.php">Gruppeoversikt</a><br>
	<img src="/images/standard/pil.gif" width="20" height="10"><a class="menu" href="index.php?page=groupedit.php&Action=New">Ny gruppe</a><br>
	<img src="/images/standard/pil.gif" width="20" height="10"><a class="menu" href="/user/logout/">Logg ut</a><br>

<?
    $user = new eZUser( $session->userID() );
    print( "<img src=\"/images/standard/pil.gif\" width=\"20\" height=\"10\"><b>User: </b>" . $user->firstName() . " " . $user->lastName() . "</p>" );
?>

    </td>
</tr>
<tr>
    <td><img src="/images/standard/menu-b.gif" width="150" height="30"></td>
</tr>
<tr>
	<td><img src="/images/standard/1x1.gif" width="10" height="10"></td>
</tr>

