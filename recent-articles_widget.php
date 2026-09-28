<?php
/*
 * Widget Name:  Bootstrap Theme - Display - Recent Articles
 * Short Description: Enables the Streaming of Particular Membership Feature Posts.
 */
$featureUrl = $wa['custom_189']; // Use to Set the name of the Membership Feature this Streaming Widget will use. Can be controlled in the Design Settings.
$maxItems = $wa['custom_166']; // Maximum amount of streaming items shown. Default is 4. Can be controlled in the Design Settings.
$hidePostsWithoutPhotos = $wa['custom_178']; // This will show or hide posts if they do not have photos. Default is true. Can be controlled in the Design Settings.
if ($wa['post_memberarticles_hideEmptyStream'] == "1" || $wa['post_memberarticles_hideEmptyStream'] == "") {
    $hideEmptyStream = true; // This hides the feature if there are no posts to show on it. Default is true. Can be controlled in the Design Settings.
} else {
    $hideEmptyStream = false;
}
if ($wa['post_memberarticles_onlyActiveMembers'] == "1" || $wa['post_memberarticles_onlyActiveMembers'] == ""){
    $onlyActiveMembers = true; // Show or Hide results from Active Members Only. Default is true.
} else {
    $onlyActiveMembers = false;
}
if ($wa['post_memberarticles_searchablePostsOnly'] == "0" || $wa['post_memberarticles_searchablePostsOnly'] == ""){
    $searchablePostsOnly = true; // Show posts even if they are not searchable. Useful for Membership Levels that do not want to show in the search results but want to show their posts in the streaming widgets, similar to how an admin account would work.
} else {
    $searchablePostsOnly = false;
}
$sortingOrder = 'dp.post_id DESC'; // Sorting Order in which results will be shown. Values supported are DESC (Descending) & ASC (Ascending). Default is DESC.
if(isset($wa['custom_287'])){
    $sortingOrder = ($wa['custom_287'] != 'RAND()')?"dp.post_id ".$wa['custom_287']:$wa['custom_287'];
}
$onlySubscriptionIds = $wa['post_memberarticles_onlySubscriptionIds']; // Comma separated list of the subscription_ids that members are listed in to show posts of
if($wa['streaming_view_more_class'] != ''){
    $viewAllClass = $wa['streaming_view_more_class'];
} else {
    $viewAllClass = 'btn-info';
}
if($wa['streaming_read_more_class'] != ''){
    $readMoreClass = $wa['streaming_read_more_class'];
} else {
    $readMoreClass = 'btn-success';
}
if (empty($featureUrl)) {
    $featureUrl = 'articles';
}

if (empty($maxItems)) {
    $maxItems = 4;
}

if (is_numeric($featureUrl)) {
    $where = "data_id = ".$featureUrl;

} else {
    $where = "data_filename LIKE '".$featureUrl."'";
}

