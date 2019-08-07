<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 *
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{

    private $decoded;
    public $paginate = [
        'page' => 1,
        'limit' => 10,
        'maxLimit' => 50,
        'sortWhitelist' => [
            'id', 'title', 'content', 'created'
        ]
    ];

    public function initialize()
    {
        parent::initialize();

        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
            $this->decoded = JWT::decode($token, Security::getSalt(), ['HS256']);
        }

        $this->Auth->allow(['index', 'view']);

    }

    public function index()
    {

        try {

            if (!$this->request->is(['GET'])) {
                throw new MethodNotAllowedException();
            }

            $articles = $this->Articles->find('all', [
                'contain' => 'Users'
            ])
                ->select(['Articles.id', 'Articles.title', 'Articles.content', 'Users.id', 'Users.name', 'Users.email', 'Articles.created', 'Articles.modified'])
                ->orderAsc('Articles.title');

            $articles = $this->paginate($articles);

            $pageConfig = $this->request->getParam('paging')['Articles'];

            $pagination = [
                'page_count' => $pageConfig['pageCount'],
                'current_page' => $pageConfig['page'],
                'has_next_page' => $pageConfig['nextPage'],
                'has_prev_page' => $pageConfig['prevPage'],
                'count' => $pageConfig['count'],
                'limit' => $pageConfig['limit'],
            ];

            $this->set([
                'data' => [
                    'articles' => $articles
                ],
                'pagination' => $pagination,
                '_serialize' => ['data', 'pagination']
            ]);

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

    }

    public function view($id = null)
    {
        try {

            if (!$this->request->is('GET')) {
                throw new MethodNotAllowedException();
            }

            $article = $this->Articles->find()
                ->select(['Articles.id', 'Articles.title', 'Articles.content', 'Users.id', 'Users.name', 'Users.email', 'Articles.created', 'Articles.modified'])
                ->contain(['Users'])
                ->where(['Articles.id' => $id])
                ->first();

            if (!$article) {
                throw new \Exception(__('Article not Found'), 404);
            }

            $this->set([
                'data' => [
                    'article' => $article
                ],
                '_serialize' => ['data']
            ]);

        } catch (\Exception $e) {

            throw new \Exception($e->getMessage(), $e->getCode());

        }
    }

    public function add()
    {

        try {

            if (!isset($this->decoded->sub)) {
                throw new UnauthorizedException();
            }

            if (!$this->request->is('POST')) {
                throw new MethodNotAllowedException();
            }

            $entity = $this->Articles->newEntity();

            $entity->user_id = $this->decoded->sub;
            $entity->title = $this->request->getData('title');
            $entity->content = $this->request->getData('content');

            if (!$this->Articles->save($entity)) {
                throw new \Exception(__('The article could not be saved. Please, try again.'), 502);
            }

            $this->set([
                'data' => __('The article has been saved.'),
                '_serialize' => ['data']
            ]);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

    public function edit($id = null)
    {

        try {

            if (!isset($this->decoded->sub)) {
                throw new UnauthorizedException();
            }

            if (!$this->request->is(['POST', 'PUT'])) {
                throw new MethodNotAllowedException();
            }

            $entity = $this->Articles->find()
                ->where(['Articles.id' => $id])
                ->andWhere(['Articles.user_id' => $this->decoded->sub])
                ->first();

            if (!$entity) {
                throw new \Exception(__('Article not Found'), 404);
            }

            $entity = $this->Articles->patchEntity($entity, $this->request->getData());

            if (!$this->Articles->save($entity)) {
                throw new \Exception(__('The article could not be updated. Please, try again.'), 502);
            }

            $this->set([
                'data' => __('The article has been updated.'),
                '_serialize' => ['data']
            ]);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }

}
