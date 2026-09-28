<?php
/*
 * Widget Name:  Bootstrap Theme - Display - Featured Restaurants
 * Short Description: Enables the Streaming of Featured Restaurant Members only.
 */
global $Label;
$_ENV['isHomepage'] = true;

// ── Restaurant Profession Filter ──────────────────────────────────────────────
// Replace RESTAURANT_PROFESSION_ID_HERE with the actual profession_id from your DB
// To find it: go to BD Admin > Professions/Categories and look for Restaurants
$restaurantProfessionId = '6';

// ── Widget-Specific Settings (independent from other widgets) ─────────────────
// Title, category badge, and View All button are pure HTML below — not tied to any BD/PHP setting
// To change them, edit the HTML directly in the header section

// Main Query Settings
$fr_maxItems = $wa['custom_171'];
$fr_onlyActiveMembers = true;
$fr_enableMemberPriority = false;
$fr_sortingOrder = 'DESC';
$fr_hideEmptyStream = true;

// Profile Settings
$fr_showMembersWithServicesOnly = false;
$fr_onlySubscriptionIds = $wa['members_onlySubscriptionIds'];
$fr_hideNoSearchable = true;
$fr_hidePostsWithoutPhotos = $wa['custom_183'];

// Category Settings
$fr_showMemberProfession = true;
$fr_useServiceName = 0;

// Location Settings
$fr_showMemberLocation = true;
$fr_enableLocalArea = true;
$fr_showProfileBtn = ($wa['recent_members_show_profile_btn']) ? true : false;

if (isset($wa['HL-HSOO-recent_members-category_to_show'])) {
    $fr_useServiceName = $wa['HL-HSOO-recent_members-category_to_show'];
    if ($fr_useServiceName == "2") {
        $fr_showMemberProfession = false;
    }
}
if ($wa['streaming_view_more_class'] != '') {
    $fr_viewAllClass = $wa['streaming_view_more_class'];
} else {
    $fr_viewAllClass = 'btn-info';
}
if ($wa['recent_members_show_location'] == "0") {
    $fr_showMemberLocation = false;
}

// Character Text Limits
$fr_memberNameLength = 50;
$fr_companyLength = 50;
$fr_serviceLength = 30;
$fr_locationLength = 24;

if (empty($fr_maxItems)) {
    $fr_maxItems = 6;
}

$fr_sqlSelectParameters = array(
    "ud.user_id",
    "ud.filename",
    "ud.first_name",
    "ud.last_name",
    "ud.company",
    "ud.listing_type",
    "ud.state_ln",
    "ud.state_code",
    "ud.city",
    "ud.country_ln",
    "ud.profession_id",
    "ud.subscription_id",
    "ud.about_me"
);

$fr_sqlTablesParameters = array("`users_data` AS ud");
$fr_sqlWhereParameters = array();
$fr_sqlGroupByParameters = array();
$fr_sqlHavingParameters = array();

if ($wa['members_sort_order'] == 'newest' || $wa['members_sort_order'] == '') {
    $fr_sqlOrderByParameters = array($fr_sortingOrder = 'DESC');
} else if ($wa['members_sort_order'] == 'oldest') {
    $fr_sqlOrderByParameters = array($fr_sortingOrder = 'ASC');
}
if ($wa['members_sort_order'] != 'random') {
    $fr_sqlOrderByParameters = array("ud.signup_date " . $fr_sortingOrder);
} else {
    $fr_sqlOrderByParameters = array("RAND()");
}

$fr_sqlLimitParameters = array($fr_maxItems);

if ($fr_hideNoSearchable == true || $fr_enableMemberPriority == true) {
    $fr_sqlTablesParameters[] = " INNER JOIN subscription_types AS st ON ud.subscription_id = st.subscription_id";
}

if ($wa['recent_members_with_star_rating']) {
    $fr_sqlSelectParameters[] = '(SELECT AVG(rating_overall) FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2) AS rating_overall ';
    $fr_sqlTablesParameters[] = "INNER JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
    $fr_sqlWhereParameters[] = "ur.review_status = 2";
    $fr_sqlGroupByParameters[] = "ud.user_id";
    $fr_sqlHavingParameters[] = "rating_overall >= " . $wa['recent_members_with_star_rating'];
}

