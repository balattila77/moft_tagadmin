<?php

$version = '1.3.1';
/**
 * Plugin Name:       Tag Admin
 * Description:       WP eMember / egyedi tagadmin oldal összekapcsolása
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Balogh Attila
 * Author URI:        http://weblapotakarok.hu
 * Text Domain:       tagadmin
 * Domain Path:       /languages
 *  
 */
/* !0. TABLE OF CONTENTS */
/*
 * 1. HOOKS
 *      1.1 - registers all our custom shortcodes on init
 * 
 * 2. SHORTCODES
 *      2.1 - tagadmin_register_shortcodes()
 *      2.2 - tagadmin_register_form_shortcode()
 * 
 * 3. FILTERS
 * 
 * 4. EXTERNAL SCRIPTS
 * 
 * 5. ACTIONS
 * 5.1 - Save new subscriber
 * 
 * 6. HELPERS
 * 
 * 7. CUSTOM POST TYPES
 * 
 * 8. ADMIN PAGES
 * 
 * 9. SETTINGS
 * 
 * 10. MISC.
 */

/* !1. HOOKS */
/**
 * 1.1
 * registers all our custom shortcodes on init
 */
add_action('init', 'tagadmin_register_shortcodes');
// 1.4
// hint: register ajax actions
add_action('wp_ajax_nopriv_tagadmin_save_subscription', 'tagadmin_save_subscription'); // regular website visitor
add_action('wp_ajax_tagadmin_save_subscription', 'tagadmin_save_subscription'); // regular website visitor
//// 1.5
// hint: modify ajax actions
add_action('wp_ajax_nopriv_tagadmin_save_profile', 'tagadmin_save_profile'); // regular website visitor
add_action('wp_ajax_tagadmin_save_profile', 'tagadmin_save_profile'); // regular website visitor
// 1.5
// load external files to public website
add_action('wp_enqueue_scripts', 'tagadmin_public_scripts');
/* !2. SHORTCODES */

/**
 * 2.1
 * registers all our custom shortcodes
 */
function tagadmin_register_shortcodes() {
    add_shortcode('tagadmin_register_form', 'tagadmin_register_form_shortcode');
    add_shortcode('tagadmin_profile_form', 'tagadmin_profile_form_shortcode');
}

/**
 * 2.2
 * returns a html string for a register form
 * @param type $args
 * @param type $content
 * @return string
 * 
 */
