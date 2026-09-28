PAGE HEADER
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

PAGE LOOP

<?php 
$subscription = getSubscription($user['subscription_id'],$w);
$postFeaturedClass = ($post['sticky_post'] &&
    ($post['sticky_post_expiration_date'] == '0000-00-00' || $post['sticky_post_expiration_date'] >= date('Y-m-d')))
    ? ' featured-post featured-post-' . $dc['data_filename']
    : '';
?>
<div class="row-fluid search_result member-level-<?php echo $user['subscription_id'] . $postFeaturedClass; ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
    <meta itemprop="position" content="<?php echo ++$GLOBALS['search_result_position']; ?>">
    <meta itemprop="url" content="/<?php echo $post['post_filename']; ?>">
    <meta itemprop="name" content="<?php echo htmlspecialchars($post['post_title'], ENT_QUOTES, 'UTF-8'); ?>">
    [widget=Bootstrap Theme - Detail Page - Schema Markup - Website Blog Article]
    <div class="grid_element">

        <?php 
        if ($post['post_image'] != "") {
            $postImageFile = explode("/", str_replace("'", "", $post['post_image']));
            $postImageFileName = $postImageFile[3];
            $thumbnailImage = "/uploads/news-pictures-thumbnails/" . $postImageFileName;
            list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $thumbnailImage);
            if ($attr == "") { $attr = 'width="525" height="280"'; }
        ?>
        <div class="post-card-image">
            <a title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                <img <?php echo $attr; ?> class="post-card-img"
                    alt="<?php echo (!empty($post['post_alt']) ? $post['post_alt'] : $post['post_title']); ?>"
                    title="<?php echo $post['post_title']; ?>"
                    src="<?php echo $thumbnailImage; ?>"/>
            </a>
        </div>
        <?php } ?>

        <div class="post-card-body">

            <div class="post-card-meta">
                <?php if ($post['post_category'] != "") { ?>
                <span class="post-card-category">
                    <a title="<?php echo $dc['data_name']; ?> - <?php echo $post['post_category']; ?>" href="/<?php echo $dc['data_filename']; ?>?category[]=<?php echo urlencode($post['post_category']); ?>">
                        <?php echo $post['post_category']; ?>
                    </a>
                </span>
                <?php } ?>
            </div>

            <a class="post-card-title" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                <?php echo $post['post_title']; ?>
            </a>

            <?php if ($post['post_content'] != "") { ?>
            <p class="post-card-excerpt">
                <?php echo limitWords(preg_replace('#<[^>]+>#', ' ', $post['post_content']), 115); ?>...
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
                <a class="btn btn-primary post-card-readmore" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
                    %%%view_more_label%%%  →
                </a>
            </div>

            <?php if ($subscription['searchable'] != 0) { ?>
            <span class="post-card-author font-sm">
                %%%by_label%%%
                <a class="bold notranslate" href="/<?php echo $user['filename']; ?>" title="%%%posted_by_membership_features%%% <?php echo $user['full_name']; ?>">
                    <?php echo $user['full_name']; ?>
                </a>
            </span>
            <?php } ?>

        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
<hr>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap');

/* ═══════════════════════════════
   GRID VIEW (default — image top, content bottom)
═══════════════════════════════ */
.grid_element {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    height: 100%;
    padding: 0 !important;
    box-shadow: 0 8px 8px -4px #0000001a, 0 4px 4px -4px #0000001a;
	border-radius: 12px;
}
.grid_element .post-card-image {
    width: 100%;
    border-radius: 0;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
}
.grid_element .post-card-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}
.grid_element .post-card-body {
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex: 1;
}

