<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
	<title>{intl-header}: {site_url}</title>

	<link rel="stylesheet" type="text/css" href="{www_dir}/admin/templates/{site_style}/style.css" />
	<meta http-equiv="Content-Type" content="text/html; charset={charset}"/>

	<link REL="shortcut icon" HREF="http://www.ladivaloca.org/favicon.ico" TYPE="image/x-icon">
</head>

<body bgcolor="#ffffff" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td class="repeaty" width="1" background="{www_dir}/admin/images/{site_style}/top-l02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-l01.gif" width="10" height="10" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m01.gif" valign="absmiddle" bgcolor="#ffffff" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m01.gif" valign="absmiddle" bgcolor="#ffffff" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeaty" width="1" background="{www_dir}/admin/images/{site_style}/top-r02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-r01.gif" width="10" height="10" border="0" /><br /></td>
</tr>
<tr>
    <td class="repeaty" width="1" background="{www_dir}/admin/images/{site_style}/top-l02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="10" height="10" border="0" alt="" /><br /></td>
    <td class="repeatx" colspan="2" width="98%" valign="absmiddle" bgcolor="#ffffff" align="left">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td width="1%" class="tdmini">
	<a href="http://{admin_site_url}"><img src="{www_dir}/admin/images/{site_style}/top-ezpublishlogo.gif" width="200" height="40" border="0" alt="" /></a><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="91%" valign="top">
	<form action="{charset_submit_url}" method="post" name="CharsetSwitch">
	<!-- BEGIN charset_switch_tpl -->
	<select name="page_charset" onchange="SwitchCharset()">
            <!-- BEGIN charset_switch_item_tpl --> 
	    <option value="{charset_code}" {charset_selected}>{charset_description}</option>
	    <!-- END charset_switch_item_tpl -->
        </select>
        <input type="submit" class="stdbutton" value="Set" />
        <!-- END charset_switch_tpl -->
	</form>
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>						
	<td width="1%">
	<span class="top">{intl-ezpublish_version}:</span><br />
	<span class="topusername">{ezpublish_version}</span><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="80" height="10" border="0" alt="" /><br />
	</td>	
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%">
	<span class="top">{intl-site_url}:</span><br />
	<span class="topusername">{site_url}</span><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="100" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%">
	<span class="top">{intl-user_name}:</span><br />
	<span class="topusername">{first_name}&nbsp;{last_name}</span><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="80" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%">
	<span class="top">{intl-ip_address}:</span><br />
	<span class="topusername">{ip_address}</span><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="80" height="10" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%" valign="top">
	<img src="{www_dir}/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/user/passwordchange/">{intl-change_user_info}</a><br />
	<img src="{www_dir}/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/user/settings?RefURL={ref_url}">{intl-user_settings}</a><br />
	<img src="{www_dir}/admin/images/1x1.gif" width="100" height="1" border="0" alt="" /><br />
	</td>
	<td width="1%"><img src="{www_dir}/admin/images/1x1.gif" width="10" height="10" border="0" alt="" /></td>
	<td width="1%" align="right">
	<a  href="{www_dir}{index}/user/login/logout/"><img src="{www_dir}/admin/images/{site_style}/top-logout.gif" width="35" height="40" border="0" alt="" /></a>
	</td>
</tr>
</table>

<!-- BEGIN module_list_tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<!-- BEGIN module_item_tpl -->
	<td align="center"><a href="{www_dir}{index}/module/{module_action}/{ez_module_name}?RefURL={ref_url}"><img src="{www_dir}/{ez_dir_name}/admin/images/module_icon.gif" width="32" height="32" border="0" alt="{module_name}" /></a></td>
<!-- END module_item_tpl -->
<!-- BEGIN module_control_tpl -->
	<td>&nbsp;&nbsp;</td>
	<td align="left">
	<img src="{www_dir}/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/module/activate/all?RefURL={ref_url}">{intl-all}</a><br />
	<img src="{www_dir}/admin/images/{site_style}/top-arrow.gif" width="10" height="13" border="0" alt="" />&nbsp;<a class="top" href="{www_dir}{index}/module/activate/none?RefURL={ref_url}">{intl-none}</a>
	</td>
<!-- END module_control_tpl -->
</tr>
</table>
<!-- END module_list_tpl -->
	
	</td>
    <td class="repeaty" width="%" background="{www_dir}/admin/images/{site_style}/top-r02.gif" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="10" height="10" border="0" alt="" /><br /></td>
</tr>
<tr>
    <td class="repeaty" width="1" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-l03.gif" width="10" height="10" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m02.gif" valign="absmiddle" align="left" bgcolor="#ffffff"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeatx" width="50%" background="{www_dir}/admin/images/{site_style}/top-m02.gif" valign="absmiddle" align="left" bgcolor="#ffffff"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="120" height="6" border="0" alt="" /><br /></td>
    <td class="repeaty" width="1" valign="top" align="left"><img src="{www_dir}/admin/images/{site_style}/top-r03.gif" width="10" height="10" border="0" alt="" /><br /></td>
</tr>
<tr>
	<td colspan="4" class="tdmini"><img src="{www_dir}/admin/images/{site_style}/1x1.gif" width="6" height="6" border="0" alt="" /><br /></td>
</tr>
</table>


<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<!-- BEGIN menu_tpl -->

	<td width="1%" valign="top">

<!-- END menu_tpl -->

<!-- Menues: Start -->