function tagadmin_register_form_shortcode($args, $content = "") {
// setup our output variable - the form html    
    $output .= '<div class="tagadmin  clearfix">'
            . '<div id="retStatus"></div>'
            . '<form action="' . get_site_url() . '/wp-admin/admin-ajax.php?action=tagadmin_save_subscription" id="tagadmin_register_form" class="tagadmin_register_form" method="post">'
            . '<div class="et_pb_contact">'
            . '<p class="formfield et_pb_contact_field et_pb_contact_field_half">'
            . '<label>Vezetéknév</label>'
            . '<input type="text" name="lastname" placeholder="Vezetéknév" class="widefat" />'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
            . '<label>Keresztnév</label>'
            . '<input type="text" name="firstname" placeholder="Keresztnév" class="widefat" />'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half">'
            . '<label>Születési dátum</label>'
            . '<input type="text" name="born_at" placeholder="" class="widefat datepicker" />'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
            . '<label>E-mail cím</label>'
            . '<input type="email" name="email" placeholder="Elsődleges e-mail cím" class="widefat" />'
            . '</p>'
            . '<p><strong>Lakcím:</strong></p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half">'
            . '<label>Irányítószám</label>'
            . '<input type="text" name="postal_code" placeholder="Irányítószám" class="widefat" />'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
            . '<label>Megye</label>'
            . '<input type="text" name="county" placeholder="Megye" class="widefat" />'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half ">'
            . '<label>Község</label>'
            . '<input type="text" name="town" placeholder="Község" class="widefat" />'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
            . '<label>Közterület / Hsz., em., ajtó</label>'
            . '<input type="text" name="address" placeholder="Közterület / Hsz., em., ajtó" class="widefat" />'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half">'
            . '<label>Szakképzettség</label>'
            . '<input type="text" name="education" placeholder="Szakképzettség" class="widefat" />'
            . '</p>'
            . '<p class="et_pb_contact_field  et_pb_contact_field_last">'
            . '<label>Megjegyzés</label>'
            . '<textarea placeholder="Megjegyzés" name="note" rows="3" class="widefat"></textarea>'
            . '</p>'
            . '</div><!-- END et_pb_contact -->'
            . '<div id="charter"></div>'
            . '<p style="padding-top: 20px;" class="formfield et_pb_contact_field  et_pb_contact_field_last">'
            . '<input id="chartercheck" type="checkbox" name="charter" class="input" value="charter">'
            . '<label for="chartercheck">'
            . 'Az egyesület alapszabályát megismertem és elfogadom, ennek alapján a jelen kérelem beküldésével kérem az Egyesület rendes tagjaként való felvételemet'
            . '</label>'
            . '</p>'
            . '<div id="privacy_policy"></div>'
            . '<p style="padding-top: 20px;" class="formfield et_pb_contact_field  et_pb_contact_field_last">'
            . '<input type="checkbox" id="privacy_policy_check" name="privacy_policy" class="input" value="privacy_policy">'
            . '<label for="privacy_policy_check">'
            . 'Az egyesület adatkezelési tájékoztatóját megismertem, személyes adataimat ennek ismeretében adom meg.'
            . '</label>'
            . '</p>'
            . '<p class="formfield et_pb_contact_field  et_pb_contact_field_last">'
            . '<strong>Tagfelvételi kérelem típusa</strong>'
            . '</p>'
            . '<p><input class="selectMOFTtype" data-display-value="4 000 Ft" checked="checked" type="radio" id="tagdij_4000" class="input" value="4000" name="affiliation_fee">'
            . '<label for="tagdij_4000">Éves MOFT tagdíj (teljes díj - 35 éves kortól nyugdíjas korig) - <strong>4 000 Ft</strong></label>'
            . '</p>'
            . '<p><input class="selectMOFTtype" data-display-value="2 000 Ft" type="radio" id="tagdij_2000" class="input" value="2000" name="affiliation_fee">'
            . '<label for="tagdij_2000"><i></i>Éves MOFT tagdíj 35 év alatt és nyugdíjasoknak (kedvezményes tagdíj) - <strong>2 000 Ft</strong></label>'
            . '</p>'
            . '<p><input class="selectMOFTtype" data-display-value="0 Ft" type="radio" id="tagdij_0" class="input" value="0" name="affiliation_fee">'
            . '<label for="tagdij_0">Éves MOFT tagdíj (ingyenes tagdíj 70 év felett) - <strong>0 Ft</strong></label>'
            . '</p>'
            . '<div id="totalbrutto">Bruttó végösszeg: <span>4 000 Ft</span></div>'
    ;

    if (strlen($content)):
        $output .= '<div class="tagadmin_content">' . wpautop($content) . '</div>';
    endif;
    $output .= '<div class="et_pb_contact">';
    $output .= '<p class="tagadmin_input_container" style="text-align: center;">'
            . '<input type="submit" name="tagadmin_submit" value="Tagfelvételi kérelem elküldése" />'
            . '</p>'
            . '<p style="padding: 20px 0 70px; text-align: center;">Magyarországi Fájdalom Társaság<br />BBRT 10103719-49570733-00000008</p>'
            . '</div>'
            . '</form>'
            . '</div>';
    return $output;
}

/**
 * 2.3
 * returns a html string for a profile modification form
 */
