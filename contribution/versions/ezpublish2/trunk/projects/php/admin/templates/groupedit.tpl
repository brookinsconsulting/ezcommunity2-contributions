<h1>Brukergrupperedigering</h1>

<form action="index.php4?page=groupedit.php4" method="post">

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white">
	<b>Gruppe</b>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<p>
	Navn:<br>
	<input type="text" name="Name" value="{name}">
	</p>
	
	<p>
	Beskrivelse:<br>
	<input type="text" name="Description" value="{description}">
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	</td>
</tr>
</table>

<table width="100%" height="4" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td bgcolor="ffffff"><img src="../ezpublish/images/1x1.gif" width="1" height="4" border="0"></td>
</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white">
	<b>Rettigheter</b>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">	
	
<table>

  <tr>
    <td colspan="2"><b>eZPublish</b></td>
  </tr>
  <tr>
    <td>
        Legg til artikler
    </td>
    <td><input type="checkbox" name="eZPublish_Add" {eZPublish_Add}></td>
  </tr>

  <tr>
    <td>
        Redigere egne artikler
    </td>
    <td><input type="checkbox" name="eZPublish_Edit" {eZPublish_Edit}></td>
  </tr>
    
  <tr>
    <td>
       Endre instillinger
    </td>
    <td><input type="checkbox" name="eZPublish_Preferences" {eZPublish_Preferences}></td>
  </tr>
    
  <tr>
    <td>
        Redigere alle artikler
    </td>
    <td><input type="checkbox" name="eZPublish_EditAll" {eZPublish_EditAll}></td>
  </tr>  

  <tr>
    <td colspan="2"><b>eZ Link</b></td>
  </tr>
  <tr>
    <td>
      Legge til Link
    </td>
    <td><input type="checkbox" name="eZLink_Add" {eZLink_Add}></td>
  </tr>
    
  <tr>
    <td>
     Redigere Link 
    </td>
    <td><input type="checkbox" name="eZLink_Edit" {eZLink_Edit}></td>
  </tr>
    
  <tr>
    <td>
      Slette Link
    </td>
    <td><input type="checkbox" name="eZLink_Delete" {eZLink_Delete}></td>
  </tr>
    
  <tr>
     <td colspan="2"><b>eZ Forum</b></td>
  </tr>  
  <tr>
    <td>
        Legge til kategori
    </td>
    <td><input type="checkbox" name="eZForum_AddCategory" {eZForum_AddCategory}></td>
  </tr>
    
  <tr>
    <td>
        Legge til forum
    </td>
    <td><input type="checkbox" name="eZForum_AddForum" {eZForum_AddForum}></td>
  </tr>
    
  <tr>
    <td>
        Slette kategori
    </td>
    <td><input type="checkbox" name="eZForum_DeleteCategory" {eZForum_DeleteCategory}></td>
  </tr>
    
  <tr>
    <td>
        Slette forum 
    </td>
    <td><input type="checkbox" name="eZForum_DeleteForum" {eZForum_DeleteForum}></td>
  </tr>
    
  <tr>
    <td>
        Legge til meldinger
    </td>
    <td><input type="checkbox" name="eZForum_AddMessage" {eZForum_AddMessage}></td>
  </tr>
    
  <tr>
    <td>
        Fjerne meldinger
    </td>
    <td><input type="checkbox" name="eZForum_DeleteMessage" {eZForum_DeleteMessage}></td>
  </tr>
    
  <tr>
    <td colspan="2"><b>ZEZ globale instillinger</b></td>
  </tr>

  <tr>
    <td>
        Gi nye brukere rettigheter
    </td>
    <td><input type="checkbox" name="GrantUser" {GrantUser}></td>
  </tr>

  <tr>
    <td>
        Legge til brukergruppe
    </td>
    <td><input type="checkbox" name="zez_AddGroup" {zez_AddGroup}></td>
  </tr>
    
  <tr>
    <td>
        Fjerne brukergruppe
    </td>
    <td><input type="checkbox" name="zez_DeleteGroup" {zez_DeleteGroup}></td>
  </tr>
    
  <tr>
    <td>
        Legge til bruker
    </td>
    <td><input type="checkbox" name="zez_AddUser" {zez_AddUser}></td>
  </tr>

  <tr>
    <td>
        Fjerne bruker
    </td>
    <td><input type="checkbox" name="zez_DeleteUser" {zez_DeleteUser}></td>
  </tr>

  <tr>
    <td>
        Tilgang til administrasjon
    </td>
    <td><input type="checkbox" name="zez_Admin" {zez_Admin}></td>
  </tr>
</table>

	</td>
</tr>
</table>
<br>

<input type="hidden" name="UserGroupID" value="{user_group_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" name="modifyGroup" value="Endre">
<br>
</form>