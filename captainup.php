<?php
/*
Plugin Name: Captain Up 
Plugin URI: http://www.captainup.com
Description: Add Game Mechanics to your site and increase your engagement and retention. 2 minutes install: Simply add your free Captain Up API Key and you are good to go. The plugin also adds widgets you can use to show leaderboards and activities within your site.
Version: 1.3
Author: Captain Up Team
License: GPL2
*/

// Admin menu
// --------------------------------------------

function cptup_settings() {

	if (isset($_POST['submit'])) {
		// Save the Captain Up options on POST
		update_option('captain-api-key', $_POST['captain-api-key']);
		update_option('captain-api-secret', $_POST['captain-api-secret']);
		update_option('captain-locale', $_POST['captain-locale']);

		// We're using JavaScript to redirect back to the page as a GET
		// request. The reason we do that in JS instead of PHP is that
		// the headers were already sent. This is a hack, like everything
		// in WordPress.
		?>
			<script>
				var url = location.href;
				url +=  ~url.indexOf('submitted=true') ? '' : '&submitted=true';
				location.replace(url);
			</script>
		<?php
	}

	if(isset($_GET['submitted'])) {
		echo "<div id='update' class='updated'><p>Settings updated.</p></div>\n";
	}

	// Get the Captain Up API Key
	$captain_api_key = get_option('captain-api-key');

	// Get the Captain Up API Secret
	$captain_api_secret = get_option('captain-api-secret');

	// Get the Captain Up Locale
	$captain_locale = get_option('captain-locale');

	$pwd = dirname(__FILE__) . '/'; # Plugin Directory

	?>

	<div class="wrap" id="cpt-wrap">
		<div class="cpt-stripe cpt-colors">
			<img id="cpt-logo" src="<?php echo plugins_url('img/cptup_logo.png', __FILE__); ?>" />
			<h1>Captain Up - Game Mechanics</h1>
		</div>

		<div class="postbox-container ">
			<div class="metabox-holder ">	
				<div class="meta-box-sortables">
					<form action="" method="post">
						<div class="postbox cpt-colors">
							<div class="inside">
								<h2>Configure Captain Up</h2>

								<p>Copy your API key from the <a href='http://captainup.com/manage#settings' target='_blank'>Settings tab</a> in your Captain Up admin panel and paste it here. You need to <a href='http://captainup.com/users/sign_up' target='_blank'>Sign Up</a> if you don't have a Captain Up account.</p>

								<div id='cpt-api'>
									<label for='captain-api-key'>Your API Key:</label>
									<input id='captain-api-key' name='captain-api-key' type='text' size='50' value='<?php echo $captain_api_key; ?>' />
								</div>

								<script src='http://captainup.com/assets/web-available-languages.js'></script>

								<script>
									// Add all the language options, the web-available-languages
									// script loaded them into __cpt_available_languages.
									(function($) {
										// grab the selected language, default to english
										var selected_language = "<?php echo get_option('captain-locale', 'en'); ?>";
										$(function() {
											// Grab the language <select> options and empty it
											var $select = $('select#captain-locale').empty();
											// Run on all the languages
											for (code in __cpt_available_languages) {
												var lang = __cpt_available_languages[code];
												// Add an option for each language, its value
												// is the key of __cpt_available languages and
												// its text is the value of it.
												var $option = "<option value='" + code + "'";
												// Add a 'selected' attribute if needed
												if (selected_language === code) {
													$option += " selected";
												}
												$option += ">" + lang + "</option>";
												// Append it to the select box
												$select.append($option);
											}
										});
									})(jQuery);
								</script>

								<div id="cpt-language">
									<label for="captain-language">Language:</label>
									<select id='captain-locale' name='captain-locale'>
										<option value='en'>English</option>
									</select>
								</div>

								<div id='cpt-submit'>
									<input type="submit" class="cpt-x-btn cpt-btn-success padded" name="submit" value="Save" />
								</div>

								<hr />

								<h2>Advanced Options</h2>
								<p>You will need to add your API Secret to enable advanced options. Copy the API Secret from the <a href='http://captainup.com/manage#settings' target='_blank'>Settings tab</a> in your Captain Up admin panel and paste it here.
								<div id='cpt-secret'>
									<label for='captain-api-secret'>API Secret:</label>
									<input id='captain-api-secret' name='captain-api-secret' type='text' size='50' value='<?php echo $captain_api_secret; ?>' />
								</div>

								<div id='cpt-submit'>
									<input type="submit" class="cpt-x-btn cpt-btn-success padded" name="submit" value="Save" />
								</div>

								<hr />

								<div id='cpt-quick-links'>
									<h2>Quick Links and Support</h2>
									<div id='cpt-footer'>
										<a href='http://captainup.com/manage' target='_blank'>Dashboard</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/help' target='_blank'>Help & Support</a>
										<span class='cpt-sep'>|</span>
										
										<a href='http://captainup.com/manage#badges' target='_blank'>Edit Badges</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/manage#levels' target='_blank'>Edit Levels</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/blog' target='_blank'>Blog</a>
										<span class='cpt-sep'>|</span>

										<a href='http://twitter.com/cptup' target='_blank'>Twitter</a>
										<span class='cpt-sep'>|</span>

										<a href='mailto:team@captainup.com' target='_blank'>Contact Us</a>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
} 

