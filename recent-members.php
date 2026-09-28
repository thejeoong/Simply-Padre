<?php
/*
 * Widget Name:  Bootstrap Theme - Display - Recent Members
 * Short Description: Enables the Streaming of the Membership Feature Listing.
 */
global $Label;
$_ENV['isHomepage'] = true;

// Main Query Settings
$maxItems = $wa['custom_171']; // Maximum amount of streaming members shown. Default is 4.
$onlyActiveMembers = true; // Show or Hide results from Active Members Only. Default is true.
$enableMemberPriority = false; // Gives priority to members based on the search priority in their membership level. Default is false.
$sortingOrder = 'DESC'; // Sorting Order - DESC (Descending) & ASC (Ascending). Default is DESC.
$hideEmptyStream = true; // This hides the feature if there are no posts to show in it. Default is true.

// Profile Settings
$showMembersWithServicesOnly = false; // Only show members who have sub level categories? Default is false.
$onlySubscriptionIds = $wa['members_onlySubscriptionIds']; // Comma separated list of the subscription_ids that members are listed in to show
$hideNoSearchable = true; // This hides members who are "not-publicly" searchable on the website
$hidePostsWithoutPhotos = $wa['custom_183']; // This will show or hide members if they do not have profile photos. Default is true.

// Category Settings
$showMemberProfession = true; // Enable or Disable showing the member's category. Default is true.
$useServiceName = 0; // A value of 0 displays the member's Top Level Category. A value of 1 displays the member's Sub Level Category.

// Location Settings
$showMemberLocation = true; // Enable or Disable showing the Location of a member. Default is true.
$enableLocalArea = true; // Show the member's City and State location instead of State and Country. Default is true.
$showProfileBtn = ($wa['recent_members_show_profile_btn'])?true:false;


if(isset($wa['HL-HSOO-recent_members-category_to_show'])){
    $useServiceName = $wa['HL-HSOO-recent_members-category_to_show'];
    if($useServiceName == "2"){
        $showMemberProfession = false;
    }
}
if($wa['streaming_view_more_class'] != ''){
    $viewAllClass = $wa['streaming_view_more_class'];
} else {
    $viewAllClass = 'btn-info';
}

if($wa['recent_members_show_location'] == "0"){
    $showMemberLocation = false;
}

// Character Text Limits
$memberNameLength = 50; // increased from 30 to allow more text before ellipsis
$companyLength = 50;    // increased from 30
$serviceLength = 30;
$locationLength = 24;

if (empty($maxItems)) {
    $maxItems = 6;
}

