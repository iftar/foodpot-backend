<?php

namespace Dashboard\SettingDisplay;

use Laravel\Nova\Card;

class SettingDisplay extends Card
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
        return 'setting-display';
    }

    public function withSettingData()
    {
        $charity = auth()->user()->charity();
        $collectionPoint = auth()->user()->charity()->collectionPoints->first();

        return $this->withMeta([
            'charity' => $charity,
            'collectionPoint' => $collectionPoint,
        ]);
    }


}
