//PAGE HEADER
<h2 class="xs-text-center notranslate"><?php echo $user[full_name]?> <?php echo plural($dc[data_name])?></h2><hr>

//PAGE LOOP
<?php if($subscription['searchable'] == "1"){

$reviewIdQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
data_id
FROM
`data_categories`
WHERE
data_type = '13'
LIMIT
1");
$reviewId = mysql_fetch_assoc($reviewIdQuery);
?>
<div class="row-fluid member_results level_<?php echo $user_data['subscription_id'];?> search_result clearfix">
    <div class="grid_element">
        <div class="img_section col-xs-2 nopad">
            <a title="<?php echo $user_data['full_name'];?> - <?php echo ucwords($w['profession'])?>" href="/<?php echo $user_data['filename']?>">
                <img class="search_result_image img-rounded center-block" alt="<?php echo $user_data['full_name'];?>" src="<?php echo $user['image_main_file']?>" />
            </a>
        </div>
        <div class="mid_section col-xs-10 col-sm-7 norpad">
            <?php
            $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
            if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                echo '<span class="postItem" data-userid="'.$user_data['user_id'].'" data-datatype="10" data-dataid="10" data-postid="0"></span>';
                echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
            }

            ?>
            <a class="center-block" title="<?php echo $user_data['full_name']?>" href="/<?php echo $user_data['filename']?>">
        <span class="h3 bold inline-block rmargin member-search-full-name notranslate">
            <?php echo $user_data['full_name']?>
        </span>
                <?php if ($user_data['company'] != "" && $user_data['listing_type'] == "Individual") { ?>
                    <span class="hidden-xs inline-block bmargin member-search-company notranslate">
                <?php echo $user_data['company']?>
            </span>
                <?php } ?>
            </a>
            <?php if ($totalreviews > 0 && (in_array($reviewId['data_id'], $subscription['data_settings'])) == 1 && $subscription['hide_reviews_rating_options'] == 0) { ?>
                <div class="bmargin bold member-search-reviews inline-block rmargin">
                    <?php echo $rating['stars']?>
                </div>
            <?php }
            if ($user_data['verified'] == "1") { ?>
                <div title="%%%verified_badge_content%%%" class="btn-xs alert-success bmargin bold nopad nolpad rmargin inline-block member-search-verified">
            <span class="btn-xs novpad bg-success pull-left" style="border-radius: 3px 0 0 3px;">
                <i class="fa fa-check"></i>
            </span>
                    <span class="btn-xs novpad">
                %%%search_results_verified_member%%%
            </span>
                </div>
            <?php } if ($exact != "") { ?>
                <div class="hidden-sm hidden-md hidden-lg inline-block">
                    <?php echo $exact ?>
                </div>
            <?php } ?>
            <?php
            if ($w['top_category_in_results'] == "1" && $user_data['profession_name'] != '') { ?>
                <p class="small">
                    <b>%%%search_results_category_label%%%</b> <?php echo $user_data['profession_name']?>
                </p>
            <?php } ?>
            <?php if ($w['sub_category_in_results'] == "1"){
                $memberSubCategories = getMemberSubCategory($user_data['user_id'],"all","settings",intval($w['profile_services_display_limit']),"text");
                if ($memberSubCategories != "") { ?>
                    <p class="small">
                        <b><?php echo plural($Label[service]);?>:</b> <?php echo $memberSubCategories;?>
                    </p>
                <?php } }
            if ($user_data['search_description'] != "") { ?>
                <p class="small member-search-description">
                    <?php echo (preg_replace('#<[^>]+>#', ' ', $user_data['search_description']))?>
                </p>
            <?php }
            else if ($user_data['about_me'] !="" && $subscription['show_about_tab'] != 0 ) { ?>
                <p class="small member-search-description">
                    <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $user_data['about_me']),155)?>
                </p>
            <?php }
            if (($user_data['city'] != '' || $user_data['state_ln'] !='' || $user_data['zip_code']!="" || $user_data['country_ln'] != '') && $subscription['profile_layout'] == "1") { ?>
            <div class="clearfix"></div>
            <span class="small member-search-location rmargin rpad">
        <i class="fa fa-map-marker text-danger"></i>
        <?php if ($user_data['city'] != '') {
            echo $user_data['city'];
        }

        if ($user_data['state_ln'] != '') {

            if ($user_data['city'] != '') {
                echo ', ';
            }
            echo $user_data['state_ln'];
        }

        if ($user_data['zip_code'] != '') {

            if ($user_data['state_ln'] != '' || $user_data['city'] != '') {
                echo ', ';
            }
            echo '<span class="inline-block">' . $user_data['zip_code'] . '</span>';

        }

        if  ($user_data['country_ln'] != "") {

            if ($user_data['state_ln'] != '' || $user_data['city'] != '' || $user_data['zip_code'] != '') {
                echo ', ';
            }
            echo '<span class=inline-block>' . $user_data['country_ln'] . '</span>';
        }
        echo '</span>';
        }

        if ($user['phone_number'] != "" && $subscription['show_phone'] == 1) {
            $clickPhoneAddOnLoop = getAddOnInfo("click_to_phone","1c75909cfae116e22fd25f087d8d4f7b");
            $statisticsAddOn = getAddOnInfo("user_statistics_addon","79c260ec6118524a0dea65acd7759ebe");
            if(isset($clickPhoneAddOnLoop['status']) && $clickPhoneAddOnLoop['status'] === 'success'){
                echo widget($clickPhoneAddOnLoop['widget'],"",$w['website_id'],$w);
            } else if (isset($statisticsAddOn['status']) && $statisticsAddOn['status'] === 'success') {
                echo widget($statisticsAddOn['widget'],"",$w['website_id'],$w);
            } else { ?>
                <span class="inline-block small xs-nomargin xs-nopad member-search-phone">
                <i class="fa fa-phone fa-fw"></i><?php echo $user['phone_number']; ?>
            </span>
            <?php }
        }
        ?>
        </div>
        <div class="info_section hidden-xs col-sm-3 norpad">
            <div class="module nomargin text-center">
                <?php if ($user['nationwide']==1) { ?>
                    <div class="alert alert-success fpad bmargin">
                        <i class="fa fa-map-marker"></i>
                        %%%serves_this_area%%%
                    </div>
                <?php } else if ($exact != "" && $distance != "") {
                    if ($exact != "") { echo $exact; } ?>
                    <div class="clearfix bpad"></div>
                <?php }
                $badgesAddOn = getAddOnInfo('member_listing_badges','4bfd736fb96d71876957b942d0293b1a');
                if ($subscription['category_badge'] != "" && isset($badgesAddOn['status']) && $badgesAddOn['status'] == 'success') {
                    echo widget($badgesAddOn['widget'],"",$w['website_id'],$w);
                }

                if (($user_data['service_area'] > 0 || $user_data['area_id_big_location'] > 0 || ($user_data['service_distance'] != "" && $user_data['service_distance'] <=  $w['default_radius'])) && $user['nationwide'] != 1) { ?>
                    <div class="alert alert-success fpad bmargin">
                        <i class="fa fa-map-marker"></i>
                        %%%serves_this_area%%%
                    </div>
                <?php } ?>

                <a class="btn btn-success btn-block" href="/<?php echo $user_data['filename']?>">%%%view_listing_label%%%</a>
                <?php if ($subscription['receive_messages'] != 1){ ?>
                    <div class="clearfix bpad"></div>
                    <a class="btn btn-primary btn-block" href="/<?php echo $user_data['filename']?>/connect">%%%contact_now_label%%%</a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php echo widget("Bootstrap Theme - Google Pins Locations","",$w['website_id'],$w);
} ?>

//PAGE FOOTER
<?php
$clickPhoneAddOnFooter = getAddOnInfo("click_to_phone", "19dd6c19c0943cc078aa0873b330ada2");
if (isset($clickPhoneAddOnFooter['status']) && $clickPhoneAddOnFooter['status'] === 'success') {
    echo widget($clickPhoneAddOnFooter['widget'], "", $w['website_id'], $w);
} else {
    $statisticsAddOnFooter = getAddOnInfo("user_statistics_addon", "ebb3e8d8cd4b30cb80a24d75f660987c");
    if (isset($statisticsAddOnFooter['status']) && $statisticsAddOnFooter['status'] === 'success') {
        echo widget($statisticsAddOnFooter['widget'], "", $w['website_id'], $w);

    }
}
echo widget("Bootstrap Theme - Listing Search Results Statistics", '', $w['website_id'], $w);

?>