if ($fr_onlyActiveMembers) {
    $fr_sqlWhereParameters[] = "ud.active = '2'";
}
if ($wa['recent_members_display_verified']) {
    $fr_sqlWhereParameters[] = "ud.verified = '1'";
}

$fr_verifyCode = '';
if ($wa['recent_members_display_verified_badge']) {
    $fr_verifyCode = '<div class="pull-right">' . widget("Bootstrap Theme - Member Verified Badge") . '</div>';
}

if ($fr_enableMemberPriority && $wa['members_sort_order'] != 'random') {
    if (count($fr_sqlWhereParameters) > 0) {
        array_unshift($fr_sqlWhereParameters, "ud.subscription_id = st.subscription_id");
    } else {
        $fr_sqlWhereParameters[] = "ud.subscription_id = st.subscription_id";
    }
    if (count($fr_sqlOrderByParameters) > 0) {
        array_unshift($fr_sqlOrderByParameters, "st.search_priority ASC");
    } else {
        $fr_sqlOrderByParameters[] = "st.search_priority ASC";
    }
}

if ($fr_showMembersWithServicesOnly) {
    $fr_sqlTablesParameters[] = "INNER JOIN `rel_services` AS rs ON rs.user_id = ud.user_id";
    $fr_sqlWhereParameters[] = "rs.service_id > 0";
    $fr_sqlGroupByParameters[] = "ud.user_id";
}

if ($fr_onlySubscriptionIds != "") {
    $fr_sqlWhereParameters[] = "ud.subscription_id IN (" . $fr_onlySubscriptionIds . ")";
}

// ── Filter by Restaurant Profession ID ────────────────────────────────────────
$fr_sqlWhereParameters[] = "ud.profession_id = '" . $restaurantProfessionId . "'";

if (addonController::isAddonActive('sub_accounts') && property_exists(bd_controller::user(WEBSITE_DB), "parent_id")) {
    $fr_hideParentsWhere = array(
        array('value' => '1,0', 'column' => 'value', 'logic' => '='),
        array('value' => 'hide_parent_accounts', 'column' => 'key', 'logic' => '='),
        array('value' => 'subscription_types', 'column' => 'database', 'logic' => '=')
    );
    $fr_subsWithHideParent = bd_controller::users_meta()->get($fr_hideParentsWhere);
    $fr_hideParentSubs = 0;
    if (is_array($fr_subsWithHideParent)) {
        foreach ($fr_subsWithHideParent as $fr_subWithParent) {
            $fr_hideParentSubsArray[] = $fr_subWithParent->database_id;
        }
        $fr_hideParentSubs = implode(',', $fr_hideParentSubsArray);
    } else if ($fr_subsWithHideParent !== false) {
        $fr_hideParentSubs = $fr_subsWithHideParent->database_id;
    }
    $fr_subAccountsIds = bd_controller::user()->getSubAccountsUserIds();
    $fr_sqlWhereParameters[] = "IF ( 
                    st.subscription_id IN (" . $fr_hideParentSubs . "),
                    IF(
                        ud.user_id NOT IN (" . $fr_subAccountsIds . "), 1, 0
                    ) ,1
                )  = 1";
}

if (!isset($fr_hidePostsWithoutPhotos)) {
    $fr_hidePostsWithoutPhotos = true;
}
if ($fr_hidePostsWithoutPhotos == true) {
    $fr_sqlTablesParameters[] = "INNER JOIN `users_photo` AS up ON up.user_id = ud.user_id";
    $fr_sqlWhereParameters[] = "up.file != ''";
    $fr_sqlGroupByParameters[] = "ud.user_id";
}

if ($fr_hideNoSearchable == true) {
    $fr_sqlWhereParameters[] = "st.subscription_id = ud.subscription_id";
    $fr_sqlWhereParameters[] = "st.searchable = 1";
}

$fr_membershipAdvOptQuery = mysql($w['database'], "SHOW COLUMNS FROM `subscription_types` LIKE 'search_membership_permissions'");
$fr_membershipAdvOpt = mysql_num_rows($fr_membershipAdvOptQuery);

if ($wa['custom_313'] == "2") {
    $fr_columnWidth = "col-md-6";
} else if ($wa['custom_313'] == "0") {
    $fr_columnWidth = "col-md-3";
} else {
    $fr_columnWidth = "col-md-4";
}

