<?php
foreach ($_GET as $gkey => $gvalue) {
    if (!is_array($gvalue)) {
        $_GET[$gkey] = stripslashes($gvalue);
    }
}
?>
<div class="col-md-7 text-right sm-text-center header-right-container nolpad xs-hpad">
    <?php
    if ($_COOKIE['userid'] > 0) {
        echo widget('Bootstrap Theme - Header - Member Links', '', $w['website_id'], $w);
    } else { ?>
        <ul class="mini-nav <?php if ($wa['header_menu_alignment'] == 1) { ?>mini-nav-flex-spaced<?php } ?> nobmargin list-inline xs-nopad xs-tmargin <?php if ($wa['custom_159'] != 4) { ?>tpad<?php } ?>">
            <?php
            if ($wa['custom_148'] == "1") {
                $addOnGoogleTranslate = getAddOnInfo("google_translate", "b20c91eaa0e30a1322cade0a40001dc8");
                if (isset($addOnGoogleTranslate['status']) && $addOnGoogleTranslate['status'] === 'success') {
                    echo widget($addOnGoogleTranslate['widget'], "", $w['website_id'], $w);
                }
            }
			if ($wa['header_public_menu'] == "hidden_admin_menu") {
				/// Show Nothing
			} else if($wa['header_public_menu'] != ''){
				echo menuArray($wa['header_public_menu'], 0, $w);	
			} else {
				echo menuArray("header_mini_nav", 0, $w);	
			} ?>
        </ul>
        <?php
    } ?>
    <div class="clearfix"></div>
    <?php
    if (($wa['custom_159'] != 0 || $wa['custom_159'] == "") && !stristr($_SERVER['HTTP_HOST'],"securemypayment.com")) {
        if ($wa['custom_159'] == 4) { ?>
            [widget=Bootstrap Theme - Banner - 320_50]
            <?php
        } elseif ($wa['custom_159'] == 14) { ?>
            [widget=Bootstrap Theme - Social Media Links]
            <?php
        } else {
            $featurename = '';

            if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) {
                $featurename = 'search_results';
            }
            if ($wa['custom_159'] == 5) {
                $featurename = 'coupons';
            }
            if ($wa['custom_159'] == 6) {
                $featurename = 'events';
            }
            if ($wa['custom_159'] == 7) {
                $featurename = 'jobs';
            }
            if ($wa['custom_159'] == 8) {
                $featurename = 'products';
            }
            if ($wa['custom_159'] == 9) {
                $featurename = 'properties';
            }
            if ($wa['custom_159'] == 10) {
                $featurename = 'classifieds';
            }
            if ($wa['custom_159'] == 11) {
                $featurename = 'videos';
            }
            if ($wa['custom_159'] == 12) {
                $featurename = 'blog';
            }
            if ($wa['custom_159'] == 13) {
                $featurename = 'articles';
            }
            if ($wa['custom_159'] == 17) {
                $featurename = 'discussions';
                $autoSuggestName = 'discussion';
            }
            if($wa['custom_159'] == 15 || $wa['custom_159'] == 16){
                $featurename = bd_controller::list_seo_template()->getSeoFilename('globalSearch');
                $autoSuggestName = 'global';
            }
            ?>
            <form action="/<?php echo $featurename; ?>" name="frm1" class="form-inline website-search">
                <?php
                if ($wa['custom_159'] == 3 || $wa['custom_159'] == 2 || $wa['custom_159'] == 5 || $wa['custom_159'] == 6 || $wa['custom_159'] == 7 || $wa['custom_159'] == 8 || $wa['custom_159'] == 9 || $wa['custom_159'] == 10 || $wa['custom_159'] == 11 || $wa['custom_159'] == 12 || $wa['custom_159'] == 13 || $wa['custom_159'] == 15 || $wa['custom_159'] == 16|| $wa['custom_159'] == 17) { ?>
                    <div class="input-group input-group-sm bmargin sm-autosuggest">
                        <span class="input-group-addon hidden-md"><i class="fa fa-search"></i></span>
                        <input type="text"
                               placeholder="<?php if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) { echo $label['keyword_search_default'];
                               } else if($wa['custom_159'] == 15 || $wa['custom_159'] == 16){ ?> %%%global_search_placeholder%%% <?php }else{ ?>%%%search_by_keyword_label%%%<?php } ?>" value="<?php if ($_GET['q'] != '') {
                            echo htmlspecialchars($_GET['q']);
                        } ?>" name="q"
                               class="<?php if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 2) { ?>member<?php } else if ($wa['custom_159'] != 9) {
                                   if($wa['custom_159'] == 15 || $wa['custom_159'] == 16 || $wa['custom_159'] == 17){ $featurename = $autoSuggestName; } echo $featurename;
                               } else { ?>property<? } ?>_search form-control input-sm" autocomplete="off">
                    </div>
                    <?php
                }
                if ($wa['custom_159'] == 3 || $wa['custom_159'] == 1 || $wa['custom_159'] == 10  || $wa['custom_159'] == 16) { ?>
                    <div class="input-group input-group-sm bmargin">
                        <span class="input-group-addon hidden-md"><i class="fa fa-location-arrow"></i></span>
                        <input type="text" autocomplete="off" placeholder="<?= $label['location_search_default'] ?>"
                               value="<? if ($_GET['location_value'] != "") {
                                   echo htmlspecialchars($_GET['location_value']);
                               } ?>" id="location_google_maps_header" name="location_value"
                               class="googleSuggest googleLocation form-control">
                    </div>
                    <?php
                } ?>
                <input type="submit" value="%%%search_label%%%" class="btn btn-sm btn_search bmargin xs-btn-block bold">
            </form>
            <?php
        }
    } ?>
