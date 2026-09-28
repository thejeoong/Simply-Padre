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
$p = getMetaData("users_portfolio_groups",$p['group_id'],$p,$w);

$allPhotosResults = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
        photo_id, file
    FROM `users_portfolio`
    WHERE
        users_portfolio.group_id = '".$p['group_id']."'
        AND users_portfolio.data_id = '".$p['data_id']."'
    ORDER BY
        users_portfolio.order ASC,
        users_portfolio.photo_id ASC");

$allPhotos = array();
while($photoRow = mysql_fetch_assoc($allPhotosResults)){
    $allPhotos[] = $photoRow;
}

$totalPhotos = count($allPhotos);
$extraPhotoCount = max(0, $totalPhotos - 1);

$firstPhoto = isset($allPhotos[0]) ? $allPhotos[0]['file'] : '';
if ($firstPhoto != '') {
    $firstImagePath = '/' . $w['photo_folder'] . '/display/' . $firstPhoto;
} else if ($p['default_picture'] != '') {
    $firstImagePath = '/uploads/news-pictures/' . basename($p['default_picture']);
} else {
    $firstImagePath = '';
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

    <div class="grid_element album-card">

        <!-- Full cover background image -->
        <?php if ($firstImagePath != '') { ?>
        <a href="/<?php echo $p['group_filename']; ?>" class="album-card-bg" title="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>">
            <img src="<?php echo $firstImagePath; ?>"
                 alt="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>"
                 class="album-card-img" />
        </a>
        <?php } ?>

        <!-- Dark gradient overlay -->
        <div class="album-card-overlay"></div>

        <!-- Date — top left -->
        <div class="album-card-date">
            <?php echo transformDate($p['date_updated'],"QB"); ?>
        </div>

        <!-- Like button + Photo count — top right -->
        <div class="album-card-topright">
            <?php
            $addonFavorites = getAddOnInfo("add_to_favorites","a8ad175dd81204563b3a9fc3ebcd5354");
            if (isset($addonFavorites['status']) && $addonFavorites['status'] === 'success') {
                echo '<span class="postItem" data-userid="'.$p['user_id'].'" data-datatype="'.$p['data_type'].'" data-dataid="'.$p['data_id'].'" data-postid="'.$p['group_id'].'"></span>';
                echo widget($addonFavorites['widget'],"",$w['website_id'],$w);
            }
            ?>
            <?php if ($extraPhotoCount > 0) { ?>
            <div class="album-card-count">
                <i class="bi bi-images"></i> <?php echo $extraPhotoCount; ?>+
            </div>
            <?php } ?>
        </div>

        <!-- Content overlay — bottom -->
        <div class="album-card-content">
            <a class="album-card-title" href="/<?php echo $p['group_filename']; ?>" title="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo $p['group_name']; ?>
            </a>
            <a class="btn btn-primary album-card-readmore" href="/<?php echo $p['group_filename']; ?>" title="<?php echo htmlspecialchars($p['group_name'], ENT_QUOTES, 'UTF-8'); ?>">
                %%%view_more_label%%%  →
            </a>
            <div class="album-card-footer">
                <?php
                addonController::showWidget('post_comments','88a1bbf8d5d259c20c2d7f6c7651f672');
                addonController::showWidget('star_ratings_for_posts','609d748eaa051578e8ae2e3c8f848d9a');
                ?>
            </div>
        </div>

    </div>
</div>
<div class="clearfix"></div>
<hr>

<style>
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');

/* Ensure the parent is a solid anchor for the absolute image */
.grid_element.album-card {
    position: relative !important;
    display: block !important; /* Changed from flex to block to stabilize absolute children */
    width: 100% !important;
    height: 100% !important;
    min-height: 280px; 
    overflow: hidden !important;
    border-radius: 12px !important;
    padding: 0 !important;
}

/* ═══════════════════════════════
   THE FILL LOGIC
═══════════════════════════════ */
.album-card-bg {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 1 !important;
}

.album-card-img {
    width: 100% !important;
    height: 100% !important;
    min-width: 100% !important;
    min-height: 100% !important;
    object-fit: cover !important; /* This is the key for any ratio */
    object-position: center !important;
    display: block !important;
	transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) !important;
}
.grid_element.album-card:hover .album-card-img {
    transform: scale(1.04);
}

/* ═══════════════════════════════
   LIKE BUTTON (NO TEXT ON MOBILE)
═══════════════════════════════ */
.album-card-topright button.favorite {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}
.grid_element.album-card:hover .album-card-img {
    transform: scale(1.04);
}

/* ═══════════════════════════════
   GRADIENT OVERLAY
═══════════════════════════════ */
.album-card-overlay {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 2;
    background: linear-gradient(
        to bottom,
        rgba(0,0,0,0.2) 0%,
        rgba(0,0,0,0.05) 35%,
        rgba(0,0,0,0.7) 100%
    );
    border-radius: 12px;
}

