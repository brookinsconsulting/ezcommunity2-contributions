<h1>{intl-mailedit}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="/mail/mailedit/{current_mail_id}" enctype="multipart/form-data" >

<p class="boxtext">{intl-to}:</p>
<input type="text" size="40" name="To" value="{to_value}"/>

<p class="boxtext">{intl-from}:</p>
<input type="text" size="40" name="From" value="{from_value}"/>

<p class="boxtext">{intl-cc}:</p>
<input type="text" size="40" name="Cc" value="{cc_value}"/>

<p class="boxtext">{intl-bcc}:</p>
<input type="text" size="40" name="Bcc" value="{bcc_value}"/>

<p class="boxtext">{intl-subject}:</p>
<input type="text" size="40" name="Subject" value="{subject_value}"/>

<p class="boxtext">{intl-body}:</p>
<textarea name="MailBody" cols="70" rows="20" wrap="soft">{mail_body}</textarea>

<!-- BEGIN inserted_attachments_tpl -->
<h2>{intl-attachments}:</h2>
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
        <th>{intl-file_name}:</th>
        <th>{intl-file_size}:</th>
        <th>&nbsp;</th>
</tr>
 
<!-- BEGIN attachment_tpl -->
<tr>
        <td width="89%" class="{td_class}">
        {file_name}
        </td>
        <td width="10%" class="{td_class}">
        {file_size}
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
  <!-- BEGIN attachment_delete_tpl -->
  <td><input class="stdbutton" type="submit" Name="DeleteAttachments" value="{intl-delete_attachments}" /></td>
  <!-- END attachment_delete_tpl -->
</tr>
</table>

<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" Name="Send" value="{intl-send}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Preview" value="{intl-preview}" /></td>
  <td>&nbsp;</td>  
  <td><input class="stdbutton" type="submit" Name="Save" value="{intl-save}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Cancel" value="{intl-cancel}" /></td>
</tr>
</table>
</form>