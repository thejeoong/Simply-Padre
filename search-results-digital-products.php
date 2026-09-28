//PAGE HEADER
<?php
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
<div class="grid-container">


//PAGE LOOP
<?php
$p = getMetaData("users_portfolio_groups",$p['group_id'],$p,$w);
$presults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        *
    FROM
        `users_portfolio`
    WHERE
        users_portfolio.group_id = '".$p[group_id]."'
    ORDER BY
        users_portfolio.order ASC,
        users_portfolio.photo_id ASC
    LIMIT
        1");
$pic = mysql_fetch_assoc($presults);
$p['group_picture'] = $pic['file'];

if ($p['title'] == "") {
    $p['title'] = defaultPhotoTitle($p,$w);
} else {
    $nofollow = "";
}
$subscription = getSubscription($user['subscription_id'],$w);

// Build direct image path
$firstImagePath = '';
if ($p['group_picture'] != '') {
    $firstImagePath = '/' . $w['photo_folder'] . '/display/' . $p['group_picture'];
} else if ($p['default_picture'] != '') {
    $firstImagePath = '/uploads/news-pictures/' . basename($p['default_picture']);
}
?>

<div class="search_result row-fluid member-level-<?php echo $user['subscription_id']; ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
    <meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
    <meta itemprop="url" content="/<?php echo $p['group_filename']; ?>">
    <meta itemprop="name" content="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>">

    <div class="grid_element">

        <?php if ($firstImagePath != '') { ?>
        <div class="post-card-image">

            <!-- Digital product badge -->
            <div class="post-card-digital-badge">
                <i class="bi bi-download"></i> Digital
            </div>

            <a href="/<?php echo $p['group_filename']; ?>" title="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>">
                <img class="post-card-img"
                    src="<?php echo $firstImagePath; ?>"
                    alt="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>"
                    title="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>" />
            </a>

            <?php if ($p['post_promo'] != "") { ?>
            <div class="post-card-promo">
                <?php echo str_replace(' ', '', $p['post_promo']); ?>
            </div>
            <?php } ?>

        </div>
        <?php } ?>

        <div class="post-card-body">

            <div class="post-card-meta">
                <span class="post-card-date">
                    %%%posted_on%%%
                    <?php echo transformDate($p['date_updated'],"QB"); ?>
                </span>
                <?php if ($subscription['searchable'] != 0) { ?>
                <span class="post-card-author">
                    %%%by_label%%%
                    <a class="bold notranslate" href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
                        <?php echo $user['full_name']; ?>
                    </a>
                </span>
                <?php } ?>
            </div>

            <?php if ($p['group_category'] != "") { ?>
            <div class="post-card-category">
                <a title="<?php echo $dc['data_name']; ?> - <?php echo $p['group_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo $p['group_category']; ?>">
                    <?php echo $p['group_category']; ?>
                </a>
            </div>
            <?php } ?>

            <a class="post-card-title" title="<?php echo $p['group_name']; ?>" href="/<?php echo $p['group_filename']; ?>">
                <?php echo $p['group_name']; ?>
            </a>

            <?php if ($p['post_location'] != "") { ?>
            <div class="post-card-location">
                <i class="bi bi-geo-alt-fill"></i>
                <?php echo $p['post_location']; ?>
            </div>
            <?php } ?>

            <?php if ($p['group_desc'] != "") { ?>
            <p class="post-card-excerpt">
                <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $p['group_desc']), 115); ?>...
            </p>
            <?php } ?>

            <div class="post-card-footer">
                <?php
                $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
                if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                    echo '<span class="postItem" data-userid="'.$p['user_id'].'" data-datatype="'.$p['data_type'].'" data-dataid="'.$p['data_id'].'" data-postid="'.$p['group_id'].'"></span>';
                    echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
                }
                addonController::showWidget('post_comments','88a1bbf8d5d259c20c2d7f6c7651f672');
                ?>
                <a class="btn btn-primary post-card-readmore" title="<?php echo $p['group_name']; ?>" href="/<?php echo $p['group_filename']; ?>">
                    %%%view_more_label%%%  →
                </a>
            </div>

        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php
if ($p['lat'] != '' && $p['lon'] != '') {
    $_ENV['group'] = $p;
    echo widget("Bootstrap Theme - Google Pins Locations","",$w['website_id'],$w);
} ?>

<style>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap');

