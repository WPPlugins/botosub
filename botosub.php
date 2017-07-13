<?php
/*
Plugin Name: Botosub - Newsletters By Facebook Messenger Chatbot
Plugin URI: https://www.botosub.com/
Description: Send Newsletters directly to Facebook Messenger users
Version: 1.2
Author: Botosub Dev
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( '!' );

add_action('wp_enqueue_scripts', 'botosub_load_jssdk', 1);
add_action('wp_footer', 'botosub_headercode', 90);
add_action('admin_init', 'botosub_admin_init');
add_action('admin_menu', 'botosub_plugin_menu');

$botosub_newsletter_text = "Subscribe to our Newsletters from Messenger Chatbot!";

function botosub_admin_init()
{
    register_setting('bs_botosub_options', 'botosub_page_id');
    register_setting('bs_botosub_options', 'botosub_fb_lang');
    register_setting('bs_botosub_options', 'botosub_text');
    register_setting('bs_botosub_options', 'botosub_text_color');
    register_setting('bs_botosub_options', 'botosub_text_style');
    register_setting('bs_botosub_options', 'botosub_box_bg_color');
    register_setting('bs_botosub_options', 'botosub_switch_color');
    register_setting('bs_botosub_options', 'botosub_plugin_type');
    
    register_setting('bs_botosub_options', 'botosub_sc_title');
    register_setting('bs_botosub_options', 'botosub_sc_title_color');
    register_setting('bs_botosub_options', 'botosub_sc_desc');
    register_setting('bs_botosub_options', 'botosub_sc_desc_color');
    register_setting('bs_botosub_options', 'botosub_sc_bg_color');
    register_setting('bs_botosub_options', 'botosub_sc_img');

    register_setting('bs_botosub_options', 'botosub_mod_title');
    register_setting('bs_botosub_options', 'botosub_mod_title_color');
    register_setting('bs_botosub_options', 'botosub_mod_desc');
    register_setting('bs_botosub_options', 'botosub_mod_desc_color');
    register_setting('bs_botosub_options', 'botosub_mod_bg_color');
    register_setting('bs_botosub_options', 'botosub_mod_img');
    register_setting('bs_botosub_options', 'botosub_mod_img_pos');
    register_setting('bs_botosub_options', 'botosub_mod_img_when');
    register_setting('bs_botosub_options', 'botosub_mod_img_when_val');
    register_setting('bs_botosub_options', 'botosub_mod_img_again');
    register_setting('bs_botosub_options', 'botosub_mod_img_again_val');
}

function botosub_load_jssdk()
{
    $lang = "";
    if (get_option('botosub_fb_lang')==''){ $lang = "en_US"; }else{ $lang = esc_attr( get_option('botosub_fb_lang') ); }

    echo '
	<script>
    window.fbAsyncInit = function() {
      FB.init({
        appId: "627891880745321",
        xfbml: true,
        version: "v2.8"
      });
    };
    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) { return; }
       js = d.createElement(s); js.id = id;
       js.src = "//connect.facebook.net/' . $lang . '/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));
    </script>
	';
    wp_enqueue_style("botosub-top-plugin", plugins_url('/style.css', __FILE__));
    wp_enqueue_style("tingle", plugins_url('/tingle.min.css', __FILE__));
    wp_enqueue_script("tingle", plugins_url('/tingle.min.js', __FILE__));

}

function botosub_plugin_menu()
{
    add_options_page('Botosub', 'Botosub', 'manage_options', 'bs_botosub_options', 'botosub_plugin_options');
}

function botosub_plugin_options()
{
//    echo '<div class="wrap">'; ?>
<div class="wrap">
    <h2>Botosub - Newsletters From Messenger Chatbot</h2>
    <ol style="margin-left:20px;">
        <li>Register your Facebook Page in <a target="_blank" href="https://www.botosub.com/">Botosub</a>.</li>
        <li>Encourage users to click &#x22;Get Started&#x22; in Facebook message inbox.</li>
        <li>Send newsletters in <a target="_blank" href="https://www.botosub.com/">Botosub</a>.</li>
    </ol>
    <p>Visit <a target="_blank" href="https://www.botosub.com/">www.botosub.com</a> for more info.</p>
    <form method="post" action="options.php">
        <?php settings_fields('bs_botosub_options'); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Facebook Page ID</th>
                <td style="padding-right: 20px; width:37%;"><input type="text" name="botosub_page_id"
                           value="<?php echo get_option('botosub_page_id'); ?>" required/>
                <small>In order to get Page ID, visit your Facebook Page, click About section then scroll to the bottom.</small></td>
                <th scope="row" style="vertical-align: middle;"><label for="botosub_fb_lang">Language</label></th>
                <td style="width:37%;">
                    <input type="text" size="10" placeholder="en_US" name="botosub_fb_lang" value="<?php echo esc_attr( get_option('botosub_fb_lang') ); ?>" /> <small>All supported languages available at <a href="https://www.facebook.com/translations/FacebookLocales.xml">here</a></small>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Plugin Type</th>
                <td>
                    <?php
                    $tab_location_default = 'Modal';

                    $tab_location = (get_option('botosub_plugin_type') == FALSE) ? $tab_location_default : get_option('botosub_plugin_type');

                    ?>

                    <select id="botosub_plugin_type" name="botosub_plugin_type" onchange="botosubChangeHandler()" style="width:40%">
                      <option value="Top" <?php if ($tab_location == "Top") echo "selected"; ?>>Top Bar</option>
                      <option value="Bottom" <?php if ($tab_location == "Bottom") echo "selected"; ?>>Bottom Bar</option>
                      <option value="Shortcode" <?php if ($tab_location == "Shortcode") echo "selected"; ?>>Shortcode</option>
                      <option value="Modal" <?php if ($tab_location == "Modal") echo "selected"; ?>>Modal</option>
                      <option value="None" <?php if ($tab_location == "None") echo "selected"; ?>>None</option>
                    </select>
                </td>

            </tr>
            <tr valign="top">
                <!-- bar -->
                <th class="botosub_bar_class" scope="row">Text</th>
                <td class="botosub_bar_class">
                    <input type="text" name="botosub_text" style="width:70%;" placeholder="<?php echo $botosub_newsletter_text; ?>" value="<?php echo get_option('botosub_text'); ?>" />
                </td>

                <th class="botosub_bar_class" scope="row">Text Color</th>
                <td class="botosub_bar_class"><input type="text" name="botosub_text_color" placeholder="#ffffff" style="width:70%;"
                           value="<?php echo get_option('botosub_text_color'); ?>"/></td>

                <!-- scode -->
                <th class="botosub_scode_class" scope="row">Title</th>
                <td class="botosub_scode_class">
                    <input type="text" name="botosub_sc_title" style="width:70%;" placeholder="<?php echo $botosub_newsletter_text; ?>" value="<?php echo get_option('botosub_sc_title'); ?>" />
                </td>
                
                <th class="botosub_scode_class" scope="row">Title Color</th>
                <td class="botosub_scode_class"><input type="text" name="botosub_sc_title_color" placeholder="#333" style="width:70%;"
                           value="<?php echo get_option('botosub_sc_title_color'); ?>"/></td>
                
                <!-- modal -->
                <th class="botosub_mod_class" scope="row">Title</th>
                <td class="botosub_mod_class">
                    <input type="text" name="botosub_mod_title" style="width:70%;" placeholder="<?php echo $botosub_newsletter_text; ?>" value="<?php echo get_option('botosub_mod_title'); ?>" />
                </td>
                
                <th class="botosub_mod_class" scope="row">Title Color</th>
                <td class="botosub_mod_class"><input type="text" name="botosub_mod_title_color" placeholder="#333" style="width:70%;"
                           value="<?php echo get_option('botosub_mod_title_color'); ?>"/></td>

            </tr>
            <tr valign="top">
                
                <!-- bar -->
                <th class="botosub_bar_class" scope="row">Text Style</th>
                <td class="botosub_bar_class">
                    <input type="text" name="botosub_text_style" style="width:70%;" placeholder="font-weight:bold; padding-right: 10px;" value="<?php echo get_option('botosub_text_style'); ?>" />
                </td>
                <th class="botosub_bar_class" scope="row">Switch Color</th>
                <td class="botosub_bar_class">
                    <input type="text" name="botosub_switch_color" placeholder="#000" value="<?php echo get_option('botosub_switch_color'); ?>" />
                </td>
                
                <!-- scode -->
                <th class="botosub_scode_class" scope="row">Description</th>
                <td class="botosub_scode_class">
                    <input type="text" name="botosub_sc_desc" style="width:70%;" value="<?php echo get_option('botosub_sc_desc'); ?>" />
                </td>
                
                <th class="botosub_scode_class" scope="row">Description Color</th>
                <td class="botosub_scode_class"><input type="text" name="botosub_sc_desc_color" placeholder="#333" style="width:70%;"
                           value="<?php echo get_option('botosub_sc_desc_color'); ?>"/></td>
                
                <!-- modal -->
                <th class="botosub_mod_class" scope="row">Description</th>
                <td class="botosub_mod_class">
                    <input type="text" name="botosub_mod_desc" style="width:70%;" value="<?php echo get_option('botosub_mod_desc'); ?>" />
                </td>
                
                <th class="botosub_mod_class" scope="row">Description Color</th>
                <td class="botosub_mod_class"><input type="text" name="botosub_mod_desc_color" placeholder="#666" style="width:70%;"
                           value="<?php echo get_option('botosub_mod_desc_color'); ?>"/></td>
            </tr>
            <tr valign="top">
                <!-- bar -->
                <th class="botosub_bar_class" scope="row">Background Color</th>
                <td class="botosub_bar_class"><input type="text" name="botosub_box_bg_color" placeholder="#fed136" style="width:70%;"
                           value="<?php echo get_option('botosub_box_bg_color'); ?>"/></td>
                
                <!-- scode -->
                <th class="botosub_scode_class" scope="row">Background Color</th>
                <td class="botosub_scode_class">
                    <input type="text" name="botosub_sc_bg_color" placeholder="#ffffff" style="width:70%;" value="<?php echo get_option('botosub_sc_bg_color'); ?>" />
                </td>

                <th class="botosub_scode_class" scope="row" style="vertical-align: middle;">Shortcode</th>
                <td class="botosub_scode_class"><span style="margin-bottom: 20px; font-size: 21px; font-weight: 300; line-height: 1.4;">[botosub_newsletters]</span></td>
                
                <!-- modal -->
                <th class="botosub_mod_class" scope="row">Background Color</th>
                <td class="botosub_mod_class">
                    <input type="text" name="botosub_mod_bg_color" placeholder="#ffffff" style="width:70%;" value="<?php echo get_option('botosub_mod_bg_color'); ?>" />
                </td>

            </tr>

            <tr valign="top">

                <!-- scode -->
                <th class="botosub_scode_class" scope="row">Image URL</th>
                <td class="botosub_scode_class">
                    <input type="text" name="botosub_sc_img" placeholder="" style="width:70%;" value="<?php echo get_option('botosub_sc_img'); ?>" />
                </td>
                
                <!-- modal -->
                <th class="botosub_mod_class" scope="row">Image URL</th>
                <td class="botosub_mod_class">
                    <input type="text" name="botosub_mod_img" style="width:70%" placeholder="" value="<?php echo get_option('botosub_mod_img'); ?>" />
                </td>
                
                <th class="botosub_mod_class" scope="row">Image Position</th>
                <td class="botosub_mod_class">
                    <?php $mod_img_pos = (get_option('botosub_mod_img_pos') == FALSE) ? "" : get_option('botosub_mod_img_pos'); ?>
                    <select name="botosub_mod_img_pos" style="width:70%">
                      <option value="0" <?php if ($mod_img_pos == "0" || $mod_img_pos == "") echo "selected"; ?>>Above Title</option>
                      <option value="1" <?php if ($mod_img_pos == "1") echo "selected"; ?>>Above Description</option>
                      <option value="2" <?php if ($mod_img_pos == "2") echo "selected"; ?>>Below Description</option>
                    </select>
                </td>
            </tr>
            
            <tr valign="top">
                
                <!-- modal -->
                <th class="botosub_mod_class" scope="row" style="vertical-align: middle;">When the plugin pops up</th>
                <td class="botosub_mod_class">
                    <?php $mod_img_when_val = (get_option('botosub_mod_img_when_val') == FALSE) ? "30" : get_option('botosub_mod_img_when_val'); ?>
                    <input type="text" id="botosub_mod_img_when_val" name="botosub_mod_img_when_val" value="<?php echo $mod_img_when_val; ?>" style="margin-right:10px; width:10%" maxlength="2"/>
                    <?php $mod_img_when = (get_option('botosub_mod_img_when') == FALSE) ? "1" : get_option('botosub_mod_img_when'); ?>
                    <select id="botosub_mod_img_when" name="botosub_mod_img_when" style="width:55%" onchange="botosubWhenChHandler()">
                      <option value="0" <?php if ($mod_img_when == "0" || $mod_img_when == "") echo "selected"; ?>>Page Loaded</option>
                      <option value="1" <?php if ($mod_img_when == "1") echo "selected"; ?>>Exit Intent</option>
                      <option value="2" <?php if ($mod_img_when == "2") echo "selected"; ?>>Percent Scrolled</option>
                      <option value="3" <?php if ($mod_img_when == "3") echo "selected"; ?>>After XX Seconds</option>
                    </select>
                </td>
                
                <th class="botosub_mod_class" scope="row" style="vertical-align: middle;">When the plugin pops up after closed by user</th>
                <td class="botosub_mod_class">
                    <?php $mod_img_again_val = (get_option('botosub_mod_img_again_val') == FALSE) ? "10" : get_option('botosub_mod_img_again_val'); ?>
                    <input type="text" id="botosub_mod_img_again_val" name="botosub_mod_img_again_val" value="<?php echo $mod_img_again_val; ?>" style="margin-right:10px; width:10%" maxlength="2"/>
                    <?php $mod_img_again = (get_option('botosub_mod_img_again') == FALSE) ? "0" : get_option('botosub_mod_img_again'); ?>
                    <select id="botosub_mod_img_again" name="botosub_mod_img_again" style="width:55%" onchange="botosubAgainChHandler()">
                      <option value="0" <?php if ($mod_img_again == "0" || $mod_img_again == "") echo "selected"; ?>>Always</option>
                      <option value="1" <?php if ($mod_img_again == "1") echo "selected"; ?>>Never</option>
                      <option value="2" <?php if ($mod_img_again == "2") echo "selected"; ?>>Minutes later</option>
                      <option value="3" <?php if ($mod_img_again == "3") echo "selected"; ?>>Hours later</option>
                      <option value="4" <?php if ($mod_img_again == "4") echo "selected"; ?>>Days later</option>
                    </select>
                </td>
            </tr>
            
        </table>

        <p class="submit" style="text-align: center"><input type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>"/></p>
    </form>
    <br/><br/>


</div>

<script>


    function botosubChangeHandler() {

        var displBar = "", displSCode = "", displModal = "";
        var val = document.getElementById('botosub_plugin_type').value;

       if ( val === 'Shortcode' ) {
           displBar = "none";
           displSCode = "table-cell";
           displModal = "none";
       } else if ( val === 'Modal' ) {
           displBar = "none";
           displSCode = "none";
           displModal = "table-cell";
       } else if ( val === 'None' ) {
           displBar = "none";
           displSCode = "none";
           displModal = "none";
       } else {
           displBar = "table-cell";
           displSCode = "none";
           displModal = "none";
       }

        var barElements = document.getElementsByClassName("botosub_bar_class");
        for (var ind = 0; ind < barElements.length; ind++) {
            barElements[ind].style.display = displBar;
        }

        var scodeElements = document.getElementsByClassName("botosub_scode_class");
        for (var ind = 0; ind < scodeElements.length; ind++) {
            scodeElements[ind].style.display = displSCode;
        }
        
        var modElements = document.getElementsByClassName("botosub_mod_class");
        for (var ind = 0; ind < modElements.length; ind++) {
            modElements[ind].style.display = displModal;
        }

    }
    
    function botosubWhenChHandler() {

        var val = document.getElementById('botosub_mod_img_when').value;
        var whenValElement = document.getElementById('botosub_mod_img_when_val');

       if ( val === '2' || val === '3' ) {
           whenValElement.disabled = false;
           whenValElement.style.display = "inline-block";
       } else {
           whenValElement.disabled = true;
           whenValElement.style.display = "none";
       }
    }
    
    function botosubAgainChHandler() {

        var val = document.getElementById('botosub_mod_img_again').value;
        var againValElement = document.getElementById('botosub_mod_img_again_val');

       if ( val === '2' || val === '3' || val === '4' ) {
           againValElement.disabled = false;
           againValElement.style.display = "inline-block";
       } else {
           againValElement.disabled = true;
           againValElement.style.display = "none";
       }
    }
    
    botosubChangeHandler();
    botosubWhenChHandler();
    botosubAgainChHandler();

</script>
    <?php

}


function botosub_headercode()
{
//    $form_action = apply_filters('mctb_form_action', null);
    

    $tab_location_default = 'None';

    $tab_location = (get_option('botosub_plugin_type') == FALSE) ? $tab_location_default : get_option('botosub_plugin_type');
    if ($tab_location == 'Top') {
        $plugin_class = "botosub-plugin-top";
        $plugin_switch_class = "botosub-plugin-switch-top";
    } else if ($tab_location == 'Bottom') {
        $plugin_class = "botosub-plugin-bottom";
        $plugin_switch_class = "botosub-plugin-switch-bottom";
    }
    

    if ($tab_location == 'Top' || $tab_location == 'Bottom') {
    ?>
    
    <script>
        var botosub_hidden = true;
        var botosub_hideChar = "▲";
        var botosub_showChar = "▼";

        var botosub_tab_location = "<?php echo $tab_location ?>";
        if (botosub_tab_location === "Top") {
            botosub_hideChar = "▲";
            botosub_showChar = "▼";
        } else {
            botosub_hideChar = "▼";
            botosub_showChar = "▲";
        }

        function botosubTogglePlugin() {

            if (botosub_hidden) {
                document.getElementById("botosub-plugin").style.display = "none";
                document.getElementById("botosub-plugin-switch").innerHTML = botosub_showChar;
                botosub_hidden = false;
            } else {
                document.getElementById("botosub-plugin").style.display = "block";
                document.getElementById("botosub-plugin-switch").innerHTML = botosub_hideChar;
                botosub_hidden = true;
            }

        }

        setTimeout(function () {
            document.getElementById("botosub-plugin-switch").innerHTML = botosub_hideChar;
        }, 500)
        
    </script>


    <!-- Botosub - SWITCH -->
    <div class="botosub-plugin-outer <?php echo $plugin_class ?> ">
        <div id="botosub-plugin" class="botosub-plugin-style" style="background: <?php
        $myOption_1 = '#fed136';
        $myOption_2 = (get_option('botosub_box_bg_color') == FALSE) ? $myOption_1 : get_option('botosub_box_bg_color');
        echo $myOption_2;
        ?>">
            <div style="padding-top: 5px; padding-bottom: 5px; min-height: 33px; text-align: center;">
                <span style="<?php echo (get_option('botosub_text_style') == FALSE) ? "font-weight:bold; padding-right: 10px;" : get_option('botosub_text_style'); ?> color:<?php echo get_option('botosub_text_color'); ?>">
                    <?php
                    $myOption = (get_option('botosub_text') == FALSE) ? $botosub_newsletter_text : get_option('botosub_text');
                    echo $myOption;
                    ?>
                </span>
                <div class="fb-messengermessageus" 
                     messenger_app_id="627891880745321" 
                     page_id="<?php echo get_option('botosub_page_id'); ?>"
                     color="blue"
                     size="large" >
                </div>
            </div>
            <a target='_blank' href='https://www.botosub.com' style='position:absolute; bottom:4px; left:5px; font-size:.7em'>botosub</a>
        </div>


        <div id="botosub-plugin-switch-outer"
             class="<?php echo $plugin_switch_class ?>"
             style="background: <?php $myOption_2 = (get_option('botosub_box_bg_color') == FALSE) ? '#fed136' : get_option('botosub_box_bg_color');
             echo $myOption_2; ?>"
             onclick="botosubTogglePlugin()"><?php $switchColor = (get_option('botosub_switch_color') == FALSE) ? '#000' : get_option('botosub_switch_color'); ?>
            <span id="botosub-plugin-switch" style="float: right; padding: 5px 15px; color:<?php echo $switchColor; ?>">
        </span>
        </div>
    </div>

    <?php
    }
    else if ($tab_location == 'Modal') {
    ?>

    <script>
        
        <?php $mod_img_when = (get_option('botosub_mod_img_when') == FALSE) ? "0" : get_option('botosub_mod_img_when'); ?>
        <?php $mod_img_when_val = (get_option('botosub_mod_img_when_val') == FALSE) ? "0" : get_option('botosub_mod_img_when_val'); ?>
        var botosub_mWhen = "<?php echo $mod_img_when; ?>";
        var botosub_mWhenVal = parseInt("<?php echo $mod_img_when_val; ?>");
        var botosub_mFired = false;

        // NEW MODAL
        var botosub_modal = new tingle.modal({
            footer: false,
            stickyFooter: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            onOpen: function() {

            },
            onClose: function() {
                var d = new Date();
                localStorage.setItem("botosub_mcd", d.getTime());
            },
            beforeClose: function() {
                return true; // close the modal
            }
        });
        
        jQuery( document ).ready(function() {

            if (botosub_mWhen === "0") {
                if (!botosub_mFired && botosubModalOk()) { botosub_modal.open(); botosub_mFired = true; }
            }
            else if (botosub_mWhen === "1") {
                if(document.addEventListener)
                    document.addEventListener("mouseout", botosubExitIntent, false);
                else if(document.attachEvent)
                    document.attachEvent("on" + "mouseout", botosubExitIntent);
            }
            else if (botosub_mWhen === "2") {
                jQuery(window).scroll(function(e){
                    var s = jQuery(window).scrollTop(),
                    d = jQuery(document).height(),
                    c = jQuery(window).height();

                    var scrollPercent = (s / (d-c)) * 100;
                    
                    if (!botosub_mFired && scrollPercent >= (botosub_mWhenVal - 3) && scrollPercent <= (botosub_mWhenVal + 3) && botosubModalOk()) {
                        botosub_modal.open();
                        botosub_mFired = true;
                    }
                });
            }
            else if (botosub_mWhen === "3") {
                setTimeout(function(){ if (!botosub_mFired && botosubModalOk()) { botosub_modal.open(); botosub_mFired = true; } }, botosub_mWhenVal * 1000);
            }
        });
        
        function botosubExitIntent(e) {

			e = e ? e : window.event;

			if(e.target.tagName.toLowerCase() == "input")
				return;

			var vpWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

			if(e.clientX >= (vpWidth - 50))
				return;

			if(e.clientY >= 50)
				return;

			var from = e.relatedTarget || e.toElement;
			if(!from) {

                if (!botosub_mFired && botosubModalOk()) { botosub_modal.open(); botosub_mFired = true; }
            }
		}

        function botosubModalOk() {
            
            var lastModalDisp = localStorage.getItem("botosub_mcd");
            if (lastModalDisp) {

                <?php $mod_img_again = (get_option('botosub_mod_img_again') == FALSE) ? "0" : get_option('botosub_mod_img_again'); ?>
                var mAgain = "<?php echo $mod_img_again; ?>";
                if (mAgain === "0") { return true; }
                else if (mAgain === "1") { return false; }
                
                var dat = new Date();
                dat.setTime(lastModalDisp);
                var diff = Math.abs(new Date() - dat);
                <?php $mod_img_again_val = (get_option('botosub_mod_img_again_val') == FALSE) ? "10" : get_option('botosub_mod_img_again_val'); ?>
                var mAgainVal = parseInt("<?php echo $mod_img_again_val; ?>");
                var limit = 0;
                if (mAgain === "2") { limit = mAgainVal * 60; }
                else if (mAgain === "3") { limit = mAgainVal * 60 * 60; }
                else if (mAgain === "4") { limit = mAgainVal * 60 * 60 * 24; }
                if ((diff / 1000) < limit) {
                    return false;
                }
                else {
                    return true;
                }
            }
            else {
                return true;
            }            
        }


        <?php $myOption = (get_option('botosub_mod_img_pos') == FALSE) ? "0" : get_option('botosub_mod_img_pos'); ?>
        var mImgPos = "<?php echo $myOption; ?>";
        <?php $myOption = (get_option('botosub_mod_title') == FALSE) ? $botosub_newsletter_text : get_option('botosub_mod_title'); ?>
        <?php $urOption = (get_option('botosub_mod_title_color') == FALSE) ? "" : "color:" . get_option('botosub_mod_title_color') . ";"; ?>
        var mTitle = "<span style='display:block; font-size:2.5em; margin-bottom:15px;<?php echo $urOption; ?>'><?php echo $myOption; ?></span>";
        <?php $myOption = (get_option('botosub_mod_desc') == FALSE) ? "" : get_option('botosub_mod_desc'); ?>
        <?php $urOption = (get_option('botosub_mod_desc_color') == FALSE) ? "color:#666;" : "color:" . get_option('botosub_mod_desc_color') . ";"; ?>
        var mDesc = "<span style='display:block; font-size:26px; font-weight:300; line-height:36.4px; margin-bottom:15px;<?php echo $urOption; ?> '><?php echo $myOption; ?></span>";
        <?php $myOption = (get_option('botosub_mod_img') == FALSE) ? "" : "<img style='max-height:500px; margin-bottom:15px;' src='" . get_option('botosub_mod_img') . "'>"; ?>
        var mImg = "<?php echo $myOption; ?>";
        var mButton = '<div class="fb-messengermessageus" messenger_app_id="627891880745321" page_id="<?php echo get_option("botosub_page_id"); ?>" color="blue" size="xlarge"></div>';

        var mContent = "";
        if (mImgPos === "0") { mContent = mImg + mTitle + mDesc; }
        else if (mImgPos === "1") { mContent = mTitle + mImg + mDesc; }
        else if (mImgPos === "2") { mContent = mTitle + mDesc + mImg; }
        <?php $urOption = (get_option('botosub_mod_bg_color') == FALSE) ? "" : "background-color:" . get_option('botosub_mod_bg_color') . ";"; ?>
        var cnt = "<div style='text-align:center; font-family:alright_sansmedium,HelveticaNeue,Helvetica,Arial,sans-serif; -webkit-font-smoothing:antialiased;<?php echo $urOption; ?>'>" + mContent + mButton;
        cnt += "<a target='_blank' href='https://www.botosub.com' style='position:absolute; bottom:7px; right:7px; font-size:.8em'>botosub</a></div>";

        // set content
        botosub_modal.setContent(cnt);

    </script>

    <?php
    }

}

// SHORTCODE
function botosub_letter_shortcode_init() {
    // Add Shortcode
    function botosub_letter_shortcode( $atts , $content = null ) { 

        $botosub_newsletter_text = "Subscribe to our Newsletters from Messenger Chatbot!";
        // PLUGIN TITLE
        $myOption = (get_option('botosub_sc_title') == FALSE) ? $botosub_newsletter_text : get_option('botosub_sc_title');
        $sc_title_color = (get_option('botosub_sc_title_color') == FALSE) ? "" : "color:" . get_option('botosub_sc_title_color') . ";";
        $sc_desc = (get_option('botosub_sc_desc') == FALSE) ? "" : get_option('botosub_sc_desc');
        $sc_desc_color = (get_option('botosub_sc_desc_color') == FALSE) ? "" : "color:" . get_option('botosub_sc_desc_color') . ";";
        $sc_bg_color = (get_option('botosub_sc_bg_color') == FALSE) ? "" : "background-color:" . get_option('botosub_sc_bg_color') . ";";

        $sc_img = (get_option('botosub_sc_img') == FALSE) ? "" : "<img src='" . get_option('botosub_sc_img') . "' style='display:inline-block; max-height:158px; float:left;'>";


        $div = "<div style='position:relative; border-radius: 16px; padding: 20px 20px; border: 1px solid #ccc!important; text-align:center; overflow:auto; height:200px;" . $sc_bg_color . "'>" . $sc_img . "
            <div style='display:table-cell; vertical-align:middle; line-height:normal; height:158px;'>
                <span style='font-size: 1.2em; text-align: center; display: block; margin-bottom: 10px; " . $sc_title_color . "'>
                        " . $myOption . "
                    </span>
                    <span style='margin-bottom:10px; display:block; " . $sc_desc_color . "'>" . $sc_desc . "</span>
                <div class='fb-messengermessageus' style='display:block; text-align:center;'
                     messenger_app_id='627891880745321' 
                     page_id=" . get_option('botosub_page_id') . "
                     color='blue'
                     size='xlarge' >
                </div>
            </div><a target='_blank' href='https://www.botosub.com' style='position:absolute; bottom:7px; right:7px; font-size:.7em'>botosub</a>
        </div>";
        return $div;

    }
    add_shortcode( 'botosub_newsletters', 'botosub_letter_shortcode' );
}
add_action('init', 'botosub_letter_shortcode_init');
