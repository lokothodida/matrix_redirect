<?php

/* The Matrix: Redirect */

# get filename
  $thisfile = basename(__FILE__, ".php");
  
# language
  i18n_merge($thisfile) || i18n_merge($thisfile, 'en_US');
  
# class
  include(GSPLUGINPATH.$thisfile.'/php/class.php');
  
# instantiate class object
  $matrixredir = new MatrixRedir;
 
# register plugin
  register_plugin(
    $matrixredir->pluginInfo('id'),
    $matrixredir->pluginInfo('name'),
    $matrixredir->pluginInfo('version'),
    $matrixredir->pluginInfo('author'),
    $matrixredir->pluginInfo('url'),
    $matrixredir->pluginInfo('desc'),
    $matrixredir->pluginInfo('page'),
    array($matrixredir, 'admin')
  );
  
# hooks
  add_action('theme-header', array($matrixredir, 'redirect'));
  add_action($matrixredir->pluginInfo('page').'-sidebar', 'createSideMenu' , array($matrixredir->pluginInfo('id'), $matrixredir->pluginInfo('sidebar')));
  #add_filter('content', array($matrixredir, 'content'));

?>
  