<?php

namespace Dashboard\QuickLinks;

use Laravel\Nova\Card;

class QuickLinks extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'quick-links';
    }

    public function addLinks()
    {
        return $this->withMeta([
            "links" => [
                "My Charity" => url("/nova/resources/charities/". auth()->user()->charity()->id),
                "My Collection point" =>  url("/nova/resources/collection-points/". auth()->user()->charity()->collectionPoints->first()->id)
            ]
        ]);
    }
}