$sqlSelectParameters = array(
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

$sqlTablesParameters = array(
    "`users_data` AS ud"
);
$sqlWhereParameters = array();
$sqlGroupByParameters = array();
$sqlHavingParameters = array();
if($wa['members_sort_order'] == 'newest' || $wa['members_sort_order'] == ''){
    $sqlOrderByParameters = array(
        $sortingOrder = 'DESC'
    );
} else if ($wa['members_sort_order'] == 'oldest') {
    $sqlOrderByParameters = array(
        $sortingOrder = 'ASC'
    );
}
if ($wa['members_sort_order'] != 'random'){
    $sqlOrderByParameters = array(
        "ud.signup_date " . $sortingOrder
    );
} else {
    $sqlOrderByParameters = array(
        "RAND()"
    );
}
$sqlLimitParameters = array(
    $maxItems
);
if ($hideNoSearchable == true || $enableMemberPriority == true) {
    $sqlTablesParameters[] = " INNER JOIN subscription_types AS st ON ud.subscription_id = st.subscription_id";
}

if ($wa['recent_members_with_star_rating']) { //rating
    $sqlSelectParameters[] = '(SELECT AVG(rating_overall) FROM `users_reviews` AS ur WHERE ud.user_id = ur.user_id AND ur.review_status = 2) AS rating_overall ';
    $sqlTablesParameters[] = "INNER JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
    $sqlWhereParameters[] = "ur.review_status = 2";
    $sqlGroupByParameters[] = "ud.user_id";
    $sqlHavingParameters[] = "rating_overall >= " . $wa['recent_members_with_star_rating'];
}

if ($onlyActiveMembers) {
    $sqlWhereParameters[] = "ud.active = '2'";
}
if ($wa['recent_members_display_verified']) {
    $sqlWhereParameters[] = "ud.verified = '1'";
}
$verifyCode = '';
if ($wa['recent_members_display_verified_badge']) {
    $verifyCode = '<div class="pull-right">' . widget("Bootstrap Theme - Member Verified Badge") . '</div>';
}
if ($enableMemberPriority && $wa['members_sort_order'] != 'random') {

    if (count($sqlWhereParameters) > 0) {
        array_unshift($sqlWhereParameters, "ud.subscription_id = st.subscription_id");
    } else {
        $sqlWhereParameters[] = "ud.subscription_id = st.subscription_id";
    }
    if (count($sqlOrderByParameters) > 0) {
        array_unshift($sqlOrderByParameters, "st.search_priority ASC");
    } else {
        $sqlOrderByParameters[] = "st.search_priority ASC";
    }
}
if ($showMembersWithServicesOnly) {
    $sqlTablesParameters[] = "INNER JOIN `rel_services` AS rs ON rs.user_id = ud.user_id";
    $sqlWhereParameters[] = "rs.service_id > 0";
    $sqlGroupByParameters[] = "ud.user_id";
}
if ($onlySubscriptionIds != "") {
    $sqlWhereParameters[] = "ud.subscription_id IN (".$onlySubscriptionIds.")";
}

if(addonController::isAddonActive('sub_accounts') && property_exists(bd_controller::user(WEBSITE_DB), "parent_id")){

    $hideParentsWhere = array(
        array('value' => '1,0' , 'column' => 'value', 'logic' => '='),
        array('value' => 'hide_parent_accounts' , 'column' => 'key', 'logic' => '='),
        array('value' => 'subscription_types' , 'column' => 'database', 'logic' => '=')
    );

    $subsWithHideParent = bd_controller::users_meta()->get($hideParentsWhere);
    $hideParentSubs     = 0;
    if(is_array($subsWithHideParent)){
        foreach($subsWithHideParent as $subWithParent){
            $hideParentSubsArray[] = $subWithParent->database_id;
        }
        $hideParentSubs = implode(',', $hideParentSubsArray);
    } else if($subsWithHideParent !== false) {
        $hideParentSubs = $subsWithHideParent->database_id;
    }

    $subAccountsIds = bd_controller::user()->getSubAccountsUserIds();

    $sqlWhereParameters[]=  "IF ( 
                    st.subscription_id IN (".$hideParentSubs."),
                    IF(
                        ud.user_id NOT IN (".$subAccountsIds."), 1, 0
                    ) ,1
                )  = 1";
}

if (!isset($hidePostsWithoutPhotos)) {
    $hidePostsWithoutPhotos = true;
}
if ($hidePostsWithoutPhotos == true) {
    $sqlTablesParameters[] = "INNER JOIN `users_photo` AS up ON up.user_id = ud.user_id";
    $sqlWhereParameters[] = "up.file != ''";
    $sqlGroupByParameters[] = "ud.user_id";
}

if ($hideNoSearchable == true) {
    $sqlWhereParameters[] = "st.subscription_id = ud.subscription_id";
    $sqlWhereParameters[] = "st.searchable = 1";
}

$membershipAdvOptQuery = mysql($w['database'], "SHOW COLUMNS FROM `subscription_types` LIKE 'search_membership_permissions'");
$membershipAdvOpt = mysql_num_rows($membershipAdvOptQuery);

