<h1>{intl-headline}</h1>

<hr noshade="noshade" size="4"/ >

<!-- BEGIN search_box_tpl -->
<form action="/contact/search/company" method="post">
    <input type="text" name="SearchText" size="12" value="{search_text}" />       
    <input type="submit" value="{intl-search}" />
    <input type="submit" name="AdvancedSearch" value="{intl-advanced_search}" />
</form>
<!-- END search_box_tpl -->

<!-- BEGIN advanced_search_box_tpl -->
<form action="/contact/search/company" method="post">
    <p class="boxtext">{intl-search_string}:</p>
    <input type="text" name="SearchText" size="12"  value="{search_text}" /><br />
    
    <p class="boxtext">{intl-categories}:</p>
    <p>{intl-search_in_categories}.</p>
    <input type="hidden" name="ParentID" value="{current_category_id}">
    
    <select multiple size="10" name="CategoryArray[]">
        <!-- BEGIN category_option_tpl -->
        <option {category_selected} value="{category_id}">{category_value}</option>
        <!-- END category_option_tpl -->
    </select><br />
    
    <!-- input type="checkbox" name=""  /><span class="boxtext">{intl-}</span><br /-->

    <hr noshade="noshade" size="4"/ >
    
    <input type="submit" value="{intl-search}" />
    <input type="hidden" name="AdvancedSearch" value="{advanced_search}" />
</form>
<!-- END advanced_search_box_tpl -->

<!-- BEGIN search_results_tpl -->
<p class="boxtext">{intl-results_prefix} {results} {intl-results_postfix}</p>

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <th>{intl-item_name}</th>
    <th>{intl-item_description}</th>
    <th>{intl-item_categories}</th>
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




