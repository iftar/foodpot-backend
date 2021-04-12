<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3 w-2/3">
            <h1 class="font-bold text-center text-xl text-80 font-light mb-5">Quick Links</h1>
            <div class="flex flex-col items-left justify-center">
                <template v-for="(link, linkName) in card.links">
                    <a :href="link" >{{ linkName }}</a>
                </template>
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
        //
    },
}
</script>
