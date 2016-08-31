<?php

/**
 * Prepares variables for gallery_slick.html.twig templates.
 */
function template_preprocess_gallery_slick(&$variables) {
  $slides = array();
  if (!empty($variables['elements']['#slides'])) {
    foreach ($variables['elements']['#slides'] as $slide) {
      $slides[] = array(
        '#theme' => 'gallery_slick_slide',
        '#slide' => $slide,
      );
    }
  }
  $variables['slider'] = array(
    '#theme' => 'gallery_slick_slider',
    '#slides' => $slides,
  );
}
