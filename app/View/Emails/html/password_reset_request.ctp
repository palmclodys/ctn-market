<?php
echo __d('users', 'Une demande de réinitialisation de mot de passe a été envoyée. Pour modifier votre mot de passe cliquez sur le lien ci-dessous');
echo "\n";
?>
<a href="<?php echo Router::url(array('admin' => false, 'controller' => 'users', 'action' => 'reset_password', $token), true); ?>">Réinitialisez votre mot de passe</a>