/* ═══════════════════════════════
   GRID VIEW
═══════════════════════════════ */
.grid_element {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    height: 100%;
    padding: 0 !important;
    box-shadow: 0 8px 8px -4px #0000001a, 0 4px 4px -4px #0000001a;
    border-radius: 12px !important;
    background: #ffffff;
}
.grid_element .post-card-image {
    width: 100%;
    overflow: hidden;
    flex-shrink: 0;
    line-height: 0;
    margin: 0 !important;
    padding: 0 !important;
    position: relative;
    border-radius: 0;
}
.grid_element .post-card-image > a {
    display: block;
    line-height: 0;
}
.grid_element .post-card-image > a > img.post-card-img {
    width: 100% !important;
    height: 200px !important;
    object-fit: cover !important;
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    border-radius: 0 !important;
    vertical-align: top !important;
}
.grid_element .post-card-body {
    padding: 14px 16px !important;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
    margin: 0 !important;
    box-sizing: border-box;
}
.grid_element:not(:has(.post-card-image)) .post-card-body {
    border-radius: 12px !important;
}

/* ═══════════════════════════════
   GRID EQUAL HEIGHT CARDS
═══════════════════════════════ */
.grid-container.row {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
}
.grid-container.row > .search_result {
    display: flex;
    flex-direction: column;
}
.grid-container.row > .search_result .grid_element {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.grid-container.row > .search_result .post-card-body {
    flex: 1;
}

/* ═══════════════════════════════
   LIST VIEW
═══════════════════════════════ */
.list-card-active .grid_element {
    flex-direction: row !important;
    align-items: stretch !important;
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    border-radius: 0 !important;
}
.list-card-active .post-card-image {
    width: 320px;
    min-width: 320px;
    max-width: 320px;
    flex-shrink: 0;
    align-self: stretch;
    position: relative;
    overflow: hidden;
    margin: 0 !important;
    padding: 0 !important;
    border-radius: 0 !important;
    line-height: 0;
}
.list-card-active .post-card-image > a {
    display: block;
    width: 100%;
    height: 100%;
    line-height: 0;
}
.list-card-active .post-card-image > a > img.post-card-img {
    width: 100% !important;
    height: 100% !important;
    min-height: 220px;
    object-fit: cover !important;
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    border-radius: 0 !important;
    vertical-align: top !important;
}
.list-card-active .post-card-body {
    border-radius: 0 12px 12px 0;
    flex: 1;
    padding: 14px 16px !important;
    margin: 0 !important;
}
.list-card-active .post-card-title {
    font-size: 24px;
}

/* ═══════════════════════════════
   DIGITAL PRODUCT BADGES
═══════════════════════════════ */
.post-card-digital-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    background: #e8f4fd;
    color: #2a7ab5;
    font-size: 10px;
    font-weight: 700;
    padding: 5px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 5px;
    letter-spacing: 0.04em;
}
.post-card-promo {
    position: absolute;
    bottom: 10px;
    right: 10px;
    z-index: 2;
    background: rgba(0,0,0,0.65);
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    padding: 2px 12px;
    border-radius: 20px;
    line-height: 1.4;
}

