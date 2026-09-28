//PAGE HEADER
<?php
global $subscription;
$schema_base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
$schema_collection_url = $schema_base_url . strtok($_SERVER['REQUEST_URI'], '?');
?>
<hr>
<?php if (!isset($_COOKIE['userid']) || empty($_COOKIE['userid'])) { ?>
    <div style="text-align: right;">
        <a target="_blank" href="/checkout/5" class="btn btn-primary" style="background: #fd9e25;border: 1px solid #fd9e25;">
            <i class="bi bi-plus-circle"></i> List Your Business
        </a>
    </div>
<?php } ?>
<div class="grid-container">


//PAGE LOOP
<div class="row-fluid member_results level_<?php echo $user_data['subscription_id'];?> search_result <?php echo ($user_data['verified'] == "1") ? 'verified_member_result' : ''; ?> clearfix" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
    <meta itemprop="name" content="<?php echo htmlspecialchars($user_data['full_name'], ENT_QUOTES, 'UTF-8'); ?>">
    <link itemprop="url" href="<?php echo $schema_collection_url; ?>">
    <meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
    <?php
    $filenameParts = explode('/', ltrim($user_data['filename'], '/'));
    $encodedParts = array_map('rawurlencode', $filenameParts);
    $encodedFilename = implode('/', $encodedParts);
    ?>
    <div itemprop="item" itemscope itemtype="https://schema.org/LocalBusiness" itemid="<?php echo $schema_base_url . '/' . $encodedFilename; ?>#entity">
        <meta itemprop="name" content="<?php echo htmlspecialchars($user_data['full_name'], ENT_QUOTES, 'UTF-8'); ?>">
        <link itemprop="url" href="<?php echo $schema_base_url . '/' . $encodedFilename; ?>">
        <link itemprop="image" href="<?php echo $user['image_main_file']; ?>">
        <meta itemprop="priceRange" content="$$">
        <meta itemprop="telephone" content="<?php echo !empty($user['phone_number']) ? preg_replace('/[^0-9+\-]/', '', $user['phone_number']) : 'N/A'; ?>">
        <?php if (!empty($user_data['search_description'])) { ?>
        <meta itemprop="description" content="<?php echo htmlspecialchars(strip_tags($user_data['search_description']), ENT_QUOTES, 'UTF-8'); ?>">
        <?php } elseif (!empty($user_data['about_me'])) { ?>
        <meta itemprop="description" content="<?php echo htmlspecialchars(strip_tags(limitWords($user_data['about_me'], 170)), ENT_QUOTES, 'UTF-8'); ?>">
        <?php } ?>
        <div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
            <meta itemprop="streetAddress" content="<?php echo htmlspecialchars(trim($user_data['address1']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
            <meta itemprop="addressLocality" content="<?php echo htmlspecialchars(trim($user_data['city']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
            <meta itemprop="addressRegion" content="<?php echo htmlspecialchars(trim($user_data['state_code']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
            <meta itemprop="postalCode" content="<?php echo htmlspecialchars(trim($user_data['zip_code']) ?: 'N/A', ENT_QUOTES, 'UTF-8'); ?>">
            <meta itemprop="addressCountry" content="<?php echo htmlspecialchars(trim($user_data['country_code']) ?: 'US', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <?php if (!empty($user_data['lat']) && !empty($user_data['lon'])) { ?>
        <div itemprop="geo" itemscope itemtype="https://schema.org/GeoCoordinates">
            <meta itemprop="latitude" content="<?php echo $user_data['lat']; ?>">
            <meta itemprop="longitude" content="<?php echo $user_data['lon']; ?>">
        </div>
        <?php } ?>
        <div itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
            <?php
            $ratingValue = (isset($_ENV['results']['rating']) && $_ENV['results']['rating'] > 0) ? $_ENV['results']['rating'] : 5;
            $reviewCount = (isset($_ENV['results']['total']) && $_ENV['results']['total'] > 0) ? intval($_ENV['results']['total']) : 1;
            ?>
            <meta itemprop="ratingValue" content="<?php echo $ratingValue; ?>">
            <meta itemprop="reviewCount" content="<?php echo $reviewCount; ?>">
            <meta itemprop="bestRating" content="5">
            <meta itemprop="worstRating" content="1">
        </div>
        <link itemprop="mainEntityOfPage" href="<?php echo $schema_base_url . '/' . $encodedFilename; ?>#page">
    </div>

    <div class="grid_element">
        <div class="img_section col-xs-2 nopad text-center">
            <a title="<?php echo $user_data['full_name'];?> - <?php echo ucwords($w['profession'])?>" href="/<?php echo $user_data['filename']?>">
                <?php if (!empty($w['lazy_load_images'])) { ?>
                <img class="search_result_image img-rounded center-block lazyloader" loading="auto" width="400" height="400" alt="<?php echo $user_data['full_name'];?>" data-src="<?php echo $user['image_main_file']?>">
                <?php } else { ?>
                <img class="search_result_image img-rounded center-block" loading="auto" width="400" height="400" alt="<?php echo $user_data['full_name'];?>" src="<?php echo $user['image_main_file']?>" />
                <?php } ?>
            </a>
        </div>

        <div class="mid_section col-xs-10 col-sm-7 norpad">
            <?php if ($w['top_category_in_results'] == "1" && $user_data['profession_name'] != '') { ?>
                <span class="category-pill-top">
                    <?php echo strtoupper($user_data['profession_name']); ?>
                </span>
            <?php } ?>
            
            <div class="like-container-wrap">
                <?php
                $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
                if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                    echo '<span class="postItem" data-userid="'.$user_data['user_id'].'" data-datatype="10" data-dataid="10" data-postid="0"></span>';
                    echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
                }
                ?>
            </div>

            <a class="center-block" title="<?php echo $user_data['full_name']?>" href="/<?php echo $user_data['filename']?>">
                <span class="h3 bold inline-block nomargin member-search-full-name notranslate">
                    <?php echo $user_data['full_name']?>
                </span>
                <?php if ($user_data['company'] != "" && $user_data['listing_type'] == "Individual") { ?>
                    <span class="hidden-xs inline-block member-search-company notranslate">
                        <?php echo $user_data['company']?>
                    </span>
                <?php } ?>
            </a>
            <div class="clearfix fpad-sm nobpad"></div>
            <?php if ($exact != "") { ?>
            <div class="hidden-sm hidden-md hidden-lg center-block small line-height-xl talign mobile-distance-within">
                <?php echo $exact ?>
            </div>
            <div class="clearfix fpad-sm nobpad"></div>
            <?php } if ($user_data['verified'] == "1") { ?>
            <div class="rmargin inline-block">
                [widget=Bootstrap Theme - Member Verified Badge]
            </div>
            <?php } if ($totalreviews > 0 && (in_array($reviewId['data_id'], $subscription['data_settings'])) == 1 && $subscription['hide_reviews_rating_options'] == 0) { ?>
            <div class="bold member-search-reviews inline-block rmargin align-middle small">
                <?php echo $rating['stars']?>
            </div>
            <?php } ?>
            <div class="clearfix fpad-sm nobpad"></div>
            
            <?php if ($w['member_position_in_results'] == "1" && $user_data['position'] != '') { ?>
            <p class="small nomargin member_position_in_results">
                <b class="line-height-xl">%%%search_results_position_label%%%</b> <?php echo $user_data['position']?>
            </p>
            <?php } ?>          
            
            <?php if ($w['sub_category_in_results'] == "1"){
                $memberSubCategories = getMemberSubCategory($user_data['user_id'],"all","settings",intval($w['profile_services_display_limit']),"text");
                if ($memberSubCategories != "") { ?>
                    <p class="small nomargin sub_category_in_results">
                        <b class="line-height-xl">%%Service%%:</b> <?php echo $memberSubCategories;?>
                    </p>
            <?php } } ?>

