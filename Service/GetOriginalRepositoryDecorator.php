<?php
namespace CodeMonkeysRu\RepositoryAliasBundle\Service;

class GetOriginalRepositoryDecorator
{
    private $repo = null;

    public function __construct($creator, $name, $repo)
    {
        $this->repo = $repo;
        if (is_a($this->repo, '\\Symfony\\Component\\DependencyInjection\\ContainerAwareInterface')) {
            $this->repo->setContainer($this->creator->getContainer());
        }
    }

    public function getOriginalRepository()
    {
        return $this->repo;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->repo, $method), $args);
    }
}
