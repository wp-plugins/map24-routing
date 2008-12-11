<?php
  /*
    Plugin Name: Map24 Routing
    Plugin URI: http://www.azettl.de/2008/11/map24-routing/
    Description: This plugin allows you to display an Map24 Routing Form on your Blog.
    Version: 0.0.1
    Author: Andreas Zettl
    Author URI: http://azettl.de/
    Min WP Version: 2.6.2
    Max WP Version: 2.7.0
  */
  
  add_action('admin_menu', 'map24_add_menu');
  add_action('wp_head', 'map24_head');
  add_filter('the_content', 'map24_content');
  
  $map24_key = get_option('map24_key');
  $map24_height = get_option('map24_height');
  $map24_width = get_option('map24_width');
  $map24_width = get_option('map24_start');
  $map24_width = get_option('map24_end');
  if ('insert' == $HTTP_POST_VARS['action']){
    update_option("map24_key",$HTTP_POST_VARS['map24_key']);
    update_option("map24_height",$HTTP_POST_VARS['map24_height']);
    update_option("map24_width",$HTTP_POST_VARS['map24_width']);
    update_option("map24_start",$HTTP_POST_VARS['map24_start']);
    update_option("map24_end",$HTTP_POST_VARS['map24_end']);
  }
  
  function map24_option_page() {
    echo '<div class="wrap">
            <h2>Map24</h2>
            <form name="form1" method="post" action="'.$location.'">
              <label for="map24_key">Map24 API-Key (<a href="http://developer.navteq.com/site/global/zones/ms/map24-ajax-api/ajax_free_register.jsp" target="_blank">Get your Free MapTP AJAX Application Key</a>):</label><br />
              <input name="map24_key" id="map24_key" value="'.get_option("map24_key").'" type="text" />
              <br /><br />
              <label for="map24_height">Map Height:</label><br />
              <input name="map24_height" id="map24_height" value="'.get_option("map24_height").'" type="text" />
              <br /><br />
              <label for="map24_width">Map Width:</label><br />
              <input name="map24_width" id="map24_width" value="'.get_option("map24_width").'" type="text" />
              <br /><br />
              <label for="map24_start">Default Start Address:</label><br />
              <input name="map24_start" id="map24_start" value="'.get_option("map24_start").'" type="text" />
              <br /><br />
              <label for="map24_end">Default Destination Address:</label><br />
              <input name="map24_end" id="map24_end" value="'.get_option("map24_end").'" type="text" />
              <br /><br />
              <input type="submit" value="Save" />
              <input name="action" value="insert" type="hidden" />
            </form>
          </div>';
  }
  
  function map24_add_menu() {
    add_option("map24_key","");
    add_option("map24_height","200");
    add_option("map24_width","300");
    add_option("map24_start","Berlin");
    add_option("map24_end","Hamburg");
    add_options_page('Map24', 'Map24', 9, __FILE__, 'map24_option_page');
  }
  
  function map24_head() {
    echo '<script type="text/javascript" src="http://api.maptp.map24.com/ajax?appkey='.get_option("map24_key").'"></script>';
    echo '<script type="text/javascript" src="'.get_option('siteurl').'/wp-content/plugins/map24-routing/map24.js"></script>';
  }
  
   
  function map24_content($content){
    if(!eregi('[map24]', $content)) return $content;
    
    $map24 = "
      <div id='map24_area' style='width:".get_option('map24_width')."px;height:".get_option('map24_height')."px'>
        Loading...
      </div>
      <div id='map24_form' style='width:".get_option('map24_width')."px;'>
        <form name='map24_form' method='post' action='#'>
          <fieldset>
            <legend>Calculate Route</legend>
            <label for='map24_start'>Start Address:</label><br />
            <input name='map24_start' id='map24_start' value='".get_option("map24_start")."' type='text'/>
            <br /><br />
            <label for='map24_end'>Destination Address:</label><br />
            <input name='map24_end' id='map24_end' value='".get_option("map24_end")."' type='text'/>
            <br /><br />
            <input type='button' id='map24_calc' value='Calculate Route' onclick='startRouting()'/>
            <input type='button' id='map24_del' value='Remove Route' onclick='removeRoute(routeID)' disabled='disabled'/>
          </fieldset>
        </form>
        <div id='map24_desc'></div>
      </div>";
    return str_replace('[map24]', $map24, $content);
  }
?>
