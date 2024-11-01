<?php
/**
  * Plugin Name: Topic
  * Plugin URI: https://www.usetopic.com/
  * Description: Topic helps editors and agencies create content briefs in half the time.
  * Author: Topic
  * License: GPL v2 or later
  * License URI: https://www.gnu.org/licenses/gpl-2.0.html
  * Version: 1.0.30
  */ 
if( ! defined( 'ABSPATH') ) {
    exit;
}


class useTopicSeo {

	
    public function __construct() {
    	global $pagenow;
    	if($pagenow == 'post.php' || $pagenow == 'post-new.php'){
				add_action( 'post_submitbox_misc_actions', array($this, 'add_usetopic_button_classic_editor') );
				add_action( 'admin_enqueue_scripts', array($this, 'topic_enqueue_assets_classic') );
				add_action('admin_footer', array($this, 'useTopicSeo_sidebar_box'));
				add_action( 'enqueue_block_editor_assets', array($this,'topic_enqueue_assets') );
			}
			add_action('wp_ajax_get_permalink_for_post_id', array($this, 'get_permalink_for_post_id'));
			add_action('wp_ajax_search_posts', array($this, 'search_posts'));
    }

	
	public function add_usetopic_button_classic_editor(){
			$html  = '<div id="major-publishing-actions-use-topic" style="overflow:hidden"><p>'.__( 'Topic SEO Content Optimization Tool', 'usetopic-plugin' ).'</p>';
			$html .= '<div id="publishing-action-use-topic">';
			$html .= '<button type="button" aria-pressed="true" aria-expanded="true" id="usetopicMain" class="components-button is-pressed has-icon" aria-label="Topic SEO Content Optimization Tool">Grade Content</button>';
			$html .= '</div>';
			$html .= '</div>';
			echo $html;
	}

	/**
	 * Function to find the file path for index.[hash].js or index-classic.[hash].js
	 * @param string $filePattern - The file pattern to search for (e.g., 'index' or 'index-classic')
	 * @param string $directory - The directory where the build files are located
	 * @return string|null - Returns the full file path or null if not found
	*/
	public function getHashedFilePath($filePattern, $directory = 'build') {
		// Get the full path to the plugin directory
		$plugin_dir = plugin_dir_path(__FILE__);
		$full_directory_path = $plugin_dir . $directory;

		if (!is_dir($full_directory_path)) {
			return null;
		}

		// Define the regex pattern to match files like index.[hash].js or index-classic.[hash].js
		$pattern = sprintf('/^%s\.[a-f0-9]{8}\.js$/', preg_quote($filePattern, '/'));

		// Scan the directory for files
		$files = scandir($full_directory_path);

		// Iterate through the files to find a match
		foreach ($files as $file) {
			if (preg_match($pattern, $file)) {
				// Return the relative path of the matched file
				return $directory . '/' . $file;
			}
		}

		// If no match was found, return null
		return null;
	}

	public function topic_enqueue_assets() {
		$index_file_path = $this->getHashedFilePath('index');
		if ($index_file_path === null) {
			$index_file_path = 'build/index.js'; 
		}
		wp_enqueue_script(
			'topic-gutenberg-sidebar',
			plugins_url( $index_file_path, __FILE__ ),
			array( 'wp-plugins', 'wp-edit-post', 'wp-i18n', 'wp-element' ),
			null
		);
		wp_enqueue_style('topic-sidebar-global', plugins_url( 'build/index.css', __FILE__ ), array(), 'all');
	}
	
	public function topic_enqueue_assets_classic() {
		$index_file_path = $this->getHashedFilePath('index-classic');
		if ($index_file_path === null) {
			$index_file_path = 'build/index-classic.js'; 
		}
		  wp_enqueue_script(
				'topic-gutenberg-sidebar-classic',
				plugins_url( $index_file_path , __FILE__ ), array('wp-plugins'), null
		  );
		  wp_enqueue_style('topic-classic-sidebar', plugins_url( 'classic-editor/classic.css', __FILE__ ), array(), 'all');
		  wp_enqueue_style('topic-sidebar-global', plugins_url( 'build/index.css', __FILE__ ), array(), 'all');
	}
	
	public function useTopicSeo_sidebar_box($data) {
		 echo '<div id="usetopicData"><div class="closeTopicdata"><strong>'.__( 'Topic SEO Content Optimization Tool', 'usetopic-plugin' ).'</strong><button type="button" class="close" aria-label="Close plugin"><svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path></svg><p class="usetopic-tooltip">Close Plugin</p></button></div><div class="content"></div></div>';
	}

	public function get_permalink_for_post_id(){
		if(isset($_POST['post_id'])){
			$post_id = $_POST['post_id'];
			$permalink = get_permalink(intval($post_id));

			wp_send_json([
				'permalink' => $permalink
			]);
		}
		wp_die();
	}

	public function search_posts(){
		if(isset($_POST['s'])){
			$s = $_POST['s'];

			global $wpdb;
			$myposts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND post_title LIKE '%s'", '%'. $wpdb->esc_like( $s ) .'%') );
			$post_array = array();
			foreach ( $myposts as $mypost ) 
			{
			    // $post = get_post($mypost);
					$permalink = get_permalink(intval($mypost->ID));
					$mypost->permalink = $permalink;
					$mypost->post_content = "";
			    array_push($post_array, $mypost);
			}

			wp_send_json([
				'posts' => $post_array
			]);
		}
		wp_die();
	}


}
 
$wpUseTopic= new useTopicSeo();
