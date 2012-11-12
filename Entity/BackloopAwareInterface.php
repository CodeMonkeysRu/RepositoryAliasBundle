<?php
namespace CodeMonkeysRu\RepositoryAliasBundle\Entity;

interface BackloopAwareInterface
{

    public function setBackloop($backloop); //@Todo typehint?
    public function getBackloop();

}