function tagadmin_profile_form_shortcode() {
    if (wp_emember_is_member_logged_in()) {
        global $wpdb;
        $emember_auth = Emember_Auth::getInstance();
        $member_id = $emember_auth->getUserInfo('member_id');
        $tagadmin_user = getUser($member_id);
        // tagdijak lekérdezése
        $output = '';
        //$output .= "SELECT * FROM user_tagdij WHERE user_membership_fees = (SELECT id FROM users WHERE member_id = " . $member_id . " LIMIT 1 )<br />";
        $fees = $result = $wpdb->get_results("SELECT * FROM user_membership_fees WHERE user_id = (SELECT id FROM users WHERE member_id = " . $member_id . " LIMIT 1 )");
        //$output = 'tag adatok: ' . $member_id . '<br />';
        //$output .= print_r($tagadmin_user,1);
        $output .= '<div class="tagadmin  clearfix"><h2>Adatok módosítása</h2>'
                . '<div id="retStatus"></div>'
                . '<form action="' . get_site_url() . '/wp-admin/admin-ajax.php?action=tagadmin_save_profile" id="tagadmin_profile_form" class="tagadmin_profile_form" method="post">'
                . '<input type="hidden"   name="member_id" value="' . $member_id . '" />'
                . '<div class="et_pb_contact">'
                . '<p class="formfield et_pb_contact_field et_pb_contact_field_half">'
                . '<label>Vezetéknév</label>'
                . '<input type="text" name="lastname" placeholder="Vezetéknév" class="widefat" value="' . $tagadmin_user->lastname . '" />'
                . '</p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
                . '<label>Keresztnév</label>'
                . '<input type="text" name="firstname" placeholder="Keresztnév" class="widefat" value="' . $tagadmin_user->firstname . '" />'
                . '</p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half">'
                . '<label>Születési dátum</label>'
                . '<input type="text" name="born_at" placeholder="" class="widefat datepicker" value="' . $tagadmin_user->born_at . '" />'
                . '</p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
                . '<label>E-mail cím</label>'
                . '<input type="email" name="email" placeholder="Elsődleges e-mail cím" class="widefat" value="' . $tagadmin_user->email . '" />'
                . '</p>'
                . '<p><strong>Lakcím:</strong></p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half">'
                . '<label>Irányítószám</label>'
                . '<input type="text" name="postal_code" placeholder="Irányítószám" class="widefat" value="' . $tagadmin_user->postal_code . '" />'
                . '</p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
                . '<label>Megye</label>'
                . '<input type="text" name="county" placeholder="Megye" class="widefat" value="' . $tagadmin_user->county . '" />'
                . '</p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half ">'
                . '<label>Község</label>'
                . '<input type="text" name="town" placeholder="Község" class="widefat" value="' . $tagadmin_user->town . '" />'
                . '</p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half et_pb_contact_field_last">'
                . '<label>Közterület / Hsz., em., ajtó</label>'
                . '<input type="text" name="address" placeholder="Közterület / Hsz., em., ajtó" class="widefat" value="' . $tagadmin_user->address . '" />'
                . '</p>'
                . '<p class="formfield et_pb_contact_field  et_pb_contact_field_half">'
                . '<label>Szakképzettség</label>'
                . '<input type="text" name="education" placeholder="Szakképzettség" class="widefat" value="' . $tagadmin_user->education . '" />'
                . '</p>'
                . '<p class="et_pb_contact_field  et_pb_contact_field_last">'
                . '<label>Megjegyzés</label>'
                . '<textarea placeholder="Megjegyzés" name="note" rows="3" class="widefat">' . $tagadmin_user->note . ' </textarea>'
                . '</p>'
                . '</div><!-- END et_pb_contact -->';
        if ($tagadmin_user->charter == 0):
            $output .= '<div id="charter"></div>'
                    . '<p style="padding-top: 20px;" class="formfield et_pb_contact_field  et_pb_contact_field_last">'
                    . '<input id="chartercheck" type="checkbox" name="charter" class="input" value="charter">'
                    . '<label for="chartercheck">'
                    . 'Az egyesület alapszabályát megismertem és elfogadom, ennek alapján a jelen kérelem beküldésével kérem az Egyesület rendes tagjaként való felvételemet'
                    . '</label>'
                    . '</p>';
        else:
            $output .= '<input id="chartercheck" type="hidden" name="charter" value="charter">';
        endif;
        if ($tagadmin_user->privacy_policy == 0):
            $output .= '<div id="privacy_policy"></div>'
                    . '<p style="padding-top: 20px;" class="formfield et_pb_contact_field  et_pb_contact_field_last">'
                    . '<input type="checkbox" id="privacy_policy_check" name="privacy_policy" class="input" value="privacy_policy">'
                    . '<label for="privacy_policy_check">'
                    . 'Az egyesület adatkezelési tájékoztatóját megismertem, személyes adataimat ennek ismeretében adom meg.'
                    . '</label>'
                    . '</p>';
        else:
            $output .= '<input id="privacy_policy_check" type="hidden" name="privacy_policy" value="privacy_policy">';
        endif;
        $output .= '<p class="formfield et_pb_contact_field  et_pb_contact_field_last">'
                . '<strong>Tagfelvételi kérelem típusa</strong>: ' . $tagadmin_user->affiliation_fee . ' Ft / év'
                . '</p>';
        $output .= '<div class="et_pb_contact" style="padding: 20px; border: 3px solid #555; margin-bottom: 20px;">';
        $output .= '<p class="formfield et_pb_contact_field  et_pb_contact_field_last">'
                . '<label>Jelszó - kitöltése esetén a fiók jelszavát is módosítjuk, ami a következő bejelentkezéskor lép érvénybe. Új jelszó: </label>'
                . '<input type="password" name="password" class="widefat" value="" />'
                . '</p></div>';


        $output .= '<div class="et_pb_contact">';
        $output .= '<p class="tagadmin_input_container" style="text-align: center;">'
                . '<input type="submit" name="tagadmin_submit" value="Adataim módosítása" />'
                . '</p>'
                . '</div>'
                . '</form>'
                . '</div>';
        $output .= '<div class="tagadmin  clearfix" style="padding-top: 20px;margin-top: 20px;border-top: 1px solid #333;"><h2>Tagdíjak</h2>';
        $output .= '<table><tr><td><strong>Év</strong></td><td><strong>Díj</strong></td></tr>';
        foreach ($fees as $fee) {
            $output .= '<tr><td>' . $fee->year . '</td><td>' . $fee->fee . ' Ft</td></tr>';
        }

        $output .= '</table></div>';
    } else {
        $output .= '<div class="et_pb_contact">Nincs bejelentkezve!</div>';
    }
    return $output;
}

