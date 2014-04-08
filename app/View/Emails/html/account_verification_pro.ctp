<?php
echo __d('users', 'Bonjour %s,', $user[$model]['firstname'] . ' '. $user[$model]['lastname']);
echo "\n";
echo __d('users', 'pour activer votre compte, vous devez cliquer sur le lien dessous dans les prochaines 24 heures');
echo "\n";
?>
<a href="<?php echo Router::url(array('admin' => false, 'controller' => 'users', 'action' => 'verify', 'email', $user[$model]['email_token']), true); ?>">Activez votre compte</a>