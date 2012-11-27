# CodeMonkeys RepositoryAliasBundle #

Bundle provides alternative syntax for working with entity repositories.
See Example section for more.

## Installation ##

Install thru composer

    php composer.phar require codemonkeys-ru/repository-alias-bundle

And add bundle to your AppKernel.php

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            ...
            new CodeMonkeysRu\RepositoryAliasBundle\RepositoryAliasBundle(),
            ...
        }
    }


### Example ###

config.yml

	repository_alias:
        repository_key: "project.repo"
		repository:
			blogpost: AcmeBundle:Blog\Post
			blogcomment: AcmeBundle:Blog\Comment

Instead of:

	$repo = $this->getDoctrine()->getRepository('AcmeBundle:Blog\Post');
	$post = new Acme\AcmeBundle\Blog\Post('title', 'post', $author);

Use:

	$repo = $this->get('project.repo.blogpost'); //Note repository_key "project.repo" usage.
	$post = $this->get('project.repo.blogpost')->newEntity('title', 'post', $author);


# ChangeLog

## v0.1.2

* Added getAliasFor() method

## v0.1.1

* Added backloop interface

## v0.1.0

* Initial version
