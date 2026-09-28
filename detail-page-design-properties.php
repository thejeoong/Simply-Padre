<?php
echo widget("Bootstrap Theme - Detail Page - Schema Markup - Property Listing Post Type");
echo widget("Bootstrap Theme - Display - Posted By Snippet");
?>

<div id="post-content">

	<div class="row">

		<div class="col-md-12 vmargin">

			<h1 class="bold h2">
				<?php echo $group['group_name']; ?>
			</h1>

			<div class="clearfix"></div>

			<?php
			if ($group['post_location'] != "") { ?>
			<span class="inline-block">
				<i class="fa fa-map-marker text-danger"></i>
				<?php echo $group['post_location']; ?>
			</span>
			<?php
			} if ($group['property_beds']!="" || $group['property_baths']!="" || $group['property_sqr_foot']!="") { ?>
			<div class="pull-right xs-center-block xs-tmargin">
				<?php if ($group['property_beds']!="") { ?>
				<span class="inline-block rmargin">
					<b>%%%property_search_bedrooms%%%</b>
					<?php echo number_format($group['property_beds']); ?>
				</span>
				<?php } if ($group['property_baths']!="") { ?>
				<span class="inline-block rmargin">
					<b>%%%property_search_bathrooms%%%</b>
					<?php echo floatval(number_format($group['property_baths'],1)); ?>
				</span>
				<?php } if ($group['property_sqr_foot']!="" && $group['property_sqr_foot'] > 0) { ?>
				<span class="inline-block">
					<b>%%%property_search_sq%%%</b>
					<?php echo number_format($group['property_sqr_foot']); ?>
				</span>
				<?php
				} ?>
			</div>
			<?php
			} ?>

			<div class="clearfix"></div>

		</div>

		<?php
		if ($group['post_promo'] != "" || $group['post_availability'] != "" || $group['property_status'] != "") { ?>
			<div class="col-md-12">
				<div class="btn-sm fpad bg-primary nomargin no-radius-bottom">
					<?php
					if ($group['post_promo'] != "") { ?>
						<span class="h4 nomargin bold">
							<?php echo $group['post_promo'];  ?>
						</span>
					<?php } if ($group['property_status'] != "" || $group['property_type'] != "") { ?>
						<span class="pull-right badge">
							<?php echo $group['property_type']; ?> <?php echo $group['property_status']; ?>
						</span>
					<?php
					} ?>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
		<?php
		} ?>

	</div>

	<div class="clearfix"></div>

	<?php
	$photogroup = mysql(brilliantDirectories::getDatabaseConfiguration('database'),"SELECT
			*
		FROM
			`users_portfolio`
		WHERE
			`group_id` = '".$group['group_id']."'
		AND
			`data_id` = '".$group['data_id']."'
		AND
			`file` != ''
		ORDER BY
			`order` ASC");
	$total = mysql_num_rows($photogroup);

	if ($total > 0) { ?>
    <!-- Dynamically appends 'single-image-gallery' class if there is exactly 1 image -->
    <div id="gallery-1" class="royalSlider rsDefault <?php echo ($total == 1) ? 'single-image-gallery' : ''; ?>" style="width: 100%;">
        <?php
        while ($p = mysql_fetch_array($photogroup)) { 
			$p = getMetaData('users_portfolio', $p['photo_id'], $p, $w); ?>
            <a class="rsImg" data-rsbigimg="/<?php echo $w['photo_folder']; ?>/main/<?php echo $p['file']; ?>"  <?php if($p['video'] != "" && $dc['photo_gallery_videos'] == "1")  { ?>data-rsVideo="<?php echo $p['video'] ?>" <?php } ?>  href="/<?php echo $w['photo_folder']; ?>/main/<?php echo $p['file']; ?>">
                <?php if ($total > 1) { ?><img  class="rsTmb" src="/<?php echo $w['photo_folder']; ?>/display/<?php echo $p['file']; ?>"><?php } ?>
                <?php
                if ($p['desc'] != "" || $p['title'] != "") { ?>
                    <figure class="rsCaption">
                        <div class="captionContent">
                            <h4 class='nomargin'><?php echo $p['title']; ?></h4>
                            <?php echo limitWords($p['desc'],160); ?>
                        </div>
                    </figure>
                    <?php
                } ?>
            </a>
            <?php
        } ?>
    </div>
	<div class="clearfix"></div>
    <?php
	} ?>

	<div class="row">
		<?php
		if ($subscription['receive_messages'] != 1 && $user['active'] == 2) { ?>
		<div class="<?php if ($group['post_link']!="") { ?>col-sm-6<?php } else { ?>col-sm-12<?php } ?> vmargin">
			<a data-toggle="modal" data-target="#contactModal" class="btn btn-success btn-lg btn-block bold">
				%%%contact_member_label%%%
			</a>
			[widget=Bootstrap Theme - Contact Member Modal]
		</div>
		<?php }
		if ($group['post_link'] != "") { ?>
		<div class="<?php if ($subscription['receive_messages'] != 1 && $user['active'] == 2) { ?>col-sm-6<?php } else { ?>col-sm-12<?php } ?> vmargin">
			<a class="btn btn-primary btn-lg btn-block bold" title="<?php echo $group['group_name']; ?>" <?php if ($subscription['nofollow_links'] =="1") { ?>rel="nofollow"<?php } ?> href="<?php if (strpos($group['post_link'],'http') !== FALSE) { ?><?=$group['post_link']?><?php } else { ?>//<?=$group['post_link']?><?php } ?>" target="_blank">
				%%%more_details_membership_feature%%%
			</a>
		</div>
		<?php
		} ?>
		<div class="clearfix"></div>
	</div>

    <?php
    if ($group['post_tags'] != "" || $group['group_desc_clean'] != "") { ?>
        <div class="well">
            <?php
            // the post description
            if ($group['group_desc'] != "") {
                echo '<div class="the-post-description">' . $group['group_desc_clean'] . '<div class="clearfix"></div></div>';
            }
            if ($group['post_tags'] != "") {
                if ($group['group_desc_clean'] != "") { ?>
                    <hr class="tmargin">
                    <?php
                } ?>
                <div class="tags">
                    <?php
                    foreach (explode(",", $group['post_tags']) as $_ENV['tag']) {
                        echo widget("Bootstrap Theme - Tag Link") . " ";
                    } ?>
                </div>
                <?php
            } ?>
        </div>
        <?php
    } ?>

</div>

<style>
/* ========================================================
   1. PROPERTY SPEC LABELS & ICONS (Beds, Baths, SqFt)
   ======================================================== */
.pull-right.xs-center-block .inline-block i.fa {
    color: #3d7da3 !important;
    margin-right: 4px;
}
.pull-right.xs-center-block .inline-block,
.pull-right.xs-center-block .inline-block b {
    color: #555 !important;
}

/* ========================================================
   2. PROMO & STATUS HIGHLIGHT BAR
   ======================================================== */
#post-content .bg-primary {
    background-color: #3d7da3 !important;
    border-color: #3d7da3 !important;
    color: #ffffff !important;
}
#post-content .badge {
    background-color: rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.6);
}

