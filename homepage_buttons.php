<?php
// Logic to calculate how many columns to fill the buttons
$buttonCount = 0;
$buttonsArray = array();
for ($i=1; $i <= 3; $i++) { 
    $textVariable = "hero_button_text_".$i;
    $linkVariable = "hero_button_url_".$i;
    $buttonClassVariable = "hero_button_class_".$i;
    $buttonStyleVariable = "hero_button_style_".$i;
    if($wa[$textVariable] != '' && $wa[$linkVariable] != ''){
        $buttonCount++;
        $buttonsArray[$i]['text'] = $wa[$textVariable];
        $buttonsArray[$i]['url'] = $wa[$linkVariable];
        $buttonsArray[$i]['class'] = $wa[$buttonClassVariable];
        // Add btn-outline if style is set to outline
        if($wa[$buttonStyleVariable] == 'outline'){
            $buttonsArray[$i]['class'] .= ' btn-outline';
        }
    }
}

switch ($buttonCount) {
    case '1':
        $columnWidth = 'col-md-8 col-md-offset-2';
        break;
    case '2':
        $columnWidth = 'col-md-6';
        break;
    case '3':
        $columnWidth = 'col-md-4';
        break;
    
    default:
        
        break;
}
?>

<div class="col-lg-12 search_box fpad img-rounded center-block text-center hero-message-with-link">
    <?php if ($wa['custom_130'] != ''){ ?>
		<h1 class="sm-text-center">
			<?php echo replaceChars($w,$wa['custom_130']);?>
		</h1>
	<?php } ?>
	<div class="clearfix"></div>
	<?php if ($wa['custom_131'] != ''){ ?>
		<h2 class="bpad sm-text-center">
			<?php echo replaceChars($w,$wa['custom_131']);?>
		</h2>
	<?php } ?>
	<div class="clearfix"></div>
	<div class="homepage_button_links row vmargin">
        <?php
        // Rendering the buttons
        foreach ($buttonsArray as $heroButton) { ?>
            <div class="<?php echo $columnWidth?>">
            	<a href="<?php echo $heroButton['url']?>" class="btn <?php echo $heroButton['class'] ?> btn-xl btn-block tmargin">  <?php echo $heroButton['text']?> </a>
            </div>
        <?php } ?>
		<div class="clearfix"></div>
	</div>
</div>

.search_box h2 {
color: <?php echo $wa['custom_38']?>;
display: block;
float: none!important;
}
.search_box{
padding: 30px;
}

.homepage_button_links .btn-outline {
    background-color: rgba(255, 255, 255, 0.15) !important;
    border: 2px solid rgba(255, 255, 255, 0.45) !important;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}

.homepage_button_links .btn-outline:hover {
    background-color: rgba(255, 255, 255, 0.25) !important;
    border-color: currentColor !important;
}

.homepage_button_links .btn:not(.btn-outline) {
    border: 2px solid transparent !important;
}

/* Mobile buttons */
@media (max-width: 767px) {
    .homepage_button_links .btn {
        padding: 10px 20px !important;  /* height and width */
        font-size: 18px !important;     /* text size */
    }
}

/* Desktop buttons */
@media (min-width: 768px) {
    .homepage_button_links .btn {
        padding: 12px 20px !important;   /* slightly smaller on desktop */
        font-size: 18px !important;     /* slightly smaller text */
    }
}