<?php
//get user subscription
$sub = getSubscription($user['subscription_id'],$w);
//get user top level category name
$topCategory = stripslashes(getProfession($user['profession_id'], $w)) ?: $w['profession'];
//get the review data_id
$reviewIdQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        *
    FROM
        `data_categories`
    WHERE
        data_type = '13'
    LIMIT
        1");
$reviewId = mysql_fetch_assoc($reviewIdQuery);
$reviewState = 0;
foreach ($sub['data_settings'] as $sdskey => $sdsvalue) {

    if ($sdsvalue == $reviewId['data_id']) {
        $reviewState = 1;//ON
    }
}
$userPhoto = getUserPhoto ($user['user_id'], $user['listing_type'], $w);
$userPhoto = $userPhoto['file'];

if($sub['coverPhoto'] == 1){
    addonController::showWidget('profile_cover_photo', '023876071143573e41dd0e03f5f56894','');
}
?>

<div class="row member-profile-header bmargin">
    <div class="col-xs-12 <?php if ($subscription['profile_badge'] != "" || $user['verified'] == 1 || $user['nationwide'] == 1 || strtolower($subscription['location_limit']) == "all") { ?>col-sm-2<? } else { ?>col-sm-3<?php } ?> text-center xs-hpad xs-bmargin profile-image">
		<?php
		list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $userPhoto);
		if ($attr == "") {
			$attr = 'width="400" height="400"';
		}
		if ($sub['receive_messages'] != 1) { 
		?>
            <a href="/<?php echo $user['filename']; ?>/<?php echo $w['default_connect_url'];?>" title="%%%contact_label%%% <?php echo $user['full_name'];?> - <?php echo $topCategory; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>">
                <img <?php echo $attr; ?> class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $user['full_name']; ?> %%%Profile_photo_alt_attribute%% - <?php echo $topCategory; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name'];?> - <?php echo $topCategory; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>">
            </a>
        <? } else { ?>
            <img <?php echo $attr; ?> class="img-rounded" src="<?php echo $userPhoto;?>" alt="<?php echo $user['full_name']; ?> %%%Profile_photo_alt_attribute%% - <?php echo $topCategory; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>" title="%%%contact_label%%% <?php echo $user['full_name'];?> - <?php echo $topCategory; ?> %%%in_label%%% <?php echo $user['city']; ?> <?php echo $user['state_code']; ?>">
        <?php } ?>
    </div>
    <div class="xs-text-center col-xs-12 col-sm-9 nolpad xs-hpad the-header-member-main-info">
        <div class="row the-header-member-name">
<div class="col-sm-10 norpad xs-hpad header-member-name xs-center-block notranslate">

    <h1 class="bold inline-block">
        <?php echo $user['full_name']; ?>
    </h1>

    <?php if ($user['profession_id'] != "") { ?>
        <div class="profile-header-top-category <?php echo $invisibleClass; ?>">
            <?php echo strtoupper($topCategory); ?>
        </div>
    <?php } ?>

