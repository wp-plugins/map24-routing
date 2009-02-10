<?php
  /*
    Plugin Name: Map24 Routing
    Plugin URI: http://wpdemo.azettl.de/2008/11/map24-routing/
    Description: This plugin allows you to display an Map24 Routing Form on your Blog.
    Version: 0.0.3
    Author: Andreas Zettl
    Author URI: http://azettl.de/
    Min WP Version: 2.6.2
    Max WP Version: 2.7.0
  */
  
  add_action('admin_menu', 'map24_add_menu');
  add_action('wp_head', 'map24_head');
  add_shortcode('map24', 'map24_content');
  
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
            <div id="icon-options-general" class="icon32"><br /></div>
            <h2>Map24</h2>
            <form name="map24_options_form" method="post" action="'.$location.'">
              <table class="form-table">
                <tr valign="top">
                  <th scope="row">Map24 API-Key</th>
                  <td>
                    <fieldset>
                      <legend class="hidden">Map24 API-Key </legend>
                      <input name="map24_key" id="map24_key" value="'.get_option("map24_key").'" type="text" />
                      <label for="map24_key">(<a href="http://developer.navteq.com/site/global/zones/ms/map24-ajax-api/ajax_free_register.jsp" target="_blank">Get your Free MapTP AJAX Application Key</a>)</label>
                    </fieldset>
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Map Height</th>
                  <td>
                    <fieldset>
                      <legend class="hidden">Map Height </legend>
                      <input name="map24_height" id="map24_height" value="'.get_option("map24_height").'" type="text" />
                      <label for="map24_height">Pixel</label>
                    </fieldset>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">Map Width</th>
                  <td>
                    <fieldset>
                      <legend class="hidden">Map Width </legend>
                      <input name="map24_width" id="map24_width" value="'.get_option("map24_width").'" type="text" />
                      <label for="map24_width">Pixel</label>
                    </fieldset>
                  </td>
                </tr>
                
                <tr valign="top">
                  <th scope="row">Default Start Address</th>
                  <td>
                    <fieldset>
                      <legend class="hidden">Default Start Address </legend>
                      <input name="map24_start" id="map24_start" value="'.get_option("map24_start").'" type="text" />
                      <label for="map24_start">(like Berlin, Paris, London)</label>
                    </fieldset>
                  </td>
                </tr>
                <tr valign="top">
                  <th scope="row">Default Destination Address</th>
                  <td>
                    <fieldset>
                      <legend class="hidden">Default Destination Address </legend>
                      <input name="map24_end" id="map24_end" value="'.get_option("map24_end").'" type="text" />
                      <label for="map24_end">(like Berlin, Paris, London)</label>
                    </fieldset>
                  </td>
                </tr>
              </table>
              <p class="submit">
                <input type="submit" name="Submit" class="button-primary" value="Save Changes" />
              </p>
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
    return $map24;
  }
?>
