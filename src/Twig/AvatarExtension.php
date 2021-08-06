<?php

namespace App\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AvatarExtension extends AbstractExtension
{
    /**
     * @var \Twig\Environment
     */
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('avatar', [$this, 'avatar'], ['is_safe' => ['html']]),
        ];
    }

    public function avatar($value): string
    {

        return "<img alt='' src='https://avatars.dicebear.com/api/human/$value.svg?w=50&h=50'>";
    }
}
