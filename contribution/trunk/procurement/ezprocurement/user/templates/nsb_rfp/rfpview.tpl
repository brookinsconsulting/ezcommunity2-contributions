<div id="LayerContent" class="LayerContent" style=""> 

	<span>
	<!-- BEGIN rfp_path_header_tpl -->
		<span class="subdiv">/</span>
		<a class="subdiv" href="{www_dir}{index}/procurement/archive/0/">{intl-top_level}</a>
	<!-- END rfp_path_header_tpl -->
		<span class="subdiv">/</span>
		<a class="subdiv" href="{www_dir}{index}/procurement/archive/10/">{intl-north_slope_burough_category}</a>
	
	<!-- BEGIN path_item_tpl -->
		<span class="subdiv">/</span>
		<a class="subdiv" href="{www_dir}{index}/procurement/archive/{category_id}/">{category_name}</a>
	<!-- END path_item_tpl -->
	</span>

	<span>
	<!-- BEGIN rfp_header_tpl -->
	<div> 
		<br />
		<span class="subdiv"><a class="subdiv" style="font-size: 13px; font-weight: normal; color: rgb(124, 122, 110); text-decoration: none;" href="{rfp_uri}">{rfp_name}</a></span><br /><br />
	        <!-- BEGIN rfp_author_list_tpl -->
                <span class="subdiv">{intl-rfp_author}: </span><br />		
		<!-- BEGIN rfp_author_tpl -->
		<div style="text-indent: 20pt;">
 		 <!-- BEGIN procurement_holder_organization_tpl -->
		 <a class="subdiv" href="{www_dir}{index}/procurement/company/view/{author_organization_id}">{author_organization}</a> : 
                 <!-- END procurement_holder_organization_tpl -->
		 <a class="subdiv" href="{www_dir}{index}/procurement/holder/view/{author_id}">{author_text}</a>
		</div>
		<!-- END rfp_author_tpl -->
                <!-- END rfp_author_list_tpl -->
                <!-- BEGIN procurement_become_planholder_tpl -->
                <div style="text-indent: 20pt; position: relative; top: 5px;">
                 <a class="subdiv" href="{www_dir}{index}/procurement/join/{procurement_id}">{intl-become_planholder_text}</a>
		</div>
                <!-- END procurement_become_planholder_tpl -->
		<br />
		<!-- BEGIN procurement_number_item_tpl -->
                <span class="subdiv">{intl-procurement_number}: {procurement_number}</span><br />
                <!-- END procurement_number_item_tpl -->

		<span class="subdiv">{intl-rfp_date}: {rfp_created}</span><br />
		<span class="subdiv">{intl-rfp_updated_date}: {rfp_modified}</span><br />
		<span class="subdiv">{intl-rfp_responce_due_date}: {rfp_responce_due_date}</span><br />
		<!-- BEGIN bid_award_date_item_tpl -->
                <span class="subdiv">{intl-bid_award_date}: {bid_award_date}</span><br />
		<!-- END bid_award_date_item_tpl -->

		<!-- BEGIN rfp_estimate_tpl -->
		<span class="subdiv">{intl-rfp_project_estimate}: {intl-rfp_project_estimate_cash_sign} {rfp_project_estimate}</span>
		<!-- END rfp_estimate_tpl -->
	</div>

	<!-- END rfp_header_tpl -->
	<div class="subdiv">{rfp_body}</div>

		<!-- BEGIN attached_file_list_tpl -->
		<h1>{intl-attached_files}</h1>
		<!-- BEGIN attached_file_tpl -->
		<div class="{td_class}" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
		   <span class="{td_class}" style="color: #7c7a6e; font-size: 12px;">

		     <span style="Z-index:2;"><a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}">{file_name}</a></span>

<span class="{td_class}" style="font-size: 9px; padding: 7px; position:absolute; right:0px; Z-index:0;">
                      <a href="{www_dir}{index}/filemanager/download/{file_id}/{original_file_name}"> {file_size}&nbsp;{file_unit}</a>
                     </span>

                     <span style="font-size: 11px"><br />{file_description}</span>


		   </span>
		</div>
		<!-- END attached_file_tpl -->
		<!-- END attached_file_list_tpl -->

		<br /><br />

		<!-- BEGIN bid_list_tpl -->
                <h1>{intl-planholder_bids}</h1>
                <!-- BEGIN bid_tpl -->
                <div class="{td_class}" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
                   <span class="{td_class}" style="color: #7c7a6e; font-size: 12px;">
                     <span style="padding-left: 3px; Z-index:2;"> 

	                <!-- BEGIN bid_winner_tpl -->
                        <span style="font-size: 11px; color: green;">{bid_iswinner}</span> :
                        <!-- END bid_winner_tpl -->
                        <!-- BEGIN bid_rank_tpl -->
                        <span style="font-size: 11px; {rank_color}">{bid_rank_alpha}</span> :
                        <!-- END bid_rank_tpl -->



<!--
			<span style="font-size: 11px"> {bid_date}</span> : 
-->

<span>			<a href="{www_dir}{index}/procurement/company/view/{bid_company_id}/">{bid_company_name}</a></span> :  <a href="{www_dir}{index}/procurement/holder/view/{bidder_id}/">{bidder_name}</a>


			<span class="{td_class}" style="font-size: 9px; padding: 1px; padding-right: 4px;  position:absolute; right:0px; Z-index:0;">
                         <span>{intl-bid_currency_symbol}&nbsp;{bid_amount}</span>
                        </span>

		</span>
<!--
                     <span style="font-size: 11px"><br /></span>
-->


                   </span>
                </div>
                <!-- END bid_tpl -->
                <!-- END bid_list_tpl -->


	</span>

</div>