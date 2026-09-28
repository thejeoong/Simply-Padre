<?php
global $oldFormName, $newFormName, $optionValue, $fieldName, $dataID, $showSlider;

$oldFormName = "property_listing";              // Name of the old form (used on sites before master forms update)
$newFormName = $dc['form_fields_name'];         // Name of new master form
$fieldName = "property_type";                   // Name of the field of the select
$dataID = $dc['data_id'];                       // ID of the feature

echo widget("Bootstrap Theme - Function - Post Category Dropdown Controller","",$w['website_id'],$w);
echo widget("Bootstrap Theme - Function - Post Category Price Slider Check","",$w['website_id'],$w);
?>
<div class="module">
    <h3>%%%search_dc_h1%%%</h3>

    <form class="form website-search" action="/<?php echo $dc['data_filename']; ?>" method="get">
        <div class="input-group md-autosuggest bmargin">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control md-autosuggest-input property_search" id="keyword" placeholder="%%%home_search_keyword%%%" name="q"
                   value="<?php echo stripslashes($_GET['q']);?>">
        </div>
        <div class="input-group bmargin location-search-field">
            <span class="input-group-addon"><i class="fa fa-globe"></i></span>
            <input type="text" autocomplete="off" class="form-control  googleSuggest googleLocation" id="location_google_maps_homepage"
                   placeholder="%%%location_search_default%%%" name="location_value"
                   value='<?php echo $_GET['location_value']; ?>'>
        </div>
        <?php 
        $connectionType             = WEBSITE_DB;
        $formModel                  = new form($connectionType);
        $customPropertyFormExist    = $formModel->get($dc['form_fields_name'], 'form_name');

        if($customPropertyFormExist !== false){
            $formModel->loadProperties($customPropertyFormExist);
        }

        if($formModel->isDisable() === true || $customPropertyFormExist === false){
            $connectionType = MAIN_DB;
        }

        $formFieldsModel    = new form_fields($connectionType);
        $status_where       = array(
            array('value' => $dc['form_fields_name'] , 'column' => 'form_name', 'logic' => '='),
            array('value' => 'property_status' , 'column' => 'field_name', 'logic' => '=')
        );
        $propertyStatusInfo     = $formFieldsModel->get($status_where);
        $propertyStatusArray    = explode(",",$propertyStatusInfo->field_options);

        if ($propertyStatusInfo !== false && $customPropertyFormExist !== false) { ?>
            <div class="form-group">
                <select name="property_status" id="property_status" class="selectpicker form-control">
                    <option value="" <?php if ($_GET['property_status'] == "") {
                        echo 'selected="selected"';
                    }?>>%%%property_all_statuses%%%
                    </option>
                    <?php 
                        foreach($propertyStatusArray as $status){ 
                            $value  = $status;
                            $text   = $status;
                            
                            if(strpos($status,"=>")){
                                $statusExploded = explode("=>",$status);
                                $value  = $statusExploded[0];
                                $text   = $statusExploded[1];
                            }
                    ?>
                            <option value="<?php echo $value?>" <?php if ($_GET['property_status'] == $value) { echo 'selected="selected"'; }?>><?php echo $text?></option>
                    <?php } ?>
                </select>
            </div>
        <?php } else if ($customPropertyFormExist === false) { ?>
            <div class="form-group">
                <select name="property_status" id="property_status" class="selectpicker form-control">
                    <option value="" <?php if ($_GET['property_status'] == "") {
                        echo 'selected="selected"';
                    }?>>%%%property_all_statuses%%%
                    </option>
                    <option value="For Sale" <?php if ($_GET['property_status'] == "For Sale") {
                        echo 'selected="selected"';
                    }?>>%%%property_search_sale%%%
                    </option>
                    <option value="For Rent" <?php if ($_GET['property_status'] == "For Rent") {
                        echo 'selected="selected"';
                    }?>>%%%property_search_rent%%%
                    </option>
                    <option value="Rented" <?php if ($_GET['property_status'] == "Rented") {
                        echo 'selected="selected"';
                    }?>>%%%property_search_rented%%%
                    </option>
                    <option value="Sold" <?php if ($_GET['property_status'] == "Sold") {
                        echo 'selected="selected"';
                    }?>>%%%property_search_sold%%%
                    </option>
                </select>
            </div>
        <?php } ?>
        <?php 
            $propertyTypeWhere = array(
                array('value' => $dc['form_fields_name'] , 'column' => 'form_name', 'logic' => '='),
                array('value' => 'property_type' , 'column' => 'field_name', 'logic' => '=')
            );
            $propertyTypeInfo = $formFieldsModel->get($propertyTypeWhere);
            $propertyTypeArray = explode(",",$propertyTypeInfo->field_options);
        ?>
        <?php if (!empty($propertyTypeArray)){ ?>
        <div class="form-group">
            <select name="property_type[]" multiple data-selected-text-format="count>2" id="type_property" class="selectpicker form-control"
                    title="%%%search_property_type%%%">
                <?php
                foreach ($propertyTypeArray as $key) {
                    if (preg_match('/=>/',$key)) {
                        $key = explode('=>', $key);
                        $key[0] = trim($key[0]);
                        $key[1] = trim($key[1]);
                        if (in_array($key[0], $_GET['property_type'])) {
                            echo '<option value="' .$key[0] . '" selected>' . $key[1] . '</option>';
                        } else {
                            echo '<option value="' . $key[0] . '">' . $key[1] . '</option>';
                        }
                    } else {
                        if (in_array($key, $_GET['property_type'])) {
                            echo '<option value="' . trim($key) . '" selected>' . $key . '</option>';
                        } else {
                            echo '<option value="' . trim($key) . '">' . $key . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </div>
        <?php } ?>
        <?php 
            $bedrooms_where = array(
                array('value' => $dc['form_fields_name'] , 'column' => 'form_name', 'logic' => '='),
                array('value' => 'property_beds' , 'column' => 'field_name', 'logic' => '=')
            );
            $propertyBedsInfo = $formFieldsModel->get($bedrooms_where);
            $propertyBedsArray = explode(",",$propertyBedsInfo->field_options);
        ?>

        <?php if ($propertyBedsInfo !== false && $customPropertyFormExist !== false) { ?>
            <div class="form-group">
                <select name="bedrooms" id="bedrooms" class="selectpicker form-control">
                    <option value="" selected>%%%property_search_bedrooms%%%</option>
                        <?php foreach($propertyBedsArray as $beds){
                            if ($beds != ''){
                                $value  = $beds;
                                $text   = $beds;
                                
                                if(strpos($beds,"=>")){
                                    $bedsExploded = explode("=>",$beds);
                                    $value  = $bedsExploded[0];
                                    $text   = $bedsExploded[1];
                                }
                                echo '<option value="' . $value . '" ' . (($_GET['bedrooms'] == $value) ? 'selected="selected"' : "") . '>' . $text . '+ '; ?>%%%property_search_bedrooms%%%</option><?php 
                            }
                        } ?>
                </select>
            </div>
        <?php } else if ($customPropertyFormExist === false) { ?>
            <div class="form-group">
                <select name="bedrooms" id="bedrooms" class="selectpicker form-control">
                    <option value="" selected>%%%property_search_bedrooms%%%</option>
                        <option value="0">0+</option>
                        <?php
                        for ($value = 1; $value <= 21; $value++) {
                            echo '<option value="' . $value . '" ' . (($_GET['bedrooms'] == $value) ? 'selected="selected"' : "") . '>' . $value . '+ '; ?>%%%property_search_bedrooms%%%</option><?php 
                        } ?>
                </select>
            </div>
        <?php } ?> 
        <?php 
        $bathrooms_where = array(
            array('value' => $dc['form_fields_name'] , 'column' => 'form_name', 'logic' => '='),
            array('value' => 'property_baths' , 'column' => 'field_name', 'logic' => '=')
        );
        $propertyBathsInfo = $formFieldsModel->get($bathrooms_where);
        $propertyBathsArray = explode(",",$propertyBathsInfo->field_options);
        ?>
        <?php if ($propertyBathsInfo !== false && $customPropertyFormExist !== false) { ?>
            <div class="form-group">
                <select name="bathrooms" id="bathrooms" class="selectpicker form-control">
                    <option value="" selected>%%%property_search_bathrooms%%%</option>
                    <?php foreach($propertyBathsArray as $bathrooms){
                        if ($bathrooms != ''){
                            
                            $value  = $bathrooms;
                            $text   = $bathrooms;
                            
                            if(strpos($bathrooms,"=>")){
                                $bathroomsExploded = explode("=>",$bathrooms);
                                $value  = $bathroomsExploded[0];
                                $text   = $bathroomsExploded[1];
                            }

                            echo '<option value="' . $value . '" ' . (($_GET['bathrooms'] == $value) ? 'selected="selected"' : "") . '>' . $text . '+ '; ?>%%%property_search_bathrooms%%%</option><?php 
                        }
                    } ?>
                </select>
            </div>
        <?php } else if ($customPropertyFormExist === false) { ?>
            <div class="form-group">
                <select name="bathrooms" id="bathrooms" class="selectpicker form-control">
                    <option value="" selected>%%%property_search_bathrooms%%%</option>
                    <option value="0">0+</option>
                    <?php
                    for ($value = 1; $value <= 21; $value++) {
                        echo '<option value="' . $value . '" ' . (($_GET['bathrooms'] == $value) ? 'selected="selected"' : "") . '>' . $value . '+ '; ?>%%%property_search_bathrooms%%%</option><?php 
                    } ?>
                </select>
            </div>
        <?php } ?>
        <?php if($showSlider == true){ ?>            
            <div class="form-group hpad" title="%%%home_search_pricerange%%%">
                <div id="property_slider" class="property_slider"></div>
                <input type="hidden" name="price">
            </div>
        <?php } ?>
        <div class="form-group nobmargin">
            <button type="submit" class="btn btn-primary btn-block">%%%sidebar_module_search%%%</button>
        </div>
    </form>
</div>

<style> /* CSS CODE */


.form-group .price-range {
    background-color: <?=$wa['custom_33']?>;
    font-size: 14px;
    padding: 20px 15px 10px;
    box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.2);
    transition: 0.3s;
    color: <?=$wa['custom_39']?>;
    border-radius:4px;
}
.cleanAll{ cursor:pointer;}
.properties_form .row div div .bootstrap-select button {
    background-color: white;
}
/* ========================================================
   1. SEARCH BUTTON STYLE: Blue (#3d7da3)
   ======================================================== */
.ui-rangeSlider-label {
    background-color: #3d7da3 !important;
    color: #fff !important;
}

.ui-rangeSlider-bar {
    background: rgb(253, 158, 37) !important;
}
.ui-rangeSlider-container {
    background: rgba(0, 0, 0, .1) !important;
}
.module .website-search button[type="submit"].btn-primary,
.module button.btn-block {
    background-color: #3d7da3 !important;
    border-color: #3d7da3 !important;
    color: #ffffff !important;
    transition: background-color 0.2s ease-in-out;
}

</style>

<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jshashtable-2.1_src.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jquery.numberformatter-1.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/tmpl.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jquery.dependClass-0.1.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/draggable-0.1.min.js"></script>
<script type="text/javascript" src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/jQRangeSlider-min.js"></script>
<script src="<?php echo brilliantDirectories::cdnUrl();?>/directory/cdn/assets/bootstrap/js/numeral.min.js"></script>
<script>
var decimalDivider      = "<?php echo brilliantDirectories::getCurrencyDecimalDivider();?>";
var thousandsDivider    = "<?php echo brilliantDirectories::getCurrencyThousandsDivider();?>";

    $(document).ready(function () {
        <?php
        $feature_id = $dc['data_id']; // This has to be the form name of the feature.
        $slider_limits = array();
        $fresults = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT 
    `key`,
    `value` 
  FROM 
    `users_meta` as meta 
  LEFT JOIN 
    `data_categories` as categories
  ON 
    meta.database_id = categories.data_id
  WHERE 
    `data_id` = '" . $feature_id . "'
  AND 
    `key` LIKE 'slider%'");

        while ($f = mysql_fetch_assoc($fresults)) {
            $slider_limits[$f['key']] = $f['value'];
        };
        ?>
        $(".property_slider").rangeSlider({
            formatter: function (val) {
                let value       = Math.round(val * 5) / 5;
                value           = numeral(value).format('$0,0');
                amountSplit     = value.split('.');
                thousandAmount  = amountSplit[0].replace("$","<?php echo brilliantDirectories::getCurrencySymbol();?>")+"<?php echo brilliantDirectories::getCurrencySuffix();?>";
                
                return thousandAmount.toString();
            },
            defaultValues: {
                min: <?php if ($slider_limits['slider_min'] != "" && is_numeric($slider_limits['slider_min'])){
                    echo $slider_limits['slider_min'];
                } else {?>0<?php }?>,
                max: <?php if ($slider_limits['slider_max'] != "" && is_numeric($slider_limits['slider_max'])){
                    echo $slider_limits['slider_max'];
                } else {?>5000000<?php }?>},
            arrows: false,
            bounds: {
                min: <?php if ($slider_limits['slider_min'] != "" && is_numeric($slider_limits['slider_min'])){
                    echo $slider_limits['slider_min'];
                } else {?>0<?php }?>,
                max: <?php if ($slider_limits['slider_max'] != "" && is_numeric($slider_limits['slider_max'])){
                    echo $slider_limits['slider_max'];
                } else {?>5000000<?php }?>}
        });

        $(document).on("valuesChanging", ".property_slider", function (e, data) {
            let basicValues = $(this).rangeSlider("values");
            basicValues.min = Math.round(basicValues.min);
            basicValues.max = Math.round(basicValues.max);
            $('input[name="price"]').val(basicValues.min + ";" + basicValues.max);
        });

        <?php if ($_GET["price"] != "") { ?>
        $('.property_slider').each(function () {
            $(this).rangeSlider("values", <?php $price_split = explode(';', $_GET["price"]); echo $price_split[0];?>, <?php echo $price_split[1];?>);
        });
        <?php } ?>
    });
</script>