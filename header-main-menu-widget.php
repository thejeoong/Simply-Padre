<?php $hasAnnouncement = !empty($page['announcement_bar']) || !empty($wa['announcement_bar']); ?>

<?php if ($_COOKIE['userid'] > 0 && $wa['members_main_menu'] != '') {
	$selectedMenu = subscription_types_controller::getMemberMenu($_COOKIE['userid'],$wa['members_main_menu']);
} else if ($wa['public_main_menu'] != '') { 
	$selectedMenu = $wa['public_main_menu'];
} else {
	$selectedMenu = 'main_menu';
}

// Set up mobile menu logic
$selectedMobileMenu = $selectedMenu; 
if ($wa['custom_296'] == 2) {
	if ($_COOKIE['userid'] > 0 && $wa['header_logged_in_menu'] != '') {
		$selectedMobileMenu = subscription_types_controller::getMemberMenu($_COOKIE['userid'],$wa['header_logged_in_menu']);
	} else if ($wa['header_public_menu'] != '') { 
		$selectedMobileMenu = $wa['header_public_menu'];
	}
}
?>
<div class="mobile-main-menu">
    <div class="mobile-menu-header">
        <a href="<?php echo brilliantDirectories::getWebsiteURL();?>" class="mobile-menu-logo">
            <?php 
            if (!empty($w['website_logo'])) {
                list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $w['website_logo']);
            ?>
                <img <?php echo $attr; ?> src="<?php echo trim($w['website_logo']);?>" alt="<?php echo $w['website_name'];?>">
            <?php 
            } else if (!empty($w['favicon'])) {
                list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $w['favicon']);
            ?>
                <img <?php echo $attr; ?> src="<?php echo trim($w['favicon']);?>" alt="<?php echo $w['website_name'];?>">
            <?php } ?>
        </a>
        <span class="mobile-menu-close">
            <i class="bi bi-x" style="font-size:28px; cursor:pointer;"></i>
        </span>
    </div>
	[widget=Bootstrap Theme - Search Module - Keyword Search - Sidebar]
    <ul class="sidebar-nav">
        <?php 
        if ($wa['custom_296'] == 2 && isset($selectedMobileMenu)) {
            echo menuArray($selectedMobileMenu,0,$w);
        } else {
            echo menuArray($selectedMenu,0,$w);
        }
        ?>
    </ul>

<div class="mobile-menu-footer">
	<a href="/join-community" class="mobile-menu-join-btn">Join Today »</a>
    <a href="/login" class="mobile-menu-login-btn">Login <i class="bi bi-person-fill"></i></a>
	<a href="/activate-your-business" class="mobile-menu-activate-btn">Activate/List Your Business »</a>
    <p class="mobile-menu-follow-text">Follow Us</p>
    <div class="mobile-menu-social">
        [widget=Bootstrap Theme - Social Media Links]
    </div>
</div>
</div>

<nav class="navbar navbar-default <?php echo $wa['custom_152'];?>">
	<div class="container container-fluid">

		<div class="navbar-header">
			<?php if (($w['website_logo']!='' && $wa['custom_295'] == '2') || ($w['favicon']!='' && $wa['custom_295'] == '3')) { ?>
			<div class="mobile_website_logo">
				<a href="<?php echo brilliantDirectories::getWebsiteURL();?>" title="<?php echo $w['website_name'];?>" class="visible-xs">
					<?php if ($w['website_logo']!='' && $wa['custom_295'] == '2') { 
	list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $w['website_logo']);
					?>
					<img <?php echo $attr; ?> src="<?php echo trim($w['website_logo']);?>" alt="<?php echo $w['website_name'];?>">
					<?php } else { 
	list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $w['favicon']);
					?>
					<img <?php echo $attr; ?> src="<?php echo trim($w['favicon']);?>" alt="<?php echo $w['website_name'];?>">
					<?php } ?>
				</a>
			</div>
			<?php } ?>
			<button type="button" class="navbar-toggle collapsed main_menu" data-toggle="collapse" aria-label="main_menu">
				<?php if ($label['mobile_main_menu_icon'] != "") {?>
				%%%mobile_main_menu_icon%%%
				<?php } else { ?>
				<i class="fa fa-bars" aria-hidden="true"></i>
				<?php } ?>
			</button>

			<?php if ($wa['custom_297'] == "2" && $wa['custom_159'] != "0" && $wa['custom_159'] != "4" && $wa['custom_159'] != "14") { ?>
			<button type="button" id="compact-mobile-search" class="navbar-toggle compact-mobile-search hidden-md" style="margin-right: 5px;" aria-label="Search">
				%%%header_search_compact_view%%%
			</button>
			<?php } ?>

			<?php
