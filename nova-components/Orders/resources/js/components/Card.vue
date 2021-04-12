<template>
    <card class="flex flex-col items-center justify-center cursor-pointer">
        <div class="px-3 py-3">
            <h1 class="font-bold text-center text-xl text-80 font-light mb-5">Total Orders</h1>
            <br>
            <p class="text-center text-4xl">{{  numberOfOrders }}</p>
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
            numberOfOrders: 0
        }
    },
    methods: {
    },
    mounted() {
        this.numberOfOrders = Object.keys( this.card.orders).length;
        console.log(typeof this.card.orders)
    },
}
</script>
