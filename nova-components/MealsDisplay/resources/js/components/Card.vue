<template>
    <card class="flex flex-col items-start justify-center h-full">
        <div class="min-w-full px-8 py-3">
            <h4 class="font-bold text-center text-xl text-80 font-light mb-4">{{ this.card.heading }}</h4>
            <div v-if="this.card.orders !== undefined && Object.keys(computed_orders).length !== 0">
                <ul  class="list-reset">
                    <li v-for="(orders, index) in computed_orders" class="flex justify-between min-w-24 mb-1" >
                        <p>{{ orders.meal.name }}</p>
                        <div>
                            <p> <span class="font-black">{{ orders.quantity }}</span>  <span class="text-primary text-sm">QTY</span></p>
                        </div>
                    </li>
                </ul>
            </div>

            <div v-else-if="this.card.orders === undefined && Object.keys(this.card.meals).length !== 0">
                <ul  class="list-reset">
                    <li v-for="meal in this.card.meals" class="flex justify-between min-w-24 mb-1" >
                        <p>{{ meal.name }}</p>
                        <div>
                            <p> <span class="font-black">{{ meal.total_quantity_available }}</span>  <span class="text-primary text-sm">left</span></p>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p class="text-center text-xl"> There has been no {{ this.card.orders ? 'orders' : 'meals' }}</p>
            </div>
        </div>
    </card>
</template>

<script>
export default {
    props: [
        'card',

        // The following props are only available on resource detail cards...
        // 'resource',
        // 'resourceId',
        // 'resourceName',
    ],
    data() {
        return {
            computed_orders: false,
            meals_capacity: false
        }
    },

    mounted() {
        if(this.card.orders) {
            this.computed_orders = this.card.orders.reduce((meal, order) => {
                meal[order.pivot.meal_id] = meal[order.pivot.meal_id] || { quantity: 0 };
                meal[order.pivot.meal_id].quantity += order.pivot.quantity;
                return meal
            }, {})

            for (let index in this.computed_orders) {
                this.computed_orders[index].meal = this.card.meals.find((meal) => meal.id === parseInt(index));
            }
        }
    },
}
</script>
