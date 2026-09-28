<h1 class="h2 bold dashboard_home_title">
    %%%account_dashboard_icon%%% %%%dashboard_home_title%%%
    <?php
    echo widget("Bootstrap Theme - Account - H1 Action Links", "", $w['website_id'], $w); ?>
</h1>
<hr class="hr-account-member-dashboard">
<?php
if ($subscription['user_dashboard_header_widget'] != "") {
    if($subscription['user_dashboard_header_widget'] == 'dashboard_header_custom') {
        echo $subscription['membership_dashboard_header_content'];
    } else {
        echo widget($subscription['user_dashboard_header_widget'], "", $w['website_id'], $w);
    }

}
$user = getUser($_COOKIE['userid'], $w);

if(!isset($subscription['subscription_id']) || $subscription['subscription_id'] <= 0){
    $subscription = getSubscription($user['subscription_id'], $w);
}

$percent = 0;
$enabledOptions = 0;
$completedOptions = 0;
$phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
        *
    FROM
        `users_photo`
    WHERE
        `user_id`='$user[user_id]'
    AND
        `type`='logo'
    LIMIT 1");
$photoLogoUsed = mysql_num_rows($phresults);
$phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
        *
    FROM
        `users_photo`
    WHERE
        `user_id`='$user[user_id]'
    AND
        `type`='photo'
    LIMIT 1");
$photoProfileUsed = mysql_num_rows($phresults);
$photoTabText = htmlspecialchars($label['account_listing_tab_2'],ENT_QUOTES,"UTF-8");

if ($w['enable_new_search_engine'] == 1) {
    $phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            *
        FROM
            `service_areas`
        WHERE
            `user_id`='$user[user_id]'");

} else {
    $phresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            *
        FROM
            `users_locations`
        WHERE
            `user_id`='$user[user_id]'");
}
$user['pictures'] = mysql_num_rows($phresults);
$user['styles'] = relTotals('memberstyles', $user['user_id'], $w);
$user['services'] = relTotals('services', $user['user_id'], $w);
$pt = 3;

if (is_null($subscription['show_contact_detail'])) {
    $subscription['show_contact_detail'] = 1;
    $subscription['show_logo_upload'] = 1;
    $subscription['show_profile_photo'] = 1;
    $subscription['show_listing_details'] = 1;
    $subscription['show_about_tab'] = 1;
    $subscription['location_limit'] = 0;
}

if ($subscription['show_about_tab'] == 1) {
    $pt++;
}
$multiListing = getAddOnInfo('multi_location', '6c84d51c9fe026cacd4e055bb594fb69');
if ($subscription['location_limit'] != 0 && $subscription['location_limit'] != '' && $w['account_locations_tab'] == 1 && isset($multiListing['status']) && $multiListing['status'] === 'success') {
    $pt++;
} else {
    $subscription['location_limit'] = 0;
}
$increment = floor(100 / $pt);

if ($subscription['show_logo_upload'] == 1 || $subscription['show_profile_photo'] == 1) {
    $photoTabEnabled = 1;

} else {
    $photoTabEnabled = 0;
}

if ($subscription['show_logo_upload'] == 1 && $subscription['show_profile_photo'] == 1) {
    $photoName = 1;

} else {
    $photoName = 0;
}


$enabledOptions++;
$completed = 'warning';

if (form::isFormComplete($subscription['contact_details_form'], $user['user_id'])) {
    $setting['complete_contact'] = 1;
    $percent += $increment;
    $completedOptions++;
    $completed = 'success';
}

$contactDetailsTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/". $w['default_account_url'] ."/". $w['default_contact_url']."'>".htmlspecialchars($label['account_listing_tab_1'],ENT_QUOTES,"UTF-8")."</a></td></tr>";



