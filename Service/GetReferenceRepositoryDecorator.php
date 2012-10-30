<?php
namespace CodeMonkeysRu\RepositoryAliasBundle\Service;

class GetReferenceRepositoryDecorator
{

    private $name = null;
    private $repo = null;

    /**
     *
     * @var \CodeMonkeysRu\RepositoryAliasBundle\Service\Repository
     */
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

    public function getReference($id)
    {
        return $this->creator->getEntityManager()->getReference(
            $this->creator->getRepositoryName($this->name),
            $id
        );
    }

    public function getContainer()
    {
        return $this->creator->getContainer();
    }

}