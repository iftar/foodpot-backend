<template>
    <card class="flex flex-col items-start justify-center h-full">
        <div class="min-w-full px-8 py-3">
            <h4 class="text-xl mb-4">Meals ordered</h4>
            <ul class="list-reset">
                <li v-for="orders in computed_orders" class="flex justify-between min-w-24 mb-1" >
                    <p>{{ orders.meal.name }}</p>
                    <p> <span class="font-black">{{ orders.quantity }}</span>  <span class="text-primary text-sm">QTY</span></p>
                </li>
            </ul>
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
            computed_orders: false
        }
    },

    mounted() {
        this.computed_orders = this.card.orders.reduce((meal, order) => {
            meal[order.pivot.meal_id] = meal[order.pivot.meal_id] || {
                quantity: 0
            };
            meal[order.pivot.meal_id].quantity += order.pivot.quantity;
            return meal
        }, {})

        for (let index in this.computed_orders) {
            this.computed_orders[index].meal = this.card.meals.find((meal) => meal.id === parseInt(index));
        }

        console.log(this.computed_orders)

    },
}
</script>
