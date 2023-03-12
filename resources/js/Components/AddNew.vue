<template>
    <div>
        <div class="flex itemx-center justify-between">
            <slot></slot>
            
            <PrimaryButton v-if="$slots.filters" color="white" @click="showFilters=!showFilters">
                <FilterIcon class="w-4 h-4"></FilterIcon>
            </PrimaryButton>
        </div>
    </div>
    <slot v-if="showFilters" name="filters"></slot>
</template>

<script setup>
    import FilterIcon from '@/Components/Icons/Filter.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { ref } from 'vue';

    const showFilters = ref(false)
</script>

<style lang="scss" scoped>

</style>