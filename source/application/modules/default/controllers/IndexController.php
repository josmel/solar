<?php

require_once('../library/SolrPhpClient/Apache/Solr/Service.php');

class Default_IndexController extends Core_Controller_ActionDefault
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {

        $this->view->hola = "Prueba Apache Solr";
    }

    public function insertAction()
    {
        if ($this->_request->isPost())
        {
            $params = $this->_getAllParams();
            $obj = new Application_Entity_RunSql('User');
            $objTwo = new Application_Entity_HobbyUser();
            if (empty($params['idUser']))
            {
                $obj->save = $params;
                $id = $obj->save;
                for ($i = 0; $i < count($params['hobby']); $i++)
                {
                    $objTwo->insertUserHobby($params['hobby'][$i], $id);
                }
                $this->indexarSolr($params, $id);
            }
            else
            {
                $objTwo->deleteUserHobby($params['idUser']);
                $obj->edit = $params;
                for ($i = 0; $i < count($params['hobby']); $i++)
                {
                    $objTwo->insertUserHobby($params['hobby'][$i], $params['idUser']);
                }
                $this->indexarSolr($params, $params['idUser']);
            }

            $this->_redirect('/default/index/list');
        }
        $form = new Application_Entity_UserForm();
        $this->view->form = $form;
        $this->view->hola = "Prueba Apache Solr";
    }

    public function editAction()
    {

        $id = $this->_getParam('id', 0);
        $form = new Application_Entity_UserForm();
        if (!empty($id))
        {
            $obj = new Application_Entity_RunSql('User');
            $obj->getone = $id;
            $dataObj = $obj->getone;
            $form->populate($dataObj);
        }
        $this->view->form = $form;
    }

    public function listAction()
    {

        $obj = new Application_Entity_User();
        $dataObj = $obj->listAll();
        $this->view->data = $dataObj;
        $this->view->submit = "Guardar Blog";
        $this->view->action = "/blog/new";
    }

    public function indexarSolr($params, $id)
    {

        $solr = new Apache_Solr_Service('localhost', 8983, '/solr/');
        $hobby = new Application_Entity_User();
        $totalHobby = $hobby->findAll($id);

        if ($solr->ping())
        {
            $solr->deleteByQuery('id:' . $id);
          //  $solr->deleteByQuery('*:*');
            $document = new \Apache_Solr_Document ( );
            $document->addField('id', $id);
            $document->addField('url', $params['flagAct']);
            $document->addField('category', $params['firstName']);
            $document->addField('keywords', $params['lastName']);
            if (count($totalHobby) > 0)
            {
                foreach ($totalHobby as $resultado)
                {
                    $document->setMultiValue('title', $resultado['name']);
                }
            }
            $document->addField('author', $params['name']);
            $solr->addDocument($document);
            //$solr->commit();
            //$solr->optimize();
            return;
        }
        echo 'error de conexion con Solr';
        exit;
    }

    public function buscarAction()
    {
        if ($this->_request->isGet())
        {
            $idParametro = $this->_getParam('q', 0);
            if (!empty($idParametro))
            {
                $limit = 10;
                $query = isset($idParametro) ? $idParametro : false;
                $results = false;
                if ($query)
                {
                    $solr = new Apache_Solr_Service('localhost', 8983, '/solr/');
                    if (get_magic_quotes_gpc() == 1)
                    {
                        $query = stripslashes($query);
                    }
                    try
                    {
                        $results = $solr->search($query, 0, $limit);
                        if ($results)
                        {
                            $total = (int) $results->response->numFound;
                            $start = min(1, $total);
                            $end = min($limit, $total);
                        }
                    }
                    catch (Exception $e)
                    {
                        return;
                    }
                }
                $this->view->hola = "Prueba Apache Solr";
                $this->view->total = $total;
                $this->view->start = $start;
                $this->view->end = $end;
                $this->view->resultado = $results->response->docs;
            }
        }
    }

}

/*public function indexarSolr()
    {
    
        $solr = new Apache_Solr_Service('localhost', 8080, '/solr/');
        if ($solr->ping())
        {
            // $solr->deleteByQuery('id:' . $id);
            //  $solr->deleteByQuery('*:*');
            $document = new \Apache_Solr_Document ( );
            $document->addField('id', rand(20, 100) . 'id');
            $document->addField('url', 'http//ww.facebook3.com');
            $document->addField('category', 'futbol');
            $document->addField('keywords', '245745474');
            $document->setMultiValue('title', 'futbol total');
            $document->setMultiValue('title', 'voley');
            $document->addField('author', 'josmel yupanqui');
            $solr->addDocument($document);
            //$solr->commit();
            //$solr->optimize();
            echo 'se indexo correctamente';
            exit;
        }
        else
        {
            $this->_redirect('/default/index/indexar');
        }

        ;
        $this->_redirect('/default/index/indexar');
    }

/* $document->id = rand(4, 10);
              $document->title = 'prueba.solr';
              $document->_version_ = '45454545454';
              // $document->_root_ = 'root'; */

            //   $document->addField('price', 12.4554);
            //    $document->addField('title','prueba de titulo');
            // $document->addField('_version_', 14673725);
            //  $document->addField('_root_', );
            /*  $document->links = 'httt';
              $document->last_modified = '2014-11-15';
              $document->content_type = 'prueba.solr';
              $document->url = 'prueba.solr';
              $document->resourcename = 'prueba.solr';
              $document->comments = 'prueba.solr';
              $document->description = 'prueba.solr';
              $document->subject = 'prueba.solr';
              $document->title = 'prueba.solr';
              $document->store = 'prueba.solr';
              $document->inStock = true;
              $document->popularity = 14545;
              $document->price = 155.255;
              $document->weight = true;
              $document->includes = 'prueba.solr';
              $document->features = 'prueba.solr';
              $document->cat = 'prueba.solr';
              $document->manu = 'prueba.solr';
              $document->name = 'prueba.solr';
              $document->sku = 'prueba.solr';
              $document->content = 'prueba.solr';
              $document->text = 'prueba.solr';
              $document->text_rev = 'prueba.solr';
              $document->manu_exact = 'prueba.solr';
              $document->payloads = 'prueba.solr'; */

            //  var_dump($document);exit;