/* !3. FILTERS */

/* !4. EXTERNAL SCRIPTS */

// 4.2
// hint: loads external files into PUBLIC website
function tagadmin_public_scripts() {
    global $version;
    // register scripts with WordPress's internal library
    wp_register_script('tagadmin-js-public', plugins_url('/js/tagadmin.js', __FILE__), array('jquery'), $version, true);
    wp_register_script('tagadmin-js-jqueryui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'));
    wp_register_script('tagadmin-js-jqueryui_hu', plugins_url('/js/datepicker-hu.js', __FILE__), array('tagadmin-js-jqueryui'));
    wp_register_style('tagadmin-css-jqueryui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_register_style('tagadmin-css-public', plugins_url('/css/tagadmin.css', __FILE__), array(), $version);
    wp_register_script('tagadmin-js-pdfobject', 'https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js', array('jquery'));

    // add to que of scripts that get loaded into every page
    wp_enqueue_script('tagadmin-js-jqueryui');
    wp_enqueue_script('tagadmin-js-jqueryui_hu');
    wp_enqueue_script('tagadmin-js-pdfobject');
    wp_enqueue_script('tagadmin-js-public');
    wp_enqueue_style('tagadmin-css-jqueryui');
    wp_enqueue_style('tagadmin-css-public');
}

/* !5. ACTIONS */

// 5.1 - Save new subscriber
function tagadmin_save_subscription() {
// setup default result data
    $result = [
        'status' => 0,
        'message' => 'A kérelem mentése NEM sikerült!',
        'error' => '',
        'errors' => []
    ];
    try {
// prepare subscriber data
        $subscriber_data = array(
            'name' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING),
            'firstname' => filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING),
            'lastname' => filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING),
            'born_at' => filter_input(INPUT_POST, 'born_at', FILTER_SANITIZE_STRING),
            'postal_code' => filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_STRING),
            'town' => filter_input(INPUT_POST, 'town', FILTER_SANITIZE_STRING),
            'address' => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING),
            'county' => filter_input(INPUT_POST, 'county', FILTER_SANITIZE_STRING),
            'education' => filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING),
            'note' => filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING),
            'charter' => filter_input(INPUT_POST, 'charter'),
            'privacy_policy' => filter_input(INPUT_POST, 'privacy_policy'),
            'affiliation_fee' => filter_input(INPUT_POST, 'affiliation_fee'),
        );
        $errors = array();

