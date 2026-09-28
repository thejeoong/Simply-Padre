<?php
// Check if the feature can show content
$showFeatureContent = false;
$memberCanView = false;
$membersOnlyActivated = false;

if(!empty($page['form_name'])){
    $sidebar = $page['form_name'];
    $dc['category_sidebar'] = $page['form_name'];
    switch($page['menu_layout']){
        case "1":
            $wa['custom_47'] = "0";
            break;
            case "2":
                $wa['custom_47'] = "1";
            break;
            case "3":
                $wa['custom_47'] = "3";
            break;
            case "4":
                $wa['custom_47'] = "4";
            break;
    }
}

$wa['custom_47'] = (checkIfMobile(true) && $dc['sidebar_position_mobile'] == 'hide') ? '2' : $wa['custom_47'];
if (addonController::isAddonActive('members_only')) {
    $membersOnlyActivated = true;
}

if (strlen($dc['always_on']) > 1) {
    if ($dc['always_on'][0] == "2" && $dc['always_on'][1] == "1") {
        $dc['always_on'] = "2";
    }
    if ($dc['always_on'][0] == "2" && $dc['always_on'][1] == "2") {
        $dc['always_on'] = "1";
    }
    if ($dc['always_on'][0] == "1" && $dc['always_on'][1] == "2") {
        $dc['always_on'] = "3";
    }
    if ($dc['always_on'][0] == "1" && $dc['always_on'][1] == "1") {
        $dc['always_on'] = "0";
    }
}
if ($dc['always_on'] != "1") {
    $me = getUser($_COOKIE['userid'], $w);
    $meSubscription = getSubscription($me['subscription_id'], $w);

    // Check membership-level settings
    if ($me['token'] != "") {
        $data_array = explode(",", $meSubscription['data_settings_read']);

        // Check if member can view according to membership-level setting 'data_settings_read'
        if (in_array($dc['data_id'], $data_array) && $me['active'] == "2" || $dc['data_type'] == "13") {
            $memberCanView = true;
        }
    }
}
$hidePost = false;

