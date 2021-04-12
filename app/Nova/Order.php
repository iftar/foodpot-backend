<?php

namespace App\Nova;

use App\Nova\Filters\TodaysOrders;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            ID::make(__('ID'), 'id')->sortable(),
            Text::make("first_name"),
            Text::make("last_name"),
            Text::make("phone"),

            Text::make("Address Line 1"),
            Text::make("Address Line 2")->hideFromIndex(),
            Text::make("City")->hideFromIndex(),
            Text::make("County")->hideFromIndex(),
            Text::make("Post code"),
            Text::make("Notes", "notes"),
            Text::make("Status", "status")->hideFromIndex(),
            Date::make("Ordered Date", "required_date")
                ->hideFromIndex(),
            BelongsTo::make("user"),
            BelongsTo::make("collectionPointTimeSlot")->hideFromIndex(),
            BelongsTo::make("collectionPoint")->hideFromIndex(),
            BelongsToMany::make("meals")->fields(function () {
                return [
                    Number::make('Quantity'),
                ];
            })
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
        $collection_point_id = $request->user()->charities->first()->collectionPoints->first()->id;
        return $query->where("orders.collection_point_id", $collection_point_id);
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
        return [
            new TodaysOrders()
        ];
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
