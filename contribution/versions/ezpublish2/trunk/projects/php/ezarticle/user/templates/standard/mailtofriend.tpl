<!-- BEGIN first_page_tpl -->
<h1>{intl-header}</h1>
<h2>{Topic}</h2>
<h3>{Intro}</h3>
<hr noshade="noshade" size="4" />
<br />
<form method="post">
<table border="0">
<tr>
    <td>{intl-real_name}:</td><td><input type="text"
     name="RealName" value="" />*</td>
</tr>
<tr>
    <td>{intl-send_to}:</td><td><input type="text"
     name="SendTo" value="" />*</td>
</tr>
<tr>
    <td>{intl-from_mail}</td><td> <input type="text" name="From" value="">*</td>
</tr>
</table>
<br />
{intl-comment}:<br />
<textarea name="Textarea" value="" cols="40" rows="7">
</textarea><br />
<br />
<input type="submit" name="Submit" value="{intl-send}">
<input type="reset" name="Reset" value="{intl-reset}">
</form>
<!-- END first_page_tpl -->
<!-- BEGIN success_tpl -->
<H2>{intl-this_message_is_send_to} {to_name}</h2>
{intl-mailsendto} {server_name}<br />
<br />
{intl-the_article}:<br />
{header_text}<br />
{intro_text}<br />
<br />
{intl-the_users_comment}:<br />
<pre>
{user_comment}<br />
</pre>
{intl-url}:<br />
<a href="http://{site_url}/article/view/{art_id}">http://{site_url}/article/view/{art_id}</a>
<!-- END success_tpl -->
<!-- BEGIN article_url_tpl -->
http://{site_url}/article/view/{art_id}
<!-- END article_url_tpl -->
<!-- BEGIN mail_subject_tpl -->
{intl-mail_subject_head} {server_name}
<!-- END mail_subject_tpl -->
<!-- BEGIN mail_body_tpl -->
{intl-mailsendto} {server_name}

{intl-the_article}:
{name}
{intro}

{intl-the_users_comment}:
{comment}

{intl-url}:
<!-- END mail_body_tpl -->
<!-- BEGIN errormsg_tpl -->
<h1>{intl-errors_occured}:</h1><br />
<!-- END errormsg_tpl -->
<!-- BEGIN errormsg_real_name_tpl -->
<h3 class="error" >{intl-error_real_name}</h3><br />
<!-- END errormsg_real_name_tpl -->

<!-- BEGIN errormsg_send_to_tpl -->
<h3 class="error" >{intl-error_send_to}</h3><br />
<!-- END errormsg_send_to_tpl -->

<!-- BEGIN errormsg_from_tpl -->
<h3 class="error" >{intl-error_from}</h3><br />
<!-- END errormsg_from_tpl -->
