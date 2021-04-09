<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
            <h1 class="font-bold text-center text-xl text-80 font-light mb-5">Configuration</h1>
            <p>Last Order {{ card.collectionPoint.cut_off_point }}</p>
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

    mounted() {
    },
}
</script>
