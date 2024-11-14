<?php if (count($errors) > 0) : ?>
  <div class="error">
    <?php foreach ($errors as $error) : ?>
      <div style="color: red; font-weight: bold; border: 1px solid red; padding: 10px; background-color: #ffe6e6; border-radius: 5px;">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endforeach ?>
  </div>
<?php endif ?>