<?php if ($user_data['search_description'] != "") { ?>                
    <p class="small nomargin member-search-description">
        <?php
            $user_data['search_description'] = bdString::prepareSpecialCharacter($user_data['search_description']);
            // Strip tags and limit to 25 words with ellipsis
            echo limitWords(preg_replace('#<[^>]+>#', ' ', $user_data['search_description']), 150) . '...';
        ?>
    </p>
<?php } else if ($user_data['about_me'] !="" && $subscription['show_about_tab'] != 0 ) { ?>
    <p class="small nomargin member-search-description">
        <?php
            $user_data['about_me'] = bdString::prepareSpecialCharacter($user_data['about_me']);
            // Strip tags and limit to 25 words with ellipsis
            echo limitWords(preg_replace('#<[^>]+>#', ' ', $user_data['about_me']), 150) . '...';
        ?>
    </p>
<?php } ?>

            <?php if (($user_data['city'] != '' || $user_data['state_ln'] !='' || $user_data['zip_code']!="" || $user_data['country_ln'] != '') && $subscription['profile_layout'] == "1") { ?>
                <div class="clearfix fpad-sm nobpad"></div>
                <span class="member-search-location rmargin">
                <i class="fa fa-map-marker text-danger"></i>
                    <small>
                        <?php if ($user_data['city'] != '') { echo $user_data['city']; }
                        if ($user_data['state_ln'] != '') { echo ($user_data['city'] != '' ? ', ' : '') . $user_data['state_ln']; }
                        if ($user_data['zip_code'] != '') { echo ', ' . $user_data['zip_code']; }
                        if ($user_data['country_ln'] != "") { echo ', ' . $user_data['country_ln']; } ?>
                    </small></span>
            <?php } ?>
        </div>

        <div class="info_section hidden-xs col-sm-3 norpad">
            <div class="module nomargin fpad text-center">
                <?php if ($user['nationwide']==1 && $label['serves_this_area'] !="") { ?>
                    <div class="alert alert-success fpad-sm bmargin">
                        <i class="fa fa-map-marker"></i> %%%serves_this_area%%%
                    </div>
                <?php } else if ($exact != "" && $distance != "") {
                    if ($exact != "") { echo $exact; } ?>
                    <div class="clearfix bpad"></div>
                <?php }
                $badgesAddOn = getAddOnInfo('member_listing_badges','4bfd736fb96d71876957b942d0293b1a');
                if ($subscription['category_badge'] != "" && isset($badgesAddOn['status']) && $badgesAddOn['status'] == 'success') {
                    echo widget($badgesAddOn['widget'],"",$w['website_id'],$w);
                }
                if (($user_data['service_area'] > 0 || $user_data['area_id_big_location'] > 0 || ($user_data['service_distance'] != "" && $user_data['service_distance'] <=  $w['default_radius'])) && $user['nationwide'] != 1 && $label['serves_this_area'] !="") { ?>
                    <div class="alert alert-success fpad-sm bmargin">
                        <i class="fa fa-map-marker"></i> %%%serves_this_area%%%
                    </div>
                <?php } ?>
                
                <a title="View Listing" class="btn btn-sm btn-primary btn-block bold search_view_listing_button" href="/<?php echo $user_data['filename']?>">%%%view_listing_label%%%</a>
                
                <?php if ($subscription['receive_messages'] != 1){ ?>
                    <div class="clearfix fpad-sm nobpad"></div>
                    <a title="Contact Now" class="btn btn-sm btn-secondary btn-block bold search_contact_now_button" href="/<?php echo $user_data['filename']?>/<?php echo $w['default_connect_url'];?>">%%%contact_now_label%%%</a>
                <?php } ?>