/* ========================================================
   3. INLINE SLIDER SYSTEM (Applied only when NOT fullscreen)
   ======================================================== */
.royalSlider:not(.rsFullscreen), 
.royalSlider:not(.rsFullscreen) .rsOverflow, 
.royalSlider:not(.rsFullscreen) .rsSlide {
    height: 500px !important;
    border-top-left-radius: 0px !important;
    border-top-right-radius: 0px !important;
}

/* Dynamic patch: Adds breathing space below the gallery only when 1 image exists */
.royalSlider:not(.rsFullscreen).single-image-gallery {
    margin-bottom: 20px !important;
}

.royalSlider:not(.rsFullscreen) .rsImg {
    width: 100% !important;
    height: 100% !important;
    top: 0 !important;
    left: 0 !important;
    margin-top: 0 !important;
    margin-left: 0 !important;
    border-top-left-radius: 0px !important;
    border-top-right-radius: 0px !important;
    object-fit: cover !important;
    object-position: center !important; 
}
.royalSlider:not(.rsFullscreen) .rsCaption {
    position: absolute !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: auto !important;
    margin-left: -15px !important;
    margin-right: -15px !important;
    margin-bottom: -10px !important;
    padding: 0 !important;
    box-sizing: border-box !important;
    z-index: 10 !important;
}
.royalSlider:not(.rsFullscreen) .rsCaption .captionContent {
    width: 100% !important;
    padding: 12px 20px !important; 
    background: rgba(0, 0, 0, 0.75) !important; 
    box-sizing: border-box !important;
}
.rsOverflow {
    overflow: hidden !important;
}

/* ========================================================
   4. MOBILE STRUCTURAL OPTIMIZATIONS
   ======================================================== */
@media (max-width: 767px) {
    .royalSlider:not(.rsFullscreen), 
    .royalSlider:not(.rsFullscreen) .rsOverflow, 
    .royalSlider:not(.rsFullscreen) .rsSlide {
        height: 300px !important;
    }
    .royalSlider:not(.rsFullscreen).single-image-gallery {
        margin-bottom: 20px !important;
    }
}

/* ========================================================
   5. FULLSCREEN DISPLAY ENVIRONMENT OPTIMIZATIONS
   ======================================================== */
.rsFullscreen,
.rsFullscreen .rsWrap,
.rsFullscreen .rsOverflow,
.rsFullscreen .rsSlide {
    background: #000000 !important;
    border-radius: 0px !important;
    border-top-left-radius: 0px !important;
    border-top-right-radius: 0px !important;
    border-bottom-left-radius: 0px !important;
    border-bottom-right-radius: 0px !important;
}
.rsFullscreen .rsImg {
    width: auto !important;
    height: auto !important;
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: contain !important; 
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    margin: 0 !important;
}
.rsFullscreen .rsCaption {
    position: absolute !important;
    left: 0 !important;
    bottom: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    margin-bottom: 0 !important;
    width: 100% !important;
    z-index: 20 !important;
}
</style>