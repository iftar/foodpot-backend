<?php

namespace App\Nova;

use Davidpiesse\Map\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laraning\NovaTimeField\TimeField;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class CollectionPoint extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\CollectionPoint::class;

    public static function availableForNavigation(Request $request) {
        if(auth()->user()->type === "admin") return true;
        return false;
    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id'),
            Text::make("Name"),
            Text::make("Address Line 1"),
            Text::make("Address Line 2")->hideFromIndex(),
            Text::make("City"),
            Text::make("County")->hideFromIndex(),
            Text::make("Post code"),
            Select::make("Delivery Radius")
                ->searchable()
                ->hideFromIndex()
                ->options([
                '5' => '5',
                '15' => '15',
                '20' => '20',
            ]),
            Number::make("Latitude", "lat")
                ->hideFromIndex()
                ->hideFromDetail()
                ->hideWhenUpdating(),
            Number::make("Longitude", "lng")
                ->hideFromIndex()
                ->hideFromDetail()
                ->hideWhenUpdating(),
            Number::make("max daily capacity")
                ->hideFromIndex()
                ->hideFromDetail()
                ->hideWhenUpdating(),
            Slug::make("slug")
                ->hideFromIndex()
                ->creationRules('unique:slug')
                ->updateRules('unique:slug,{{resourceId}}'),
            TimeField::make("Cut off point", "cut_off_point")
                ->help("This is the time collection point stops taking orders"),
            Number::make("Quantity Per Person", "set_quantity_per_person")
                ->help("How many times can a user order?"),
            HasMany::make("Meals"),
        ];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if(auth()->user()->type == "admin") return $query;
        $collectionPoints = $request->user()->charities->map->collectionPoints->flatten()->pluck("id")->toArray();
        return $query->whereIn("collection_points.id", array_values($collectionPoints));
    }
    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

}
