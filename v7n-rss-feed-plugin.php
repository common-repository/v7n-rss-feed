<?php
/*
Plugin Name: V7N Rss Feed Plugin
Plugin URI: http://www.guyro.com/v7n-rss-feed-plugin/
Description: Adds a customizeable widget which displays the latest threads from v7n. It can be integrated anywhere in the blog. This newsticker shows up the last five or more threads. This is a very nice solution for all V7N members that want to stay in the loop of what's happening on V7N or just integrate V7N related content into their blog.
Version: 1.0
Author: Guy
Author URI: http://www.guyro.com
License: GPL3
*/

function v7nnews()
{
  $options = get_option("widget_v7nnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'V7N News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://www.v7n.com/forums/external.php?type=RSS2'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_v7nnews($args)
{
  extract($args);
  
  $options = get_option("widget_v7nnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'V7N News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  v7nnews();
  echo $after_widget;
}

function v7nnews_control()
{
  $options = get_option("widget_v7nnews");
  if (!is_array($options)){
    $options = array(
      'title' => 'V7N News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['v7nnews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['v7nnews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['v7nnews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['v7nnews-CharCount']);
    update_option("widget_v7nnews", $options);
  }
?> 
  <p>
    <label for="v7nnews-WidgetTitle">Widget Title: </label>
    <input type="text" id="v7nnews-WidgetTitle" name="v7nnews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="v7nnews-NewsCount">Max. News: </label>
    <input type="text" id="v7nnews-NewsCount" name="v7nnews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="v7nnews-CharCount">Max. Characters: </label>
    <input type="text" id="v7nnews-CharCount" name="v7nnews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="v7nnews-Submit"  name="v7nnews-Submit" value="1" />
  </p>
  
<?php
}

function v7nnews_init()
{
  register_sidebar_widget(__('V7N News'), 'widget_v7nnews');    
  register_widget_control('V7N News', 'v7nnews_control', 300, 200);
}
add_action("plugins_loaded", "v7nnews_init");
?>