if ($fr_membershipAdvOpt > 0) {
    $fr_membersOnlySearchVisibilityAddOn = getAddOnInfo('members_only', 'a12e81906e726b11a95ed205c0c1ed36');
    if (isset($fr_membersOnlySearchVisibilityAddOn['status']) && $fr_membersOnlySearchVisibilityAddOn['status'] === 'success') {
        echo widget($fr_membersOnlySearchVisibilityAddOn['widget'], "", $w['website_id'], $w);
        if ($_ENV['whereValueSearchOption'] != "" && $w['new_members_search_visibility_options'] == 1) {
            $fr_sqlWhereParameters[] = $_ENV['whereValueSearchOption'];
        }
    }
}

$fr_completedFrom = array();
$w['is_front_end'] = true;
$_ENV['completed_users_meta_fields_user_ids'] = array();

users_controller::setCompleteProfileFieldsQuery($fr_completedFrom, false);
users_controller::setCompleteProfileFieldsQuery($fr_sqlWhereParameters);

if (count($fr_completedFrom) > 0 && isset($fr_completedFrom['file'])) {
    if (($w['search_results_require_complete_profile'] == "1" && strpos($w['complete_profile_fields'], "file") === false) && $fr_hidePostsWithoutPhotos == "1") {
        $fr_completedFrom['file'] .= " INNER JOIN `users_photo` AS up ON up.user_id = ud.user_id ";
    }
    if (strpos($fr_completedFrom['file'], "INNER JOIN `subscription_types`") !== false) {
        $fr_tableString = "`users_data` AS ud " . $fr_completedFrom['file'];
    } else {
        $fr_tableString = "`users_data` AS ud INNER JOIN `subscription_types` AS st on st.subscription_id = ud.subscription_id " . $fr_completedFrom['file'];
    }
    if ($wa['recent_members_with_star_rating']) {
        $fr_tableString .= " INNER JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
    }
    if (count($_ENV['completed_users_meta_fields_user_ids']) > 0) {
        $fr_tableString .= " LEFT JOIN users_meta ON ud.user_id = users_meta.database_id AND users_meta.database = 'users_data'";
        $fr_listOfIds = array();
        foreach ($_ENV['completed_users_meta_fields_user_ids'] as $fr_ids) {
            if (strpos($fr_ids, "-1") !== false) {
                $fr_listOfIds = array("-1");
                break;
            }
            $fr_ids = explode(',', $fr_ids);
            if (count($fr_listOfIds) <= 0) {
                $fr_listOfIds = $fr_ids;
            } else {
                $fr_listOfIds = array_intersect($fr_listOfIds, $fr_ids);
            }
        }
        $fr_listOfIds = implode(',', array_unique($fr_listOfIds));
        $fr_sqlWhereParameters[] = " ud.user_id IN (" . $fr_listOfIds . ") ";
    }
    $fr_sqlTablesParameters  = array($fr_tableString);
    $fr_sqlGroupByParameters = array("ud.user_id");
}

$fr_sql = "";
/* -------------------------- Code That Constructs the SQL statement ------------------------------------- */
if (count($fr_sqlSelectParameters) > 0) {
    $fr_sql .= "SELECT ";
    $fr_sql .= implode(", ", $fr_sqlSelectParameters);
}
if (count($fr_sqlTablesParameters) > 0) {
    $fr_sql .= " FROM ";
    $fr_sql .= implode(" ", $fr_sqlTablesParameters);
}
if (count($fr_sqlWhereParameters) > 0) {
    $fr_sql .= " WHERE ";
    $fr_sql .= implode(" AND ", $fr_sqlWhereParameters);
}
if (count($fr_sqlGroupByParameters) > 0) {
    $fr_sqlGroupByParameters = array_unique($fr_sqlGroupByParameters);
    $fr_sql .= " GROUP BY ";
    $fr_sql .= implode(", ", $fr_sqlGroupByParameters);
}
if (count($fr_sqlHavingParameters) > 0) {
    $fr_sql .= " HAVING ";
    $fr_sql .= implode(" AND ", $fr_sqlHavingParameters);
}
if (count($fr_sqlOrderByParameters) > 0) {
    $fr_sql .= " ORDER BY ";
    $fr_sql .= implode(", ", $fr_sqlOrderByParameters);
}
if (count($fr_sqlLimitParameters) > 0) {
    $fr_sql .= " LIMIT ";
    $fr_sql .= implode(", ", $fr_sqlLimitParameters);
}
/* -------------------------- END Code That Constructs the SQL statement ------------------------------------- */