if ($photoTabEnabled == 1) {
    $enabledOptions++;
    $completed = 'warning';

    if ($subscription['show_profile_photo'] == 0 && $subscription['show_logo_upload'] == 1) {
        if ($photoLogoUsed > 0) {
            $setting['complete_photo'] = 0;
            $percent += $increment;
            $completedOptions++;
            $completed = 'success';

        } else {
            $setting['complete_photo'] = 0;
        }
    } else if ($subscription['show_profile_photo'] == 1 && $subscription['show_logo_upload'] == 0) {

        if ($photoProfileUsed > 0) {
            $setting['complete_photo'] = 0;
            $percent += $increment;
            $completedOptions++;
            $completed = 'success';

        } else {
            $setting['complete_photo'] = 0;
        }
    } else if ($subscription['show_profile_photo'] == 1 && $subscription['show_logo_upload'] == 1) {

        if ($photoProfileUsed > 0 || $photoLogoUsed > 0) {
            $setting['complete_photo'] = 0;
            $percent += $increment;
            $completedOptions++;
            $completed = 'success';

        } else {
            $setting['complete_photo'] = 0;
        }
    }

    $profilePhotoTab = '<tr><td><a class="btn btn-block btn-' . $completed . ' bold" href="/'. $w["default_account_url"] .'/'. $w["default_profile_url"].'">' . $photoTabText . '</a></td></tr>';

} else {
    $setting['complete_photo'] = 0;
    $profilePhotoTab = '';
}

if ($subscription['show_listing_details'] == 1) {
    $enabledOptions++;
    $completed = 'warning';

    if (form::isFormComplete($subscription['listing_details_form'], $user['user_id'])) {
        $setting['complete_resume'] = 1;
        $percent += $increment;
        $completedOptions++;
        $completed = 'success';
    }

    $listingDetailsTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/". $w['default_account_url'] ."/". $w['default_resume_url']."'>".htmlspecialchars($label['account_listing_tab_3'],ENT_QUOTES,"UTF-8")."</a></td></tr>";

} else {
    $setting['complete_resume'] = 1;
    $listingDetailsTab = '';
}

if ($subscription['show_about_tab'] == 1) {
    $enabledOptions++;
    $completed = 'warning';

    if (form::isFormComplete($subscription['about_form'], $user['user_id'])) {
        $setting['complete_about'] = 1;
        $percent += $increment;
        $completedOptions++;
        $completed = 'success';
    }
    $aboutTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/". $w['default_account_url'] ."/". $w['default_about_url']."'>".htmlspecialchars($label['account_listing_tab_4'],ENT_QUOTES,"UTF-8")."</a></td></tr>";

} else {
    $setting['complete_about'] = 0;
    $aboutTab = '';
}

if ($subscription['location_limit'] != 0 && $subscription['location_limit'] != '') {
    $enabledOptions++;
    $completed = 'warning';

    if ($user['pictures'] > 0) {
        $setting['complete_portfolio'] = 1;
        $percent += $increment;
        $completedOptions++;
        $completed = 'success';
    }
    $serviceAreaTab = "<tr><td><a class='btn btn-block btn-" . $completed . " bold' href='/". $w['default_account_url'] ."/". $w['default_locations_url']."'>".htmlspecialchars($label['account_listing_tab_5'],ENT_QUOTES,"UTF-8")."</a></td></tr>";

} else {
    $setting['complete_portfolio'] = 0;
    $serviceAreaTab = '';
    $subscription['location_limit'] = 0;
}

if ($percent > 95) {
    $percent = 100;
}

