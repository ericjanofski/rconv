<?php
/**
 * @package WordPress
 * @subpackage appcms
 */

add_filter('excerpt_more', 'appcms_excerpt_more');
function appcms_excerpt_more($more) {
	return '';
}

add_filter('the_excerpt', 'appcms_get_excerpt');
function appcms_get_excerpt() { // Fakes an excerpt if needed
  $text = get_the_content('');
  $text = apply_filters('the_content', $text);
  $text = str_replace('\]\]\>', ']]&gt;', $text);
  $text = strip_tags($text, '<a>');
  $text = trim($text);
  $excerpt_length = 45;
  $words = explode(' ', $text, $excerpt_length + 1);
  if (count($words) > $excerpt_length) {
    array_pop($words);
    $text = implode(' ', $words);
    $text .=  '… <a class="more-link" href="' . get_permalink() . '" title="Read More">Read More...</a>';
  }
	return '<p>' . $text . '</p>';
}