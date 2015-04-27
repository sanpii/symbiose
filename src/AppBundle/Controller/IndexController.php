<?php

namespace AppBundle\Controller;

use \PommProject\Foundation\Pomm;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Templating\EngineInterface;

class IndexController
{
    private $pomm;
    private $templating;

    public function __construct(EngineInterface $templating, Pomm $pomm)
    {
        $this->templating = $templating;
        $this->pomm = $pomm;
    }

    public function indexAction()
    {
        $result = $this->pomm['default']->getQueryManager()
            ->query('select 1');

        return new Response(
            $this->templating->render(
                'AppBundle:Index:index.html.twig',
                compact('result')
            )
        );
    }
}
