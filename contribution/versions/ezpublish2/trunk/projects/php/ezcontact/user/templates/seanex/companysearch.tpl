<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" class="tdmini" bgcolor="#465da1" align="left" valign="top"><img src="/images/box-tl.gif" width="4" height="4" border="0" alt="" /><br /></td>
	<td width="98%" bgcolor="#465da1" class="tdminipath" rowspan="3" valign="middle"><div class="smallpath"><span class="smallbold">Bransjeguide</span> | {intl-headline}</div></td>
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

<!-- BEGIN search_box_tpl -->
<form action="/contact/search/company" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="h2">{intl-headline}&nbsp;&nbsp;</td>
	<td align="right">
    <input type="text" name="SearchText" size="12" value="{search_text}" /><br />
    <input type="image" value="{intl-search}" src="/images/button-searchmain.gif" border="0" /><br />
 	</td>
</tr>
<tr>
	<td colspan="2" align="right"><input type="submit" name="AdvancedSearch" value="{intl-advanced_search}" /></td>
</tr>
</table>
</form>
<!-- END search_box_tpl -->

<!-- BEGIN advanced_search_box_tpl -->
<h2>Avansert søk</h2>
<p>Velg hvilke kategorier du vil søke i:</p>

<form action="/contact/search/company" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td>    
    <p class="boxtext">{intl-categories}:</p>
    
    <input type="hidden" name="ParentID" value="{current_category_id}">
    
    <select multiple size="10" name="CategoryArray[]">
        <!-- BEGIN category_option_tpl -->
        <option {category_selected} value="{category_id}">{category_value}</option>
        <!-- END category_option_tpl -->
    </select><br />
    <!-- input type="checkbox" name=""  /><span class="boxtext">{intl-}</span><br /-->
    </td>
	<td align="right" valign="top">
    <p class="boxtext">{intl-search_string}:</p>
    <input type="text" name="SearchText" size="20"  value="{search_text}" /><br />    
    <input type="image" value="{intl-search}" src="/images/button-searchmain.gif" border="0" /><br />
    <input type="hidden" name="AdvancedSearch" value="{advanced_search}" />
	</td>
</tr>
</table>
</form>
<!-- END advanced_search_box_tpl -->

<!-- BEGIN search_results_tpl -->
<p class="boxtext">{intl-results_prefix} {results} {intl-results_postfix}</p>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-item_name}:</th>
    <th>{intl-item_description}:</th>
    <th>{intl-item_categories}:</th>
</tr>
<!-- BEGIN result_item_tpl -->
<tr class="{item_color}">
    <td><a href="{item_view_path}/{item_id}/">{item_name}</a></td>
    <td>{item_description}</td>
    <td>
    <!-- BEGIN result_category_tpl -->
        <a href="{item_category_view_path}/{item_category_id}">{item_category_name}</a> 
    <!-- END result_category_tpl -->
    </td>
</tr>
<!-- END result_item_tpl -->
</table>
<!-- END search_results_tpl -->

<!-- BEGIN no_results_tpl -->
<p class="boxtext">{intl-no_results}</p>
<!-- END no_results_tpl -->




