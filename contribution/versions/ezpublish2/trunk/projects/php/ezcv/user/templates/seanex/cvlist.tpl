<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Jobbmarked</span> | {intl-headline_list}</div></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="top"><img src="/images/box-tr.gif" width="4" height="4" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1"><img src="/images/1x1.gif" width="1" height="1" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="bottom"><img src="/images/box-bl.gif" width="4" height="4" /><br /></td>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="right" valign="bottom"><img src="/images/box-br.gif" width="4" height="4" /><br /></td>
</tr>
</table>

<br />

<!-- BEGIN cv_items_tpl -->
<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <th>{intl-th_person_name}:</th>
    <th>{intl-th_current_created}:</th>
    <th>{intl-th_current_valid_until}:</th>
</tr>
<!-- BEGIN cv_item_tpl -->
<tr class="{theme-type_class}">
    <!-- BEGIN cv_item_permissible_tpl -->
    <td><a href="/cv/cv/view/{item_id}">{person_last_name}, {person_first_name}&nbsp;</a></td>
    <!-- END cv_item_permissible_tpl -->
    <!-- BEGIN cv_item_not_permissible_tpl -->
    <td>{person_last_name}, {person_first_name}&nbsp;</td>
    <!-- END cv_item_not_permissible_tpl -->
    <td class="small">{item_created}</td>
    <td class="small">{item_valid_until}</td>
</tr>
<!-- END cv_item_tpl -->
</table>
<!-- END cv_items_tpl -->
<!-- BEGIN cv_no_items_tpl -->
<p>{intl-th_no_cvs}</p>
<!-- END cv_no_items_tpl -->