if ($_COOKIE['userid'] > 0) { ?>
			<button type="button" id="member_sidebar_toggle" class="navbar-toggle collapsed pull-left user_sidebar hidden-lg hidden-md">
				<?php 
	$user_data = getUser($_COOKIE['userid'],$w);
	$pic = getUserPhoto($user_data['user_id'],$user_data['listing_type'],$w);
	if($pic['file'] != '' && $pic['file'] != $w['default_profile_image']){
		echo '<style>#member_sidebar_toggle{padding-left: 28px;}</style><img src="'.$pic['file'].'">';
		echo $label['mobile_profile_sidebar_icon'];
	} else{
		if ($label['mobile_profile_sidebar_icon'] != "") { ?>
				%%%mobile_profile_sidebar_icon%%%
				<?php } else { ?>
				<i class="fa fa-user" aria-hidden="true"></i>
				<?php } 
	} ?>
			</button>
			<?php } ?>

		</div>


		<div class="tablet-menu collapse navbar-collapse nopad" id="bs-main_menu">
			<ul class="tablet-menu-ul nav navbar-nav nav-justified">
				<?php echo menuArray($selectedMenu,0,$w);?>
			</ul>
		</div>
	</div>
</nav>
<?php if ($wa['custom_152'] == "navbar-fixed-top" && $wa['custom_44'] != "1" && $page['hide_header_links'] != "1") { ?>
<style>
	body {
		padding-top:50px;
	}
</style>
<?php }
if ($wa['custom_152'] == "navbar-fixed-bottom") { ?>
<style>
	body {
		margin-bottom:0;
	}
	@media only screen and (min-width: 1024px){
		.header ul.nav.navbar-nav li:hover > ul {
			position: absolute;
			bottom: 50px;
			border-radius: 3px 3px 0 0;
		}
	}
	@media only screen and (max-width: 991px){
		.header nav.navbar-default {
			top: inherit!important;
		}
	}
</style>
<?php }
if ($wa['public_main_menu'] == "hidden_admin_menu" && !$_COOKIE['userid']) { ?>
<style>
	@media only screen and (max-width:1100px){body .header{margin-top:0}
</style>
<?php } ?>


/* =========================
   MOBILE MAIN MENU (ALL SCREENS)
========================= */
.header,
.navbar-default,
nav.navbar {
    z-index: 99997 !important;
}

#menuOverlay {
    z-index: 99998 !important;
}

.mobile-main-menu {
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    right: -320px;
    width: 320px;
    height: 100%;
    background: #fff;
    z-index: 99999 !important;
    transition: right 0.2s ease-in-out;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    overscroll-behavior: none;
    -webkit-overflow-scrolling: auto;
}

/* Show menu when opened */
.mobile-main-menu.opened {
    right: 0 !important;
}

/* Mobile menu header (logo + close button) */
.mobile-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    flex-shrink: 0;
}

/* Mobile logo adjustments */
.mobile-menu-logo img {
    max-height: 80px;
    width: auto;
    margin-left: 5px;
    margin-top: 5px;
}