</div>



body input.tt-hint,body input.form-control.normal-autosuggest-input.tt-query {
	background-color: white !important;
}
.mini-nav:not(:has(li)) {
	display: none!important;
}
/* Parent Container - Main List */
.mini-nav li:has(ul) {
	position: relative;
	border-radius: 5px 5px 0 0;
}
.mini-nav > li:has(ul) {
	padding-left: 0;
	padding-right: 0;
	background: <?php echo $wa['custom_5']?>;
}
.mini-nav > li:has(ul) > a:not(.btn), .mini-nav > li:has(ul) > span {
	padding: 10px 15px;
	margin: 0!important;
}
.mini-nav > li:hover:has(ul), .mini-nav > li:hover:has(ul) {
	box-shadow: 0 -1px 1px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.25);
}
/* Parent Links */
.mini-nav > li > a:not(.btn), .mini-nav > li > span:not(.btn) {
	display:inline-block;
	border-radius: 5px 5px 0 0;
}
.mini-nav > li:hover > a:not(.btn), .mini-nav > li:hover > span {
	background: <?php echo $wa['custom_5']?>;
	display: inline-block;
	position: relative;
	z-index: 1000;
}
/* First Level Dropdowns */
.mini-nav li ul {
	background: <?php echo $wa['custom_5']?>;
	box-shadow: 0 0px 1px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.25), 0 0 5px 5px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.05);
	text-align: left;
	display: none;
	border-radius: 0 5px 5px 5px;
	list-style: none;
	padding: 10px;
	position: absolute;
	white-space: nowrap;
	min-width: 100%;
	width: auto;
	top: 100%;
	left: 0;
	z-index: 999;
	margin-top: -1px;
}
.mini-nav li:hover > ul {
	display: block;
}
/* First Level Dropdown Items */
.mini-nav li ul li {
	position: relative;
	display: block;
	width: 100%;
}
.mini-nav li ul li a, .mini-nav li ul li span {
	font-size: <?php echo $wa['header_font_link_size'] * 0.925; ?>px !important;
	display: block;
	font-weight: 400;
	padding: 10px;
	border-radius: 5px;
	text-decoration: none;
	width: 100%;
}
.mini-nav li ul li a:hover, .mini-nav li ul li span:hover {
	box-shadow: 0 0 0 25px rgba(<?php echo cleanRGBA($wa['custom_6']); ?>,0.1) inset;
}
/* Second Level Dropdowns */
.mini-nav li ul li ul {
	display: none;
	position: absolute;
	top: 0;
	left: 100%;
	margin: 0;
	border-radius: 5px;
}
.mini-nav li ul li:hover > ul {
	display: block;
}
/* Edge Positioning - Last Child Dropdowns */
.mini-nav > li:last-child ul {
	right: 0;
	left: auto;
	border-radius: 5px 0 5px 5px;
}
.mini-nav > li:last-child ul li ul {
	right: 100%;
	left: auto;
}
/* Flex Spacing Utility */
.mini-nav.mini-nav-flex-spaced {
	display: flex;
	justify-content: space-between;
	align-items: center;
	flex-wrap: wrap;
	row-gap: 0;
}
.mini-nav.mini-nav-flex-spaced > li {
	flex: none;
	padding: 0;
	margin: 2px 0;
	order: 2;
}
.mini-nav.mini-nav-flex-spaced > li.header-member-account-links,
.mini-nav:has(li ul) > li.header-member-account-links {
	order: 1;
	margin-left: auto;
	width: 100%;
	margin-bottom: 10px;
}
.logged-in-member-header .mini-nav.mini-nav-flex-spaced.list-inline > li {
	line-height: 1em;
	min-height: 0;
}
@media (min-width: 992px) {
	.header-main-row {
		display: flex;
		align-items: center;
		flex-wrap: wrap;
	}
}
@media (max-width: 991px) {
	.mini-nav.mini-nav-flex-spaced {
		justify-content: center;
	}
	.mini-nav.mini-nav-flex-spaced > li {
		margin-left: 5px !important;
		margin-right: 5px !important;
	}
}