/* Add Admin Panel CSS and JS Files
 * -------------------------------------------*/
function cptup_settings_files($page) {
	// I swear to god this is what Wordpress Codex suggests to do
	if ($page != "toplevel_page_cptup-config-menu") { 
		return;
	}
	// Add the scripts
	wp_enqueue_style('cpt-css');
	wp_enqueue_script('cpt-js');
}

/* Setup Admin Panel Resources
 * -------------------------------------------*/
function cptup_config() {
	// Add Captain Up to the Menu
	add_menu_page('Captain Up Settings - Game Mechanics', 'Captain Up', 'manage_options', 'cptup-config-menu', 'cptup_settings');

	// Register additional files
	wp_register_style('cpt-css', plugins_url('captainup.css', __FILE__));
	wp_register_script('cpt-js', plugins_url('captainup.js', __FILE__));
	add_action('admin_enqueue_scripts', 'cptup_settings_files');
}

// Add Captain Up to the main menu
add_action('admin_menu', 'cptup_config'); 



/* Add the Captain Up Script to the Site
 * -------------------------------------------*/

// cptup_print() initializes the Captain Up script,
// unless the API Key wasn't set yet.
function cptup_print() {
	$captain_key = get_option('captain-api-key');
	if ($captain_key != '') {
		add_action('wp_footer', 'cptup_start', 1000);
	}
}

