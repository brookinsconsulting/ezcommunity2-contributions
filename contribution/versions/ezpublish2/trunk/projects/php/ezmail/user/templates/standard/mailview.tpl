<h1>{intl-readmail}</h1>

<hr noshade="noshade" size="4">

<form method="post" action="/mail/view/{current_mail_id}" enctype="multipart/form-data" >

<p>{intl-to}: {to}</p>
<p>{intl-from}: {from}</p>

<!-- BEGIN cc_value_tpl -->
<p>{intl-cc}: {cc}</p>
<!-- END cc_value_tpl -->

<!-- BEGIN bcc_value_tpl -->
<p>{intl-bcc}: {bcc}</p>
<!-- END bcc_value_tpl -->

<p>{intl-subject}: {subject}</p>
<table width="100%">
<tr>
  <td class="bgdark">
  {mail_body}
  </td>
<tr>
</table>

<hr noshade="noshade" size="4" />
<table cellspace="0" cellpadding="0" border="0">
<tr>
  <td><input class="stdbutton" type="submit" Name="Reply" value="{intl-reply}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="ReplyAll" value="{intl-replyall}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Forward" value="{intl-forward}" /></td>
  <td>&nbsp;</td>
  <td><input class="stdbutton" type="submit" Name="Delete" value="{intl-delete}" /></td>
</tr>
</table>
</form>