<?php
/*  
Plugin Name: My Envato
Plugin URI: http://www.polevaultweb.co.uk/plugins/my-envato/  
Description: A super simple plugin to display your recent 25 items from an Envato Marketplace.
Author: polevaultweb 
Version: 1.0.0
Author URI: http://www.polevaultweb.com/

Copyright 2013  polevaultweb  (email : info@polevaultweb.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
new my_envato();
class my_envato {

    private $plugin_path;
    private $plugin_url;
	private $plugin_version;
    private $plugin_table;
	private $plugin_l10n;
	
	private $api_base;
	private $api_version;
 	
 	private $timeout;
	private $useragent;
	private $sslverify;

    function __construct() {	

        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
		$this->plugin_version = '1.0.0';
		
        add_shortcode('my-envato', array($this, 'my_envato') );
        add_action( 'widgets_init', create_function( '', 'register_widget( "pvw_me_widget" );' ) );
		
	}
	
	function my_envato($atts, $content = null) {
		extract(shortcode_atts(array( 'marketplace' => 'codecanyon', 'user' => 'pvw' ), $atts));					
		return $this->my_envato_output(strtolower($marketplace), strtolower($user));		
	}
	
	function my_envato_output($marketplace, $user) {
		$this->api_base = 'http://marketplace.envato.com/api/';
		$this->api_version = 3;
		$this->api_method = 'new-files-from-user';
 	
		$this->timeout = 60;
		$this->useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)'; 
		$this->sslverify = false;
		$this->plugin_l10n = 'my-envato';
		
		$html = '';
		$items = array();
	    if ( false === ( $items = get_transient( 'my_enavato_'. $marketplace .'_'. $user ) ) ) {
			$url = $this->api_base .'v'. $this->api_version .'/'. $this->api_method .':'. $user .','. $marketplace .'.json';
			$feed_content = wp_remote_get($url, array('sslverify' => $this->sslverify, 'user-agent' => $this->useragent, 'timeout' => $this->timeout)) ;
			if ( is_wp_error($feed_content)) return $html;
			$data = json_decode($feed_content['body']);
			if (isset($data->{$this->api_method})) {
				$items = $data->{$this->api_method};
				set_transient( 'my_enavato_'. $marketplace .'_'. $user, $items, apply_filters( 'my_envato_cache', 60 * 60) );
			}
		}
		if (is_array($items)) {
			$html .= apply_filters( 'my_envato_items_start', '<ul>');
			foreach ($items as $item) {
				$html .= apply_filters( 'my_envato_item_start', '<li style="padding: 0 10px 10px 0; float:left;">');
				$html .= '<a href="'. $item->url . '?ref='. $item->user .'" title="View '. $item->item .'" class="'. apply_filters( 'my_envato_item_anchor_class', '') .'">';
				$html .= '<img src="'. $item->thumbnail .'" alt="'. $item->item .'" class="'. apply_filters( 'my_envato_item_image_class', '') .'">';
				$html .= '</a>';
				$html .= apply_filters( 'my_envato_item_end', '</li>');
			}
			$html .= apply_filters( 'my_envato_items_end', '</ul><div style="clear: both"></div>');
		}
		return $html;
	}
}

class pvw_me_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'pvw_me_widget', 
			'My Envato', 
			array( 'description' => __( 'A widget to display marketplace items from Envato', 'my-envato' ), )
		);
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$marketplace = isset($instance['marketplace']) ? $instance['marketplace'] : false;
		$user = isset($instance['user']) ? $instance['user'] : false;
	
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		
		$html = my_envato::my_envato_output($marketplace, $user);	
		echo $html;
		echo $after_widget;
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['marketplace'] = strip_tags( $new_instance['marketplace'] );
		$instance['user'] = strip_tags( strtolower($new_instance['user']) );
		delete_transient( 'my_enavato_'. $old_instance['marketplace'] .'_'. $old_instance['user']);
		return $instance;
	}
	
	public function form( $instance ) {
		$defaults = array( 	'title' => 'My Items' , 
							'marketplace' => 'codecanyon', 
							'user' => 'pvw');

		$instance = wp_parse_args( (array) $instance, $defaults );
		$title = strip_tags( $instance['title'] );
		$marketplace = isset($instance['marketplace']) ? $instance['marketplace'] : false;
		$user = isset($instance['user']) ? strip_tags($instance['user']) : false;		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'my-envato' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'marketplace' ); ?>"><?php _e( 'Marketplace:', 'my-envato'); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'marketplace' ); ?>" name="<?php echo $this->get_field_name( 'marketplace' ); ?>">
			<option <?php selected('activeden', $marketplace); ?> value="activeden">activeden</option>
			<option <?php selected('audiojungle', $marketplace); ?> value="audiojungle">audiojungle</option>
			<option <?php selected('codecanyon', $marketplace); ?> value="codecanyon">codecanyon</option>
			<option <?php selected('graphicriver', $marketplace); ?> value="graphicriver">graphicriver</option>
			<option <?php selected('photodune', $marketplace); ?> value="photodune">photodune</option>
			<option <?php selected('themeforest', $marketplace); ?> value="themeforest">themeforest</option>
			<option <?php selected('videohive', $marketplace); ?> value="videohive">videohive</option>
			<option <?php selected('3docean', $marketplace); ?> value="3docean">3docean</option>
		</select>
		</p>
		<label for="<?php echo $this->get_field_id( 'user' ); ?>"><?php _e( 'User:', 'my-envato'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'user' ); ?>" name="<?php echo $this->get_field_name( 'user' ); ?>" type="text" value="<?php echo $user; ?>" />
		</p>
		<?php 
	}
}