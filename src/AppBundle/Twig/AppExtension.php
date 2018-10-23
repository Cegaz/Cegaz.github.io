<?php

namespace AppBundle\Twig;

use AppBundle\Service\Slug;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('slug', array($this, 'slugFilter')),
        );
    }

    public function slugFilter($text)
    {
        return Slug::slugify($text);
    }

}