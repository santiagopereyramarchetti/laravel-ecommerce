<template>

    <div class="flex justify-center">
        <nav aria-label="Page navigation example">
            <ul class="list-style-none flex">
            <li v-for="link in links" :key="link.label">
                <button v-html="link.label" @click.prevent="goToUrl(link)" :disabled="link.url == null" :class="{ '!bg-blue-500 text-white' : link.active, '!text-gray-500 cursor-not-allowed' : link.url == null}" class="relative block rounded bg-transparent py-1.5 px-3 text-sm text-black transition-all duration-300 hover:bg-neutral-100 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white">

                </button>
            </li>
            </ul>
        </nav>
    </div>

</template>

<script setup>
    import { router } from '@inertiajs/vue3'
    
    defineProps({
        links: {
            type: Array
        }
    })

    const goToUrl = (link) => {
        router.get(link.url)
    }
</script>

<style lang="scss" scoped>

</style>