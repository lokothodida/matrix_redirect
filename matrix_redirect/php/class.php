<?php

class MatrixRedir {
/* constants */
  const FILE          = 'matrix_redirect';
  const VERSION       = '0.1';
  const MATRIX_VER    = '1.03';
  const AUTHOR        = 'Lawrence Okoth-Odida';
  const URL           = 'http://lokida.co.uk';
  const PAGE          = 'plugins';
  const TABLE_REDIR   = 'matrix-redir';
  
  /* properties */
  private $plugin;
  private $matrix;
  private $dir;
  private $config;
  
  /* methods */
  # constructor
  public function __construct() {
    // plugin information
    $this->plugin  = array();
    $this->plugin['id']      = self::FILE;
    $this->plugin['name']    = i18n_r(self::FILE.'/PLUGIN_NAME');
    $this->plugin['version'] = self::VERSION;
    $this->plugin['author']  = self::AUTHOR;
    $this->plugin['url']     = self::URL;
    $this->plugin['desc']    = i18n_r(self::FILE.'/PLUGIN_DESC');
    $this->plugin['page']    = self::PAGE;
    $this->plugin['sidebar'] = i18n_r(self::FILE.'/PLUGIN_SIDEBAR');
    
    // dependencies
    if ($this->checkDependencies()) {
      // load the matrix
      $this->matrix = new TheMatrix;
    }
  }
  
  # check dependencies
  private function checkDependencies() {
    if (
      class_exists('TheMatrix') &&
      TheMatrix::VERSION >= self::MATRIX_VER
    ) return true;
    else return false;
  }
  
  # missing dependencies (returns array of missing dependencies)
  private function missingDependencies() {
    $dependencies = array();
    
    if (!(class_exists('TheMatrix') && TheMatrix::VERSION >= self::MATRIX_VER)) {
      $dependencies[] = array('name' => 'The Matrix ('.self::MATRIX_VER.'+)', 'url' => 'https://github.com/n00dles/DM_matrix/');
    }
    
    return $dependencies;
  }
  
  # create the tables
  private function createTable() {
    if (!$this->matrix->tableExists(self::TABLE_REDIR)) {
      $fields = array(
        0 => array(
          'name' => 'title',
          'type' => 'input',
          'placeholder' => i18n_r(self::FILE.'/PAGE_TITLE'),
          'mask' => 'long',
        ),
        1 => array(
          'name' => 'slug',
          'type' => 'input',
          'label' => i18n_r(self::FILE.'/SLUG'),
          'mask' => 'text',
          'class' => 'leftsec',
          'required' => 'required',
        ),
        2 => array(
          'name' => 'url',
          'type' => 'input',
          'label' => i18n_r(self::FILE.'/URL'),
          'mask' => 'url',
          'required' => 'required',
          'class' => 'leftsec',
        ),
        3 => array(
          'name' => 'delay',
          'type' => 'input',
          'label' => i18n_r(self::FILE.'/DELAY').' (s)',
          'mask' => 'number',
          'default' => 0,
          'class' => 'rightsec',
        ),
        4 => array(
          'name' => 'content',
          'type' => 'textarea',
          'mask' => 'wysiwyg',
          'height' => '300px',
        ),
      );

      // CREATE
      return $this->matrix->createTable(self::TABLE_REDIR, $fields, $maxrecords=0);
    }
  }
  
  # plugin info
  public function pluginInfo($info) {
    if (isset($this->plugin[$info])) return $this->plugin[$info];
    else return false;
  }
  
  # redirector
  public function redirect() {
    global $data_index, $id, $content;
    if ($this->checkDependencies()) {
      $redir = $this->matrix->query('SELECT * FROM '.self::TABLE_REDIR.' WHERE slug = "'.$id.'"', 'SINGLE');
      if (!$redir) {
        $http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
        $url = str_replace(array($this->matrix->getSiteURL(), '='), array('', '&#61;'), $http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $redir = $this->matrix->query('SELECT * FROM '.self::TABLE_REDIR.' WHERE slug = "'.$url.'"', 'SINGLE');
      }
      
      if ($redir) {
        echo '<meta http-equiv="refresh" content="'.$redir['delay'].'; url='.$redir['url'].'">';
        if (!empty($data_index->title)) $data_index->title = $redir['title'];
        if (!empty($content)) $content = $redir['content'];
      }
    }
  }
  
  # admin
  public function admin() {
    $url = 'load.php?id='.self::FILE;
    $path = GSPLUGINPATH.self::FILE.'/php/admin/';
    
    if ($this->checkDependencies()) {
      $this->createTable();
    
      if (isset($_GET['redir']) && $this->matrix->recordExists(self::TABLE_REDIR, $_GET['redir'])) {
        include($path.'redirect.php');
      }
      elseif (isset($_GET['create'])) {
        include($path.'create.php');
      }
      else {
        include($path.'redirects.php');
      }
    }
    else {
      $dependencies = $this->missingDependencies();
      include($path.'/dependencies.php');
    }
  }
}

?>