//PAGE HEADER<?php
$addOnWidget = '';
$gridViewAddOn = getAddOnInfo('grid_view_search_reults','769e3e86f08b2d05aaabb1e555221b87'); 
$mapViewAddOn = getAddOnInfo('google_map_search_results','ccda1a004e20781cca3712ec22a57434');

if (isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success'){
	$addOnWidget = $gridViewAddOn['widget'];
}
if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success'){
	$addOnWidget = $mapViewAddOn['widget'];
}
if ((isset($gridViewAddOn['status']) && $gridViewAddOn['status'] == 'success') || (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success')) { 
	echo widget($addOnWidget,"",$w['website_id'],$w);
} ?>
<hr>
[widget=create-post-btn]
<div class="grid-container">

//PAGE LOOP
<?php
$p = getMetaData("users_portfolio_groups",$p['group_id'],$p,$w);
$presults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        *
    FROM
        `users_portfolio`
    WHERE
        users_portfolio.group_id = '".$p['group_id']."'
        AND users_portfolio.data_id = '".$p['data_id']."'
    ORDER BY
        users_portfolio.order ASC,
        users_portfolio.photo_id ASC
    LIMIT
        1");
$pic = mysql_fetch_assoc($presults);
$p['group_picture'] = $pic['file'];
//$user = getUser($pic[user_id],$w);

if ($p['title'] == "") {
    $p['title'] = defaultPhotoTitle($p,$w);
} else {
    $nofollow = "";
}
$subscription = getSubscription($user['subscription_id'],$w);
$postFeaturedClass = ($p['sticky_post'] &&
    ($p['sticky_post_expiration_date'] == '0000-00-00' || $p['sticky_post_expiration_date'] >= date('Y-m-d')))
    ? ' featured-post featured-post-' . $dc['data_filename']
    : '';
?>
<div class="search_result row-fluid member-level-<?php echo $user['subscription_id'] . $postFeaturedClass; ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
	<meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
	<meta itemprop="url" content="/<?php echo $p['group_filename']; ?>">
	<meta itemprop="name" content="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>">
    <div class="grid_element">

        <div class="col-xs-8 col-sm-10 nolpad font-sm bmargin posted_meta_data"> 
            <span>
            %%%posted_on%%%
            <?php echo transformDate($p['date_updated'],"QB"); ?>
            </span>
            <?php 
            if ($subscription['searchable'] != 0) { ?> 
            <span class="posted-by inline-block">
                %%%by_label%%%
                <a class="bold notranslate" href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
                    <?php echo $user['full_name']; ?>
                </a>
            </span>
            <?php 
            } ?>
        </div>
        
        <div class="col-xs-4 col-sm-2 norpad favorite_button">
            <?php
            $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
            if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
            echo '<span class="postItem" data-userid="'.$p['user_id'].'" data-datatype="'.$p['data_type'].'" data-dataid="'.$p['data_id'].'" data-postid="'.$p['group_id'].'"></span>';
            echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
            } 
            addonController::showWidget('post_comments','88a1bbf8d5d259c20c2d7f6c7651f672'); ?>                
            <div class="clearfix"></div>
        </div>
        
        <div class="clearfix"></div>

        <?php
        if ($p['group_picture'] != "" || $p['default_picture'] != "") { ?>
            <div class="img_section col-sm-4 nopad sm-bmargin">
                
                <?php
                if ($p['post_availability'] != "" || $p['post_promo'] != "") { ?>
                <div class="btn-sm bg-primary line-height-xl no-radius-bottom">
                    <?php
                    if ($p['post_promo'] != "") { ?>
                    <span class="pull-left font-lg">
                        <?php echo str_replace(' ', '', $p['post_promo']);  ?>            
                    </span> 
                    <?php
                    } if ($p['post_availability'] != "") { ?> 
                    <span class="pull-right badge">
                        <?php echo $p['post_availability']; ?>
                    </span>
                    <?php
                    } ?>
                    <div class="clearfix"></div>
                </div>
                <?php
                } ?> 
                
                <div class="alert-secondary btn-block text-center">
                    <?php echo widget("Bootstrap Theme - Search Results - Display Image Slider","",$w['website_id'],$w);?>
                </div>
            
            </div>
        <?php
        } ?>

        <div class="mid_section xs-nopad <?php if ($p['group_picture'] != "" || $p['default_picture'] != "") { ?>col-sm-8<?php } else { ?>col-sm-12<?php } ?>">

            <a class="h3 bold bmargin center-block" title="<?php echo $p['group_name']; ?>" href="/<?php echo $p['group_filename']; ?>">
                <?php echo $p['group_name']; ?>
            </a>

            <div class="clearfix"></div>
            
            <?php
            if ($p['post_location'] != "" || $p['post_venue'] !="") { ?>
            <div class="post-location-snippet bmargin xs-nomargin">
                <i class="fa fa-map-marker text-danger"></i> 
                <b><?php echo $p['post_venue']; ?></b>
                <span class="inline-block font-sm">
                    <?php echo $p['post_location']; ?>
                </span>     
            </div>
            <?php
            } ?>                

            <?php
            if ($p['group_desc'] != "") { ?>
                <p class="bpad xs-nopad xs-nomargin">
                    <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $p['group_desc']),115)?>...
                    <a class="inline-block bold" title="<?php echo $p['group_name']; ?>" href="/<?php echo $p['group_filename']; ?>">
                        %%%view_more_label%%%
                    </a>
                </p>
            <?php
            } ?>

            <div class="clearfix"></div>

            <div class="hidden-xs row-fluid bpad">
                <a title="<?php echo $p['group_name']; ?>" class="btn btn-success col-sm-5 view-details rmargin " href="/<?php echo $p['group_filename']; ?>">
                    %%%results_view_details_link%%%
                </a>
                <div class="clearfix"></div>
            </div>

            <?php if ($p['group_category'] != "") { ?>
            <div class="nolpad hidden-sm hidden-xs font-sm text-left post-search-category inline-block">
                %%%category%%%
                <a class="bold" title="<?php echo $dc['data_name']; ?> - <?php echo $p['group_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($p['group_category']); ?>">
                    <?php echo $p['group_category']; ?>
                </a> 
            </div>
            <?php } addonController::showWidget('star_ratings_for_posts','609d748eaa051578e8ae2e3c8f848d9a');?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php
if ($p['lat'] != '' && $p['lon'] != ''){
    $_ENV['group'] = $p;
    echo widget("Bootstrap Theme - Google Pins Locations","",$w['website_id'],$w);
} ?>

//PAGE FOOTER
</div>
<?php
$lazyLoadSearchReults = getAddOnInfo("search_results_lazy_load","8e5c29cd2531efea4db02ebc567b8442");
if(isset($lazyLoadSearchReults["status"]) && $lazyLoadSearchReults["status"] === "success" && ($dc["enableLazyLoad"] == 1 || $dc["enableLazyLoad"] == "")) {
    echo widget($lazyLoadSearchReults["widget"],"",$w['website_id'],$w);
} ?>

<div class="map_container">
    <?php
    $mapViewAddOn = getAddOnInfo('google_map_search_results','8342a12b460d88d90a8c421953a44530');
    if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success') {
        echo widget($mapViewAddOn['widget'],"",$w['website_id'],$w);
    } ?>
</div>