// cptup_start() adds the Captain Up script
// asynchronously to the footer. It's only
// called from cptup_print if the API Key was
// set.
function cptup_start() {
	// Grab a reference to the API Key and Secret
	$captain_api_key = get_option('captain-api-key');
	$api_secret = get_option('captain-api-secret', false);

	// Check if the API secret is valid - 64 hexadecimal characters
	$valid_api_secret = preg_match("/^[0-9A-Fa-f]{64,64}$/", $api_secret) == 1;

	// Add a language suffix to the Embed Script, if the
	// captain-locale was not set it will return 'en'.
	$lang = "." . get_option('captain-locale', 'en');

	// Grab the current user ID
	$current_user_id = get_current_user_id();

	// Only hash the user id if the user is signed in and the api secret
	// is valid. Otherwise we want need it and won't add it to the script
	// configuration options below.
	if ($current_user_id != 0 && $valid_api_secret) {
		// Hash the WordPress User ID with with the API Secret using SHA512
		// We need this to connect WordPress user IDs and Captain Up IDs. The
		// $hashed_user_id makes sure the user_id is not being tampered on
		// the client side.
		$hashed_user_id = hash_hmac('sha512', $current_user_id, $api_secret);
		// Encode it as Base64
		$hashed_user_id = base64_encode($hashed_user_id);
		// remove new lines and and the '=' part at the end.
		$hashed_user_id = preg_replace('/(\n|=+\n?$)/', '', $hashed_user_id);
	}

	?>

	<div id='cptup-ready'></div> 
	<script type='text/javascript'>
	  window.captain = {up: function(fn) { captain.topics.push(fn) }, topics: []};
	  captain.up({
			api_key: '<?php echo $captain_api_key; ?>',
			platform: 'wordpress',
			app: {
				<?php if($current_user_id != 0 && $valid_api_secret) { ?>
				user_id: '<?php echo $current_user_id; ?>',
				hashed_user_id: '<?php echo $hashed_user_id; ?>'
				<?php } ?>
			}
	  });
	</script>
	<script type='text/javascript'>
	  (function() {
	      var cpt = document.createElement('script'); cpt.type = 'text/javascript'; cpt.async = true;
	      cpt.src = 'http' + (location.protocol == 'https:' ? 's' : '') + '://captainup.com/assets/embed<?php echo $lang; ?>.js';
	      (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(cpt);
	   })();
	</script>

	<?php
}

// Add the Captain Up script unless we're inside the
// admin panel.
if(!is_admin()) {
	add_action('wp_print_scripts', 'cptup_print');
}


/* Widgets
 * ---------------------------------------*/

/*
Plugin Name: Captain Up Widget
Plugin URI: http://captainup.com/
Description: Captain Up Widget - Leaderboards and Activity Feed
Version: 1.0
*/
class Captainup_Widget extends WP_Widget {

	/** constructor */
	function __construct() {
		parent::WP_Widget('cptup_widget', 'Captainup Widget', array(
			'description' => 'Captain Up Leaderboards and Recent Activity'
		));
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract($args);
		$type = $instance['type'];
		$height = $instance['height'];
		
		echo $before_widget;
		?>

		<div class='captain-<?php echo $type; ?>-widget' style='width: auto; height: <?php echo $height; ?>px; display: none;'>
		</div>
		
		<?php 
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['type']   = strip_tags($new_instance['type']);
		$instance['css']    = strip_tags($new_instance['css']);
		$instance['height'] = strip_tags($new_instance['height']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		$type   = esc_attr($instance['type']);
		$css    = esc_attr($instance['css']);
		$height = esc_attr($instance['height']);

		if (!$type) $type = 'leaderboard';
		if (!$css) $css = 'height: 300px; margin-top: 20px;';
		if (!$height) $height = '350';
		
		?>

		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>">
				<?php _e('Widget type'); ?>
			</label>
			<select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
				<option <?php if($type == "activity") { echo "selected"; }; ?> value="activity">
					Activity Widget
				</option>
				<option <?php if($type == "leaderboard") { echo "selected"; }; ?> value="leaderboard">
					Leaderboard Widget
				</option>			
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>">
				<?php _e('Height:'); ?>
			</label> 
			<input size="4" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />px
		</p>
		
		<?php
	}
}

// Initialize the Widget
add_action('widgets_init', create_function('', 'register_widget("CaptainUp_Widget");') );


/* Shortcodes
 * -----------------------------------*/

// Leaderboard Widget Shortcode
// [captain-leaderboard width="300" height="400" title="Hello"]
// Options:
// - width - css attribute. by default 300px
// - height - css attribute. by default 500px
// - title - the title of the widget, by default 'Leaderboard'
//           in the current locale language
function captain_leaderboard_shortcode($atts) {
	extract(shortcode_atts(
		array(
			'width' => '300px',
			'height' => '500px',
			'title' => false
		), $atts
	));
	return "<div style='margin: 20px auto; width: $width; height: $height;' class='captain-leaderboard-widget' data-cpt-title='$title'></div>";
}
add_shortcode('captain-leaderboard', 'captain_leaderboard_shortcode' );

// Activity Widget Shortcode
// [captain-activitiy width="500" height="400" title="Hello"]
// Options:
// - width - css attribute. by default 300px
// - height - css attribute. by default 500px
// - title - the title of the widget, by default 'Activities'
//           in the current locale language
function captain_activity_shortcode($atts) {
	extract(shortcode_atts(
		array(
			'width' => '300px',
			'height' => '500px',
			'title' => false
		), $atts
	));
	return "<div style='margin: 20px auto; width: $width; height: $height;' class='captain-activity-widget' data-cpt-title='$title'></div>";
}
add_shortcode('captain-activity', 'captain_activity_shortcode' );

// Sign Up Link Shortcode
// [captain-sign-up text="Hello"]
// Options:
// - text - the text of the link, by default "Sign Up Now"
function captain_sign_up_link_shortcode($atts) {
	extract(shortcode_atts(
		array(
			'text' => 'Sign Up Now',
		), $atts
	));
	return "<a style='cursor: pointer' class='captain-sign-up-link'>$text</a>";
}
add_shortcode('captain-sign-up', 'captain_sign_up_link_shortcode' );


/* WordPress Comments Integration
 * ----------------------------------------------------------------*/
// The flow for detecting a new wordpress comment and sending
// to Captain Up goes like this: First, we add an action hook to
// `comment_post` that notifies us when comments are saved to the
// database. Then, if the comment was approved, we create a cookie
// called `wp_cpt_new_comment`. We do this since after a comment
// is POSTed wordpress redirects back to the post. It's like a bad
// man's flash messaging. We check if this cookie exists on every
// request. If it does, we remove it, hook to the <head> element
// and add a JavaScript variable there called `_cpt_wordpress_events`
// that stores the new comment event. The Captain Up embed code
// does the rest of the work.
//
// NOTE: This process -will- change and will be moved to a server-side
// flow.

// Setup a hook to get a notification after a new comment has been posted.
add_action('comment_post', 'captain_mark_new_comment', 10, 2);

// `captain_mark_new_comment` is called from the `comment_post` wordpress
// hook. It receives $comment_id and the $approval status of the comment,
// and stores a cookie telling us in the follow up request (after the
// redirection) that a comment was created.
function captain_mark_new_comment($comment_id, $approval) {
	// $approval can either be 'spam', 0 for disapproved or 1 for approved.
	// We give points for approved and disapproved (held for moderation)
	// comments but not for spam.
	if ($approval == 1 || $approval == 0) {
		// we need to mark this in a cookie since wordpress has no built-in
		// session or flash support and after a comment is posted wordpress
		// redirects the user.
		setcookie("wp_cpt_new_comment", $comment_id, time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
	}
}

// `captain_add_new_comment` adds a new JS snippet to the page with
// the `_cpt_wordpress_events` variable. The Captain Up embed picks
// this up later and then syncs the new comment action to our servers.
function captain_add_new_comment() {
	?>
	<script type='text/javascript'>
		window._cpt_wordpress_events = {
			new_comment: true
		};
	</script>
	<?php
}

// On every request, check if the new comment cookie is set
if (isset($_COOKIE['wp_cpt_new_comment'])) {
	// Clean the Cookie
	setcookie("wp_cpt_new_comment", "", time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
	// hook into the <head> of the page to insert our JS snippet
	// that tells the Captain Up embed a new comment was created.
	add_action('wp_head', 'captain_add_new_comment');
}

?>