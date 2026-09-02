<?php

namespace App\Twig;

use App\Entity\Medium;
use Twig\Attribute\AsTwigFunction;

class MediaExtension
{
    #[AsTwigFunction('guess_medium')]
    public function guessMedium(iterable $media, string $tag): ?Medium
    {
        foreach ($media as $medium) {
            if ($medium->getTag() === $tag) {
                return $medium;
            }
        }

        return null;
    }
}