if ($_GET['devmode'] == 1) {
    echo $fr_sql;
}

$fr_featureResults = mysql($w['database'], $fr_sql);
$fr_featureNum = mysql_num_rows($fr_featureResults);
$fr_showFeature = true;

if ($fr_hideEmptyStream == true) {
    if ($fr_featureNum > 0) {
        $fr_showFeature = true;
    } else {
        $fr_showFeature = false;
    }
}

$fr_titleStyleColor = "";
if ($wa['recent_members_tColor'] != "") {
    $fr_titleStyleColor = 'style="color: ' . $wa['recent_members_tColor'] . '"';
}

if ($fr_showFeature == true) { ?>
    <div class="clearfix"></div>
    <div class="content-container">
        <div class="clearfix"></div>
        <div class="fr-section-wrap">
            <div class="fr-header">
                <div style="width: 100%;">
                    <div style="display: flex; justify-content: center; margin-bottom: 2px;">
                        <div style="display: inline-flex; align-items: center; background-color: #fff3d6; border-radius: 50px; padding: 6px 12px;">
                            <span style="font-size: 11px; font-weight: 800; color: #a06b00; letter-spacing: 2px; font-family: 'Nunito', sans-serif; line-height: 1;">FEATURED RESTAURANTS</span>
                        </div>
                    </div>
                    <div style="margin: 0; padding: 0; text-align: center;">
                        <span style="font-size: 46px; font-weight: 700; color: #1e2d3a; font-family: 'Playfair Display', serif; line-height: 1.1;">Dine like a </span><span style="font-size: 46px; font-weight: 550; font-style: italic; color: #f07a2a; font-family: 'Playfair Display', serif; line-height: 1.1;">local</span>
                    </div>
                </div>
                <a href="/restaurants" class="fr-view-all">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="clearfix"></div>
            <div class="grid-container display-featured-restaurants">
                <div class="row fr-cards-row">
                    <div class="slickFeaturedRestaurants col-md-12">
                            <?php while ($fr_post = mysql_fetch_array($fr_featureResults)) {

                                foreach ($fr_post as $fr_key => $fr_value) {
                                    $fr_post[$fr_key] = stripslashes($fr_value);
                                }
                                $fr_post = getUser($fr_post['user_id'], $w);

                                $fr_subscription = getSubscription($fr_post['subscription_id'], $w);

                                if ($fr_enableLocalArea) {
                                    $fr_stateName = $fr_post['city'];
                                    $fr_countryName = $fr_post['state_code'];
                                } else {
                                    $fr_stateName = $fr_post['state_ln'];
                                    $fr_countryName = $fr_post['country_ln'];
                                }

                                $fr_memberUrl = $fr_post['filename'];
                                $fr_serviceName = $fr_post['service_name'];
                                $fr_professionName = '';
                                $fr_subSubCategory = '';

                                if ($fr_post['profession_id']) {
                                    $fr_professionName = getProfession($fr_post['profession_id'], $w);
                                    $fr_mainProfessionName = $fr_professionName;
                                    if (strlen($fr_professionName) > $fr_serviceLength) {
                                        $fr_professionName = mb_substr($fr_professionName, 0, $fr_serviceLength, 'UTF-8') . '...';
                                    }
                                }

                                if ($fr_useServiceName == 1 || $fr_useServiceName == 5) {
                                    if ($fr_useServiceName == 1) {
                                        if (strlen($fr_serviceName) > $fr_serviceLength) {
                                            $fr_professionName = mb_substr($fr_serviceName, 0, $fr_serviceLength, 'UTF-8') . '...';
                                        } else {
                                            $fr_professionName = $fr_serviceName;
                                        }
                                        if (empty($fr_serviceName)) {
                                            $fr_professionName = $fr_mainProfessionName;
                                        }
                                    } else if ($fr_useServiceName == 5) {
                                        $fr_subSubCategory = getMemberSubCategory($fr_post['user_id'], "subsub", "first", 1, "text");
                                        if (strlen($fr_subSubCategory) > $fr_serviceLength) {
                                            $fr_professionName = mb_substr($fr_subSubCategory, 0, $fr_serviceLength, 'UTF-8') . '...';
                                        } else {
                                            $fr_professionName = $fr_subSubCategory;
                                        }
                                        if (empty($fr_subSubCategory)) {
                                            $fr_professionName = $fr_mainProfessionName;
                                        }
                                    }
                                }

                                $fr_firstName = $fr_post['first_name'];
                                $fr_lastName = $fr_post['last_name'];

                                $fr_userPhoto = getUserPhoto($fr_post['user_id'], $fr_post['listing_type'], $w);
                                $fr_userPhoto = $fr_userPhoto['file'];

                                if ($fr_showMemberProfession) {
                                    if ($fr_professionName) {
                                        $fr_professionName;
                                    } else {
                                        $fr_professionName = '';
                                    }
                                } else {
                                    $fr_professionName = '';
                                }

                                if ($fr_stateName && $fr_countryName) {
                                    $fr_location = $fr_stateName . ', ' . $fr_countryName;
                                } else if (empty($fr_stateName) && $fr_countryName) {
                                    $fr_location = $fr_countryName;
                                } else if ($fr_stateName && empty($fr_countryName)) {
                                    $fr_location = $fr_stateName;
                                } else {
                                    $fr_location = '';
                                }

                                if (!$fr_showMemberLocation) {
                                    $fr_location = '';
                                }

                                if (strlen($fr_location) > $fr_locationLength) {
                                    $fr_location = mb_substr($fr_location, 0, $fr_locationLength, 'UTF-8') . '...';
                                }

                                $fr_companyName = $fr_post['company'];

                                if ($fr_post['listing_type'] == 'Company') {
                                    if (strlen($fr_companyName) > $fr_companyLength) {
                                        $fr_name = mb_substr($fr_companyName, 0, $fr_companyLength, 'UTF-8') . '...';
                                    } else {
                                        $fr_name = $fr_companyName;
                                    }
                                    if (strlen($fr_companyName) == 0) {
                                        $fr_name = $fr_firstName . ' ' . $fr_lastName;
                                    }
                                    $fr_nameTitle = $fr_companyName;
                                } else {
                                    $fr_name = $fr_firstName . ' ' . $fr_lastName;
                                    $fr_nameTitle = $fr_name;
                                    if (strlen($fr_name) > $fr_memberNameLength) {
                                        $fr_name = mb_substr($fr_name, 0, $fr_memberNameLength, 'UTF-8') . '...';
                                    }
                                }

                                $fr_ratingStar = '';
                                $fr_noviewclass = '';
                                if ($wa['recent_members_display_star_rating']) {
                                    $fr_rating = getUserRating($fr_post['user_id'], 'profile', $w);
                                    if ($fr_showProfileBtn) {
                                        $fr_ratingStar = '<div class="star_rating small fr-streaming-stars stars-with-image" style="line-height:1em;margin-top: 20px;"><small>' . widget('Bootstrap Theme - Function - Overall Member Rating') . '</small></div>';
                                    } else {
                                        $fr_ratingStar = '<div class="star_rating fr-streaming-stars" style="line-height:1em;margin-top: 20px;">' . widget('Bootstrap Theme - Function - Overall Member Rating') . '</div>';
                                    }
                                }
                                if ($fr_showProfileBtn) {
                                    $fr_noviewclass = 'smaller-image';
                                }
                                ?>

                                <div class="col-xs-12 col-sm-6 <?php echo $fr_columnWidth; ?> member fr-member <?php echo $fr_noviewclass; ?>">
                                    <div class="well">
                                        <div class="clearfix fpad-sm nobpad"></div>
                                        <div class="col-xs-4 nopad text-center fr-member-image">
                                            <a title="<?php echo $fr_nameTitle; ?> - %%%view_listing_label%%%" href="/<?php echo $fr_memberUrl; ?>">
                                                <?php if (!empty($w['lazy_load_images'])) { ?>
                                                    <img class="img-rounded center-block lazyloader" loading="lazy" width="400" height="400" alt="<?php echo $fr_name; ?>" data-src="<?php echo $fr_userPhoto; ?>">
                                                <?php } else { ?>
                                                    <img class="img-rounded center-block" loading="lazy" width="400" height="400" alt="<?php echo $fr_name; ?>" src="<?php echo $fr_userPhoto; ?>">
                                                <?php } ?>
                                            </a>
                                            <?php if ($fr_showProfileBtn) {
                                                echo $fr_ratingStar;
                                            } ?>
                                        </div>
                                        <div class="col-xs-8 norpad small fr-member-info">
                                            <?php if (!empty($fr_professionName)): ?>
                                                <span class="fr-category-label"><?php echo $fr_professionName; ?></span>
                                            <?php endif; ?>
                                            <a class="fr-name-title notranslate" title="<?php echo $fr_nameTitle; ?> - %%%view_listing_label%%%" href="/<?php echo $fr_memberUrl; ?>">
                                                <?php echo $fr_name; ?>
                                            </a><?php
                                            if ($fr_post['verified'] == '1') {
                                                echo $fr_verifyCode;
                                            } ?>
                                            <p>
                                                <?php if ($fr_useServiceName != "4" && $fr_useServiceName != "3") { ?>
                                                    <?php if ($fr_showMemberProfession === true && !empty($fr_professionName)): ?>
                                                        <span class="bold center-block">
                                                            <?php echo ($fr_useServiceName === '0') ? $Label['category'] : $Label['service']; ?>
                                                        </span>
                                                        <?php echo $fr_professionName; ?>
                                                    <?php endif; ?>
                                                <?php } else if ($fr_useServiceName == "4") {
                                                    $fr_post['search_description'] = bdString::prepareSpecialCharacter($fr_post['search_description']);
                                                    echo limitWords(preg_replace('#<[^>]+>#', ' ', $fr_post['search_description']), 80);
                                                    if (strlen($fr_post['search_description']) > 80) { ?>...<?php }
                                                } else if ($fr_useServiceName == "3") {
                                                    if ($fr_subscription['show_about_tab'] != 0) {
                                                        $fr_post['about_me'] = bdString::prepareSpecialCharacter($fr_post['about_me']);
                                                        if (!empty($w['froala_embedly']) && stristr($fr_post['about_me'], 'fr-embedly') !== false) {
                                                            $fr_post['about_me'] = preg_replace('#" style="(.*?)">#', '', $fr_post['about_me']);
                                                        }
                                                        echo limitWords(preg_replace('#<[^>]+>#', ' ', $fr_post['about_me']), 80);
                                                        if (strlen($fr_post['about_me']) > 80) { ?>...<?php }
                                                    }
                                                } ?>
                                                <?php if ($fr_location != ""): ?>
                                                    <span class="tmargin bold center-block">
                                                        %%%Located_in_label%%%
                                                    </span>
                                                    <?php echo $fr_location; ?>
                                                <?php endif; ?>
                                            </p>
                                            <?php if ($fr_showProfileBtn) { ?>
                                                <a title="<?php echo $fr_nameTitle; ?> - %%%view_listing_label%%%" aria-label="<?php echo $fr_nameTitle; ?> - %%%view_listing_label%%%" class="tmargin btn btn-sm btn-primary btn-block fr-btn-primary" href="/<?php echo $fr_memberUrl; ?>">
                                                    %%%view_listing_label%%%
                                                </a>
                                            <?php } else {
                                                echo $fr_ratingStar;
                                            } ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="fr-mobile-footer">
                        <a href="/restaurants" class="fr-view-all-mobile">View All <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $fr_featureNum;
    $featureSliderEnabled = (isset($wa['members_carousel_slider'])) ? $wa['members_carousel_slider'] : NULL;
    $featureMaxPerRow = $wa['custom_313'];
    $featureSliderClass = '.slickFeaturedRestaurants';
    addonController::showWidget('post_carousel_slider', '1a19675a36d28232077972bbdb6bb7fe');
    ?>
<?php } ?>

/* ── Featured Restaurants Widget Styles ── */
.fr-section-wrap {
    padding-left:0;
    padding-right: 0;
}
.fr-cards-row {
    margin-left: 0 !important;
    margin-right: 0 !important;
}
.fr-cards-row .col-md-12 {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* ── Header row — title centered, button right ── */
.fr-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
}
.fr-header::before {
    content: '';
    width: 90px;
    flex-shrink: 0;
}

/* View All button — desktop */
.fr-view-all {
    background: none;
    border: none;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none !important;
    white-space: nowrap;
    margin-right: 8px;
    padding: 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.fr-view-all:hover,
.fr-view-all:focus {
    color: #fff !important;
    opacity: 0.75;
    transform: translateX(3px);
}

/* Mobile footer */
.fr-mobile-footer {
    display: none;
    text-align: center;
    margin-top: 16px;
}

/* View All button — mobile */
.fr-view-all-mobile {
    background: none;
    border: none;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fr-view-all-mobile:hover,
.fr-view-all-mobile:focus {
    color: #fff !important;
    opacity: 0.75;
    transform: translateX(3px);
}

@media (max-width: 600px) {
    .fr-view-all {
        display: none;
    }
    .fr-header {
        justify-content: center;
    }
    .fr-header::before {
        display: none;
    }
    .fr-mobile-footer {
        display: block;
    }
}
.display-featured-restaurants {
    padding-left: 0 !important;
    padding-right: 0 !important;
}
.display-featured-restaurants .row {
    margin-left: 0 !important;
    margin-right: 0 !important;
}
.display-featured-restaurants .fr-member {
    padding-left: 5px !important;
    padding-right: 5px !important;
}
.display-featured-restaurants .row {
    margin-left: 0 !important;
    margin-right: 0 !important;
}
.display-featured-restaurants .fr-member {
    padding-left: 5px !important;
    padding-right: 5px !important;
}
.display-featured-restaurants .fr-member {
    vertical-align: top;
}
.fr-member-image {
    height: 130px !important;
}
.fr-member-image img {
    max-height: 130px !important;
    max-width: 130px !important;
    width: 100% !important;
}
.fr-member.smaller-image img {
    max-height: 100px;
}
.fr-member-info p {
    min-height: 75px;
}
.fr-streaming-stars.stars-with-image .the-rating-text {
    display: block;
}
.fr-streaming-stars .the-average-rating {
    display: none;
}
.fr-streaming-stars .the-star-icons {
    letter-spacing: -1px;
}
.fr-streaming-stars .the-review-count {
    font-size: 11px;
}
.fr-streaming-stars .star_rating {
    white-space: nowrap;
}
.display-featured-restaurants .fr-streaming-stars .fa-star,
.display-featured-restaurants .fr-streaming-stars .fa-star-half-o {
    color: rgb(245, 166, 35) !important;
}
.display-featured-restaurants .fr-member .well {
    background-color: rgb(250, 246, 239) !important;
    border-color: rgb(250, 246, 239) !important;
    border-radius: 16px !important;
    overflow: hidden;
}

/* ── Category label above the member name ── */
.display-featured-restaurants .fr-member-info .fr-category-label {
    font-family: 'Nunito', sans-serif !important;
    font-size: 10px !important;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #3e7ea3 !important;
    display: block;
    margin-bottom: 2px;
}

/* ── Member name title ── */
.display-featured-restaurants .fr-member-info .fr-name-title {
    font-family: 'Nunito', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700;
    color: #1e2d3a;
    display: block;
    margin-bottom: 6px;
    line-height: 1.3;
}

.display-featured-restaurants .fr-member-image .star_rating {
    margin-top: 20px !important;
}

.display-featured-restaurants .fr-btn-primary {
    background-color: rgb(62, 126, 163) !important;
    border-color: rgb(62, 126, 163) !important;
}

<?php
$fr_columnWidth = 0;
if ($wa['custom_313'] == "1" || !isset($wa['custom_313'])) {
    $fr_columnWidth = 3;
} else if ($wa['custom_313'] == "2") {
    $fr_columnWidth = 2;
} else if ($wa['custom_313'] == "0") {
    $fr_columnWidth = 4;
}
?>
<?php if ($wa['members_carousel_slider'] != '1' || ($wa['members_carousel_slider'] == '1' && !addonController::isAddonActive('post_carousel_slider'))): ?>
@media only screen and (min-width: 991px) {
    .display-featured-restaurants .fr-member:nth-child(<?php echo $fr_columnWidth; ?>n+1) {
        clear: left;
    }
}
@media only screen and (max-width: 991px) {
    .display-featured-restaurants .fr-member:nth-child(2n+1) {
        clear: left;
    }
}
<?php endif; ?>