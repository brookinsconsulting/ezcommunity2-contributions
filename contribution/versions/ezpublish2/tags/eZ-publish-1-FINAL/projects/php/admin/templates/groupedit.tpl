<h1>Brukergrupperedigering</h1>

<form action="index.php?page=groupedit.php" method="post">

<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<td bgcolor="#3c3c3c">
	<p class="white">
	<b>Identifikasjon</b>
	</p>
	</td>
</tr>
<tr>
	<td bgcolor="#f0f0f0">
	<br>
	<p>
	Gruppenavn:<br>
	<input type="text" name="Name" value="{name}">
	</p>
	
	<p>
	Gi en beskrivelse av gruppen:<br>
	<input type="text" name="Description" value="{description}">
	</p>
	</td>
	<img src="../images/1x1.gif" width="1" height="4" border="0"><br>
</tr>
<tr>
         <td bgcolor="#f0f0f0">
         <br>
         </td>
</tr>
</table>

<img src="../images/1x1.gif" width="1" height="4" border="0"><br>

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
    <td colspan="2"><br><b>eZPublish</b></td>
  </tr>
  <tr>
    <td><input type="checkbox" name="eZPublish_Add" {eZPublish_Add}></td>
	    <td>
        Legg til artikler
    	</td>
  </tr>

  <tr>
    <td><input type="checkbox" name="eZPublish_Edit" {eZPublish_Edit}></td>
    <td>
        Redigere egne artikler
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZPublish_EditAll" {eZPublish_EditAll}></td>
    <td>
        Redigere alle artikler
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZPublish_Preferences" {eZPublish_Preferences}></td>
    <td>
       Endre innstillinger
    </td>
  </tr>  

  <tr>
    <td colspan="2"><br><b>eZ Link</b></td>
  </tr>
  <tr>
    <td><input type="checkbox" name="eZLink_Add" {eZLink_Add}></td>
    <td>
      Legge til ny link
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZLink_Edit" {eZLink_Edit}></td>
    <td>
     Redigere link 
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZLink_Delete" {eZLink_Delete}></td>
    <td>
      Slette link
    </td>
  </tr>




  <tr>
    <td colspan="2"><b>eZ Contact</b></td>
  </tr>

  <tr>
    <td><input type="checkbox" name="eZContact_Read" {eZContact_Read}></td>
    <td>
      Rettigheter til å legge til å lese firma/personer
    </td>
   </tr>

  <tr>
    <td><input type="checkbox" name="eZContact_Add" {eZContact_Add}></td>
    <td>
      Legge til firma/personer
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZContact_Delete" {eZContact_Delete}></td>
    <td>
      Slette firma/personer
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZContact_Edit" {eZContact_Edit}></td>
    <td>
      Endre firma/person-opplysninger
    </td>
  </tr>

  <tr>
    <td><input type="checkbox" name="eZContact_AdminAdd" {eZContact_AdminAdd}></td>
    <td>
      Legge til administrasjonskomponenter
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZContact_AdminDelete" {eZContact_AdminDelete}></td>
    <td>
      Slette administrasjonskomponenter
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZContact_AdminEdit" {eZContact_AdminEdit}></td>
    <td>
      Endre administrasjonskomponenter
    </td>
  </tr>








    
  <tr>
     <td colspan="2"><br><b>eZ Forum</b></td>
  </tr>  
  <tr>
    <td><input type="checkbox" name="eZForum_AddCategory" {eZForum_AddCategory}></td>
    <td>
        Legge til ny kategori
    </td>
  </tr>

  <tr>
    <td><input type="checkbox" name="eZForum_DeleteCategory" {eZForum_DeleteCategory}></td>
    <td>
        Slette kategori
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZForum_AddForum" {eZForum_AddForum}></td>
    <td>
        Legge til nytt forum
    </td>
  </tr>
   
  <tr>
    <td><input type="checkbox" name="eZForum_DeleteForum" {eZForum_DeleteForum}></td>
    <td>
        Slette forum 
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZForum_AddMessage" {eZForum_AddMessage}></td>
    <td>
        Legge til meldinger
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="eZForum_DeleteMessage" {eZForum_DeleteMessage}></td>
    <td>
        Fjerne meldinger
    </td>
  </tr>
    
  <tr>
    <td colspan="2"><br><b>ZEZ globale instillinger</b></td>
  </tr>

  <tr>
    <td><input type="checkbox" name="zez_AddGroup" {zez_AddGroup}></td>
    <td>
        Legge til ny brukergruppe
    </td> 
  </tr>
    
  <tr>
    <td><input type="checkbox" name="zez_DeleteGroup" {zez_DeleteGroup}></td>
    <td>
        Fjerne brukergruppe
    </td>
  </tr>
    
  <tr>
    <td><input type="checkbox" name="zez_AddUser" {zez_AddUser}></td>
    <td>
        Legge til bruker
    </td>
  </tr>

  <tr>
    <td><input type="checkbox" name="GrantUser" {GrantUser}></td>
    <td>
        Gi brukere rettigheter
    </td>
  </tr>

  <tr>
    <td><input type="checkbox" name="zez_DeleteUser" {zez_DeleteUser}></td>
    <td>
        Fjerne bruker
    </td>
  </tr>

  <tr>
    <td><input type="checkbox" name="zez_Admin" {zez_Admin}></td>
    <td>
        Tilgang til administrasjon
    </td>
  </tr>
</table>

	<img src="../images/1x1.gif" width="1" height="4" border="0"><br>
	</td>
</tr>

</table>
<br>

<input type="hidden" name="UserGroupID" value="{user_group_id}">
<input type="hidden" name="Action" value="{action_value}">
<input type="submit" name="modifyGroup" value="Endre">
<br>
</form>