<?php if ($user['phone_number'] != "" && $subscription['show_phone'] == 1) { 
                    $cleanPhone = preg_replace('/[^0-9+]/', '', $user['phone_number']); 
                ?>
                    <div class="clearfix fpad-sm nobpad"></div>
                    <a href="javascript:void(0);"
                       style="cursor: pointer;"
                       class="btn btn-sm bold text-center btn-block nomargin direct-call-button click-reveal-phone" 
                       data-state="hidden"
                       data-phone="<?php echo $user['phone_number']; ?>" 
                       data-tel="tel:<?php echo $cleanPhone; ?>">
                        <i class="fa fa-phone"></i> See Phone Number
                    </a>
                <?php } ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php echo widget("Bootstrap Theme - Google Pins Locations","",$w['website_id'],$w); ?>

<style>
/* =========================
   CATEGORY PILL
========================= */
.category-pill-top {
    display: inline-block;
    background: #e8f4fd;
    color: #2a7ab5;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.05em;
    margin-bottom: 5px;
    text-transform: uppercase;
}

/* =========================
   IMAGE SECTION
========================= */
.img_section {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* =========================
   MID SECTION BASE
========================= */
.mid_section {
    position: relative;
    padding-right: 110px; /* space for LIKE button */
}
    
/* FORCE LEFT ALIGN EVERYTHING */
.mid_section,
.mid_section * {
    text-align: left !important;
}
    
.member_results .grid_element .mid_section {
        padding-left: 15px;
    }

/* =========================
   CARD INNER SPACING
========================= */
.grid_element {
    padding-left: 18px !important;
    padding-right: 18px !important;
}

/* =========================
   LIKE BUTTON WRAPPER
========================= */
.like-container-wrap {
    position: absolute;
    top: 0;
    right: 0;
    width: auto !important;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    z-index: 6;
}
.search_result_image {
    width: 100% !important;
    max-width: 180px !important;
}

/* =========================
   LIKE BUTTON (AUTO WIDTH) - REMOVED BORDER-RADIUS
========================= */
.like-container-wrap button.favorite {
    background: #f9f3e8 !important;
    color: #666666 !important;
    border: none !important;
    width: auto !important;
    min-width: unset !important;
    padding: 6px 10px !important;
    font-weight: 700 !important;
    font-size: 10px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    white-space: nowrap;
    box-shadow: none !important;
}

.like-container-wrap button.favorite #bookmark-content {
    display: none !important;
}

