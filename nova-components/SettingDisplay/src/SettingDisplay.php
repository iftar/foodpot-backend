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
        if($charity) {
            $collectionPoint = auth()->user()->charity()->collectionPoints->first();

            return $this->withMeta([
                'charity' => $charity,
                'collectionPoint' => $collectionPoint,
                "cut_off_point_link" => url("/nova/resources/collection-points/". auth()->user()->charity()->collectionPoints->first()->id . "/edit")
            ]);
        }
        return  $this->withMeta([]);
    }


}
