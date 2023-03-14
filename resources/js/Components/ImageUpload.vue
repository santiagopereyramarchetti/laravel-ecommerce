<template>
    <div>
        <div class="dropzone" id="image-upload">
            <div class="dz-message" data-dz-message>
                <div>Drop images here to upload</div>
                <div>You can only upload 3 images</div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import Dropzone from "dropzone";
    import "dropzone/dist/dropzone.css"
    import { onMounted } from "vue";

    const props = defineProps({
        maxFilesize:{
            type: Number,
            default: 1024
        },
        maxFiles: {
            type: Number,
            default: 5,
        },
        modelType:{
            type: String,
            required: true
        },
        modelId:{
            type: Number,
            required: true
        }
    })

    onMounted( () => {
        let dropzone = new Dropzone("#image-upload",{
            url: "/admin/upload-images",
            headers:{
                "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']")?.content
            },
            paramName: 'image',
            maxFilesize: props.maxFilesize,
            maxFiles: props.maxFiles,
            acceptedFiles: ".jpeg, .jpg, .png",
            addRemoveLinks: true,
        })

        dropzone.on("sending", (file, xhr, fd) => {
            fd.append('modelType', props.modelType)
            fd.append('modelId', props.modelId)
        })
    })
</script>

<style lang="scss" scoped>

</style>