.like-container-wrap button.favorite::after {
    content: "SAVE";
    font-size: 11px;
}

.like-container-wrap button.favorite.favoriteActive,
.like-container-wrap button.favorite.active {
    background: #fff4ed !important;
    color: rgb(245, 166, 35) !important;
}

.like-container-wrap button.favorite.favoriteActive::after,
.like-container-wrap button.favorite.active::after {
    content: "SAVED";
}

.like-container-wrap button.favorite i {
    margin-right: 5px !important;
    color: inherit !important;
}

/* =========================
   BUTTON COLORS
========================= */

/* VIEW LISTING */
.search_view_listing_button {
    background: rgb(62, 126, 163) !important;
    border: none !important;
    color: #fff !important;
}
.search_view_listing_button:hover {
    background: rgb(52, 106, 143) !important;
}

/* CONTACT NOW */
.search_contact_now_button {
    background: #fd9e25 !important;
    border: none !important;
    color: #fff !important;
}
.search_contact_now_button:hover {
    background: #e58d1f !important;
}

/* DIRECT CALL BUTTON */
.direct-call-button {
    background-color: #e8f4fd !important;
    color: #2a7ab5 !important;
    border: none !important;
}

.direct-call-button:hover {
    background-color: #d1e9fb !important;
    color: #2a7ab5 !important;
}

/* =========================
   TITLE SPACING
========================= */
.mid_section .member-search-full-name {
    margin-top: 8px;
}

/* =========================
   PROFILE IMAGE OUTLINE
========================= */
.search_result_image,
.lazyloader {
    border: 2px solid #e6e6e6 !important;
    border-radius: 8px !important;
    padding: 2px;
    background: #fff;
}

