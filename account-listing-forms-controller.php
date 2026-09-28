<?php
$addOnDirectMessages = getAddOnInfo("member_direct_messages","da941a43ff17f6d5c0bd373e74a1bf2d");
$subscriptionInfo = getSubscription($user_data['subscription_id'],$w);

if ($pars[1] == "contact") {
    $user = getUser($_COOKIE['userid'], $w);
    $w['form_url'] = "/" . $pars[0] . "/" . $pars[1] . "/" . $user['token'];

    $contactDetailsColumnQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SHOW COLUMNS FROM
            `subscription_types`
        LIKE
            'contact_details_form'");
    $contactDetailsColumn = mysql_num_rows($contactDetailsColumnQuery);

    if ($contactDetailsColumn > 0 && $subscription['contact_details_form'] != "") {
        if($subscription['contact_details_form'] != 'member_contact_details'){
            
            $formExists         = false;
            $customForms        = form::getListingForms();

            foreach($customForms['merged'] as $listingForms){
                if($listingForms->form_name == $subscription['contact_details_form']){
                    $formExists = true;
                }
            }
            
            if(!$formExists){
                $subscription['contact_details_form'] = 'member_contact_details';
            }
        }
        $contactFormName = $subscription['contact_details_form'];
    } else {
        $checkMemberContactForm = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                                                                form_name
                                                            FROM
                                                                `forms`
                                                            WHERE
                                                                form_name = 'contact'");

        if (mysql_num_rows($checkMemberContactForm) > 0) {
            $contactFormName = "contact";
        } else {
            $contactFormName = "member_contact_details";
        }
    }
    echo form($contactFormName, array("view" => "edit", "id" => $_COOKIE['userid']), $w['website_id'], $w);

} else if ($pars[1] == "profile") {
    $checkDataFlowsTableQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), 'SELECT
            1
        FROM
            `image_settings`
        LIMIT
            1');

    if ($checkDataFlowsTableQuery !== FALSE) {
        echo widget("Bootstrap Theme - Account - Profile Image Upload", "", $w['website_id'], $w);

    } else {
        echo widget("Bootstrap Theme - Account - Profile Photo Upload", "", $w['website_id'], $w);
    }

} else if ($pars[1] == "resume") {
    $user = getUser($_COOKIE['userid'], $w);
    $w['form_url'] = "/" . $pars[0] . "/" . $pars[1] . "/" . $user['token'];

    $listingDetailsColumnQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SHOW COLUMNS FROM
            `subscription_types`
        LIKE
            'listing_details_form'");
    $listingDetailsColumn = mysql_num_rows($listingDetailsColumnQuery);

    if ($listingDetailsColumn > 0 && $subscription['listing_details_form'] != "") {
        if($subscription['listing_details_form'] != 'member_listing_details'){
            $formExists = false;
            $customForms = form::getListingForms();
            $checkFieldsExists = bd_controller::form_fields()->get($subscription['listing_details_form'],'form_name');
            foreach($customForms['merged'] as $listingForms){
                if($listingForms->form_name == $subscription['listing_details_form']){
                    $formExists = true;
                }
            }
            if($checkFieldsExists !== true && !$formExists){
                $subscription['listing_details_form'] = 'member_listing_details';
            }
        }
        $listingDetailsFormName = $subscription['listing_details_form'];

    } else {
        $checkMemberListingForm = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SELECT
                                                            form_name
                                                        FROM
                                                            `forms`
                                                        WHERE
                                                            form_name = 'member_listing_details'");

            if (mysql_num_rows($checkMemberListingForm) > 0) {
                $listingDetailsFormName = "member_listing_details";

            } else {
                $listingDetailsFormName = "resume";
            }

        }
    echo form($listingDetailsFormName, array("view" => "edit", "id" => $_COOKIE['userid']), $w['website_id'], $w);

} else if ($pars[1] == "about") {
    $user = getUser($_COOKIE['userid'], $w);
    $w['form_url'] = "/$pars[0]/$pars[1]/$user[token]";

    $aboutColumnQuery = mysql(brilliantDirectories::getDatabaseConfiguration('database'), "SHOW COLUMNS FROM
            `subscription_types`
        LIKE
            'about_form'");
    $aboutColumn = mysql_num_rows($aboutColumnQuery);

    if ($aboutColumn > 0) {

        if ($subscription['about_form'] != "") {
            if($subscription['about_form'] != 'about'){
                $formExists = false;
                $customForms = form::getListingForms();
                $checkFieldsExists = bd_controller::form_fields()->get($subscription['about_form'],'form_name');
                foreach($customForms['merged'] as $listingForms){
                    if($listingForms->form_name == $subscription['about_form']){
                        $formExists = true;
                    }
                }
                if($checkFieldsExists !== true && !$formExists){
                    $subscription['about_form'] = 'about';
                }
            }
            $aboutFormName = $subscription['about_form'];
        } else {
            $aboutFormName = "about";
        }

    } else {
        $aboutFormName = "about";
    }
    echo form($aboutFormName, array("view" => "edit", "id" => $_COOKIE['userid']), $w['website_id'], $w);

} else if ($pars[1] == "password") {
    echo form("change_password", array("view" => "edit", "id" => $_COOKIE['userid']), $w['website_id'], $w);

} else if ($pars[1] == "changelisting") {
    echo widget("Bootstrap Theme - Account - Manage Account", "", $w['website_id'], $w);

} else if ($pars[1] == "deleteaccount") {
    echo widget("Bootstrap Theme - Account - Delete Account", "", $w['website_id'], $w);

} else if ($pars[1] == "chat_messages" && (isset($addOnDirectMessages['status']) && $addOnDirectMessages['status'] == "success" && $w['enable_direct_chat_messages'] == "1")) {
    echo widget($addOnDirectMessages['widget'],"",$w['website_id'],$w);
} else {
    echo widget("Bootstrap Theme - Account - Member Dashboard", "", $w['website_id'], $w);
}
?>