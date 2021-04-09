<template>
    <card class="flex flex-col items-center justify-center cursor-pointer"
          @click="redirect" >
        <div class="px-3 py-3">
            <h1 class="font-bold text-center text-xl text-80 font-light mb-5">Total Orders</h1>
            <br>
            <p class="text-center text-4xl">{{ card.orders.length }}</p>
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
    methods: {
       redirect: function() {
            console.log("aksjdlajs")
            // window.location.href = window.location.origin + '/nova/resources/orders'
       }
    },
    mounted() {
    },
}
</script>
