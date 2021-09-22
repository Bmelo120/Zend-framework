// Dentro de module>Base>src>Base>Services 

-> Responsavel por fazer inserção no banco de dados, atualizações e mover registros

<?php 

namespace Basa\Service;

use Entity\ORM\EntityManager;
use  Zend\Stdlib\Hydrator\ClassMethods;

class AbstractService {
    protected $em;
    protected $entity;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }
    
    public function save(Array $data = array()) //salva registros alterados e novos registros    
    {
        if(isset($data['id'])){// Caso existir algum registro no banco de dados

            $entity = $this->em->getReference($this->entity, $data['id']); // Traz um registro selecionado no banco de dados

            $hydrator = new ClassMethods();
            $hydrator->hydrate($data, $entity); // Preenche os dados

        }else{ // Caso não existi instancia a entidade 
            $entity = new $this->entity($data);

        }

        $this->em->persist($entity); // salva
        $this->em->flush(); // executa

        return $entity;
    } 
    
    public function remove(Array $data = array()) 
    {
       $entity = $this->em->getRepository($this->entity)->findOneyBy($data); // Pesquisa no reposito e trazer um registro conforme o parametro passado
       if ($entity){ // Se exitir registos remove os dados 
        $this->em->remove($entity);
        $this->em->flush; // confirmação ou Commit

        return $entity;

       }
    }

}
?>