if ($membersOnlyActivated == true && !$memberCanView && (($dc['always_on'] == "0" && ($_ENV['seo_type'] == "data_post" || $_ENV['seo_type'] == "data_category")) || ($dc['always_on'] == "2" && ($_ENV['seo_type'] == "data_post") || ($dc['always_on'] == "3" && ($_ENV['seo_type'] == "data_category"))))) {
    $hidePost = true;
    if (!$showPost && $me['user_id'] == $post['user_id'] && $me['active'] == "2" && $_ENV['seo_type'] == "data_post") {
        $hidePost = false;
    }
}
if($page['enable_hero_section'] == "1" || $page['enable_hero_section'] == "2"){ ?>
    </div>
    <style>
        <?php if($page['hero_hide_banner_ad'] == "1"){ ?>
        .above-content-banner-ad{
            display: none;
        }
        <?php } ?>
        <?php if($page['hero_content_overlay_opacity'] != "" && $page['hero_content_overlay_opacity'] != "0" && $page['hero_content_overlay_color'] != ""){ ?>
        .hero_section_container::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: <?php echo $page['hero_content_overlay_color']?>;
            opacity: <?php echo $page['hero_content_overlay_opacity']?>;
        }
        <?php } ?>
        .views {
            margin-top: -20px!important;
        }
		#first_container > .container:first-child .clearfix.body-content {
			display: none !important;
		}
        .hero_section_container {
            background-image: url("<?php echo $page['hero_image']?>");
            background-position: center center;
            background-repeat: no-repeat;
        <?php if ($page['hero_background_image_size'] == "mobile-ready" || $page['hero_background_image_size'] == "") { ?>
            background-size: cover;
        <?php } else { ?>
            background-size: 100% auto;
        <?php } ?>
            position: relative;
        }
        .hero_section_container .container {
            padding-top: <?php echo $page['hero_top_padding']?>px;
            padding-bottom: <?php echo $page['hero_bottom_padding']?>px;
        }
        .hero_section_container .hero_h1 {
            color: <?php echo $page['h1_font_color']?>;
            font-size: <?php echo $page['h1_font_size']?>px;
            font-weight: <?php echo $page['h1_font_weight']?>;
        }
        .hero_section_container .hero_h2 {
            color: <?php echo $page['h2_font_color']?>;
            font-size: <?php echo $page['h2_font_size']?>px;
            font-weight: <?php echo $page['h2_font_weight']?>;
			line-height: 1.4em;
        }
        .hero_section_container .hero_content {
            color: <?php echo $page['hero_content_font_color']?>;
            font-size: <?php echo $page['hero_content_font_size']?>px;
        }
        .hero_section_container > .container > div {
        <?php if($page['hero_alignment'] == "center"){ ?>
            float: none!important;
        <?php } else { ?>
            float: <?php echo $page['hero_alignment']; ?>!important;
        <?php } ?>
            text-align: <?php echo $page['hero_alignment']; ?>;
        }
        <?php if($page['hero_alignment'] != "center"){ ?>
        @media only screen and (max-width: 768px){
            .hero_section_container {background-position: <?php echo $page['hero_alignment']; ?> top;}
        }
        <?php } ?>
        <?php if($page['enable_hero_section'] == "2"){ ?>
        @media only screen and (max-width: 768px){
            .hero_section_container {display: none;}
        }
        <?php } ?>
    </style>
    <div class="hero_section_container">
        <div class="container">
            <div class="col-md-<?php echo $page['hero_column_width']?> center-block">
                <?php
                if ($page['h1'] != "") {
                    echo "<h1 class='hero_h1'>" . stripslashes($page['h1']) . "</h1>";
                }
                if ($page['h2'] != "") {
                    echo "<h2 class='hero_h2'>" . stripslashes($page['h2']) . "</h2>";
                }
                if ($page['hero_section_content'] != ""){
                    $heroContent = $w;
                    if(isset($service)){
                        $service['service_name']    = $service['name'];
                        $heroContent                = array_merge($heroContent,$service);
                    }
                    echo "<div class='clearfix'></div><div class='hero_content'>".replaceChars($heroContent, stripslashes($page['hero_section_content']))."</div>";
                }

                $page['hero_link_text'] = trim($page['hero_link_text']);
                $page['hero_link_url']  = trim($page['hero_link_url']);

                if ($page['hero_link_url'] != "" && $page['hero_link_text'] != ""){
                    if($page['hero_link_color'] == ""){
                        $page['hero_link_color'] = "btn-primary";
                    } else {
                        $page['hero_link_color'] = 'btn-'.$page['hero_link_color'];
                    }
                    echo "<div class='clearfix'></div><div class='hero_button tmargin'> <a class='btn ".$page['hero_link_color']." ".$page['hero_link_size']."' href='".$page['hero_link_url']."' ".($page['hero_link_target_blank'] == 1 ? "target=_blank" : "" )."> ".$page['hero_link_text']." </a> </div>";
                }
                ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix clearfix-lg"></div>

    <div class="container">
<?php }

