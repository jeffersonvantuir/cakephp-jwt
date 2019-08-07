<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    private $decoded;

    public function initialize()
    {
        parent::initialize();

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
            $this->decoded = JWT::decode($token, Security::getSalt(), ['HS256']);
        }


        $this->Auth->allow(['add', 'login']);

    }

    public function add()
    {

        try {

            $this->Crud->on('afterSave', function(Event $event) {
                if ($event->subject->created) {

                    $this->set('data', [
                        'id' => $event->subject->entity->id,
                        'message' => __('User has been registered.'),
                        'token' => JWT::encode(
                            [
                                'sub' => $event->subject->entity->id,
                                'exp' =>  time() + 604800
                            ],
                            Security::getSalt())
                    ]);
                    $this->Crud->action()->config('serialize.data', 'data');

                }

            });

            return $this->Crud->execute();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

    public function edit()
    {

        try {

            if (!isset($this->decoded->sub)) {
                throw new UnauthorizedException();
            }

            if (!$this->request->is(['PATCH', 'POST', 'PUT'])) {
                throw new MethodNotAllowedException();
            }

            $user = $this->Users->find()
                ->where(['id' => $this->decoded->sub])
                ->first();

            if (!$user) {
                throw new \Exception(__('User not found'), 404);
            }

            $user = $this->Users->patchEntity($user, $this->request->getData());

            if (!$this->Users->save($user)) {

                throw new \Exception(__('The user could not be updated. Please, try again.'), 500);

            }

            $this->set([
                'data' => __('The user has been updated.'),
                '_serialize' => ['data']
            ]);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

    public function login()
    {

        $user = $this->Auth->identify();

        if (!$user) {
            throw new UnauthorizedException(__('E-mail or Password invalid.'));
        }

        $this->set([
            'success' => true,
            'data' => [
                'token' => JWT::encode([
                    'sub' => $user['id'],
                    'exp' =>  time() + 604800
                ],
                    Security::getSalt())
            ],
            '_serialize' => ['success', 'data']
        ]);
    }

    /**
     * View method
     *
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {

        try {

            if (!isset($this->decoded->sub)) {
                throw new UnauthorizedException();
            }

            if (!$this->request->is('GET')) {
                throw new MethodNotAllowedException();
            }

            $user = $this->Users->find()
                ->where(['id' => $this->decoded->sub])
                ->first();

            if (!$user) {

                throw new \Exception(__('User not found.'), 404);

            }

            $this->set([
                'data' => [
                    'user' => $user
                ],
                '_serialize' => ['data']
            ]);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

}