/* =========================
   SMALLER MEMBERSHIP BADGE (FIXED)
========================= */

/* Targets text-based badges/labels */
.info_section .module [class*="badge"],
.info_section .module .badge,
.info_section .module .label,
.grid-container .grid_element .info_section .module .badge {
    font-size: 9px !important;
    padding: 2px 6px !important;
    border-radius: 5px !important;
    line-height: 1.2 !important;
    display: inline-block !important;
}

/* Targets image-based badges (The most common fix for Grid View) */
.info_section .module img,
.grid-container .grid_element .info_section img {
    max-width: 70px !important; /* Reduced from 85px to ensure fit in grid */
    height: auto !important;
    margin: 0 auto 5px auto !important;
    display: block !important;
}

/* Specific fix for the Brilliant Directories Badge Widget Container */
.info_section .module div[class*="badge"],
.info_section .module .member-badge-container {
    max-width: 100% !important;
    text-align: center !important;
}
    
/* ═══════════════════════════════
   GRID EQUAL HEIGHT LAYOUT
═══════════════════════════════ */
/* Forces the container to use flexbox */
.grid-container.row {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: stretch !important;
}

/* Forces the search result column to stretch */
.grid-container.row > .search_result {
    display: flex !important;
    flex-direction: column !important;
}

/* Forces the card itself to stretch and act as a flex container */
.grid-container.row > .search_result .grid_element {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    height: auto !important; /* Overrides fixed heights */
}

/* Forces the content body to take up all available space, pushing footer down */
.grid-container.row > .search_result .post-card-body {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
}

/* Ensures footer stays at the very bottom */
.grid-container.row > .search_result .post-card-footer {
    margin-top: auto !important;
}
    
/* =========================
   LOAD MORE BUTTON (EXACT)
========================= */
#btnToLoadMorePost.clickToLoadMoreBtn {
    background-color: transparent !important;
    border: transparent !important;
    display: inline-block !important;
    color: #1f5f8c !important;
    width: auto !important;
    min-width: 0px;
}

#btnToLoadMorePost.clickToLoadMoreBtn:hover {
    background-color: transparent !important;
    color: #2a7ab5 !important;
    box-shadow: none !important;
}

/* =========================
   MOBILE LIKE BUTTON (ICON ONLY) - REMOVED BORDER-RADIUS
========================= */
@media (max-width: 767px) {
    /* Remove the text labels */
    .like-container-wrap button.favorite::after,
    .like-container-wrap button.favorite.favoriteActive::after,
    .like-container-wrap button.favorite.active::after {
        display: none !important;
        content: "" !important;
    }

    /* Center the icon and remove extra padding */
    .like-container-wrap button.favorite {
        padding: 6px !important;
        min-width: 30px !important;
        justify-content: center !important;
    }

    /* Remove the margin on the icon since there's no text */
    .like-container-wrap button.favorite i {
        margin-right: 0 !important;
        font-size: 14px !important;
    }
    
    /* Adjust mid_section padding so title doesn't hit the button */
    .mid_section {
        padding-right: 45px !important;
    }

    .like-container-wrap button.favorite::after {
        display: none !important;
        content: "" !important;
    }
    .like-container-wrap button.favorite {
        padding: 6px !important;
        min-width: 30px !important;
    }
    .mid_section {
        padding-right: 45px !important;
    }
}
    
