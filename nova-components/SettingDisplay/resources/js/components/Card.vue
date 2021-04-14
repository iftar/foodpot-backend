<template>
    <card class="flex flex-col items-center justify-center w-full">
        <div class="px-8 py-3 w-2/3">
            <h1 class="font-bold text-center text-xl text-80 font-light mb-4">Charity information</h1>
            <div class="" v-if="card.collectionPoint">
                <ul class="list-reset">
                    <li class="flex justify-between">Cut off point
                        <span class="text-primary">{{ card.collectionPoint.cut_off_point }}</span>
                        <a :href="card.cut_off_point_link">Edit</a>
                    </li>
                </ul>
            </div>
            <div v-else>
                <p class="text-center text-xl">You have no Collection point</p>
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

    mounted() {
    },
}
</script>
