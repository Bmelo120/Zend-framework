// Dentro de module>Base>src>Base>Entity

-> A classe tem 2 metodos Construtor onde passa um paramento e ToArray para devolver os dados em array

<?php 

namespace Base\Entity;

use Zend\Stdlib\Hydrator\ClassMethods;

abstract class AbstractEntity {
    public function __construct(Array $options = array())
    {
        $hydrator = new ClassMethods();
        $hydrator ->hydrate($options, $this);
    }

    public function toArray() {
        $hydrator = new ClassMethods();
        return $hydrator->extends($this);

    }

}
?>