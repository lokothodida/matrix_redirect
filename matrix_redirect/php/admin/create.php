<h3><?php echo i18n_r(self::FILE.'/NEW_REDIR'); ?></h3>
<form method="post" action="<?php echo $url; ?>">
  <?php $this->matrix->displayForm(self::TABLE_REDIR); ?>
  <input type="submit" class="submit" name="addRedir" value="<?php echo i18n_r('BTN_SAVECHANGES'); ?>">&nbsp;&nbsp;
  / <a href="<?php echo $url; ?>" class="cancel"><?php echo i18n_r(TheMatrix::FILE.'/BACK'); ?></a>
</form>