/* Close button aligned */
.mobile-menu-close {
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
}

/* Sidebar nav styling */
.mobile-main-menu ul.sidebar-nav {
    list-style: none;
    margin: 0;
    padding: 0 0 20px 0;
    font-size: 14px;
    flex-shrink: 0;
}

.mobile-main-menu .sidebar-nav > li {
    display: block;
    line-height: 20px;
    padding: 0 15px 0 20px;
}

/* Sidebar links */
.mobile-main-menu .sidebar-nav li a,
.mobile-main-menu .sidebar-nav li span {
    display: inline-block;
    text-decoration: none;
	font-family: 'Poppins';
    color: #20465d;
    width: calc(100% - 30px);
    padding: 12px 0;
    float: left;
    font-size: 16px;
}

/* Submenu hidden by default */
.mobile-main-menu .sidebar-nav li ul {
    height: 0;
    overflow: hidden;
    list-style: none;
    padding-left: 10px;
    transition: height 0.5s ease;
}

/* Submenu open */
.mobile-main-menu .sidebar-nav li.sub_open > ul {
    height: auto;
}

/* Submenu items padding */
.mobile-main-menu .sidebar-nav li ul li a,
.mobile-main-menu .sidebar-nav li ul li span {
    padding-left: 10px;
}

/* Chevron icons for items with children */
.mobile-main-menu .hasChildren i {
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-top: 12px;
    margin-right: 5px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    text-indent: -9999px;
    float: right;
	cursor: pointer;
}

/* Chevron down for collapsed menu */
.mobile-main-menu .hasChildren i.fa-plus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%233d7da3' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
}

/* Chevron up for expanded menu */
.mobile-main-menu .hasChildren i.fa-minus {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%233d7da3' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z'/%3E%3C/svg%3E");
}

/* Link hover and active color */
.mobile-main-menu .sidebar-nav li a:hover,
.mobile-main-menu .sidebar-nav li span:hover,
.mobile-main-menu .sidebar-nav li a:active,
.mobile-main-menu .sidebar-nav li span:active {
    color: #3d7da3 !important;
}

/* Hamburger button adjustments */
.main_menu, .user_sidebar, .compact-mobile-search {
    padding: 5px 8px;
    margin-right: 0;
    min-height: 33px;
    min-width: 38px; 
}

.main_menu i, .user_sidebar i {
    font-size: 16px;
}

/* Nav list items float none */
.navbar-nav li {
    float: none !important;
}

/* Navbar transition */
.navbar-default {
    transition: all 0.6s ease-in-out;
}

/* Transparent menu if needed */
.transparent_menu {
    background-color: rgba(<?php $theme_color = cleanRGBA($wa['custom_16']); echo $theme_color;?>,0.95)!important;
}

/* Hide old side menu completely */
#sideMenu {
    display: none !important;
}

/* Prevent scroll when menu open */
body.menu-open {
    overflow: hidden;
}

/* Mobile user sidebar image adjustments */
.user_sidebar > img {
    width: 32px;
    height: 32px;
    position: absolute;
    z-index: 10;
    border-radius: 100px;
    top: -1px;
    left: -10px;
    object-fit: cover;
    background: <?php echo $wa['custom_74']?>;
}

/* Hide redundant icon after user image */
#member_sidebar_toggle img + .fa {
    display: none;
}

/* Mobile menu footer */
.mobile-menu-footer {
    background: rgb(62, 126, 163);
    padding: 40px 20px 30px 20px;
    text-align: center;
    margin-top: 0;
    flex: 1;
}

.mobile-menu-join-text {
    color: #fff;
    font-size: 17px;
    margin: 0 0 8px 0;
    font-weight: 700;
}

.mobile-menu-join-desc {
    color: rgba(255,255,255,0.8);
    font-size: 13px;
    margin: 0 0 16px 0;
    line-height: 1.5;
}

