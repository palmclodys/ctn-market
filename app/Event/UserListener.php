<?php

App::uses('CakeEventListener', 'Event');

class UserListener implements CakeEventListener {
    
    public function implementedEvents() {
        return array(
	        'Model.User.afterRegister' => 'afterRegister',
	    );
    }

    public function afterRegister(CakeEvent $event) {

    	if ($event->subject()->data['User']['type'] == 'pro') {

    		debug($event->subject()->data['User']);

    		$data = $event->subject()->data;
			$userDetail = array(
				'user_id' => $event->subject()->data['User']['id'],
				'credits' => 0,
				'pack' => 'none',
				'active' => 0,
			);

			$this->User = ClassRegistry::init('User');
			$this->User->Credit->create();
			$this->User->Credit->save($userDetail, false);

    		/*$emailConfig = Configure::read('Users.emailConfig');

	    	if ($emailConfig) {
				$Email = new CakeEmail($emailConfig);
			} else {
				$Email = new CakeEmail('default');
			}

			$Email = $this->_getMailInstance();
			$Email->to('palmclodys@yahoo.fr')
				->from(Configure::read('App.defaultEmail'))
				->emailFormat('html')
				->subject('Inscription Compte Pro')
				->template('new_user_pro')
				->viewVars(array(
				'model' => 'User',
				'user' => $event->subject()->data)
				->send();*/
		}
	}
}


/*Send to many users

$result = $email->template($template,'default')

                ->emailFormat('html')

                ->to(array('first@gmail.com','second@gmail.com','third@gmail.com')))
                ->from($from_email)

                ->subject($subject)

               ->viewVars($data);
*/