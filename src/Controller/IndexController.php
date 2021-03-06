<?php
declare(strict_types = 1);

namespace App\Controller;

use \PommProject\Foundation\Pomm;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Templating\EngineInterface;

final class IndexController
{
    private $pomm;
    private $templating;

    public function __construct(EngineInterface $templating, Pomm $pomm)
    {
        $this->templating = $templating;
        $this->pomm = $pomm;
    }

    public function indexAction(): Response
    {
        $result = $this->pomm['db']->getQueryManager()
            ->query('select 1');

        return new Response(
            $this->templating->render(
                'Index/index.html.twig',
                compact('result')
            )
        );
    }
}
