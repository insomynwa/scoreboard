<div class="form-check form-check-inline">
    <input type="radio" <?php if($id==1){ echo 'checked'; } ?> name="gamedraw_bowstyle" class="gamedraw-bowstyle-cls form-check-input" value="<?php echo $id; ?>" id="gamedraw-bowstyle-<?php echo strtolower($name); ?>"><label for="gamedraw-bowstyle-<?php echo strtolower($name); ?>" class="form-check-label text-gray-4"><?php echo $name; ?></label>
</div>