// form validation
        if (!strlen($subscriber_data['firstname'])) {
            $errors['firstname'] = 'A név megadása kötelező.';
        }
        if (!strlen($subscriber_data['lastname'])) {
            $errors['lastname'] = 'A név megadása kötelező.';
        }
        if (!strlen($subscriber_data['email'])) {
            $errors['email'] = 'Az e-mail megadása kötelező.';
        }
        if (strlen($subscriber_data['email']) && !is_email($subscriber_data['email'])) {
            $errors['email'] = 'Az e-mail cím nem érvényes.';
        }
        if (!strlen($subscriber_data['born_at'])) {
            $errors['born_at'] = 'A dátum megadása kötelező.';
        }
        if (!strlen($subscriber_data['postal_code'])) {
            $errors['postal_code'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['town'])) {
            $errors['town'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['county'])) {
            $errors['county'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['address'])) {
            $errors['address'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['education'])) {
            $errors['education'] = 'A szakképzettség megadása kötelező.';
        }
        if (checkEmail($subscriber_data['email'])) {
            $errors['email'] = 'Ezzel a címmel már van regisztrált tagunk!';
        }
        if (!$subscriber_data['privacy_policy']) {
            $errors['privacy_policy'] = 'Az adatkezelési átékoztató elfogadása kötelező';
        }
        if (!$subscriber_data['charter']) {
            $errors['charter'] = 'Az egyesület alapszabályának elfogadása kötelező';
        }

        // IF there are errors
        if (count($errors)):
            // append errors to result structure for later use
            $result['error'] = 'Néhány adat javításra szorul. ' . $subscriber_data['charter'];
            $result['errors'] = $errors;

        else:
            global $wpdb;
            $usersTable = 'users';
            $eMemberTable = 'wpbe_wp_eMember_members_tbl';
            // IF there are no errors, proceed...
            // wordpres emember adatok mentése
            $memberData = [
                'user_name' => $subscriber_data['email'],
                'first_name' => $subscriber_data['firstname'],
                'last_name' => $subscriber_data['lastname'],
                'password' => wp_hash_password('iSEsf8ez'),
                'member_since' => date('Y-m-d'),
                'account_state' => 'inactive',
                'membership_level' => 3,
                'last_accessed_from_ip' => '',
                'last_accessed' => '1900-01-01 00:00:00',
                'expiry_1st' => '1900-01-01',
                'expiry_2nd' => '1900-01-01',
                'email' => $subscriber_data['email']
            ];
            $memberFormat = ['%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s'];
            $wpdb->insert($eMemberTable, $memberData, $memberFormat);
            $member_id = $wpdb->insert_id;

            // user adatok mentése
            $usersData = [
                'name' => $subscriber_data['email'],
                'email' => $subscriber_data['email'],
                'password' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'active' => 0,
                'firstname' => $subscriber_data['firstname'],
                'lastname' => $subscriber_data['lastname'],
                'born_at' => $subscriber_data['born_at'],
                'registrated_at' => date('Y-m-d H:i:s'),
                'postal_code' => $subscriber_data['postal_code'],
                'town' => $subscriber_data['town'],
                'county' => $subscriber_data['county'],
                'address' => $subscriber_data['address'],
                'education' => $subscriber_data['education'],
                'member_id' => $member_id,
                'charter' => 1,
                'privacy_policy' => 1,
                'affiliation_fee' => $subscriber_data['affiliation_fee'],
            ];
            $usersFormat = ['%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d'];
            $wpdb->insert($usersTable, $usersData, $usersFormat);
            $result['status'] = 1;
            $result['message'] = 'A tagfelvételi kérelmet megkaptuk, hamarosan felvesszük önnel a kapcsolatot';
            createNotifyLetter($subscriber_data['email']);

        endif;
    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
    }
    // return result as json
    tagadmin_return_json($result);
}

