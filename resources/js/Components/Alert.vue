<template>
    <div class="bg-green-600 text-white rounded fixed right-0 top-0 px-4 py-2 mt-4 z-10" v-if="message">
        {{ message }}
    </div>
</template>

<script setup>
    import { ref, watch } from 'vue';
    import { usePage } from '@inertiajs/vue3';

    const message = ref("")
    const timeoutHandler = ref(null)

    watch(
        () => usePage().props.flash.success,
        (successMessage) =>{
            
            message.value = successMessage

            clearTimeout(timeoutHandler.value)
            //This is a debouncer. Clean the time of setTimeout before. start and if we click many times only going to count the last
            //because every time we click the time going to restart

            if(successMessage){
                timeoutHandler.value = setTimeout(() => {
                    message.value = ""
                }, 5000)
            }
        },
        {
            inmediate: true,
        }
    )
</script>

<style lang="scss" scoped>

</style>