/* ═══════════════════════════════
   LIST VIEW (image left, content right)
═══════════════════════════════ */
.list-card-active .grid_element {
    flex-direction: row !important;
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    border-radius: 0 !important;
}
.list-card-active .post-card-image {
    width: 320px;
    min-width: 320px;
    border-radius: 0 !important;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
    align-self: stretch;
}
.list-card-active .post-card-image a {
    display: block;
    height: 100%;
}
.list-card-active .post-card-img {
    width: 100%;
    height: 100% !important;
    min-height: 220px;
    object-fit: cover;
    display: block;
}
.list-card-active .post-card-body {
    border-radius: 0 12px 12px 0;
    flex: 1;
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
   SHARED CONTENT STYLES
═══════════════════════════════ */
.post-card-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 6px;
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
.post-card-title:hover { text-decoration: underline; }
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
/* DEFAULT (normal state) */
.post-card-readmore.btn {
    background: #e8f4fd !important;
    color: #2a7ab5 !important;
    border: none !important;
    box-shadow: none !important;
    text-decoration: none !important;
}

/* HOVER (KEEP YOUR EXISTING DESIGN) */
.post-card-readmore.btn:hover {
    background: #d0e8f7 !important;
    color: #2a7ab5 !important;
}

/* ACTIVE (when clicked) */
.post-card-readmore.btn:active {
    background: #c6e0f5 !important;
    color: #2a7ab5 !important;
}

/* AFTER CLICK / FOCUS (this is your problem state) */
.post-card-readmore.btn:focus,
.post-card-readmore.btn:focus-visible {
    background: #e8f4fd !important;
    color: #2a7ab5 !important;
    box-shadow: none !important;
    outline: none !important;
}

/* VISITED (prevents weird color shift after navigation starts) */
.post-card-readmore.btn:visited {
    color: #2a7ab5 !important;
}
.list-card-active .post-card-title {
    font-size: 24px;
}
.post-card-author {
    font-size: 12px !important;
	}
/* Like button — default state */
.post-card-footer button.favorite {
    background: #f9f3e8 !important;
    color: #666666 !important;
    border: none !important;
    box-shadow: none !important;
}
.post-card-footer button.favorite span {
    color: #666666 !important;
}

/* Like button — active/liked state */
.post-card-footer button.favoriteActive {
    background: #fff4ed !important;
    color: rgb(245, 166, 35) !important;
    border: none !important;
    box-shadow: none !important;
}
.post-card-footer button.favoriteActive span {
    color: rgb(245, 166, 35) !important;
}

/* Comment button — fix vertical alignment of icon and number */
.post-card-footer a.search-comment-count {
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

.post-card-footer a.search-comment-count i {
    vertical-align: middle !important;
    line-height: 1 !important;
    display: inline-flex !important;
    align-items: center !important;
}

/* Comment count badge — fix number position inside circle */
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

	/* Comment button — always stays gray regardless of favoriteActive */
.post-card-footer a.search-comment-count,
.post-card-footer a.search-comment-count.favoriteActive,
.post-card-footer a.search-comment-count.favoriteActive i,
.post-card-footer a.search-comment-count.favoriteActive span {
    background: #f9f3e8 !important;
    color: #666666 !important;
    border: none !important;
    box-shadow: none !important;
}
/* Like button — hide original text, show LIKE via CSS */
.post-card-footer button.favorite #bookmark-content {
    display: none !important;
}
.post-card-footer button.favorite::after {
    content: 'LIKE';
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Like button — show LIKED when active */
.post-card-footer button.favorite.favoriteActive::after {
    content: 'LIKED';
}
/* ═══════════════════════════════
   MOBILE — stack list view like grid
═══════════════════════════════ */
@media (max-width: 767px) {
    .list-card-active .grid_element {
        flex-direction: column !important;
    }
    .list-card-active .post-card-image {
        width: 100% !important;
        min-width: 100% !important;
        border-radius: 0 !important;
    }
    .list-card-active .post-card-img {
        width: 100% !important;
        height: 200px !important;
        object-fit: cover;
    }
    .list-card-active .post-card-body {
        border-radius: 0 0 12px 12px !important;
    }
}
</style>

PAGE FOOTER
</div>
<?php
$lazyLoadSearchReults = getAddOnInfo("search_results_lazy_load","8e5c29cd2531efea4db02ebc567b8442");
if(isset($lazyLoadSearchReults["status"]) && $lazyLoadSearchReults["status"] === "success" && ($dc["enableLazyLoad"] == 1 || $dc["enableLazyLoad"] == "")) {
    echo widget($lazyLoadSearchReults["widget"],"",$w[website_id],$w);
} ?>

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
        // Extract full score like "4/5"
        var score = text.match(/(\d+(?:\.\d+)?\/\d+)/);
        // Extract total like "1" from "(1 Total)"
        var total = text.match(/\((\d+)\s*Total\)/);
        if (score && total) {
            el.innerText = score[1] + ' (' + total[1] + ')';
        }
    });
});
</script>