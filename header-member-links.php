<?php
$me 		= getUser($_COOKIE['userid'],$w);
$userPhoto 	= getUserPhoto ($me['user_id'], $me['listing_type'], $w);
$userPhoto 	= $userPhoto['file'];
?>

<div class="col-md-12 vmargin sm-nomargin pull-right nohpad sm-text-center logged-in-member-header">
    
	<ul class="mini-nav <?php if ($wa['header_menu_alignment'] == 1) { ?>mini-nav-flex-spaced<?php } ?> list-inline nobmargin lmargin sm-text-center pull-right">
		<li class="pull-right hidden-sm hidden-xs header-member-account-links">
			<a 
			type="button" 
			id="popover" 
			tabindex="0" 
			role="button" 
			data-container="body" 
			data-toggle="popover" 
			data-placement="bottom" 
			data-html="true"
			style="margin-left:30px;"
			class="btn btn-default sm-text-center pull-right toggle-member-info notranslate">
				<img src="<?php echo $userPhoto; ?>" class="mini_profile_pic">
				%Welcome_member% 
				<?php if ($me['listing_type'] == "Company" && $me['company'] != "") { ?>
					<?php echo $me['company']; ?>
				<?php } else { ?>
					<?php echo limitWords($me['full_name'],1)?>
				<?php } ?>
				<i class="fa fa-angle-down fa-fw"></i>
			</a>
		</li>
		<?php 
		if ($wa['custom_148'] == "1") {
			$addOnGoogleTranslate = getAddOnInfo("google_translate", "b20c91eaa0e30a1322cade0a40001dc8");
			if (isset($addOnGoogleTranslate['status']) && $addOnGoogleTranslate['status'] === 'success') {
				echo widget($addOnGoogleTranslate['widget'], "", $w['website_id'], $w);
			}
		}
		if($wa['header_logged_in_menu'] != ''){
			echo menuArray($wa['header_logged_in_menu'], 0, $w);	
		}
		?>
    </ul>
</div>

<div class="clearfix"></div>
<?php 
$subscription = getSubscription($me['subscription_id'],$w);
?>
<script>
    $(document).ready(function(){
        $('#popover').popover({
			content: `<img style="width: 90px" src="<?php echo $userPhoto; ?>" class="img-responsive img-rounded center-block bmargin">
			<div class="text-center">
				<p class="badge label-primary-subtle welcome-member-name notranslate">
					<?php echo str_replace("'", "&#39;", empty($me['full_name']) ? $me['email'] : $me['full_name']); ?>
				</p>
			</div>
			<p class="nomargin small">
				<?php if ($me['active']==1) { ?>
				<a href="/account/home" class="text-danger bold">%%%dashboard_notactivated%%%</a>
				<?php } else { if ($me['active']==2) { ?>
				%%%account_status%%%: <b>%%%dashboard_active%%%</b>
				<?php } else if ($me['active'] == 5) { ?>
				%%%account_status%%%: <a href="/account/billing" class="text-danger bold">%%%past_due_text_label%%%</a>
				<?php } else { ?>
				%%%account_status%%%: <a href="/account/billing" class="text-danger bold">%%%dashboard_onhold%%%</a>
				<?php } } ?>
			</p>
			<p class="nomargin small">%%%member_level%%%: <b><?php echo str_replace("'", "&#39;",$subscription['subscription_name']); ?></b></p>

			<div class="clearfix"></div>
			<div class="text-center">
			<p class="vpad nomargin inline-block">
				<?php if ($me['filename'] != "" && $subscription['searchable'] == "1") { ?>
				<a style="margin-right: 5px;" href="/<?php echo urldecode($me['filename']);?>" class="btn btn-primary btn-sm pull-left header-view-listing-link">%%%view_listing_label%%%</a>
				<?php } ?>
				<a style="margin-left: 5px;" href="/account/home" class="btn btn-success btn-sm pull-right header-edit-listing-link">%%%profile_edit_listing%%%</a>
			</p>
			</div>
			<p class="text-right font-sm nomargin header-logout-link">
				<a href="/logout">%%%dashboard_logout%%%</a>
			</p><div class="clearfix"></div>`
		});
    });
</script>


/* CSS SECTION */
.logged-in-member-header .list-inline > li {
    min-height: 36px;
    line-height: 36px;
}
#popover .mini_profile_pic {
    width: 36px;
    height: 36px;
}
.welcome-member-name {
    word-break: break-word;
}

.welcome-member-name {
    word-break: break-word;
    background-color: #d6eaf6 !important;
    color: rgb(62, 126, 163) !important;
    border-color: #d6eaf6 !important;
    font-family: 'Nunito', sans-serif !important;
	font-weight: 700;
	font-size: 13px;
}
/* Fix popover appearing above navbar */
.popover {
    z-index: 999999 !important;
}