if ($hidePost) {
    addonController::showWidget('members_only', '389ffec8e86fc926655fab47a7b01a5a');

} else if (($page['seo_type'] == "data_post" || $_ENV['seo_type'] == "data_post")) {

    if(isset($user['active']) && $user['active'] != ACTIVE){
        echo widget("Bootstrap Theme - Post Page No Active User");
    }

    $sidebar = $dc['profile_sidebar'];
    $post = additionalFields($post, $w);
    $videoOembed = new oembed_controller($post['post_video_link'],null,$post['post_image']);
    $videoData = $videoOembed->getOembedData();
    if (!empty($videoData->html)) {
        $post['post_video'] = $videoData->html;
        $post['post_video_oembed'] = (array)$videoData;
        $post['post_video_thumbnail'] = $videoOembed->getThumbnailHtml($post);
        $post['post_video_type'] = $videoOembed->getType();
    }else if($post['post_video'] != ""){
        $post['post_video'] = "<iframe width='280' height='158' src='".str_replace("http://","//",$post['post_video'])."' frameborder='0' allowfullscreen></iframe>";
    }

    if ($post['post_tags'] != "") {
        $post['post_tags'] = explode(",", rtrim($post['post_tags'], ", "));

        foreach ($post['post_tags'] as $tag) {

            if ($tag != "") {
                $tag = str_replace('"', "", $tag);
                $tags[] = '<a href="/' . $dc['data_filename'] . '?q=' . trim(($tag)) . '" title="' . $tag . ' ' . $dc['data_name'] . '">' . trim($tag) . '</a>';
            }
        }
        if (is_array($tags)) {
            $post['post_tags'] = implode(" ", $tags);
            $tags = $post['post_tags'];
        }
    } ?>
    <div class="row content_w_sidebar feature-post feature-<?php echo $dc['data_filename']; ?>">
    <?php
if($dc['profile_sidebar'] != "" && $wa['custom_48'] != '2'){
    switch ($wa['custom_48']) {
        case "3":
            echo "<div class='col-md-9 col-md-push-3 post-detail-body page-body'>";
            break;
        case "0":
            echo "<div class='col-md-8 col-md-push-4 post-detail-body page-body'>";
            break;
        case "4":
            echo "<div class='col-md-9 post-detail-body page-body'>";
            break;
        case "1":
            echo "<div class='col-md-8 post-detail-body page-body'>";
            break;
    } ?>
<?php } else if ($dc['profile_sidebar'] == "" || $wa['custom_48'] == "2") { ?>
    <div class="col-lg-12 member-profile-body">
<?php }
    echo eval("?>" . replaceChars($w, $dc['search_results_layout']) . "<?");

    if (strstr($post['additional_fields'], "==")) {
        $fresults = mysql($w['database'], "SELECT
          *
        FROM
          `form_fields`
        WHERE
          `form_name`='$dc[form_name]'
        AND
          `field_display_view`='1'
        ORDER BY
          `field_order`
        ASC");

        if (mysql_num_rows($fresults) > 0) { ?>
            <table class="table table-striped">
                <?php
                while ($f = mysql_fetch_array($fresults)) {

                    if ($f['field_type'] == "HTML") { ?>
                        <tr>
                            <th colspan=2>
                                <h2><?php echo $f['field_text']; ?></h2>
                            </th>
                        </tr>
                        <?php
                    } else if ($pre[$f['field_name']] != "") { ?>
                        <tr>
                            <td><?php echo $f['field_text']; ?></td>
                            <td><?php echo ucfirst($post[$f['field_name']]); ?></td>
                        </tr>
                    <?php }
                } ?>
            </table>
        <?php }
    }

    if($w['enable_post_comments'] == "1") {
        addonController::showWidget('post_comments','edf6a434e514be0513ad265a71872271');
    }

    if ($dc['comments_code'] != "") {
        echo eval("?>" . $dc['comments_code'] . "<?");
    }
    ?>
    </div><!-- ENDS <div class="col-lg-12"> -->
    <?php
    if($dc['profile_sidebar'] != "" && $wa['custom_48'] != '2'){
        switch ($wa['custom_48']) {
            case "3":
                echo '<div class="col-md-3 col-md-pull-9 post-detail-sidebar sidebar-section">';
                break;
            case "0":
                echo '<div class="col-md-4 col-md-pull-8 post-detail-sidebar sidebar-section">';
                break;
            case "4":
                echo '<div class="col-sm-12 col-md-3 post-detail-sidebar sidebar-section">';
                break;
            case "1":
                echo '<div class="col-sm-12 col-md-4 post-detail-sidebar sidebar-section">';
                break;
        }
        if ($sidebar != "") {
            echo sidebar($sidebar, "", $w['website_id'], $w);
        } else {
            echo sidebar("Default Sidebar", '', $w['website_id'], $w);
        } ?>
        </div>
    <?php } ?>
    </div>  <!-- <div class="col-md-8"> -->
    <?php
} else { // ends else if ($page[seo_type] == "data_post" || $_ENV[seo_type] == "data_post")


    if ($page['content'] != "" && $page['custom_html_placement'] == "3") {
        echo '</div><div class="page-content-above-body"><div class="container">'.$page['content'].'</div><div class="clear"></div></div><div class="container">';
    }
    if (($wa['custom_198'] == 1 && addonController::isAddonActive('grid_view_search_reults')) || ($wa['custom_198'] == 2 && addonController::isAddonActive('google_map_search_results'))) { ?>
        <style>
            .grid-container{
                opacity: 0;
                transition: opacity 0.9s ease-in-out;
            }
        </style>
    <?php } ?>
    <style type="text/css">
		.search_results_royalSlider .rsMainSlideImage {
			width: 100%!important;
			height: 100%!important;
			margin-top:0!important;
			object-fit: cover; /* Ensures the image covers the container without stretching */
			object-position: center; /* Centers the image within the container */
		}
        @media only screen and (max-width: 768px) {
            /* Styles for devices with a screen width of 600 pixels or less (typical for mobile devices) */
            .content_w_sidebar .cal-month-box .pull-right {
                float: none !important;
            }

            /* Add more mobile-specific styles as needed */
        }
    </style>
<div class="row content_w_sidebar feature-search feature-<?php echo $dc['data_filename']; ?>">
    <?php
if ($dc['category_sidebar'] != ""){

    switch ($wa['custom_47']) {
        case "3":
            echo "<div class='col-md-9 col-md-push-3'>";
            break;
        case "0":
            echo "<div class='col-md-8 col-md-push-4'>";
            break;
        case "4":
            echo "<div class='col-md-9'>";
            break;
        case "1":
            echo "<div class='col-md-8'>";
            break;
    }

} else if ($dc['category_sidebar'] == "" || $wa['custom_45'] == "2") { ?>
    <div class="col-lg-12">
<?php }
	// ItemList schema
	$itemListName = htmlspecialchars(stripslashes($dc['h1']), ENT_QUOTES, 'UTF-8');
	$pageTitle = !empty($dc['title']) ? html_entity_decode($dc['title'], ENT_QUOTES, 'UTF-8') : $itemListName;
	$schema_has_category = false;
	$schema_category_name = '';
	if (!empty($_GET['category']) && is_array($_GET['category'])) {
		$cats = array_filter(array_map(function($c) { return htmlspecialchars(urldecode($c), ENT_QUOTES, 'UTF-8'); }, $_GET['category']));
		if ($cats) {
			$itemListName .= ": " . implode(", ", $cats);
			$schema_has_category = true;
			$schema_category_name = implode(", ", $cats);
		}
	}
	if (!empty($_GET['page']) && ($pageNum = intval($_GET['page'])) > 0 && !empty($label['page_label'])) {
		$itemListName .= " " . htmlspecialchars($label['page_label'], ENT_QUOTES, 'UTF-8') . " " . $pageNum;
	}
	$GLOBALS['search_result_position'] = 0;
	$schema_page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/' . strtolower($dc['data_filename']);
	$schema_url = $schema_page_url . '#entity';
	$schema_site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
	$schema_description = !empty($page['meta_desc']) ? htmlspecialchars(strip_tags($page['meta_desc']), ENT_QUOTES, 'UTF-8') : '';
	$aboutContent = trim(strip_tags(stripslashes($dc['h1'])) . (!empty($dc['h1']) && !empty($dc['h2']) ? ' - ' : '') . strip_tags(stripslashes($dc['h2'])));
    echo widget("Bootstrap Theme - Post Results - Sorting Options"); 
    echo $page['header']; 
    if ($page['enable_hero_section'] != "1" && $page['enable_hero_section'] != "2"){ ?>
        <div class="col-xs-12 col-sm-11 col-md-10 nopad xs-hpad pull-left feature_results_header">
            <h1 class="nopad nomargin bold">
                <?php echo $page['h1'];
                if ($_GET['page'] > 0 && !empty($label['page_label'])) {
                    echo " ". $label['page_label'] ." ". $_GET['page'];
                } ?>
            </h1>
            <div class="clearfix"></div>
            <?php
            if ($page['h2'] != "") { ?>
                <h2 class="tmargin nobmargin"><?php echo $page['h2']; ?></h2>
                <div class="clearfix"></div>
            <?php } ?>
        </div>
    <?php }
    if (is_array($data_results) && count($data_results) > 1) {

        if (is_array($tabs) && $notabs != 1) { ?>
            <ul class="nav nav-tabs" role="tablist" id="myTab">
                <?php
                foreach ($tabs as $key => $tab) {
                    $tabsnum++;
                    if ($data_results[$key]['total'] > 0) { ?>
                        <li role="presentation" <?php $tb++;
                        if ($tb == 1) { ?>class="active"<?php } ?>>
                            <a aria-controls="<?php echo $key ?>" role="tab" data-toggle="pill"
                               href="#t<?php echo $key; ?>" rel="nofollow">
                                <?php
                                echo eval("?>" . $tab . "<?");
                                echo $dc['data_type'];
                                if ($data_results[$key]['total'] > 0) { ?>
                                    (<font><?php echo $data_results[$key]['total']; ?></font>)
                                <?php } ?>
                            </a>
                        </li>
                    <? }
                } ?>
            </ul>
        <?php } ?>

        <div class="tab-content">

        <?php
        foreach ($data_results as $key => $value) {
            $totaltabs++; ?>
            <div class="clearfix"></div>
            <div role="tabpanel" class="tab-pane fade <?php if ($totaltabs === 1) { ?> in active <?php } ?>"
                 id="t<?php echo $key; ?>">
                <?php echo $value['results']; ?>
                <div class="post-pagination-block">
                    <ul class="pagination">
                        <?php echo str_replace("?", "$value[data_filename]?", $value['pagination']); ?>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php }
    } else {

        if ($page['content'] != "" && $page['custom_html_placement'] == "1") {
            echo "<div class='page-content-above-results inline-block btn-block'>".$page['content']."<div class='clear'></div></div>";
        }
        $totaltabs++;
        ob_start();
        ?>
<div itemscope itemtype="https://schema.org/CollectionPage" itemid="<?php echo htmlspecialchars($schema_url, ENT_QUOTES, 'UTF-8'); ?>">
	<meta itemprop="name" content="<?php echo $pageTitle; ?>">
	<meta itemprop="headline" content="<?php echo $itemListName; ?>">
	<meta itemprop="description" content="<?php echo $schema_description; ?>">
	<meta itemprop="about" content="<?php echo htmlspecialchars($aboutContent, ENT_QUOTES, 'UTF-8'); ?>">
	<?php
	$keywordsParts = array();
	if(!empty($dc['h1'])) $keywordsParts[] = strip_tags(stripslashes($dc['h1']));
	if(!empty($w['website_name'])) $keywordsParts[] = $w['website_name'];
	if(!empty($dc['data_name'])) $keywordsParts[] = $dc['data_name'];
	if(count($keywordsParts) > 0) {
	?>
	<meta itemprop="keywords" content="<?php echo htmlspecialchars(implode(', ', $keywordsParts), ENT_QUOTES, 'UTF-8'); ?>">
	<?php } ?>
	<meta itemprop="inLanguage" content="<?php echo htmlspecialchars(isset($w['website_language']) ? $w['website_language'] : 'en', ENT_QUOTES, 'UTF-8'); ?>">
	<link itemprop="url" href="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>">
	<meta itemprop="mainEntityOfPage" content="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>">
	<meta itemprop="dateModified" content="<?php echo date('c', strtotime(date('Y-m-01'))); ?>">
	<meta itemprop="lastReviewed" content="<?php echo date('c', strtotime(date('Y-m-01'))); ?>">
	<meta itemprop="copyrightYear" content="<?php echo date('Y'); ?>">
	<link itemprop="copyrightHolder" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#organization">
	<link itemprop="reviewedBy" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#organization">
	<link itemprop="isPartOf" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#website">
	<link itemprop="publisher" href="<?php echo htmlspecialchars($schema_site_url, ENT_QUOTES, 'UTF-8'); ?>/#organization">
	<?php if($schema_has_category) { ?>
	<div itemprop="about" itemscope itemtype="https://schema.org/Thing">
		<meta itemprop="name" content="<?php echo htmlspecialchars($schema_category_name, ENT_QUOTES, 'UTF-8'); ?>">
	</div>
	<?php } ?>
	<div itemprop="mainEntity" itemscope itemtype="https://schema.org/ItemList" itemid="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>#itemlist">
		<meta itemprop="name" content="<?php echo $itemListName; ?>">
		<link itemprop="url" href="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>">
		<meta itemprop="description" content="<?php echo $schema_description ? $schema_description : htmlspecialchars($itemListName, ENT_QUOTES, 'UTF-8'); ?>">
		<meta itemprop="mainEntityOfPage" content="<?php echo htmlspecialchars($schema_page_url, ENT_QUOTES, 'UTF-8'); ?>">
		<meta itemprop="itemListOrder" content="http://schema.org/ItemListOrderAscending">
		<?php if(isset($_ENV['end']) && $_ENV['end'] > 0) { ?>
		<meta itemprop="numberOfItems" content="<?php echo $_ENV['end']; ?>">
		<?php } ?>
		<?php echo $data_results[$dc['data_id']]['results']; ?>
	</div>
</div>
        <?php
        $schema_wrapped_results = ob_get_clean();
        if ($page['content'] != "" && ($page['custom_html_placement'] == "0" || $page['custom_html_placement'] == "")) { ?>
            <style>
                .views {
                    margin-top: -55px!important;
                }
                #results > hr:first-of-type {
                    visibility: hidden;
                    margin-top: -20px;
                }
            </style>

            <div class="clearfix"></div>
            <div role="tabpanel">
                <hr class="hr-header-<?php echo $dc['data_filename']; ?>">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active">
                        <a href="#results" aria-controls="results" role="tab" data-toggle="tab">%%%results_search_results_tab%%%</a>
                    </li>
                    <li>
                        <a href="#info" aria-controls="info" role="tab" data-toggle="tab">%%%read_more_search_tab%%%</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane" id="info">
                        <?php echo $page['content']; ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade in active" id="results">
                        <?php echo $schema_wrapped_results; ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo $schema_wrapped_results;
        }
        if ($page['content'] != "" && $page['custom_html_placement'] == "2") {
            echo "<div class='page-content-below-results inline-block btn-block'>".$page['content']."<div class='clear'></div></div>";
        }

        $app_uri = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        if ($pos_get = strpos($app_uri, '?')) {
            $app_uri = substr($app_uri, 0, $pos_get);
        }
        if (($slash_get = strrpos($app_uri, '/')) == strlen($app_uri) - 1) {
            $app_uri = substr($app_uri, 0, $slash_get);
        } ?>
        <div class="clearfix"></div>
        <div class="post-pagination-block">
            <ul class="pagination">
                <?php echo str_replace("?", $app_uri . "?", $data_results[$dc['data_id']]['pagination']); ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        </div> <!-- <div class="tab-content"> -->

        <?php
        if ($totaltabs < 1) {
            echo showMessage("Sorry, we did not find any results for your search.", "alert", 1);
        }
        $sidebar = $page['sidebar'];

        if ($dc['category_sidebar'] != "") {
            $sidebar = $dc['category_sidebar'];
        }
    }

    if ($sidebar == "") { ?>
        </div>
        </div>
    <?php }
    if($dc['category_sidebar'] != "" && $wa['custom_47'] != '2'){
        switch ($wa['custom_47']) {
            case "3":
                echo '<div class="col-md-3 col-md-pull-9">';
                break;
            case "0":
                echo '<div class="col-md-4 col-md-pull-8">';
                break;
            case "4":
                echo '<div class="col-sm-12 col-md-3">';
                break;
            case "1":
                echo '<div class="col-sm-12 col-md-4">';
                break;
        }
        if ($sidebar != "") {
            echo sidebar($dc['category_sidebar'],"",$w['website_id'],$w);
        } else {
            echo sidebar("Default Sidebar", '', $w['website_id'], $w);
        } ?>
        </div>
    <?php } ?>

    </div>
    <?php if ($page['content'] != "" && $page['custom_html_placement'] == "4") {
        echo '</div><div class="page-content-below-body"><div class="container">'.$page['content'].'</div><div class="clear"></div></div>';
    } ?>
<?php } ?>
<?php if($dc['data_type'] == "4" && addonController::isAddonActive('albums_search_results_slider') && $wa['searchResultsCarousel'] != "0"){
    addonController::showWidget('albums_search_results_slider','fe6a39bb61505a6904e8a88673e52084','');
} ?>