/* ─── Compact Footer Buttons (Narrow & Uniform Width) ─── */
.mobile-menu-join-btn {
    display: block;
    background: #fd9e25;
    color: #fff !important;
    font-size: 14px;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 25px;
    text-decoration: none !important;
    width: 80% !important;             /* Force equal, narrower width */
    margin: 0 auto 10px auto !important; /* Center horizontally */
    float: none !important;
    transition: background 0.2s;
    box-sizing: border-box;
}

.mobile-menu-activate-btn {
    display: block;
    background: #fff;
    color: rgb(62, 126, 163) !important;
    font-size: 14px;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 25px;
    text-decoration: none !important;
    width: 80% !important;             /* Force equal, narrower width */
    margin: 0 auto 30px auto !important; /* Center horizontally */
    float: none !important;
    transition: background 0.2s;
    box-sizing: border-box;
}

.mobile-menu-login-btn {
    display: block;
    background: rgba(255, 255, 255, 0.25);
    color: #fff !important;
    font-size: 14px;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 25px;
	border: 1px solid #fff;
    text-decoration: none !important;
    width: 80% !important;             /* Force equal, narrower width */
    margin: 0 auto 10px auto !important; /* Center horizontally */
    float: none !important;
    transition: background 0.2s;
    box-sizing: border-box;
}
.mobile-menu-follow-text {
    color: #fff;
    font-size: 14px;
    margin: 15px 0 12px 0; /* Added slight separation from the tight buttons */
    font-weight: 600;
}

.mobile-menu-social * {
    margin: 0 !important;
    padding: 0 !important;
}

.mobile-menu-social a {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    width: auto !important;
    height: auto !important;
    margin: 0 6px !important;
    color: #fff !important;
    font-size: 18px;
    background: rgb(62, 126, 163) !important;
    background-color: rgb(62, 126, 163) !important;
    border: none !important;
    box-shadow: none !important;
}

.mobile-menu-social a i,
.mobile-menu-social a svg,
.mobile-menu-social a span {
    background: transparent !important;
    background-color: transparent !important;
}

.mobile-menu-social a:hover {
    opacity: 0.7;
}

/* Swipe hover effect on sidebar links */
.mobile-main-menu .sidebar-nav li {
    position: relative;
    overflow: hidden;
}

.mobile-main-menu .sidebar-nav li a,
.mobile-main-menu .sidebar-nav li span {
    position: relative;
    z-index: 1;
    transition: color 0.3s ease;
}

.mobile-main-menu .sidebar-nav li::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(61, 125, 163, 0.1);
    transition: left 0.3s ease;
    z-index: 0;
}

.mobile-main-menu .sidebar-nav li:hover::before,
.mobile-main-menu .sidebar-nav li.touch-swipe::before {
    left: 0;
}

/* 1. Reset: Desktop Submenu Base Text */
.header ul.nav.navbar-nav li ul li a,
.header ul.nav.navbar-nav li ul li span {
    color: #20465d !important;
	background: transparent !important;
    background-color: transparent !important;
	transition: color 0.3s ease !important;
    position: relative !important;
    z-index: 1 !important;
}

/* 2. Hover: Change to Blue when mouse is over it */
.header ul.nav.navbar-nav li ul li a:hover,
.header ul.nav.navbar-nav li ul li span:hover {
    box-shadow: none !important;
    background: transparent !important;
    background-color: transparent !important;
	color: rgb(62, 126, 163) !important;
}

/* 3. Clicked/Active: Change to Black ONLY when clicked or focused */
.header ul.nav.navbar-nav li ul li a:active,
.header ul.nav.navbar-nav li ul li a:focus,
.header ul.nav.navbar-nav li ul li span:active,
.header ul.nav.navbar-nav li ul li span:focus {
    color: #000000 !important;
    background-color: transparent !important;
}


