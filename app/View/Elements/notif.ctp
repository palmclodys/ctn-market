<div class="alert alert-<?php echo isset($type)? $type:"success"; ?>">
    <a class="close" href="#" onclick="$(this).parent().slideUp()">x</a>
    <?php echo $message; ?>
</div>