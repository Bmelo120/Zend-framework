/**Controller: Essa é a camada que sabe quem chamar e quando chamar para executar determinada ação,
 View: Gerencia a saída gráfica e textual da parte da aplicação visível ao usuário final,
 Model: Parte lógica da aplicação que gerencia o comportamento dos dados, ou seja, todos os seus recursos (consultas ao BD, validações, notificações, etc) */

<?php
namespace Base\Controlller;

use Zend\Mvc\Controlller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;

abstract class AbstractController extends AbstractController 
{
    //ATRIBUTOS
    protected $em;
    protected $entity;
    protected $controller;
    protected $route;
    protected $services;
    protected $form;

    abstract function __construct();

    /**1 - listar 
    2 - inserir
    3 - editar 
    4 - excluir */

    public function indexAction() // lista resultados
    {
        $list = $this->getEm()->getRepository($this->entity)->findAll(); // Retornar a variave list com todos os resultados que encontrar no finAll
        
        $page = $this->params()->fromRoute('page'); 

        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)
        ->setDafaultItemCountPerPage(10); //Quantos resultados por pagina
        
        return new ViewModel(array('data'=> $paginator, 'page' => $page));
    }

    public function inserirAction()
    {
       /** $this->form = 'MeuForm'; // Passando uma String
        *  $this->form = $this->getServiceLocator()->get('MuForm'); //Passando um objeto*/

        if (is_string($this->form)){
            $form = new $this->form;
        } else {
            $form = $this->form; 
            $request = $this->getResqueste();
            
            if ($request->isPost()){

                $form->setData($request->getPost());

                if ($form->isValid()){

                    $services = $this->getServiceLocator()->get($this->service);

                    if ($service->save($request->getPost()->toArray())){
                        $this->flashMessenger()->addSuccessMessage('Cadastrado com sucesso!');
                    }else{
                        $this->flashMessenger()->addErrorMessage('Não foi possivel cadastrar! Tente mais tarde.');
                    } 
                    return $this->redirect()->toRoute($this->route, array('controller'=> $this->controller)); //Informa rota
                }

            }

            if($this->flashMessenger()->hasSuccessMessages()){
                return new ViewModel(array(
                    'form'=> $form, 
                    'sucess' =>$this->flashMessenger()->getSuccessMessages()));
                }
                if($this->flashMessenger()->hasErroMessages()){
                    return new ViewModel(array(
                        'form'=> $form, 
                        'erro' =>$this->flashMessenger()->getErrorMessages()));
                    }

                    $this->flashMessenger()->clearMessages;

            return new ViewModel(array('form' => $form));
        }
    }

    public function editarAction()
    {
        if (is_string($this->form)){
            $form = new $this->form;
           } else {
            $form = $this->form; 

            $reques = $this->getRequest();
            $param = $this->params()->fromRoute('id', 0);

            $repository = $this->getEm()->getRepository($this->entity)->find($param);
            }
            if ($repository){

                $array = array();
                foreach($repository->toArray() as $key => $value){
                    if ($value instanceof \DateTime) // Se em algum campo tiver um registro do tipo data converte em DateTime
                    $array[$key] = $value->format('d/m/Y');
                    else
                      $array[$key] = $value;
                }
                $form->setData($array); 


                if($request->isPost()){ // verificar se e um post
                    $form->setData($request->getPost()); //setar o formulario

                    if ($form->isValid()){ // verificar o formulario
    
                        $services = $this->getServiceLocator()->get($this->service); // buscando serviço

                        $data = $request->getPost()->toArray();
                        $data['id'] = (int) $param;
    
                        if ($service->save($data){ //  salvar em data
                            $this->flashMessenger()->addSuccessMessage('Atualizado com sucesso!');
                        }else{
                            $this->flashMessenger()->addErrorMessage('Não foi possivel atualizar! Tente mais tarde.');
                        } 
                        return $this->redirect()->toRoute($this->route, array('controller'=> $this->controller)); //Informa rota
                    }
                }
               
            
            else{
                $this->flashMessenger()->addInfoMessage('Registro não foi encontrado!');
                return $this->redirect()->toRoute($this->route, array('controller'=> $this->controller));
            } 
            if($this->flashMessenger()->hasSuccessMessages()){
                return new ViewModel(array(
                    'form'=> $form, 
                    'sucess' =>$this->flashMessenger()->getSuccessMessages(),
                    'id' =>$param
                ));
                }
                if($this->flashMessenger()->hasErroMessages()){
                    return new ViewModel(array(
                        'form'=> $form, 
                        'erro' =>$this->flashMessenger()->getErrorMessages(),
                        'id'=> $param 
                    ));
                    }
                    if($this->flashMessenger()->hasInfoMessages()){
                        return new ViewModel(array(
                            'form'=> $form, 
                            'warning' =>$this->flashMessenger()->getInfoMessages(),
                            'id'=> $param 
                        ));
                        }
                        $this->flashMessenger()->clearMessages;

                        return new ViewModel(array('form' => $form, 'id' => $param));
            }
        }
    }
    public function excluirAction()
    {
        $service = $this->getServiceLocator()->get($this->service);
        $id = $this->params()->fromRoute('id', 0);

        if($service->remove(array('id'=>$id)))
            $this->flashMessengr()->addSuccessMessage('Registro deletado com sucesso!');
        else
            $this->flashMessenger()->addErroMessage('Não foi possivel deletar o registro');

        return $this->redirect()->toRoute($this->route, array('controller'=>$this->controller));
    }

    public function getEm() 
    {
        if ($this->em == null){// Verifica se é nulo 
            $this->em = $this->getServiceLocator()->get('Docrine\ORM\EntityManager');// Carrega com a instancia 
        }
        return $this->em;
    }

    
        
    
}


?>