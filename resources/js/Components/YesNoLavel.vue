<template>
    <div>
        <PrimaryButton small :color="active ? 'green' : 'red'">{{ active ? 'Active' : 'Inactive'}}</PrimaryButton>
    </div>
</template>

<script setup>
    import PrimaryButton from './PrimaryButton.vue';
    defineProps({
        active:{
            type: Boolean
        }
    })
</script>

<style lang="scss" scoped>

</style>