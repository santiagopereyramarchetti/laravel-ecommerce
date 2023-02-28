<template>
    <div class="bg-green-600 text-white rounded fixed right-0 top-0 px-4 py-2 mt-4 z-10" v-if="message">
        {{ message }}
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    onMounted(() => {
        setTimeout(() => {
            message.value = ""
        }, 5000)
    })

    const message = ref(usePage().props.flash.success)
</script>

<style lang="scss" scoped>

</style>