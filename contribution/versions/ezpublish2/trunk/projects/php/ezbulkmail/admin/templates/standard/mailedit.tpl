<h1>{intl-mailedit}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="/bulkmail/mailedit/{current_mail_id}" enctype="multipart/form-data" >

<p class="boxtext">{intl-category_select}:</p>
<select name="CategoryID">
<!-- BEGIN category_item_tpl -->
<option value="{category_id}" {selected}>{category_name}</option>
<!-- END category_item_tpl -->
</select>

<p class="boxtext">{intl-template_select}:</p>
<select name="TemplateID">
<option value="-1" >{intl-no_template}</option>
<!-- BEGIN template_item_tpl -->
<option value="{template_id}" {selected}>{template_name}</option>
<!-- END template_item_tpl -->
</select>


<p class="boxtext">{intl-from}:</p>
<input type="text" size="40" name="From" value="{from_value}"/>

<p class="boxtext">{intl-subject}:</p>
<input type="text" size="40" name="Subject" value="{subject_value}"/>

<p class="boxtext">{intl-body}:</p>
<textarea name="MailBody" cols="40" rows="20" wrap="soft">{mail_body}</textarea>
<br /><br />

<input class="stdbutton" type="submit" Name="Preview" value="{intl-preview}" />
<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="okbutton" type="submit" Name="Send" value="{intl-send}" /></td>
  <td>&nbsp;</td>  
  <td><input class="okbutton" type="submit" Name="Save" value="{intl-save}" /></td>
  <td>&nbsp;</td>
  <td><input class="okbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>