<?php
namespace CodeMonkeysRu\RepositoryAliasBundle\Service;

class CreationalRepositoryDecorator
{

    private $name = null;
    private $repo = null;
    private $creator = null;

    public function __construct($creator, $name, $repo)
    {
        $this->creator = $creator;
        $this->name = $name;
        $this->repo = $repo;
        if (is_a($this->repo, '\\Symfony\\Component\\DependencyInjection\\ContainerAwareInterface')) {
            $this->repo->setContainer($this->getContainer());
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->repo, $method), $args);
    }

    public function newEntity()
    {
        $args = func_get_args();
        return $this->creator->newEntity($this->name, $args);
    }

    public function getContainer()
    {
        return $this->creator->getContainer();
    }

}