/* ═══════════════════════════════
   SHARED CONTENT STYLES
═══════════════════════════════ */
.post-card-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 6px;
}
.post-card-date {
    font-size: 12px;
    color: #888;
}
.post-card-date::before {
    font-family: 'bootstrap-icons';
    content: '\F1F6';
    margin-right: 4px;
    font-size: 12px;
    vertical-align: middle;
}
.post-card-author {
    font-size: 12px;
    color: #888;
}
.post-card-category a {
    display: inline-block;
    background: #e8f4fd;
    color: #2a7ab5;
    font-size: 10px;
    font-weight: 800 !important;
    padding: 4px 10px;
    border-radius: 20px;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.post-card-title {
    font-family: 'Libre Baskerville';
    font-size: 20px;
    font-weight: 700;
    color: #1a1a2e;
    text-decoration: none !important;
    display: block;
    line-height: 1.4;
}
.post-card-title:hover { text-decoration: none !important; }
.post-card-location {
    font-size: 13px;
    color: #555;
    display: flex;
    align-items: center;
    gap: 5px;
}
.post-card-location i {
    color: #e74c3c;
    font-size: 14px;
}
.post-card-excerpt {
    font-size: 13px;
    color: #555;
    line-height: 1.6;
    margin: 0;
    flex: 1;
}
.post-card-footer {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: auto;
    padding-top: 8px;
}
.post-card-readmore {
    margin-left: auto;
    font-size: 13px;
    font-weight: 700;
    color: #2a7ab5;
    background: #e8f4fd;
    padding: 8px 20px;
    border: none;
    text-decoration: none;
    white-space: nowrap;
}
.post-card-readmore:hover {
    background: #d0e8f7;
    color: #2a7ab5; 
}
.post-card-readmore.btn {
    background: #e8f4fd !important;
    color: #2a7ab5 !important;
    border: none !important;
    box-shadow: none !important;
    text-decoration: none !important;
}
.post-card-readmore.btn:hover {
    background: #d0e8f7 !important;
    color: #2a7ab5 !important;
}
.post-card-readmore.btn:active {
    background: #c6e0f5 !important;
    color: #2a7ab5 !important;
}

/* ═══════════════════════════════
   LIKE BUTTON
═══════════════════════════════ */
.post-card-footer button.favorite {
    background: #f9f3e8 !important;
    color: #666666 !important;
    border: none !important;
    box-shadow: none !important;
}
.post-card-footer button.favorite span { color: #666666 !important; }
.post-card-footer button.favoriteActive {
    background: #fff4ed !important;
    color: rgb(245, 166, 35) !important;
    border: none !important;
    box-shadow: none !important;
}
.post-card-footer button.favoriteActive span { color: rgb(245, 166, 35) !important; }
.post-card-footer button.favorite #bookmark-content { display: none !important; }
.post-card-footer button.favorite::after { content: 'LIKE'; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; }
.post-card-footer button.favorite.favoriteActive::after { content: 'LIKED'; }

/* ═══════════════════════════════
   COMMENT BUTTON
═══════════════════════════════ */
.post-card-footer a.search-comment-count,
.post-card-footer a.search-comment-count.favoriteActive,
.post-card-footer a.search-comment-count.favoriteActive i,
.post-card-footer a.search-comment-count.favoriteActive span {
    background: #f9f3e8 !important;
    color: #666666 !important;
    border: none !important;
    box-shadow: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 6px 8px !important;
    line-height: 1 !important;
}
.post-card-footer a.search-comment-count span.bookmark-number,
.post-card-footer a.search-comment-count.favoriteActive span.bookmark-number {
    background: #666666 !important;
    color: #ffffff !important;
    border-radius: 50% !important;
    width: 16px !important;
    height: 16px !important;
    padding: 0 !important;
    font-size: 8px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    line-height: 1 !important;
    margin-left: 4px !important;
    vertical-align: middle !important;
}

/* ═══════════════════════════════
   MOBILE
═══════════════════════════════ */
@media (max-width: 767px) {
    .list-card-active .grid_element {
        flex-direction: column !important;
    }
    .list-card-active .post-card-image {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        border-radius: 0 !important;
        align-self: auto;
    }
    .list-card-active .post-card-image > a { height: 220px; }
    .list-card-active .post-card-image > a > img.post-card-img {
        height: 220px !important;
        min-height: 220px;
    }
    .list-card-active .post-card-body { border-radius: 0 0 12px 12px !important; }
    .grid-container.row > .search_result {
        padding-left: 8px !important;
        padding-right: 8px !important;
        margin-bottom: 16px !important;
    }
    .grid-container.row {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }
}
</style>

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

<style>
.search_result.list-card-active {
    background: #ffffff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 0 !important;
    margin-bottom: 16px;
    overflow: hidden;
    box-shadow: 0 8px 8px -4px #0000001a, 0 4px 4px -4px #0000001a;
}
.search_result.list-card-active .grid_element {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    border-radius: 0 !important;
}
.grid-container:not(.row) hr,
.grid-container:not(.row) .clearfix + hr {
    display: none !important;
}
</style>

<script>
function applyListCardStyle() {
    var container = document.querySelector('.grid-container');
    if (!container) return;
    var isListMode = !container.classList.contains('row');
    document.querySelectorAll('.search_result').forEach(function(el) {
        if (isListMode) {
            el.classList.add('list-card-active');
        } else {
            el.classList.remove('list-card-active');
        }
    });
}

function initGridObserver() {
    var container = document.querySelector('.grid-container');
    if (!container) {
        setTimeout(initGridObserver, 200);
        return;
    }
    applyListCardStyle();
    new MutationObserver(function() {
        applyListCardStyle();
    }).observe(container, { attributes: true, attributeFilter: ['class'] });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGridObserver);
} else {
    initGridObserver();
}
</script>