</div>
			<?php
			$addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
			if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
				echo "<div class='col-sm-2 text-right nolpad bmargin xs-nopad xs-text-center xs-center-block header-favorite-button'>";
				echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
				echo "</div>";
			} ?>
        </div>
        <div class="row the-header-member-details">
            <div class="col-sm-6 tmargin xs-nomargin">
                <p class="line-height-xl nomargin">
                    <?php
                    $invisibleClass ='';
                    if($w['hide_top_level_member_profile'] == 1){
                        $invisibleClass ='invisible';
                    }
                    if ($user['listing_type'] != 'Company') {
                        echo "<span class=profile-header-company>";
                        if ($user['company'] != "") {

                            if ($user['position'] != "") { ?>
                                <?php echo $user['position'];?> %%%at_label%%%
                            <?php }
                            echo $user['company'];
                            echo "<br /></span>";
                        }
                    }
                    if ($sub['profile_layout'] != "0" && (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code']) || !empty($user['country_ln']))) {
                        echo '<span class=profile-header-location><i class="fa fa-map-marker text-danger"></i> ';

                        if (!empty($user['city']) || !empty($user['state_ln']) || !empty($user['zip_code'])) {
                            if (!empty($user['city'])) { 
                                echo $user['city'];
                            }
                            if (!empty($user['state_ln'])) {
                                if(!empty($user['city'])) {
                                    echo ", ";
                                }
                                echo $user['state_ln'];
                            }
                            if (!empty($user['zip_code'])) {
                                if(!empty($user['city']) || !empty($user['state_ln'])) {
                                    echo ", ";
                                }
                                echo $user['zip_code'];
                            }
                        } else if (!empty($user['country_ln'])) {
                            echo $user['country_ln'];
                        }
                        echo "</span>";
                    } ?>
                </p>
            </div>

            <?php
            //review on && can send leads,show phone || review on && can't send leads, hide phone
            if ($reviewState == 1 && (($sub['receive_messages'] != 1 && $user['phone_number'] != "" && $sub['show_phone'] == 1) || ($subscription['receive_messages'] == 1 && ($subscription['show_phone'] != 1 || $sub['show_phone'] == 1 && $user['phone_number'] == "")))) { ?>
                <div class="col-sm-6 tmargin profile-header-write-review">
                    <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                        echo $rating['stars'];
                    } ?>
                    <?php
                    if ($reviewState == 1) { ?>
                        <a class="tmargin btn btn-secondary btn-lg btn-block btn-write_a_review_for" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                            %%%profile_write_a_review%%%
                        </a>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            <?php } else { ?>
                <div class="clearfix"></div>
            <?php } ?>


            <?php
            //can send leads
            if ($sub['receive_messages'] != 1) { ?>
                <div class="col-sm-6 tmargin tpad xs-nomargin profile-header-send-message">
                    <a class="btn btn-primary btn-block btn-lg btn-send_message_action" title="%%%contact_label%%% <?php echo $user['full_name']; ?>" href="/<?php echo $user['filename']; ?>/<?php echo $w['default_connect_url'];?>">
                    <?php if ($w['enable_direct_chat_messages'] == "1" && $subscription['enable_direct_messages'] == "1") { ?>
                        %%%send_message_action%%%
                    <?php } else { ?>
                        %%%profile_send_message_button%%%
                    <?php } ?>
                    </a>
                </div>
            <?php } ?>

            <?php
            //show phone number
            if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>
                <div class="col-sm-6 tmargin tpad xs-nomargin profile-header-phone-number">
                    <?php
                    $clickPhoneAddOn = getAddOnInfo("click_to_phone","16c3439fea1f8b6d897987ea402dcd8e");
                    $statisticsAddOn = getAddOnInfo("user_statistics_addon","7f778bc02f0e6acbbd847b4061c7b76d");

                    if(isset($clickPhoneAddOn['status']) && $clickPhoneAddOn['status'] === 'success'){
                        echo widget($clickPhoneAddOn['widget'],"",$w['website_id'],$w);
                    } else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {
                        echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);
                    } else {
                        if ($user['phone_number'] != "" && $sub['show_phone'] == 1) { ?>
                            <span style="padding:10px 16px;" class="well nobmargin text-center btn-lg btn-block author-phone">
                                %%%show_phone_number_icon%%%
                                <?php echo $user['phone_number']; ?>
                            </span>
                        <? }
                    } ?>
                </div>
            <? } ?>

            <?php //echo $subscription['hide_reviews_rating_options'] ;?>

            <?php
            //reviews on && can send leads,hide phone || can't send leads, show phone
            if ($reviewState == 1 && (($subscription['receive_messages'] == 1 && $subscription['show_phone'] == 1 && $user['phone_number'] != "") || ($sub['receive_messages'] != 1 && ($sub['show_phone'] == 0 ||  $sub['show_phone'] == 1 && $user['phone_number'] == "")))) {
                $reviewsAmount = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT 1
                FROM
                    `users_reviews`
                WHERE
                    user_id = ".$user['user_id']." AND review_status = 2");
                $memberReviewsExist = mysql_fetch_array($reviewsAmount);
                ?>
                <div class="col-sm-6 profile-header-write-review" style="margin-top:<?php if ($memberReviewsExist == false || $subscription['hide_reviews_rating_options'] == 1){echo '8px';}else{echo '-15px';}?>;">


                    <?php
                    if ($reviewState == 1 && $subscription['hide_reviews_rating_options'] == 0) {
                        echo $rating['stars'];
                    } ?>
                    <?php
                    if ($reviewState == 1) { ?>
                        <a class="tmargin btn btn-secondary btn-lg btn-block btn-write_a_review_for" href="/<?php echo $user['filename']; ?>/writeareview" title="%%%write_a_review_for%%%">
                            %%%profile_write_a_review%%%
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>
    </div>
    <?php
    if ($subscription['profile_badge'] != "" || $user['verified'] == 1 || $user['nationwide'] == 1 || strtolower($subscription['location_limit']) == "all") { ?>
        <div class="hidden-xs col-sm-1 nolpad member-badges">
            <?php echo widget("Bootstrap Theme - Member Profile - Badges","",$w['website_id'],$w); ?>
        </div>
    <?php } ?>
</div>
<div class="clearfix"></div>

<style>
    .member-profile-header {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
}
.member-profile-header .member-badges {
	align-self: flex-start;
}

@media only screen and (max-width: 767px) {
	.profile-header-write-review {
		margin-top: 10px !important;
	}
	    .profile-header-top-category {
        margin-left: auto;
        margin-right: auto;
		margin-bottom: 8px;
    }
	    .header-member-name h1 {
        font-size: 28px;   /* adjust if you want smaller/larger */
        line-height: 1.2;
    }
	    /* General button size reduction */
    .profile-header-write-review .btn,
    .profile-header-send-message .btn,
    .profile-header-phone-number .author-phone {
        font-size: 16px !important;
        padding: 8px 12px !important;
        border-radius: 8px !important;
    }
	    .profile-header-write-review {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
    }

    .profile-header-write-review > div,
    .profile-header-write-review .rating_container {
        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
    }

    .profile-header-write-review i {
        float: none !important;
        display: inline-block !important;
    }
	
}
/* =========================
   CATEGORY PILL STYLE
========================= */
.profile-header-top-category {
    display: block;              /* 🔥 forces next line */
    width: fit-content;         /* keeps pill tight */
    background: #f1f5f9;
    color: #2a7ab5;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 800;
    line-height: 1;
    margin-top: 0;
    letter-spacing: 0.05em;
}
/* LIKE button base */
button.favorite {
    background-color: #fff4ed !important;
    border-color: #fff4ed !important;
    color: #666 !important;
    border-radius: 20px;
    transition: all 0.2s ease;
}

/* hide inner icon/text */
button.favorite #bookmark-content {
    display: none !important;
}

/* default label */
button.favorite::after {
    content: 'LIKE';
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* ACTIVE STATE */
button.favorite.favoriteActive {
    background-color: #fff4ed !important;
    border-color: #fff4ed !important;
    color: rgb(245, 166, 35) !important;
}

/* ACTIVE LABEL */
button.favorite.favoriteActive::after {
    content: 'LIKED';
    color: rgb(245, 166, 35) !important;
}

/* =========================
   SPECIAL BUTTONS (KEEP SAME STYLE)
========================= */

/* Add to Calendar */
.posted-by-snippet-buttons .btn-add-to-calendar-dropdown {
    background-color: #e8f4fd !important;
    border-color: #e8f4fd !important;
    color: #2a7ab5 !important;
}

/* Print button */
.posted-by-snippet-buttons .print-btn {
    background-color: #e8f4fd !important;
    border-color: #e8f4fd !important;
    color: #2a7ab5 !important;
}

/* =========================
   STAR RATINGS
========================= */
#the-post-comments .rating_container i.fa-star,
#the-post-comments .rating_container i.fa-star-o,
#the-post-comments .rating_container i.fa-star-half-o {
    color: rgb(245, 166, 35) !important;
}

/* =========================
   PROFILE HEADER BUTTON COLORS
========================= */


/* WRITE REVIEW → SKY BLUE */
.profile-header-write-review .btn {
    background-color: #2a7ab5 !important;
    border-color: #2a7ab5 !important;
    color: #fff !important;
}

.profile-header-write-review .btn:hover,
.profile-header-write-review .btn:focus {
    background-color: #3498db !important;
    border-color: #3498db !important;
}

/* PHONE NUMBER → SAME AS DEFAULT (NO CHANGE) */
.profile-header-phone-number .author-phone {
    background-color: #e8f4fd !important;
    border-color: #e8f4fd !important;
    color: #2a7ab5 !important;
}

</style>

<script>

$('.view_phone_number_header').click(function(event){
	event.preventDefault();
	$(this).hide();
	$('.view_phone_number').hide();
	$('.phone_number').css("display","block");
	$('.phone_number_header').css("display","block");
	
})

</script>