mysql(brilliantDirectories::getDatabaseConfiguration('database'), "UPDATE users_setting SET
    complete_contact = '$setting[complete_contact]',
    complete_resume = '$setting[complete_resume]',
    complete_photo = '$setting[complete_photo]',
    complete_about = '$setting[complete_about]',
    complete_portfolio = '$setting[complete_portfolio]'
    WHERE user_id = '$user[user_id]'");

if ($percent == 100) {
    $sresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
            *
        FROM
            users_setting
        WHERE
            show_complete = '0'
        AND
            user_id = '$user[user_id]'");

    if (mysql_num_rows($sresults) > 0) {
        $w['first_name'] = $user['first_name'];
        mysql(brilliantDirectories::getDatabaseConfiguration('database'), "UPDATE
                users_setting
            SET
                show_complete = '1'
            WHERE
                user_id = '$user[user_id]'");
    }
}

if ($percent >= 1 && $percent <= 99) {
    $percent = round($completedOptions * (100 / $enabledOptions));
}

echo '<span id="tabManager"
        data-contacttab="' . $subscription['show_contact_detail'] . '"
        data-phototab="' . $photoTabEnabled . '"
        data-photologo="' . $subscription['show_logo_upload'] . '"
        data-photologoused="' . $photoLogoUsed . '"
        data-photoprofile="' . $subscription['show_profile_photo'] . '"
        data-photoprofileused="' . $photoProfileUsed . '"
        data-photoname="' . $photoName . '"
        data-listingtab="' . $subscription['show_listing_details'] . '"
        data-abouttab="' . $subscription['show_about_tab'] . '"
        data-servicetab="' . $subscription['location_limit'] . '"
        data-phototext="' . $photoTabText . '"
        style="display:none;"></span>'; ?>

<div class="well dashboard-plan-cta text-center bmargin-lg">
    <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
        <div class="col-sm-8 text-left xs-text-center">
            <h4 class="bold nomargin">Want more features and exposure?</h4>
            <p class="text-muted nomargin tmargin-xs">Upgrade or change your current membership plan at any time.</p>
        </div>
        <div class="col-sm-4 text-right xs-text-center xs-tmargin">
            <a href="/account/upgrade" class="btn btn-lg bold custom-dashboard-cta-btn btn-block">
                <i class="fa fa-arrow-circle-up rmargin"></i>Change Plan
            </a>
        </div>
    </div>
</div>

<?php if ($subscription['show_dashboard_progress_bar'] != "0" && $percent < 100) { ?>
    <div class="well hidden-xs profile-complete-module">
        <h4 class="profile-complete-messsage">%%%your_listing_is%%% <span class="profile-complete-percent"><?php echo strip_tags($percent); ?>%</span> %%%complete_label%%%</h4>
        <div class="progress active nomargin">
            <?php
            if ($percent < 100) {
                $warningSuccessClass = 'warning';
            } else {
                $warningSuccessClass = 'success';
            } ?>
            <div class="progress-bar three-sec progress-bar-<?php echo $warningSuccessClass; ?>" role="progressbar"
                 data-transitiongoal="<?php echo strip_tags($percent); ?>"><?php echo strip_tags($percent); ?>%
                %%%complete_label%%%
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <?php
    $allowFBSync = $subscription['show_fb_login_btn'];
    $allowGoogle = $subscription['show_google_login_btn'];
    $subscription['data_settings'] = array_filter($subscription['data_settings']);
    if (is_array($subscription['data_settings']) && count($subscription['data_settings']) > 0) {
        $subresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                `data_id`
            FROM
                data_categories
            WHERE
                data_id NOT IN ('1','2','3','4')
                AND
                data_id IN (" . join(',', $subscription['data_settings']) . ")
            ORDER BY
                profile_display_order ASC");
    }
    if ($subscription['show_add_content'] == "1" && mysql_num_rows($subresults) > 0) { ?>
        <div class="col-md-12 dashboard-publish-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="nomargin bold">
                        <i class="bi bi-pencil-square"></i>
                        %%%add_content_label%%%
                    </h4>
                </div>
                <div class="panel-body">
					<div class="row">					
						<?php
						$idsToNotShow = array(28, 10, 13, 29, 21, 22, 19);
						while ($dt = mysql_fetch_assoc($subresults)) {
							global $ds;
							$ds = getDataCategory($dt['data_id'], "data_id", $w);


							if ($ds['data_type'] == 28) {
								global $parentIDExists;
								//check if this site is using the new sub accounts module
								if($parentIDExists){
									$parentId['parent_id'] =  $parentIDExists;
								}else {
									$parentIdQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
										parent_id
									FROM
										`users_data`
									WHERE
										user_id = '" . $user_data['user_id'] . "'");
									$parentId = mysql_fetch_assoc($parentIdQuery);
								}
								if ($parentId['parent_id'] != 0) {
									$hideSubAccounts = 1;

								} else {
									$hideSubAccounts = 0; //show sub_accounts
								}
								$multiListingAddOnInfo = getAddOnInfo("sub_accounts", '9268c02160f09766325e6b39cd4b7b87');
							}
							if ($ds['data_type'] == 28 && $hideSubAccounts == 0 && addonController::isAddonActive('sub_accounts') === true) {

								if ($ds['data_name'] != "") { ?>
									<div class="col-sm-6 col-md-4 hpad">
										<a href="/account/<?php echo $ds['data_filename']; ?>/add" class="alert btn-default btn-block fpad bmargin">
											<?php echo $ds['icon']; ?><?php echo $ds['data_name']; ?>
										</a>
									</div>
								<? }
							} else if ($ds['data_name'] != "" && !in_array($ds['data_type'], $idsToNotShow) && post_payment_controller::canViewDigitalFeature($ds['data_id'])) { ?>
								<div class="col-sm-6 col-md-4 hpad">
									<a href="/account/<?php echo $ds['data_filename']; ?>/add" class="alert btn-default btn-block fpad bmargin">
										<?php echo $ds['icon']; ?><?php echo $ds['data_name']; ?>
									</a>
								</div>
								<?php
							}
						} ?>
					</div>
                </div>
            </div>
        </div>
    <?php } //END SHOW ADD CONTENT IF STATEMENT
    if ($subscription['show_dashboard_manage_listing'] != "0") {
        ?>
        <div class="col-xs-12 col-sm-6 col-md-4 dashboard-module dashboard-manage-listing">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="nomargin bold">
                        %%%manage_listing_icon%%%
                        %%%manage_label_listing%%%
                    </h4>
                </div>
                <div class="panel-body" style="min-height: 290px;">
                    <table class="table nomargin table-links">
                        <tbody>
                        <?php
                        echo $contactDetailsTab . $profilePhotoTab . $listingDetailsTab . $aboutTab . $serviceAreaTab;
                        $addonFBlogin = getAddOnInfo('fb_login', 'c7303c75e37fb6af15dd8056dc19d54a');
                        $addonGoogleLogin = getAddOnInfo("google_login", "1a6d19e7b022116626760695b465066b");
                        $allowFBLogin = $w['fb_login_status'];
                        $allowGoogleLogin = $w['google_login_status'];
                        $googleKeyValidation = !empty($w['api_google_developer_clientid']);
                        $fbKeyValidation = $w['fb_app_id'];
                        if (isset($addonFBlogin['status']) && $addonFBlogin['status'] === 'success' && !empty($fbKeyValidation) && $allowFBSync == 1 && $allowFBLogin == 1) {
                            if (empty($user_data['facebook_id'])) { ?>
                                <tr>
                                    <td><a href="" id="facebookAction" data-action="sync"
                                           class="btn btn-facebook btn-block">
                                            <i class="fa fa-facebook-official" aria-hidden="true"></i>
                                            %%%fb_sync%%%
                                        </a></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td><a href="" id="facebookAction" data-action="unsync"
                                           class="btn btn-facebook btn-block">
                                            <i class="fa fa-facebook-official" aria-hidden="true"></i>
                                            %%%fb_unsync%%%
                                        </a></td>
                                </tr>
                            <?php }
                            echo widget($addonFBlogin['widget']);
                        }
                        if (isset($addonGoogleLogin['status']) && $addonGoogleLogin['status'] === 'success' && $allowGoogle == 1 && $allowGoogleLogin == 1 && $googleKeyValidation === true) {
                            if (empty($user_data['google_id'])) { ?>
                                <style>.table.nomargin.table-links td{padding: 8px 0;}div#containerGoogleLogin {text-align: center;background: #1a73e8;height: 40px;border-radius: 4px; }#gBtn{display: inline-block;width: 220px}</style>
                                <tr><td><div id="containerGoogleLogin"><a href="#" id="googleAction" data-action="sync" class="hide"></a></div></td></tr>
                            <?php } else { ?>
                                <tr>
                                    <td><a id="googleAction" data-action="unsync" onclick="unsyncWithBdGoogle()" class="btn btn-google btn-block">
                                            <i class="fa fa-google" aria-hidden="true"></i>
                                            %%%google_unsync%%%
                                        </a></td>
                                </tr>
                            <?php }
                            echo widget($addonGoogleLogin['widget']);
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php };
    if ($subscription['show_dashboard_account_details'] != "0") { ?>
        <div class="col-xs-12 col-sm-6 col-md-4 dashboard-module dashboard-account-details">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="nomargin bold">
                        %%%manage_account_icon%%%
                        %%%dashboard_accountdetails_title%%%
                    </h4>
                </div>
                <div class="panel-body" style="min-height: 290px;">
                    <table class="table nomargin" style="margin:0!important;">
                        <tr>
                            <td style="border-top: 0;" class="text-center notpad">
                                <span class="bold"><?php echo $subscription['subscription_name']; ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                %%%dashboard_status%%%:
                                <?php
                                if ($user_data['active'] == 1) { ?>
                                    <span class="text-danger bold">%%%dashboard_notactivated%%%</span>
                                    <?php
                                } else {
                                    if ($user_data['active'] == 2) { ?>
                                        <span class="bold">%%%dashboard_active%%%</span>
                                        <?php
                                    } else if ($user_data['active'] == 5) { ?>
                                        <span class="text-danger bold">%%%past_due_text_label%%%</span>
                                        <?php
                                    } else { ?>
                                        <span class="text-danger bold">%%%dashboard_onhold%%%</span>
                                    <?php }
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <span class="play">
                                <i class="fa fa-play-circle font-lg rmargin text-primary"></i> %%%dashboard_joined%%%: <b><?php echo transformDate($user_data['signup_date'], "QB"); ?></b>
                            </span>
                            </td>
                        </tr>
                        <?php
                        // if $subscription['promote_feature_verify'] is not set, set it to $w['promote_feature_verify']
                            if($w['promote_feature_verify'] == 1 && !isset($subscription['promote_feature_verify'])) { 
                                $subscription['promote_feature_verify'] = $w['promote_feature_verify'];
                            }

                        if ($user_data['verified'] == 1) { ?>
                            <tr>
                                <td>
                                    <a class="progress-done" href="/account/promote/verify-submit">
                                        <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                        %%%dashboard_verified_member%%%
                                    </a>
                                </td>
                            </tr>
                            <?php
                        } else if ($subscription['promote_feature_verify'] == 1) { ?>
                            <tr>
                                <td>
                                    <a class="play" href="/account/<?php echo $w['default_verify_url'];?>">
                                        <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                        %%%dashboard_verifyyour%%%
                                        %%%Listing%%%
                                    </a>
                                </td>
                            </tr>
                            <?php
                        } ?>
                        <?php if ($subscription['hide_billing_links'] != 1) { ?>
                            <tr>
                                <td>
                                    <a href="/account/billing/change" class="play">
                                        <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                        %%%dashboard_billingdetails%%%
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td>
                                <a class="play" href="/account/password">
                                    <i class="fa fa-play-circle font-lg rmargin text-primary"></i> %%%profile_edit_pw%%%
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a class="play" href="/logout">
                                    <i class="fa fa-play-circle font-lg rmargin text-primary"></i>
                                    %%%dashboard_logout%%%
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <?php }
    if ($user_data['filename'] != '' && $subscription['searchable'] == "1" && $subscription['show_dashboard_qr_code'] != "0") { ?>
        <div class="col-xs-12 col-sm-6 col-md-4 dashboard-module dashboard-qr-code">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="nomargin bold">
                        <i class="fa fa-bullhorn"></i>
                        %%%dashboard_qrtitle%%%
                    </h4>
                </div>
                <div class="panel-body text-center" style="min-height: 290px;">
                    <h4 class="nomargin">%%%dashboard_qrsubtitle%%%</h4>
                    <img
                            src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo brilliantDirectories::getWebsiteUrl() . '/' . $user_data['filename']; ?>&amp;size=161x161"
                            class="vmargin">
                    <p class="nomargin">
                        %%%dashboard_qrtext%%%
                    </p>
                </div>
            </div>
        </div>
        <?php
    }if($subscription['show_dashboard_transaction_history']){
        echo widget("Bootstrap Theme - Account - Member Dashboard - Recent Transactions");
    }
    ?>
</div>
<div class="clearfix"></div>

<?php
if ($user_data['filename'] != '' && $subscription['searchable'] == "1" && $subscription['show_dashboard_member_badge'] != "0") { ?>
    <div class="row dashboard-promote-banner">
        <div class="col-md-12">
            <div class="well">
                <h2 class="text-center">
                    %%%dashboard_promote_titleone%%%
                    <br>
                    %%%dashboard_promote_titletwo%%%
                </h2>
                <p class="text-center">
                    %%%dashboard_promote_subtitleone%%%
                    <br>
                    <?php
                    if ($subscription['dashboard_member_badge'] != "" && addonController::isAddonActive('member_listing_badges')) {
                        $imageSource = brilliantDirectories::getWebsiteUrl() . $subscription['dashboard_member_badge'];

                    } else if ($w['facebook_secret'] != "") {
                        $imageSource = brilliantDirectories::getWebsiteUrl() . $w['facebook_secret'];

                    } else {
                        $imageSource = brilliantDirectories::getWebsiteUrl() . '/images/memberbadge.png';
                    } ?>

                    <img class="center-block" src="<?php echo $imageSource; ?>"/>
                </p>
                <p class="text-center">%%%dashboard_promote_subtitletwo%%%</p>
                <form name=myform2>
                    <div class="form-group">
                        <textarea class="form-control" name="name" tabindex=1 onClick="selectText('markupArea');"id="markupArea"><a href="<?php echo brilliantDirectories::getWebsiteUrl() . "/" . $user_data['filename'] ?>?from=badge"  title="%%%member_badge_find_me_on%%% <?php echo $w['website_name']; ?>" target="_blank"><img src="<?php echo $imageSource; ?>" style="border: none;"/></a>
                        </textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php }
if ($subscription['user_dashboard_footer_widget'] != "") {
    if($subscription['user_dashboard_footer_widget'] == 'dashboard_footer_custom') {
        echo $subscription['membership_dashboard_footer_content'];
    } else {
        echo widget($subscription['user_dashboard_footer_widget'], "", $w['website_id'], $w);
    }
}
?>

<style>

.btn-facebook {
    background-color: #3c66c4;
    border: 1px solid #3c66c4;
    color: #ffffff !important;
    cursor: pointer;
}
.btn-google {
    background-color: #cf4332;
    border: 1px solid #cf4332;
    color: #ffffff !important;
    cursor: pointer;
}
.dashboard-publish-content a i {
	min-width: 32px;
	text-align: center;
}
table.dashboard_info {
    width: 100%;
}
table.dashboard_info td {
    width: 50%;
    padding-right: 15px;
}
table.dashboard_info td:last-child {
    padding-right: 0;
}
table.dashboard_info .reminder {
    background: none repeat scroll 0 0 #e8eef7;
    border-radius: 5px;
    border: 1px solid #cedbf3;
    clear: both;
    color: #333;
    float: left;
    font-size: 14px;
    line-height: 20px;
    margin: 0 0 15px;
    padding: 10px 15px;
    text-align: center;
    width: calc(100% - 32px);
}
#account-body .reminder > h2 {
    font-size: 24px;
    font-weight: 600;
    line-height: 1.35em;
    margin-bottom: 10px;
}
#account-body .reminder > p {
    font-size: 19px;
    line-height: 1.4em;
    margin-bottom: 20px;
    font-weight: normal;
}
#account-tabs .reminder textarea {
    float: none;
    font-size: 13px;
    min-height: 99px;
    padding: 11px 10px 10px;
    text-transform: lowercase;
    white-space: nowrap;
    width: calc(100% - 22px);
}
#account-tabs .reminder img {
    margin-top: 20px;
    max-width: 300px;
    height: 161px;
}
.four-sec {
    -webkit-transition: width 4s ease-in-out;
    -moz-transition: width 4s ease-in-out;
    -ms-transition: width 4s ease-in-out;
    -o-transition: width 4s ease-in-out;
    transition: width 4s ease-in-out;
}
.well {
    display: inline-block;
    width: 100%;
}
.clickhide {
    cursor: pointer;
}

/* Incomplete tabs (not yet filled) */
.member_wizard2 li.incomplete a,
.member_wizard2 li.incomplete a:hover,
ul.member_wizard2 li.incomplete a,
ul.member_wizard2 li.incomplete a:hover {
    background-color: #d6eaf6 !important;
    border-color: #d6eaf6 !important;
    color: #2c6e96 !important;
}

/* Completed tabs (already filled) */
.member_wizard2 li.completed a,
.member_wizard2 li.completed a:hover,
ul.member_wizard2 li.completed a,
ul.member_wizard2 li.completed a:hover {
    background-color: rgb(62, 126, 163) !important;
    border-color: rgb(62, 126, 163) !important;
    color: #ffffff !important;
}

/* Active tab (current page) */
.member_wizard2 li.active a,
.member_wizard2 li.active a:hover,
ul.member_wizard2 li.active a,
ul.member_wizard2 li.active a:hover,
ul.member_wizard2.nav-pills li.active a,
ul.member_wizard2.nav-pills li > a:hover {
    background-color: rgb(40, 95, 125) !important;
    border-color: rgb(40, 95, 125) !important;
    color: #ffffff !important;
}

/* CARD MENU */
/* Manage Listing card buttons - warning (incomplete) */
.dashboard-manage-listing .btn-warning {
    background-color: #d6eaf6 !important;
    border-color: #d6eaf6 !important;
    color: #2c6e96 !important;
}

.dashboard-manage-listing .btn-warning:hover,
.dashboard-manage-listing .btn-warning:focus {
    background-color: #b8d9ef !important;
    border-color: #b8d9ef !important;
    color: #2c6e96 !important;
}

/* Manage Listing card buttons - success (complete) */
.dashboard-manage-listing .btn-success {
    background-color: rgb(62, 126, 163) !important;
    border-color: rgb(62, 126, 163) !important;
    color: #ffffff !important;
}

.dashboard-manage-listing .btn-success:hover,
.dashboard-manage-listing .btn-success:focus {
    background-color: rgb(40, 95, 125) !important;
    border-color: rgb(40, 95, 125) !important;
    color: #ffffff !important;
}

.text-primary {
	color: #fd9e25;
}

/* NEW THEME BUTTON STYLE MATCH */
.custom-dashboard-cta-btn {
    background-color: #3d7da3 !important;
    border-color: #3d7da3 !important;
    color: #ffffff !important;
    transition: background-color 0.2s ease-in-out;
}
.custom-dashboard-cta-btn:hover,
.custom-dashboard-cta-btn:focus {
    background-color: #2e6180 !important;
    border-color: #2e6180 !important;
    color: #ffffff !important;
}

/* Mobile Structural Adaptability for the CTA */
@media only screen and (max-width: 767px) {
    .xs-text-center {
        text-align: center !important;
    }
    .xs-tmargin {
        margin-top: 15px !important;
    }
}

@media only screen and (max-width: 568px) {
    table.dashboard_info td {
        float: left;
        width: 100% !important;
        padding-right: 0;
    }
}
</style>