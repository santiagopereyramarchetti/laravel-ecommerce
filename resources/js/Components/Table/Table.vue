<template>
    <div>
        <div class="flex flex-col">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center text-sm font-light">
                    <thead
                        class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                        <tr>
                            <Th v-for="header in headers" :key="header.label"> {{ header.label }}</Th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items.data" :key="item.id" class="border-b dark:border-neutral-500">
                            <slot :item="item"/>
                        </tr>
                        <tr v-if="items.length == 0">
                            <Td :colspan="headers.length"> No data available </Td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2">
        <Pagination :links="items.meta.links"></Pagination>
    </div>
</template>

<script setup>
    import Th from './Th.vue';
    import Td from './Td.vue';
    import Pagination from './Pagination.vue';

    defineProps({
        headers: Array,
        items: Object,
    })
</script>

<style lang="scss" scoped>

</style>