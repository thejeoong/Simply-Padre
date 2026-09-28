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
	[widget=Bootstrap Theme - Detail Page - Schema Markup - Community Article]
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
				<span class="post-card-date">
					<?php 
					if ($post['post_start_date'] != "") { 
						echo transformDate($post['post_start_date'],"QB");
					} else if ($post['post_live_date'] != "") { 
						echo transformDate($post['post_live_date'],"QB"); 
					} ?>
				</span>
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
				<a class="post-card-readmore" title="<?php echo $post['post_title']; ?>" href="/<?php echo $post['post_filename']; ?>">
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

<style>


/* ═══════════════════════════════
   GRID VIEW (default — image top, content bottom)
═══════════════════════════════ */
.grid_element {
    display: flex;
    flex-direction: column;
    overflow: hidden;
    height: 100%;
    padding: 0 !important;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.10);
}
.grid_element .post-card-image {
    width: 100%;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
    flex-shrink: 0;
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
    width: 240px;
    min-width: 240px;
    border-radius: 8px 0 0 8px !important;
    overflow: hidden;
    flex-shrink: 0;
}
.list-card-active .post-card-img {
    width: 100%;
    height: 100% !important;
    min-height: 160px;
    object-fit: cover;
    display: block;
}
.list-card-active .post-card-body {
    border-radius: 0 8px 8px 0;
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
.post-card-title {
    font-family: 'Playfair Display', Georgia, serif;
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
    border-radius: 20px;
    text-decoration: none;
    white-space: nowrap;
}
.post-card-readmore:hover { background: #d0e8f7; }
.post-card-author {
    font-size: 12px;
    color: #888;
}
	
.list-card-active .post-card-title {
    font-size: 24px;
}
	
.post-card-footer .favorite,
.post-card-footer .favorite * {
    background: #f9f3e8 !important;
    color: #666666 !important;
    border-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
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
        border-radius: 8px 8px 0 0 !important;
    }
    .list-card-active .post-card-img {
        width: 100% !important;
        height: 200px !important;
        object-fit: cover;
    }
    .list-card-active .post-card-body {
        border-radius: 0 0 8px 8px !important;
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
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 0 !important;
    margin-bottom: 16px;
    overflow: hidden;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.10);
}
.search_result.list-card-active .grid_element {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    padding: 0 !important;
    border-radius: 0 !important;
}

/* Remove hr line between list cards */
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