<h1>{intl-headline_list}</h1>

<hr noshade="noshade" size="4" />
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