// Query that grabs information from the Membership Feature selected
$membershipFeaturesSQL = mysql($w[database], "SELECT 
        data_id, data_filename, data_name
    FROM 
        data_categories 
    WHERE 
        ".$where." 
    AND 
        data_active = '1' 
    LIMIT 
        1");
$features = mysql_fetch_assoc($membershipFeaturesSQL);

$sqlSelectParameters = array(
    "dp.post_id",
    "dp.post_title",
    "dp.post_content",
    "dp.post_image",
    "dp.post_filename",
    "dp.post_updated",
    "dp.user_id",
    "dp.post_category"
);
if ($wa['custom_287'] == "" || $wa['custom_287'] == "DESC"){
    $tempTableOrder = $sortingOrder.", post_live_date DESC";
} else if ($wa['custom_287'] == "ASC"){
    $tempTableOrder = $sortingOrder.", post_live_date ASC";
} else {
    $tempTableOrder = "RAND()";
}
if ($wa['post_memberarticles_onePostperMember'] == "1") {
    mysql(brilliantDirectories::getDatabaseConfiguration('database'), "CREATE TEMPORARY TABLE temp_data_posts SELECT * FROM data_posts as dp WHERE dp.data_id = '".$features['data_id']."' ORDER BY ".$tempTableOrder."");
    $sqlTablesParameters = array(
        "`temp_data_posts` AS dp",
        "`users_data` AS ud",
        "`subscription_types` AS st"
    );
} else {
    $sqlTablesParameters = array(
        "`data_posts` AS dp",
        "`users_data` AS ud",
        "`subscription_types` AS st"
    );
}
$sqlWhereParameters = array(
    "dp.user_id = ud.user_id",
    "ud.subscription_id = st.subscription_id",
    "dp.data_id = '".$features['data_id']."'",
    "dp.post_title != ''",
    "dp.post_status = '1'",
    "(dp.post_expire_date >= '".date('Ymd',websiteSettingsController::getCurrentTimeStamp())."000000' OR dp.post_expire_date = '')"
);
if (!empty($onlySubscriptionIds) && $onlySubscriptionIds != " ") {
    array_push($sqlWhereParameters,"ud.subscription_id IN (".$onlySubscriptionIds.")");
};
$sqlGroupByParameters = array();
if ($wa['post_memberarticles_onePostperMember'] == "1") {
    array_push($sqlGroupByParameters,"user_id");
}
$sqlHavingParameters = array();
$sqlOrderByParameters = array(
    $sortingOrder
);
$sqlLimitParameters = array(
    $maxItems
);

if ($onlyActiveMembers == true) {
    $sqlWhereParameters[] = "ud.active = '2'";
}
if ($hidePostsWithoutPhotos == true) {
    $sqlWhereParameters[] = "dp.post_image != ''";
}
if ($searchablePostsOnly == true) {
    $sqlWhereParameters[] = "st.searchable = '1'";
}
$sqlWhereParameters[] = "st.data_settings LIKE '%".$features['data_id']."%'";

/* -------------------------- Code That Constructs the SQL statement ------------------------------------- */
if (count($sqlSelectParameters) > 0) {
    $sql .= "SELECT ";
    $sql .= implode(", ",$sqlSelectParameters);
}
if (count($sqlTablesParameters) > 0) {
    $sql .= " FROM ";
    $sql .= implode(", ",$sqlTablesParameters);
}
if (count($sqlWhereParameters) > 0) {
    $sql .= " WHERE ";
    $sql .= implode(" AND ",$sqlWhereParameters);
}
if (count($sqlGroupByParameters) > 0) {
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

$featureResults = mysql($w[database], $sql);
$featureNum = mysql_num_rows($featureResults);
$showFeature = true;

if ($wa['custom_314'] == "2"){
    $columnWidth = "col-md-6";
} else if ($wa['custom_314'] == "1") {
    $columnWidth = "col-md-4";
} else {
    $columnWidth = "col-md-3";
}

if ($hideEmptyStream == true) {

    if ($featureNum > 0) {
        $showFeature = true;

    } else {
        $showFeature = false;
    }
}
if ($wa['post_memberarticles_onePostperMember'] == "1") {
mysql(brilliantDirectories::getDatabaseConfiguration('database'),"DROP TABLE temp_data_posts");
}
$titleStyleColor = "";
if($wa['recent_mArticles_tColor'] != ""){
    $titleStyleColor = 'style="color: '.$wa['recent_mArticles_tColor'].'"';
}
if ($showFeature == true) { ?>
    <div class="clearfix"></div>
    <div class="content-container">
		<div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($wa['marticles_show_view_all'] != '0'){ ?>
                        <a href="/<?php echo $features['data_filename'];?>" class="view-all-btn-desktop hidden-xs btn <?php echo $viewAllClass ?>">
                            %%%view_all_label%%%
                        </a>
                    <?php } ?>
                    <h2 class="nomargin sm-text-center streaming-title" <?php echo $titleStyleColor ?>>
                        <?php echo $wa['custom_120'];?>                        
                    </h2>
                    <hr>
                </div>
				<div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 slickMemberArticles">
                        <?php
                        while ($post = mysql_fetch_array($featureResults)) {                        
                            foreach ($post as $key => $value) {
                                $post[$key] = stripslashes($value);
                            }
                            $post['post_title'] = stripslashes($post['post_title']);

                            if (strlen($post['post_title']) > 40 ) {
                                $postTitle = limitName($post['post_title'],40);

                            } else {
                                $postTitle = $post['post_title'];
                            }
                            if (strlen(strip_tags($post['post_content'])) > 60 ) {
                                $postContent = limitName(strip_tags($post['post_content']),60);

                            } else {
                                $postContent = strip_tags($post['post_content']);
                            }					
                            if ($post['post_image'] != "") {
                                $postImageFile = explode("/",str_replace("'","",$post['post_image']));
                                $postImageFileName = $postImageFile[3];                        
                                $thumbnailImage = "/uploads/news-pictures/" . $postImageFileName;
                                if($wa['marticles_img_quality']){ $thumbnailImage = str_replace('news-pictures','news-pictures-thumbnails',$thumbnailImage);}
                                $postimage = "background-image:url(' ".$thumbnailImage." ')";
                            } else {
                                if($w['default_post_image'] != ''){
                                    $thumbnailImage = $w['default_post_image'];
                                    $postimage = "background-image:url(' ".$thumbnailImage." ')";
                                } else {
                                    $thumbnailImage = "/images/image-placeholder.jpg";
                                    $postimage = "background-image:url(' ".$thumbnailImage." ')";
                                }
                            } ?>

                            <div class="col-sm-6 <?php echo $columnWidth; ?> text-center bmargin">
                            <?php if (!empty($w['lazy_load_images'])) { ?>
                                <div class="pic lazyloader" data-src="<?php echo $thumbnailImage; ?>">
                            <?php } else { ?>
                                <div class="pic" style="<?php echo $postimage; ?>">
                            <?php } ?>
                                    <span class="pic-caption bottom-to-top" onclick>
                                        <?php if (!empty($post['post_category'])) { ?>
                                            <p class="pic-category"><?php echo htmlspecialchars($post['post_category']); ?></p>
                                        <?php } ?>
                                        <h3 class="pic-title"><?php echo $postTitle;?></h3>
                                        <p><?php echo $postContent;?></p>
                                        <a href="/<?php echo $post['post_filename'];?>" class="btn <?php echo $readMoreClass?> fpad-lg vpad view-more">
                                            %%%results_read_more_link%%%
                                        </a>
                                    </span>
									<a aria-label="<?php echo strip_tags($Label['results_read_more_link']);?>" href="/<?php echo $post['post_filename']; ?>" class="homepage-link-element <?php if ($wa['streaming_info_display'] == "on_hover") { ?>hidden-xs<?php } ?>"></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php if ($wa['marticles_show_view_all'] != '0'){ ?>
                    <div class="col-md-6">
                        <a href="<?php echo $features['data_filename'];?>" class="btn btn-lg <?php echo $viewAllClass ?> btn-block visible-xs-block">%%%view_all_label%%%</a>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <?php
    global $featureSliderEnabled, $featureMaxPerRow, $featureSliderClass, $postsCount;
    $postsCount = $featureNum;
    $featureSliderEnabled = $wa['marticles_posts_slider'];
    $featureMaxPerRow = $wa['custom_314'];
    $featureSliderClass = '.slickMemberArticles';
    addonController::showWidget('post_carousel_slider','1a19675a36d28232077972bbdb6bb7fe');

} ?>

    
.content-container .pic {
    position: relative;
    min-height: 200px !important;
    overflow: hidden; /* keeps the zoomed image from spilling out */
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.content-container .pic::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: inherit; /* take the same background image */
    background-size: cover;
    background-position: center;
    transition: transform 1s ease; /* slow & smooth zoom */
    z-index: 1;
}

.content-container .pic:hover::before {
    transform: scale(1.2); /* zoom in smoothly */
}

.pic-caption {
    position: relative;
    z-index: 2; /* above the image */
    background: linear-gradient(
        rgba(62, 126, 163, 1) 0%,   
        rgba(62, 126, 163, 0.6) 50%,  
        rgba(62, 126, 163, 0.0) 100%
    ) !important;
    color: #fff;
    padding: 15px;
    bottom: 25px; /* lift caption higher so title sits closer to category */
    transition: all 0.5s ease;
}

/* ── Category label above the title ── */
.content-container .pic-caption .pic-category {
    font-size: 10px !important;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: rgba(255, 255, 255, 0.75);
    margin: 10px 0 0 8px; /* big top pushes it down, tiny bottom keeps it tight to title */
    text-align: left;
}

.content-container .view-more {
    background-color: #f9a03f !important; 
    color: #fff !important;               
    padding: 10px !important;         
    font-size: 16px !important;
    border-radius: 4px;
    border: none !important;            
    box-shadow: none !important;          
    outline: none !important;   
    transition: transform 0.3s ease; /* new: enable smooth scale on hover */          
}

.content-container .pic-caption .view-more:focus,
.content-container .pic-caption .view-more:active,
.content-container .pic-caption .view-more:hover {
    outline: none !important;
    box-shadow: none !important;
    transform: scale(1.1); /* new: enlarge button on hover */
}

/* Hover effect stays as you already have */
.pic:hover .pic-caption {
    bottom: 0; 
}
.pic:hover .pic-category {
    margin-top: 20px;
}

.pic:hover .pic-title {
    margin-top: 20px;
}

.content-container .pic-caption .pic-title {
	font-family: 'Playfair Display', sans-serif !important;
    font-size: 22px !important; /* bigger font for title */
    font-weight: 700; /* slightly bolder */
    padding: 0;
    margin-bottom: 30px; /* bigger bottom spacing pushes content up away from title */
}

.pic-caption .pic-title {
    font-weight: 700;
    min-height: 250px;
    line-height: 1.6 !important;
    margin: 8px; 
    text-align: left;
}

/* Content inside the card */
.content-container .pic-caption p {
    font-size: 16px !important; /* bigger content text */
    margin-bottom: 10px;
    text-align: left; /* left-justify paragraph */
}



.pic .pic-caption .pic-title {
	margin-top: 0;
}

/* Desktop "View All" button: transparent and outlined */
@media (min-width: 768px) {
    .view-all-btn-desktop {
        background-color: transparent !important; /* transparent background */
        color: #20465d !important;               /* accent color */
        border: 1.5px solid #20465d !important;  /* outline border */
        box-shadow: none !important;
        padding: 6px 10px !important;           /* wider button */
        font-size: 14px !important;
        min-width: 90px;                        /* ensures button doesn't shrink too small */
        transition: all 0.3s ease, transform 0.3s ease; /* include transform for scaling */
    }

    .view-all-btn-desktop:hover,
    .view-all-btn-desktop:focus,
    .view-all-btn-desktop:active {
        background-color: #20465d !important;     
        color: #fff !important;                   
        border-color: #20465d !important;
        transform: scale(1.1); /* enlarge button on hover */
    }
}

/* Mobile adjustments */
@media (max-width: 767px) {
    .content-container .pic {
        min-height: 230px !important;
    }

    .slick-dots {
        margin-top: -30px !important;  
        bottom: -15px !important;    
    }

    /* Mobile "View All" button: smaller width, centered */
    .content-container .btn.btn-block.visible-xs-block {
        display: inline-block;           /* override btn-block */
        width: auto;                     /* auto width based on padding */
        max-width: 130px;                /* optional: cap the width */
        padding: 6px 12px !important;   /* adjust padding for smaller button */
        font-size: 14px !important;       
        background-color: transparent !important; 
        color: #20465d !important;            
        border: 1.5px solid #20465d !important; 
        box-shadow: none !important;
        text-align: center;              /* center text */
        transition: all 0.3s ease, transform 0.3s ease; /* smooth hover/scale */
        margin: 0 auto 15px auto;        /* center button horizontally with spacing */
    }

    /* Hover/focus effect same as desktop */
    .content-container .btn.btn-block.visible-xs-block:hover,
    .content-container .btn.btn-block.visible-xs-block:focus,
    .content-container .btn.btn-block.visible-xs-block:active {
        background-color: #20465d !important; 
        color: #fff !important;               
        border-color: #20465d !important;
        transform: scale(1.1); /* enlarge button on hover */
    }
}