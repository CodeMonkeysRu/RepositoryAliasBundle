<?php
namespace CodeMonkeysRu\RepositoryAliasBundle\Service;

class Repository
{

    private $refMap = array();
    private $repositoryMap = array();
    private $em;
    private $container;

    public function __construct($em, $repositoryMap, $container)
    {
        $this->repositoryMap = $repositoryMap;
        $this->em = $em;
        $this->container = $container;
        $result = $this;
        return $result;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * Factory method for instanciating new repo services
     *
     * @param type $repositoryName
     * @return Repository\CreationalRepositoryDecorator
     */
    public function get($repositoryName)
    {
        $repo = $this->em->getRepository(
            $this->getRepositoryName($repositoryName)
        );

        $decoratedRepo = new \CodeMonkeysRu\RepositoryAliasBundle\Service\CreationalRepositoryDecorator($this, $repositoryName, $repo);
        $decoratedRepo = new \CodeMonkeysRu\RepositoryAliasBundle\Service\GetReferenceRepositoryDecorator($this, $repositoryName, $decoratedRepo);

        if (is_a($repo, '\\CodeMonkeysRu\\RepositoryAliasBundle\\Entity\\BackloopAwareInterface')) {
            $repo->setBackloop($decoratedRepo);
        }

        return $decoratedRepo;
    }

    static private $aliasMap = null;

    public function getAliasFor($entityClass)
    {
        if (is_object($entityClass)) {
            $entityClass = get_class($entityClass);
        }
        if (self::$aliasMap === null) {
            self::$aliasMap = array();
            foreach ($this->repositoryMap as $alias => $classname) {
                $meta = $this->em->getClassMetaData($classname);
                self::$aliasMap[$meta->name] = $alias;
            }
        }
        if (!array_key_exists($entityClass, self::$aliasMap)) {
            return false;
        }
        return self::$aliasMap[$entityClass];
    }

    public function newEntity()
    {

        $args = func_get_args();
        $name = array_shift($args);

        if (!isset($this->refMap[$name])) {
            $meta = $this->em->getClassMetaData($this->getRepositoryName($name));
            $this->refMap[$name] = new \ReflectionClass($meta->name);
        }

        $class = $this->refMap[$name];

        if ($class->getConstructor() === null) {
            $result = $class->newInstance();
        } else {
            $result = $class->newInstance($args);
        }

        return $result;
    }

    public function getRepositoryName($name)
    {
        return $this->repositoryMap[$name];
    }

}