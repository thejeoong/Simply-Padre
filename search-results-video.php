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
[widget=create-post-btn]
<div class="grid-container">

//PAGE LOOP
<?php
$subscription = getSubscription($user['subscription_id'],$w);
$postFeaturedClass = ($post['sticky_post'] &&
    ($post['sticky_post_expiration_date'] == '0000-00-00' || $post['sticky_post_expiration_date'] >= date('Y-m-d')))
    ? ' featured-post featured-post-' . $dc['data_filename']
    : '';
?>

<div class="search_result row-fluid member-level-<?php echo $user['subscription_id'] . $postFeaturedClass; ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
    <meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
    <meta itemprop="url" content="/<?php echo $post['post_filename']; ?>">
    <meta itemprop="name" content="<?php echo htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8'); ?>">
    [widget=Bootstrap Theme - Detail Page - Schema Markup - Video Post Type]

    <div class="grid_element">

        <?php if ($post['post_video_thumbnail'] != "") { ?>
        <div class="post-card-image">

            <!-- Video play overlay — always visible -->
            <a href="/<?php echo $post['post_filename']; ?>" class="post-card-video-overlay" title="<?php echo htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="post-card-play-btn">
                    <i class="bi bi-play-fill"></i>
                </div>
            </a>

            <!-- Video badge top-left -->
            <div class="post-card-video-badge">
                <i class="bi bi-camera-video-fill"></i> Video
            </div>

            <!-- Thumbnail output from platform -->
            <div class="post-card-video-thumb">
                <?php echo $post['post_video_thumbnail']; ?>
            </div>

        </div>
        <?php } ?>

        <div class="post-card-body">

            <div class="post-card-meta">
                <span class="post-card-date">
                    %%%posted_on%%%
                    <?php echo transformDate($post['post_live_date'],"QB"); ?>
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

            <?php if ($post['post_category'] != "") { ?>
            <div class="post-card-category">
                <a title="<?php echo $dc['data_name']; ?> - <?php echo $post['post_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($post['post_category']); ?>">
                    <?php echo $post['post_category']; ?>
                </a>
            </div>
            <?php } ?>

            <a class="post-card-title" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                <?php echo $post['post_title']; ?>
            </a>

            <?php if ($post['post_location'] != "") { ?>
            <div class="post-card-location">
                <i class="bi bi-geo-alt-fill"></i>
                <?php echo $post['post_location']; ?>
            </div>
            <?php } ?>

            <?php if ($post['post_content'] != "") { ?>
            <p class="post-card-excerpt">
                <?php echo limitWords(strip_tags($post['post_content']), 115); ?>...
            </p>
            <?php } ?>

            <div class="post-card-footer">
                <?php
                $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
                if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                    echo '<span class="postItem" data-userid="'.$post['user_id'].'" data-datatype="'.$post['data_type'].'" data-dataid="'.$post['data_id'].'" data-postid="'.$post['post_id'].'"></span>';
                    echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
                }
                addonController::showWidget('post_comments','88a1bbf8d5d259c20c2d7f6c7651f672');
                addonController::showWidget('star_ratings_for_posts','609d748eaa051578e8ae2e3c8f848d9a');
                ?>
                <a class="post-card-readmore" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                    %%%watch_video%%%  →
                </a>
            </div>

        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr>
<?php
if ($post['lat'] != '' && $post['lon'] != '') {
    $_ENV['post'] = $post;
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
   VIDEO THUMBNAIL
═══════════════════════════════ */
.post-card-video-thumb {
    width: 100%;
    line-height: 0;
    display: block;
}
.post-card-video-thumb iframe,
.post-card-video-thumb img,
.post-card-video-thumb > * {
    width: 100% !important;
    height: 200px !important;
    object-fit: cover !important;
    display: block !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    border-radius: 0 !important;
    vertical-align: top !important;
    pointer-events: none;
}

/* ═══════════════════════════════
   VIDEO PLAY OVERLAY
═══════════════════════════════ */
.post-card-video-overlay {
    position: absolute;
    inset: 0;
    z-index: 3;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.15);
    transition: background 0.2s ease;
    text-decoration: none !important;
}
.post-card-video-overlay:hover {
    background: rgba(0,0,0,0.3);
}
.post-card-play-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(255,255,255,0.92);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    transition: transform 0.2s ease;
}
.post-card-video-overlay:hover .post-card-play-btn {
    transform: scale(1.1);
}
.post-card-play-btn i {
    font-size: 22px;
    color: #e74c3c;
    margin-left: 3px;
}

/* ═══════════════════════════════
   VIDEO BADGE
═══════════════════════════════ */
.post-card-video-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 4;
    background: rgba(231, 76, 60, 0.9);
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 5px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 5px;
    letter-spacing: 0.04em;
    backdrop-filter: blur(4px);
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
.list-card-active .post-card-video-thumb,
.list-card-active .post-card-video-thumb iframe,
.list-card-active .post-card-video-thumb img,
.list-card-active .post-card-video-thumb > * {
    width: 100% !important;
    height: 100% !important;
    min-height: 220px !important;
    object-fit: cover !important;
    display: block !important;
    border: none !important;
    border-radius: 0 !important;
    pointer-events: none;
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
    background: #fde8d8;
    color: #b8520e;
    font-size: 10px;
    font-weight: 800 !important;
    padding: 4px 10px;
    border-radius: 20px;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.post-card-title {
    font-family: 'Playfair Display', Georgia, serif;
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
.post-card-location i { color: #e74c3c; font-size: 14px; }
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
    color: #ffffff;
    background: #e74c3c;
    padding: 8px 20px;
    border-radius: 20px;
    text-decoration: none;
    white-space: nowrap;
}
.post-card-readmore:hover { background: #c0392b; color: #ffffff; }

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
        height: 220px;
    }
    .list-card-active .post-card-video-thumb,
    .list-card-active .post-card-video-thumb iframe,
    .list-card-active .post-card-video-thumb img,
    .list-card-active .post-card-video-thumb > * {
        height: 220px !important;
        min-height: 220px !important;
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

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.post-results-star span.small').forEach(function(el) {
        var text = el.innerText.trim();
        if (text.includes('((')) return;
        var score = text.match(/(\d+(?:\.\d+)?\/\d+)/);
        var total = text.match(/\((\d+)\s*Total\)/);
        if (score && total) {
            el.innerText = score[1] + ' (' + total[1] + ')';
        }
    });
});
</script>