/* ═══════════════════════════════
   DATE — top left
═══════════════════════════════ */
.album-card-date {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 3;
    font-size: 11px;
    color: rgba(255,255,255,0.9);
    background: rgba(0,0,0,0.4);
    padding: 4px 10px;
    border-radius: 20px;
    backdrop-filter: blur(4px);
    line-height: 1.4;
}
.album-card-date::before {
    font-family: 'bootstrap-icons';
    content: '\F1F6';
    margin-right: 5px;
    font-size: 11px;
    vertical-align: middle;
}

/* ═══════════════════════════════
   TOP RIGHT — like button + photo count
═══════════════════════════════ */
.album-card-topright {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ═══════════════════════════════
   LIKE BUTTON — top right
═══════════════════════════════ */
.album-card-topright button.favorite {
    background: rgba(255,255,255,0.2) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255,255,255,0.35) !important;
    box-shadow: none !important;
    backdrop-filter: blur(4px);
}
.album-card-topright button.favorite span {
    color: #ffffff !important;
}
.album-card-topright button.favoriteActive {
    background: #fff4ed !important;
    color: rgb(245, 166, 35) !important;
    border: none !important;
}
.album-card-topright button.favoriteActive span {
    color: rgb(245, 166, 35) !important;
}
.album-card-topright button.favorite #bookmark-content {
    display: none !important;
}
.album-card-topright button.favorite::after {
    content: 'LIKE';
    font-size: 11px;
    font-weight: 700;
}
.album-card-topright button.favorite.favoriteActive::after {
    content: 'LIKED';
}

/* ═══════════════════════════════
   PHOTO COUNT — beside like button
═══════════════════════════════ */
.album-card-count {
    font-size: 13px;
    font-weight: 700;
    color: #ffffff;
    background: rgba(0,0,0,0.45);
    padding: 4px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(4px);
    line-height: 1.4;
    white-space: nowrap;
}

/* ═══════════════════════════════
   CONTENT — bottom overlay
═══════════════════════════════ */
.album-card-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 3;
    padding: 16px 16px 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.album-card-title {
    font-family: 'Libre Baskerville';
    font-size: 22px;
    font-weight: 700;
    color: #ffffff !important;
    text-decoration: none !important;
    display: block;
    line-height: 1.3;
    text-shadow: 0 1px 6px rgba(0,0,0,0.6);
}
.album-card-title:hover {
    text-decoration: none !important;
    color: #ffffff !important;
}

/* ═══════════════════════════════
   VIEW MORE — below title, left aligned
═══════════════════════════════ */
.album-card-readmore {
    display: inline-block;
    align-self: flex-start;
    font-size: 12px;
    font-weight: 700;
    color: #ffffff !important;
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.35);
    padding: 6px 16px;
    text-decoration: none !important;
    white-space: nowrap;
    backdrop-filter: blur(4px);
}
.album-card-readmore:hover {
    background: rgba(255,255,255,0.35);
    color: #ffffff !important;
}

/* ═══════════════════════════════
   FOOTER — comment + rating
═══════════════════════════════ */
.album-card-footer {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.album-card-footer a.search-comment-count,
.album-card-footer a.search-comment-count.favoriteActive,
.album-card-footer a.search-comment-count.favoriteActive i,
.album-card-footer a.search-comment-count.favoriteActive span {
    background: rgba(255,255,255,0.2) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255,255,255,0.35) !important;
    box-shadow: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 5px 8px !important;
    line-height: 1 !important;
    backdrop-filter: blur(4px);
}
.album-card-footer a.search-comment-count span.bookmark-number,
.album-card-footer a.search-comment-count.favoriteActive span.bookmark-number {
    background: rgba(255,255,255,0.4) !important;
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
}

/* ═══════════════════════════════
   GRID EQUAL HEIGHT
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
.grid-container.row > .search_result .grid_element.album-card {
    flex: 1;
}

/* ═══════════════════════════════
   MOBILE
═══════════════════════════════ */
@media (max-width: 767px) {

    /* Remove the text completely on mobile */
    .album-card-topright button.favorite::after {
        content: "" !important;
        display: none !important;
    }
    .grid_element.album-card {
        min-height: 240px;
    }
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

<style>
.search_result.list-card-active {
    background: #333;
    border: none;
    border-radius: 12px;
    padding: 0 !important;
    margin-bottom: 16px;
    overflow: hidden;
    box-shadow: 0 8px 8px -4px #0000001a, 0 4px 4px -4px #0000001a;
    width: 100% !important;
    display: block !important;
}
.search_result.list-card-active .grid_element.album-card {
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    border-radius: 12px !important;
    min-height: 220px !important;
    width: 100% !important;
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