// 5.2 Save profile data
function tagadmin_save_profile() {
    // setup default result data
    $result = [
        'status' => 0,
        'message' => 'A kérelem mentése NEM sikerült!',
        'error' => '',
        'errors' => []
    ];
    try {
// prepare subscriber data
        $subscriber_data = array(
            'name' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING),
            'firstname' => filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING),
            'lastname' => filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING),
            'born_at' => filter_input(INPUT_POST, 'born_at', FILTER_SANITIZE_STRING),
            'postal_code' => filter_input(INPUT_POST, 'postal_code', FILTER_SANITIZE_STRING),
            'town' => filter_input(INPUT_POST, 'town', FILTER_SANITIZE_STRING),
            'address' => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING),
            'county' => filter_input(INPUT_POST, 'county', FILTER_SANITIZE_STRING),
            'education' => filter_input(INPUT_POST, 'education', FILTER_SANITIZE_STRING),
            'note' => filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING),
            'charter' => filter_input(INPUT_POST, 'charter'),
            'privacy_policy' => filter_input(INPUT_POST, 'privacy_policy'),
            'member_id' => filter_input(INPUT_POST, 'member_id'),
            'password' => filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING),
        );
        $errors = array();

// form validation
        if (!strlen($subscriber_data['firstname'])) {
            $errors['firstname'] = 'A név megadása kötelező.';
        }
        if (!strlen($subscriber_data['lastname'])) {
            $errors['lastname'] = 'A név megadása kötelező.';
        }
        if (!strlen($subscriber_data['email'])) {
            $errors['email'] = 'Az e-mail megadása kötelező.';
        }
        if (strlen($subscriber_data['email']) && !is_email($subscriber_data['email'])) {
            $errors['email'] = 'Az e-mail cím nem érvényes.';
        }
        if (!strlen($subscriber_data['born_at'])) {
            $errors['born_at'] = 'A dátum megadása kötelező.';
        }
        if (!strlen($subscriber_data['postal_code'])) {
            $errors['postal_code'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['town'])) {
            $errors['town'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['county'])) {
            $errors['county'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['address'])) {
            $errors['address'] = 'A cím megadása kötelező.';
        }
        if (!strlen($subscriber_data['education'])) {
            $errors['education'] = 'A szakképzettség megadása kötelező.';
        }
        if (checkEmail($subscriber_data['email'], $subscriber_data['member_id'])) {
            $errors['email'] = 'Ezzel a címmel már van regisztrált tagunk!';
        }
        if (!$subscriber_data['privacy_policy']) {
            $errors['privacy_policy'] = 'Az adatkezelési tájékoztató elfogadása kötelező';
        }
        if (!$subscriber_data['charter']) {
            $errors['charter'] = 'Az egyesület alapszabályának elfogadása kötelező';
        }

        // IF there are errors
        if (count($errors)):
            // append errors to result structure for later use
            $result['error'] = 'Néhány adat javításra szorul. ' . $subscriber_data['charter'];
            $result['errors'] = $errors;

        else:
            global $wpdb;
            $usersTable = 'users';
            $eMemberTable = 'wpbe_wp_eMember_members_tbl';
            // IF there are no errors, proceed...
            // wordpres emember adatok mentése
            $memberData = [
                'user_name' => $subscriber_data['email'],
                'first_name' => $subscriber_data['firstname'],
                'last_name' => $subscriber_data['lastname'],
                //'password' => wp_hash_password('iSEsf8ez'),
                //'member_since' => date('Y-m-d'),
                //'account_state' => 'inactive',
                //'membership_level' => 3,
                //'last_accessed_from_ip' => '',
                //'last_accessed' => '1900-01-01 00:00:00',
                //'expiry_1st' => '1900-01-01',
                //'expiry_2nd' => '1900-01-01',
                'email' => $subscriber_data['email']
            ];
            if (strlen(trim($subscriber_data['password']))) {
                $memberData['password'] = wp_hash_password($subscriber_data['password']);
            }
            $whereData = [
                'member_id' => $subscriber_data['member_id']
            ];

            $wpdb->update($eMemberTable, $memberData, $whereData);
            //$member_id = $wpdb->insert_id;
            // user adatok mentése
            $usersData = [
                'name' => $subscriber_data['email'],
                'email' => $subscriber_data['email'],
                //'password' => '',
                //'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                //'active' => 0,
                'firstname' => $subscriber_data['firstname'],
                'lastname' => $subscriber_data['lastname'],
                'born_at' => $subscriber_data['born_at'],
                //'registrated_at' => date('Y-m-d H:i:s'),
                'postal_code' => $subscriber_data['postal_code'],
                'town' => $subscriber_data['town'],
                'county' => $subscriber_data['county'],
                'address' => $subscriber_data['address'],
                'education' => $subscriber_data['education'],
                //'member_id' => $member_id,
                'charter' => 1,
                'privacy_policy' => 1,
                    //'affiliation_fee' => $subscriber_data['affiliation_fee'],
            ];

            //$wpdb->insert($usersTable, $usersData, $usersFormat);
            $wpdb->update($usersTable, $usersData, $whereData);
            $result['status'] = 1;
            $result['message'] = 'Az adatok módosítása sikeresen megtörtént';

        endif;
    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
    }
    // return result as json
    tagadmin_return_json($result);
}

