<h1>{intl-mailedit}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="/mail/mailedit/{current_mail_id}" enctype="multipart/form-data" >

<p class="boxtext">{intl-to}:</p>
<input type="text" size="40" name="To" value="{to_value}"/>

<p class="boxtext">{intl-from}:</p>
<input type="text" size="40" name="To" value="{from_value}"/>

<p class="boxtext">{intl-cc}:</p>
<input type="text" size="40" name="To" value="{cc_value}"/>

<p class="boxtext">{intl-bcc}:</p>
<input type="text" size="40" name="To" value="{bcc_value}"/>

<p class="boxtext">{intl-subject}:</p>
<input type="text" size="40" name="To" value="{subject_value}"/>

<p class="boxtext">{intl-body}:</p>
<textarea name="MailBody[]" cols="70" rows="20" wrap="soft">{mail_body}</textarea>

<!-- BEGIN inserted_attachments_tpl -->
<h2>{intl-attachments}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <th>{intl-file_name}:</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
</tr>
 
<!-- BEGIN attachment_tpl -->
<tr>
        <td width="97%" class="{td_class}">
        {file_name}
        </td>
        <td width="1%" class="{td_class}">
        <a href="/mail/attachmentedit/{file_id}/{mail_id}/" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('eztp{file_number}-red','','/images/{site_style}/redigerminimrk.gif',1)"><img name="eztp{file_number}-red" border="0" src="/images/{site_style}/redigermini.gif" width="16" height="16" align="top" border="0" alt="Edit" /></a>
        </td>
        <td width="1%" class="{td_class}">
        <input type="checkbox" name="AttachmentArrayID[]" value="{file_id}" />
        </td>
</tr>
<!-- END attachment_tpl -->
 
</table>
<!-- END inserted_attachments_tpl --> 


<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" Name="AddAttachment" value="{intl-add_attachment}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Preview" value="{intl-preview}" /></td>
</tr>
</table>

<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" Name="Send" value="{intl-send}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Save" value="{intl-save}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>