<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div v-if="isLoading" class="h-1 w-full bg-neutral-200 dark:bg-neutral-600">
            <div class="h-1 bg-primary animate-progress"></div>
        </div>
        <div v-if="$slots.header" class="px-6 py-2 font-bold">
            <slot name="header"> </slot>
        </div>
        
        <div class="p-6">
            <slot />
        </div>

    </div>
</template>

<script setup>
    defineProps({
        isLoading: {
            type: Boolean,
            default: false
        }
    })
</script>

<style lang="scss" scoped>

</style>