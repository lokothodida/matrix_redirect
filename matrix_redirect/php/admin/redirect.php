<?php

  if (!empty($_POST['editRedir'])) {
    $_POST['post-slug'] = str_replace('=', '&#61;', $_POST['post-slug']);
    $update = $this->matrix->updateRecord(self::TABLE_REDIR, $_GET['redir'], $_POST);

    if ($update) {
      $this->matrix->getAdminError(i18n_r(TheMatrix::FILE.'/UPDATE_SUCCESS'), true);
    }
    else {
      $this->matrix->getAdminError(i18n_r(TheMatrix::FILE.'/UPDATE_ERROR'), false);
    }
  }
  $redir = $this->matrix->query('SELECT * FROM '.self::TABLE_REDIR.' WHERE id = '.$_GET['redir'], 'SINGLE');

?>

<h3><?php echo $redir['title']; ?></h3>
<form method="post">
  <?php $this->matrix->displayForm(self::TABLE_REDIR, $_GET['redir']); ?>
  <input type="submit" class="submit" name="editRedir" value="<?php echo i18n_r('BTN_SAVECHANGES'); ?>">&nbsp;&nbsp;
  / <a href="<?php echo $url; ?>" class="cancel"><?php echo i18n_r(TheMatrix::FILE.'/BACK'); ?></a>
</form>