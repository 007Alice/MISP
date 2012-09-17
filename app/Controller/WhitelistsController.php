<?php
App::uses('AppController', 'Controller');
/**
 * Whitelists Controller
 *
 * @property Whitelist $Whitelist
 */
class WhitelistsController extends AppController {


/**
 * index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Whitelist->recursive = 0;
		$this->set('whitelists', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Whitelist->id = $id;
		if (!$this->Whitelist->exists()) {
			throw new NotFoundException(__('Invalid whitelist'));
		}
		$this->set('whitelist', $this->Whitelist->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Whitelist->create();
			if ($this->Whitelist->save($this->request->data)) {
				$this->Session->setFlash(__('The whitelist has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The whitelist could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Whitelist->id = $id;
		if (!$this->Whitelist->exists()) {
			throw new NotFoundException(__('Invalid whitelist'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Whitelist->save($this->request->data)) {
				$this->Session->setFlash(__('The whitelist has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The whitelist could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Whitelist->read(null, $id);
		}
	}

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Whitelist->id = $id;
		if (!$this->Whitelist->exists()) {
			throw new NotFoundException(__('Invalid whitelist'));
		}
		if ($this->Whitelist->delete()) {
			$this->Session->setFlash(__('Whitelist deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Whitelist was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
