<?php


namespace App\Service;

use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;

class Slugger
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function slugify(string $stringInput): AbstractUnicodeString
    {
        return $this->slugger->slug(strtolower($stringInput));
    }
}
