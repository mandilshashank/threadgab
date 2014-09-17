<?php

namespace Threadgab\Bundle\PortalBundle;

//use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;

class PortalTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('htmldecode', array($this, 'htmlDecoder')),
        );
    }

    public function htmlDecoder($str)
    {
        return html_entity_decode($str);
    }

    public function getName()
    {
        return 'portal_twig_extension';
    }
}