/* Swipe container — but don't clip if this li has its own submenu */
.header ul.nav.navbar-nav li ul li:not(:has(> ul)) {
    position: relative !important;
    overflow: hidden !important;
}

.header ul.nav.navbar-nav li ul li:has(> ul) {
    position: relative !important;
    /* no overflow: hidden here, so the child ul can escape */
}

/* The Invisible Bridge */
.header ul.nav.navbar-nav li ul::after {
    content: "";
    position: absolute;
    top: -15px; 
    left: 0;
    right: 0;
    height: 20px; 
    background: transparent;
    z-index: -1;
}

/* Ensure the sub-menu container itself is positioned relatively */
.header ul.nav.navbar-nav li ul {
    position: absolute;
    display: block !important;
    transform: translateY(15px); 
    opacity: 0;
    transition: transform 0.3s ease-out, opacity 0.3s ease !important;
    pointer-events: none;
    visibility: hidden;
    font-weight: 400;
    border-radius: 10px !important; 
    margin-top: 5px; 
}

.header ul.nav.navbar-nav li:hover > ul {
    transform: translateY(0);
    opacity: 1;
    pointer-events: all;
    visibility: visible;
}

/* Swipe element - white on hover, invisible by default */
.header ul.nav.navbar-nav li ul li::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(61, 125, 163, 0.1);
    opacity: 0;
    transition: left 0.3s ease, opacity 0.6s ease;
    z-index: 0;
    pointer-events: none;
}

.header ul.nav.navbar-nav li ul li:hover::before {
    left: 0;
	color: rgb(62, 126, 163);
    opacity: 1;
    transition: left 0.3s ease, opacity 0.1s ease;
}

/* =========================
   FORCED MOBILE COLLAPSE
========================= */
@media (max-width: 1100px) {
    nav.navbar.navbar-default.lockedonscroll,
    nav.navbar.navbar-default.lockedonscroll .navbar-header,
    nav.navbar.navbar-default.lockedonscroll #bs-main_menu {
        display: none !important;
        height: 0 !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        position: absolute !important; 
        top: -9999px !important; 
    }

    #member_sidebar_toggle.user_sidebar {
        display: none !important;
    }
    
    body.user-session, body {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
	body:has(.announcement-bar) main, 
	body:has(.announcement-bar) .main-content,
	body:has(.announcement-bar) .page-content, 
	body:has(.announcement-bar) .content-wrapper { 
		padding-top: 0 !important; 
	} 
	body:not(:has(.announcement-bar)) main,
	body:not(:has(.announcement-bar)) .main-content,
	body:not(:has(.announcement-bar)) .page-content,
	body:not(:has(.announcement-bar)) .content-wrapper {
		padding-top: 0 !important;
	}
	body:has(.announcement-bar) .announcement-bar {
		margin-top: 0 !important; 
	}
}