/* =========================
   CUSTOM CODE (ADDITIONAL CSS)
========================= */
#sideMenu #sideMenuList > li > div > a:hover,
#sideMenu .submenu a:hover {
  color: #3d7da3 !important; 
  transition: color 0.2s ease;
}

@media (max-width: 991px) {
 
  button.navbar-toggle.main_menu {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
      padding: 0;
	  margin: 8px 0 8px 8px;
      width: 45px;
      height: 45px;
      border-radius: 50% !important;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      z-index: 9999; /* on top */
  }

  /* Hamburger bars */
  button.navbar-toggle.main_menu i.fa-bars,
  button.navbar-toggle.main_menu i.fa-bars::before,
  button.navbar-toggle.main_menu i.fa-bars::after {
      content: "";
      display: block;
      width: 28px;
      height: 3px;
      background-color: #000;
      border-radius: 1.3px;
      transition: all 0.3s ease;
      position: absolute;
  }

  button.navbar-toggle.main_menu i.fa-bars::before {
      top: -8px;
  }
a
  button.navbar-toggle.main_menu i.fa-bars::after {
      bottom: -8px;
  }

}

/* Hide on larger screens */
@media (min-width: 991px) {
  button.navbar-toggle.main_menu {
      display: none !important;
  }
        .mini-nav > li {
        padding-bottom: 20px !important;
    }
}

/* Remove reserved navbar space on mobile and make header sticky */
@media (max-width: 991px) {

  /* Hide navbar completely */
  .navbar.navbar-default.lockedonscroll {
    display: none !important;
  }

  body {
    padding-top: 120px !important;
  }

  /* Make header sticky at the top */
  header,
  .site-header,
  .header,
  .header-wrapper {
    position: fixed !important;  /* makes it stick */
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;               /* stay above content */
    margin-top: 0 !important;

  }

  /* Push page content down so it doesn’t hide under the sticky header */
  main,
  .main-content,
  .page-content,
  .content-wrapper {
<?php if ($_COOKIE['userid'] > 0) { ?>
        padding-top: 130px !important;
    <?php } else { ?>
        padding-top: 190px !important;
    <?php } ?>
  }
    #link336,
    #link337 {
        display: none !important;
    }

    /* Make the flex container align items slightly left */
    .mini-nav {
        justify-content: flex-start !important;
        gap: 10px !important; 
        padding-left: 0 !important; 
 
  }

    /* Remove extra left margin/padding from the visible buttons */
    .mini-nav > li {
        margin-left: 0 !important;
        padding-left: 0 !important;
        padding-bottom: 20px !important;
    }

}

