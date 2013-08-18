<?php

  if (!empty($_POST['addRedir'])) {
    $_POST['post-slug'] = str_replace('=', '&#61;', $_POST['post-slug']);
    $create = $this->matrix->createRecord(self::TABLE_REDIR, $_POST);

    if ($create) {
      $this->matrix->getAdminError(i18n_r(self::FILE.'/REDIR_SUCCESS'), true);
    }
    else {
      $this->matrix->getAdminError(i18n_r(self::FILE.'/REDIR_ERROR'), false);
    }
  }
  if (isset($_GET['delete'])) {
    $delete = $this->matrix->deleteRecord(self::TABLE_REDIR, $_GET['delete']);

    if ($delete) {
      $this->matrix->getAdminError(i18n_r(TheMatrix::FILE.'/DELETE_SUCCESS'), true);
    }
    else {
      $this->matrix->getAdminError(i18n_r(TheMatrix::FILE.'/DELETE_ERROR'), false);
    }
  }
  $redirects = $this->matrix->query('SELECT * FROM '.self::TABLE_REDIR.' ORDER BY id ASC');
  $domain = $this->matrix->getSiteURL();
?>

<script>
  $(document).ready(function() {
    var pajinateSettings = {
      'items_per_page'  : 10,
      'nav_label_first' : '|&lt;&lt;', 
      'nav_label_prev'  : '&lt;', 
      'nav_label_next'  : '&gt;', 
      'nav_label_last'  : '&gt;&gt;|', 
    };
    
    // pajination
    $('.pajinate').pajinate(pajinateSettings);
    $('.pajinate .page_navigation a').addClass('cancel');
    
    // deletion
    $('.delete').bind('click', function(e) {
      var tr   = $(this).closest('tr');
      var id   = tr.data('id');
      var slug = tr.data('slug');
      var url  = tr.data('url');
      e.preventDefault();
      $.Zebra_Dialog(<?php echo json_encode(i18n_r(TheMatrix::FILE.'/ARE_YOU_SURE')); ?>, {
        'type':     'question',
        'title':    <?php echo json_encode(i18n_r(TheMatrix::FILE.'/DELETE').' '); ?> + slug + '&rarr;' + url,
        'buttons':  [
          {caption: <?php echo json_encode(i18n_r(TheMatrix::FILE.'/NO')); ?>, },
          {caption: <?php echo json_encode(i18n_r(TheMatrix::FILE.'/YES')); ?>, callback: function() {
            window.location = '<?php echo $url; ?>&delete=' + id; }
          },
        ]
      });
    });
  });
</script>

<h3 class="floated"><?php echo i18n_r(self::FILE.'/REDIRECTS'); ?></h3>

<div class="edit-nav">
  <a href="<?php echo $url; ?>&create"><?php echo i18n_r(self::FILE.'/NEW_REDIR'); ?></a>
  <div class="clear"></div>
</div>

<table class="highlight edittable pajinate">
  <thead>
    <tr>
      <th><?php echo i18n_r(self::FILE.'/SLUG'); ?></th>
      <th><?php echo i18n_r(self::FILE.'/URL'); ?></th>
      <th><?php echo i18n_r(self::FILE.'/TITLE'); ?></th>
      <th><?php echo i18n_r(self::FILE.'/DELAY'); ?></th>
      <th></th>
    </tr>
  </thead>
  <tbody class="content">
    <?php foreach ($redirects as $redirect) { ?>
    <tr
      data-id="<?php echo $redirect['id']; ?>" 
      data-slug="<?php echo $redirect['slug']; ?>" 
      data-url="<?php echo $redirect['url']; ?>" 
      data-title="<?php echo $redirect['title']; ?>" 
      data-delay="<?php echo $redirect['delay']; ?>" 
    >
      <td><a href="<?php echo $url; ?>&redir=<?php echo $redirect['id']; ?>"><?php echo $redirect['slug']; ?></a></td>
      <td><?php echo $redirect['url']; ?></td>
      <td><?php echo $redirect['title']; ?></td>
      <td><?php echo $redirect['delay']; ?></td>
      <td style="text-align: right;">
        <a href="<?php echo $domain.$redirect['slug']; ?>" target="_blank" class="cancel">#</a> 
        <a href="<?php echo $url; ?>&delete=<?php echo $redirect['id']; ?>" class="cancel delete">&times;</a>
      </td>
    </tr>
    <?php } ?>
    <?php if (empty($redirects)) { ?>
    <tr>
      <td colspan="100%">
        <?php echo i18n_r(self::FILE.'/NO_REDIRECTS'); ?>
      </td>
    </tr>
    <?php } ?>
  </tbody>
  <thead>
    <tr>
      <th colspan="100%">
        <div class="page_navigation"></div>
      </th>
    </tr>
  </thead>
</table>