<script>
	$(document).ready(function(){
		$('.navbar-header .navbar-toggle.main_menu').click(function(){
			$('.mobile-main-menu').toggleClass('opened');
		});
		$('.mobile-main-menu .sidebar-nav').find('li').each(function(){
			$(this).addClass('hasChildren');
			if ($(this).children('ul').length > 0){
				$(this).prepend('<i class="fa fa-plus" aria-hidden="true"></i>');
				$(this).find('a').after('<div class="clearfix"></div>');
				$(this).find('span').after('<div class="clearfix"></div>');
			}
			$(this).append('<div class="clearfix"></div>');
		});

		$('.mobile-main-menu .sidebar-nav li i').click(function(){
			if ($(this).parent().children('ul').length > 0){
				$(this).parent().toggleClass('sub_open');
			}
			if ($(this).hasClass('fa-plus')){
				$(this).switchClass('fa-plus','fa-minus');
			} else {
				$(this).switchClass('fa-minus','fa-plus');
			}
		});
	})

	if ($(window).width() > 740 && $(window).width() < 1100) {

		$(document).ready(function(){
			$('.tablet-menu .tablet-menu-ul').find('li').each(function(){

				if ($(this).children('ul').length > 0){
					$(this).prepend('<i class="fa fa-plus tablet-fa hidden-sm hidden-md hidden-lg" aria-hidden="true"></i>');
					$(this).find('a').after('<div class="clearfix"></div>');
					var this_link = $(this).children('a').text().replace(/[^\x00-\x7F]/g, "");;
					$(this).children('a').html(this_link);
					$(this).find('span').after('<div class="clearfix"></div>');
				}
				$(this).append('<div class="clearfix"></div>');
			});

			$('.tablet-menu .tablet-menu-ul li i').click(function(){

				if ($(this).parent().children('ul').length > 0){
					$(this).parent().toggleClass('sub_open');

					if ($(this).siblings( "ul" ).hasClass('tablet-block')){
						$(this).siblings( "ul" ).switchClass('tablet-block', 'tablet-none');
					} else {
						$(this).siblings( "ul" ).addClass( "tablet-block" );

						if ($(this).siblings( "ul" ).hasClass('tablet-none')){
							$(this).siblings( "ul" ).removeClass('tablet-none')
						}

						if ($(this).parent().siblings().children('ul').hasClass('tablet-block')) {
							$(this).parent().siblings().children('ul').switchClass('tablet-block', 'tablet-none');
							$(this).parent().siblings().children('i').switchClass('fa-minus','fa-plus');
						}

						if ($(this).parent().siblings().children('ul').children().children('ul').hasClass('tablet-block')) {
							$(this).parent().siblings().children('ul').children().children('ul').switchClass('tablet-block', 'tablet-none');
							$(this).parent().siblings().children('ul').children().children('i').switchClass('fa-minus','fa-plus');
						}

						if ($(this).siblings('ul').children('ul').children().children('ul').hasClass('tablet-block')) {
							$(this).siblings('ul').children('ul').children().children('ul').switchClass('tablet-block', 'tablet-none');
							$(this).siblings('ul').children('ul').children().children('i').switchClass('fa-minus','fa-plus');
						}

					}

				}

				if ($(this).hasClass('fa-plus')){
					$(this).switchClass('fa-plus','fa-minus');
				} else {
					$(this).switchClass('fa-minus','fa-plus');
				}
			});
		})
	}
	// Append unique ID attribute for mobile main menu links
	$('.mobile-main-menu a,.mobile-main-menu span').attr("id", function() { return $(this).attr("id") + "-mobile" });
</script>

<?php if ($wa['custom_297'] == "2") { ?>
	<script>
		$(document).ready(function() {
			$('#compact-mobile-search').on('click', function() {
				$('.header .website-search').stop(true, true).slideToggle(250);
			});
		});
	</script>
<?php } ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const mobileMenu = document.querySelector(".mobile-main-menu");

    // Move menu out of header stacking context
    document.body.appendChild(mobileMenu);

    function openMenu() {
        mobileMenu.classList.add("opened");
        document.body.classList.add("menu-open");

        let overlay = document.getElementById("menuOverlay");
        if (!overlay) {
            overlay = document.createElement("div");
            overlay.id = "menuOverlay";
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.45);
                z-index: 99998;
            `;
            document.body.appendChild(overlay);
            overlay.addEventListener("click", closeMenu);
        }
    }

    function closeMenu() {
        mobileMenu.classList.remove("opened");
        document.body.classList.remove("menu-open");
        const overlay = document.getElementById("menuOverlay");
        if (overlay) overlay.remove();
    }

    const hamburger = document.querySelector(".navbar-toggle.main_menu");
    if (hamburger) hamburger.addEventListener("click", openMenu);

    const closeBtnMoved = mobileMenu.querySelector(".mobile-menu-close");
    if (closeBtnMoved) closeBtnMoved.addEventListener("click", closeMenu);
});
</script>