if ($wa['custom_313'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_313'] == "0") {
    $columnWidth = "col-md-3";
} else {
    $columnWidth = "col-md-4";
}

if ($membershipAdvOpt > 0) {
    $membersOnlySearchVisibilityAddOn = getAddOnInfo('members_only','a12e81906e726b11a95ed205c0c1ed36');
    if (isset($membersOnlySearchVisibilityAddOn['status']) && $membersOnlySearchVisibilityAddOn['status'] === 'success') {
        echo widget($membersOnlySearchVisibilityAddOn['widget'],"",$w['website_id'],$w);
        if ($_ENV['whereValueSearchOption'] != "" && $w['new_members_search_visibility_options'] == 1) {
            $sqlWhereParameters[] = $_ENV['whereValueSearchOption'];
        }
    }
}
$completedFrom      = array();
$w['is_front_end']                              = true;
$_ENV['completed_users_meta_fields_user_ids']   = array();

users_controller::setCompleteProfileFieldsQuery($completedFrom,false);
users_controller::setCompleteProfileFieldsQuery($sqlWhereParameters);

if(count($completedFrom) > 0 && isset($completedFrom['file'])){

    if (($w['search_results_require_complete_profile'] == "1" && strpos($w['complete_profile_fields'],"file") === false) && $hidePostsWithoutPhotos == "1") {
        $completedFrom['file'].= " INNER JOIN `users_photo` AS up ON up.user_id = ud.user_id ";
    }

    if(strpos($completedFrom['file'],"INNER JOIN `subscription_types`") !== false){
        $tableString = "`users_data` AS ud ".$completedFrom['file'];
    }else{
        $tableString = "`users_data` AS ud INNER JOIN `subscription_types` AS st on st.subscription_id = ud.subscription_id ".$completedFrom['file'];
    }

    if ($wa['recent_members_with_star_rating']) {
        $tableString .= " INNER JOIN users_reviews AS ur ON ud.user_id = ur.user_id ";
    }

    if(count($_ENV['completed_users_meta_fields_user_ids']) > 0){
        $tableString .= " LEFT JOIN users_meta ON ud.user_id = users_meta.database_id AND users_meta.database = 'users_data'";
        $listOfIds = array();
        foreach($_ENV['completed_users_meta_fields_user_ids'] as $ids){
            if(strpos($ids,"-1") !== false){
                $listOfIds = array("-1");
                break;
            }
            $ids = explode(',',$ids);
            if(count($listOfIds) <= 0){
                $listOfIds = $ids;
            }else{
                $listOfIds = array_intersect($listOfIds,$ids);
            }
        }
        $listOfIds = implode(',',array_unique($listOfIds));
        $sqlWhereParameters[] = " ud.user_id IN (".$listOfIds.") ";
    }

    $sqlTablesParameters  = array($tableString);
    $sqlGroupByParameters = array("ud.user_id");
}

$sql = "";
/* -------------------------- Code That Constructs the SQL statement ------------------------------------- */
if (count($sqlSelectParameters) > 0) {
    $sql .= "SELECT ";
    $sql .= implode(", ",$sqlSelectParameters);
}
if (count($sqlTablesParameters) > 0) {
    $sql .= " FROM ";
    $sql .= implode(" ",$sqlTablesParameters);
}
if (count($sqlWhereParameters) > 0) {
    $sql .= " WHERE ";
    $sql .= implode(" AND ",$sqlWhereParameters);
}
if (count($sqlGroupByParameters) > 0) {
    $sqlGroupByParameters = array_unique($sqlGroupByParameters);
    $sql .= " GROUP BY ";
    $sql .= implode(", ",$sqlGroupByParameters);
}
if (count($sqlHavingParameters) > 0) {
    $sql .= " HAVING ";
    $sql .= implode(" AND ",$sqlHavingParameters);
}
if (count($sqlOrderByParameters) > 0) {
    $sql .= " ORDER BY ";
    $sql .= implode(", ",$sqlOrderByParameters);
}
if (count($sqlLimitParameters) > 0) {
    $sql .= " LIMIT ";
    $sql .= implode(", ",$sqlLimitParameters);
}
/* -------------------------- END Code That Constructs the SQL statement ------------------------------------- */

if ($_GET['devmode'] == 1) {
    echo $sql;
}
$featureResults = mysql($w['database'], $sql);
$featureNum = mysql_num_rows($featureResults);
$showFeature = true;

if ($hideEmptyStream == true) {
    if ($featureNum > 0) {
        $showFeature = true;
    } else {
        $showFeature = false;
    }
}

$titleStyleColor = "";
if($wa['recent_members_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_members_tColor'].'"';
}

if ($showFeature == true) { ?>
    <div class="clearfix"></div>
    <div class="content-container">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($wa['members_show_view_all'] == 1) { ?>
                        <a href="/<?php echo $w['default_search_url'];?>" class="view-all-btn-desktop hidden-xs rm-view-all">
                            %%%view_all_label%%% <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php } ?>
                    <h2 class="nomargin sm-text-center bold streaming-title" <?php echo $titleStyleColor ?>>
                        <?php echo $wa['custom_125']; ?>
                    </h2>
                    <hr>
                </div>
                <div class="clearfix"></div>
                <div class="grid-container display-recent-members ">
                    <div class="row">
                        <div class="slickMembers col-md-12">
                            <?php while ($post = mysql_fetch_array($featureResults)) {

                                foreach ($post as $key => $value) {
                                    $post[$key] = stripslashes($value);
                                }
                                $post = getUser($post['user_id'],$w);

                                $subscription = getSubscription($post['subscription_id'], $w);
                                if ($enableLocalArea) {
                                    $stateName = $post['city'];
                                    $countryName = $post['state_code'];
                                } else {
                                    $stateName = $post['state_ln'];
                                    $countryName = $post['country_ln'];
                                }

                                $memberUrl = $post['filename'];
                                $memberProfession = ucwords($w['profession']);
                                $listingLabel = $Label['listing'];
                                $listingSignupUrl = $Label['default_signup_url'];
                                $serviceName = $post['service_name'];
                                $professionName = '';
                                $subSubCategory = '';

                                if ($post['profession_id']) {
                                    $professionName = getProfession($post['profession_id'], $w);
                                    $mainProfessionName = $professionName;
                                    if (strlen($professionName) > $serviceLength ) {
                                        $professionName = mb_substr($professionName,0,$serviceLength, 'UTF-8').'...';
                                    }
                                }

                                if ($useServiceName == 1 || $useServiceName == 5) {
                                    if ($useServiceName == 1) {
                                        if (strlen($serviceName) > $serviceLength ) {
                                            $professionName = mb_substr($serviceName,0,$serviceLength, 'UTF-8').'...';
                                        } else {
                                            $professionName = $serviceName;
                                        }
                                        if (empty($serviceName)) {
                                            $professionName = $mainProfessionName;
                                        }
                                    } else if ($useServiceName == 5 ) {
                                        $subSubCategory = getMemberSubCategory($post['user_id'], "subsub", "first", 1, "text");
                                        if (strlen($subSubCategory) > $serviceLength ) {
                                            $professionName = mb_substr($subSubCategory,0,$serviceLength, 'UTF-8').'...';
                                        } else {
                                            $professionName = $subSubCategory;
                                        }
                                        if (empty($subSubCategory)) {
                                            $professionName = $mainProfessionName;
                                        }
                                    }
                                }

                                $firstName = $post['first_name'];
                                $lastName = $post['last_name'];

                                $userPhoto = getUserPhoto($post['user_id'], $post['listing_type'], $w);
                                $userPhoto = $userPhoto['file'];

                                if ($showMemberProfession) {
                                    if ($professionName) {
                                        $professionName;
                                    } else {
                                        $professionName = '';
                                    }
                                } else {
                                    $professionName = '';
                                }

                                if ($stateName && $countryName) {
                                    $location = $stateName.', '.$countryName;
                                } else if (empty($stateName) && $countryName) {
                                    $location = $countryName;
                                } else if ($stateName && empty($countryName)) {
                                    $location = $stateName;
                                } else {
                                    $location = '';
                                }

                                if (!$showMemberLocation) {
                                    $location = '';
                                }

                                if (strlen($location) > $locationLength ) {
                                    $location = mb_substr($location,0,$locationLength, 'UTF-8').'...';
                                }

                                $companyName = $post['company'];

                                if ($post['listing_type'] == 'Company') {
                                    if (strlen($companyName) > $companyLength) {
                                        $name = mb_substr($companyName,0,$companyLength, 'UTF-8').'...';
                                    } else {
                                        $name = $companyName;
                                    }
                                    if (strlen($companyName) == 0) {
                                        $name = $firstName.' '.$lastName;
                                    }
                                    $nameTitle = $companyName;
                                } else {
                                    $name = $firstName.' '.$lastName;
                                    $nameTitle = $name;
                                    if (strlen($name) > $memberNameLength) {
                                        $name = mb_substr($name,0,$memberNameLength, 'UTF-8').'...';
                                    }
                                }

                                $ratingStar ='';
                                $noviewclass ='';
                                if ($wa['recent_members_display_star_rating']) {
                                    $rating = getUserRating($post['user_id'], 'profile', $w);
                                    if ($showProfileBtn) {
                                        $ratingStar = '<div class="star_rating small streaming-recent-member-stars stars-with-image" style="line-height:1em;margin-top: 20px;"><small>' . widget('Bootstrap Theme - Function - Overall Member Rating') . '</small></div>';
                                    } else {
                                        $ratingStar = '<div class="star_rating streaming-recent-member-stars" style="line-height:1em;margin-top: 20px;">' . widget('Bootstrap Theme - Function - Overall Member Rating') . '</div>';
                                    }
                                }
                                if ($showProfileBtn) {
                                    $noviewclass = 'smaller-image';
                                }
                                ?>

                                <div class="col-xs-6 col-sm-4 <?php echo $columnWidth; ?> member recent-member <?php echo $noviewclass; ?>">
                                    <div class="rm-circle-card">
                                        <a href="/<?php echo $memberUrl; ?>" class="rm-circle-photo-link">
                                            <?php if (!empty($w['lazy_load_images'])) { ?>
                                                <img class="rm-circle-photo lazyloader" loading="lazy" width="400" height="400" alt="<?php echo $name; ?>" data-src="<?php echo $userPhoto; ?>">
                                            <?php } else { ?>
                                                <img class="rm-circle-photo" loading="lazy" width="400" height="400" alt="<?php echo $name; ?>" src="<?php echo $userPhoto; ?>">
                                            <?php } ?>
                                        </a>
                                        <div class="rm-circle-name">
                                            <?php echo $name; ?>
                                        </div>
                                        <a href="/<?php echo $memberUrl; ?>" class="rm-circle-btn">
                                            %%%view_listing_label%%%
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($wa['members_show_view_all'] == 1) { ?>
                        <div class="clearfix"></div>
                        <div class="col-md-12 text-center">
                            <a href="/<?php echo $w['default_search_url'];?>" class="rm-view-all-mobile visible-xs-block">
                                %%%view_all_label%%% <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $featureNum;
    $featureSliderEnabled = (isset($wa['members_carousel_slider']))?$wa['members_carousel_slider']:NULL;
    $featureMaxPerRow = $wa['custom_313'];
    $featureSliderClass = '.slickMembers';
    addonController::showWidget('post_carousel_slider','1a19675a36d28232077972bbdb6bb7fe');
    ?>
<?php } ?>


/* ── View All button — desktop (white text + arrow) ── */
.rm-view-all {
    background: none;
    border: none;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none !important;
    white-space: nowrap;
    padding: 0;
    float: right;
    margin-top: 25px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.rm-view-all:hover,
.rm-view-all:focus {
    color: #fff !important;
    text-decoration: none !important;
    opacity: 0.75;
    transform: translateX(3px);
}
/* ── View All button — mobile (white text + arrow) ── */
.rm-view-all-mobile {
    background: none;
    border: none;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.rm-view-all-mobile:hover,
.rm-view-all-mobile:focus {
    color: #fff !important;
    text-decoration: none !important;
    opacity: 0.75;
    transform: translateX(3px);
}
/* ── Recent Members — Circle Layout ── */
.display-recent-members .recent-member {
    vertical-align: top;
    text-align: center;
    margin-bottom: 24px;
}
/* Remove well/card background */
.display-recent-members .recent-member .well {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
}
/* Circle card wrapper */
.rm-circle-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    max-width: 200px;
    margin: 0 auto;
}
/* Circle photo */
.rm-circle-photo {
    width: 200px !important;
    height: 200px !important;
    max-width: 200px !important;
    max-height: 200px !important;
    border-radius: 50% !important;
    object-fit: cover !important;
    display: block;
    border: 4px solid rgba(255,255,255,0.4);
    transition: transform 0.3s ease, border-color 0.3s ease;
}
.rm-circle-photo-link:hover .rm-circle-photo {
    transform: scale(1.05);
    border-color: rgba(255,255,255,0.9);
}
/* Name */
.rm-circle-name {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    text-align: center;
}
/* Visit Profile button */
.rm-circle-btn {
    display: inline-block;
    background-color: rgba(255, 255, 255, 0.15) !important;
    color: #fff !important;
    border: 0.5px solid rgba(255, 255, 255, 0.8);
    padding: 6px 20px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 50px;
    text-decoration: none !important;
    font-family: 'Nunito', sans-serif;
    transition: all 0.3s ease, transform 0.3s ease;
    white-space: nowrap;
    backdrop-filter: blur(4px);
}
.rm-circle-btn:hover,
.rm-circle-btn:focus {
    background-color: rgba(255, 255, 255, 0.35) !important;
    color: #fff !important;
    border-color: #fff;
    transform: scale(1.05);
}

@media only screen and (max-width: 600px) {
    .rm-circle-card {
        max-width: 160px;
    }
    .rm-circle-photo {
        width: 160px !important;
        height: 160px !important;
        max-width: 160px !important;
        max-height: 160px !important;
    }
}

<?php
$columnWidth = 0;

if ($wa['custom_313'] == "1" || !isset($wa['custom_313'])) {
    $columnWidth = 3;
} else if ($wa['custom_313'] == "2") {
    $columnWidth = 2;
} else if ($wa['custom_313'] == "0") {
    $columnWidth = 4;
}
?>
<?php if($wa['members_carousel_slider'] != '1' || ($wa['members_carousel_slider'] == '1' && !addonController::isAddonActive('post_carousel_slider'))){ ?>
@media only screen and (min-width: 991px) {
    .display-recent-members .recent-member:nth-child(<?php echo $columnWidth; ?>n+1) {
        clear: left;
    }
}
@media only screen and (max-width: 991px) {
    .display-recent-members .recent-member:nth-child(2n+1) {
        clear: left;
    }
}
<?php } ?>