/* !6. HELPERS */

// 6.1 - return json string of an array
function tagadmin_return_json($php_array) {
    // encode result as json string
    $json_result = json_encode($php_array);
    // return result
    die($json_result);
    // stop all other processing 
    exit;
}

// 6.2 - check if registered email already exists
function checkEmail($email, $member_id = 0) {
    global $wpdb;
    if ($member_id == 0) {
        $result = $wpdb->get_results("SELECT * FROM users WHERE email = '$email' LIMIT 1");
    } else {
        $result = $wpdb->get_results("SELECT * FROM users WHERE email = '$email' AND member_id != " . $member_id . " LIMIT 1");
    }

    //error_log(print_r($result, true));
    if ($result && strlen(trim($result[0]->email)) > 0) {
        return true;
    }
    return false;
}

function getUser($member_id) {
    global $wpdb;
    $member = $wpdb->get_row("SELECT * FROM users WHERE member_id = " . $member_id, OBJECT);
    return $member;
}

function createNotifyLetter($email) {
    /*$senderUrl = 'https://app.sender.net/api/';
    $data = array(
        "method" => "campaignCreate",
        "params" => [
            "api_key" => '80118bf1843985292db3d4c13ef279e6',
            "title" => "notify-" . date('Y-m-d-H-i-s'),
            "subject" => 'MOFT regisztráció történt',
            "from" => "sender@fajdalom-tarsasag.hu",
            "reply_to" => "sender@fajdalom-tarsasag.hu",
            "google_analytics" => false,
            "lists" => [198546],
            "html_body" => "Újabb regisztráló (" . $email . ") került be a tagadmin rendszerébe"
        ]
    );
    $content = http_build_query(array('data' => json_encode($data)));

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
            "Content-Length: " . strlen($content) . "\r\n",
            'method' => 'POST',
            'content' => $content
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($senderUrl, false, $context);
    $resultObj = json_decode($result);
    $letterId = $resultObj->id;
    $senderResult = sendTheLetter($letterId);
    return $senderResult;*/
    
    $apiUrl = "https://fajdalom-tarsasag.hu/api/sendemail.php";
    $data = [
        "subject" => 'MOFT regisztráció történt',
        "from" => "sender@fajdalom-tarsasag.hu",
        "reply_to" => "sender@fajdalom-tarsasag.hu",
        "secret" => "6773C7CDD908A88AF66B99D552C2414825091F5B2EEF98BA42D7549668BDB89C",
        "html_body" => "Újabb regisztráló (" . $email . ") került be a tagadmin rendszerébe"
    ];

    $content = http_build_query(array('data' => json_encode($data)));
    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
            "Content-Length: " . strlen($content) . "\r\n",
            'method' => 'POST',
            'content' => $content
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);
    return json_encode($result);
    
    
}

function sendTheLetter($letterId) {
        $senderUrl = 'https://app.sender.net/api/';
        $data = array(
            "method" => "campaignStartSending",
            "params" => [
                "api_key" => '80118bf1843985292db3d4c13ef279e6',
                "campaign_id" => $letterId
            ]
        );
        $content = http_build_query(array('data' => json_encode($data)));

        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                "Content-Length: " . strlen($content) . "\r\n",
                'method' => 'POST',
                'content' => $content
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($senderUrl, false, $context);
        return $result;
    }

/* !7. CUSTOM POST TYPES */

/* !8. ADMIN PAGES */

/* !9. SETTINGS */

/* !10. MISC. */