/* ═══════════════════════════════
   MOBILE STACKED GRID VIEW
═══════════════════════════════ */
@media (max-width: 767px) {
    /* 1. Stack the containers */
    .grid_element {
        display: block !important;
        text-align: center !important;
        padding: 15px !important;
    }

    /* 2. Photo Section - Centered and Full Width */
    .img_section.col-xs-2 {
        width: 100% !important;
        float: none !important;
        margin-bottom: 15px !important;
    }

    /* 3. Text Section - Centered and Full Width */
    .mid_section.col-xs-10 {
        width: 100% !important;
        float: none !important;
        padding: 0 !important;
        text-align: center !important;
    }

    /* Force all child text to center */
    .mid_section * {
        text-align: center !important;
    }
    /* Force Title and Company to stack and center */
    .mid_section .member-search-full-name,
    .mid_section .member-search-company {
        display: block !important;
        text-align: center !important;
        width: 100%;
        margin: 2px auto !important;
    }

    .mid_section .member-search-company {
        font-size: 15px;
        color: #666;
    }
    /* 4. HIDE BADGE & BUTTONS ON MOBILE */
    .info_section, 
    .member-verified-badge, 
    [widget*="Member Verified Badge"] {
        display: none !important;
    }

    /* 5. ADJUST LIKE BUTTON (Higher and to the Right) */
    .like-container-wrap {
        top: 0 !important; /* Move higher up into the card padding area */
        right: 5px !important;
    }

    /* 6. Centering the Image */
    .search_result_image {
        margin: 0 auto !important;
        max-width: 150px !important; 
    }
}
</style>

//PAGE FOOTER
</div>
<?php
$lazyLoadSearchReults = getAddOnInfo('search_results_lazy_load', '8e5c29cd2531efea4db02ebc567b8442');
if (isset($lazyLoadSearchReults["status"]) && $lazyLoadSearchReults["status"] === "success" && ($dc["enableLazyLoad"] == 1 || $dc["enableLazyLoad"] == "")) {
    echo widget($lazyLoadSearchReults["widget"], "", $w[website_id], $w);
} ?>

    <div class="map_container" style="display:none;">
        <?php
        $mapViewAddOn = getAddOnInfo('google_map_search_results','8342a12b460d88d90a8c421953a44530');
        if (isset($mapViewAddOn['status']) && $mapViewAddOn['status'] == 'success') {
            echo widget($mapViewAddOn['widget'],"",$w['website_id'],$w);
        } ?>
    </div>
<?php
$clickPhoneAddOnFooter = getAddOnInfo("click_to_phone", "19dd6c19c0943cc078aa0873b330ada2");
if (isset($clickPhoneAddOnFooter['status']) && $clickPhoneAddOnFooter['status'] === 'success') {
    echo widget($clickPhoneAddOnFooter['widget'], "", $w[website_id], $w);
} else {
    $statisticsAddOnFooter = getAddOnInfo("user_statistics_addon", "ebb3e8d8cd4b30cb80a24d75f660987c");
    if (isset($statisticsAddOnFooter['status']) && $statisticsAddOnFooter['status'] === 'success') {
        echo widget($statisticsAddOnFooter['widget'], "", $w[website_id], $w);

    }
}
echo widget("Bootstrap Theme - Listing Search Results Statistics", '', $w[website_id], $w);

?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        var button = e.target.closest('.click-reveal-phone');
        if (!button) return;

        var state = button.getAttribute('data-state');

        // STEP 1: First click runs reveal only
        if (state === 'hidden') {
            e.preventDefault(); 
            e.stopPropagation(); 
            
            var phoneNumber = button.getAttribute('data-phone');
            var telLink = button.getAttribute('data-tel');
            
            // Assign the actual clickable dial protocol string
            button.setAttribute('href', telLink);
            
            // Build text payload using pointer-events: none inside child components to isolate target listeners
            button.innerHTML = '<i class="fa fa-phone" style="pointer-events: none;"></i> <span style="pointer-events: none;">' + phoneNumber + '</span>';
            
            // Apply defensive design locking rules matching original stylesheet tokens
            button.style.setProperty('color', '#2a7ab5', 'important');
            button.style.setProperty('background-color', '#e8f4fd', 'important');
            button.style.setProperty('text-decoration', 'none', 'important');
            
            // Shift processing state forward
            button.setAttribute('data-state', 'revealed');
        }
        // STEP 2: Second click ignores this script block entirely, launching the standard cellular device dialer handler natively.
    });
});
</script>