/* Follow Comments button - rgb(62, 126, 163) */
#the-post-comments .follow-post-button .btn,
#the-post-comments .follow-post-button .btn-danger,
#the-post-comments #follow-post {
    background-color: rgb(62, 126, 163) !important;
    border-color: rgb(62, 126, 163) !important;
    color: #fff !important;
}

#the-post-comments .follow-post-button .btn:hover,
#the-post-comments .follow-post-button .btn:focus,
#the-post-comments .follow-post-button .btn-danger:hover,
#the-post-comments .follow-post-button .btn-danger:focus {
    background-color: rgba(62, 126, 163, 0.85) !important;
    border-color: rgb(62, 126, 163) !important;
    color: #fff !important;
}

#the-post-comments .follow-post-button .dropdown-menu {
    border-color: rgb(62, 126, 163) !important;
}

#the-post-comments .follow-post-button .dropdown-menu li a:hover {
    background-color: rgb(62, 126, 163) !important;
    color: #fff !important;
}

/* Login to Comment button - rgb(24, 46, 69) */
#the-post-comments span.input-group-addon.member_login {
    background-color: rgb(24, 46, 69) !important;
    border-color: rgb(24, 46, 69) !important;
    color: #fff !important;
}

#the-post-comments span.input-group-addon.member_login:hover,
#the-post-comments span.input-group-addon.member_login:focus {
    background-color: rgba(24, 46, 69, 0.85) !important;
    border-color: rgb(24, 46, 69) !important;
    color: #fff !important;
}

<script>
    <?php
    if(checkIfMobile(true) && $dc['sidebar_position_mobile'] == 'top' && $page['seo_type'] != 'data_post'){?>
    let sidebarPositionTop = document.querySelectorAll('.content_w_sidebar > div');
    if (typeof sidebarPositionTop != 'undefined' && sidebarPositionTop.length > 0) {
        sidebarPositionTop[0].classList.remove('col-md-push-4', 'col-md-push-3', 'col-md-pull-8', 'col-md-pull-9','pull-right');
        sidebarPositionTop[1].classList.remove('col-md-push-4', 'col-md-push-3', 'col-md-pull-8', 'col-md-pull-9','pull-right');
        let sidebarHTML = sidebarPositionTop[1];
        sidebarPositionTop[1].remove();
        sidebarPositionTop[0].insertAdjacentElement('beforebegin', sidebarHTML);
    }
    <?php }?>
</script>