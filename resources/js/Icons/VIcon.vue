<template>
    <component :is="name" :class="classes" :color="color"></component>
</template>

<script>
import IconFacebook from "@/Icons/IconFacebook.vue";
import IconGoogle from "@/Icons/IconGoogle.vue";

export default {
    components: {
        IconFacebook,
        IconGoogle
    },
    props: {
        name: {
            type: String,
            required: true,
        },
        classes: {
            type: String,
            required: false,
            default: "icon-xs",
        },
        color: String
    },
}
</script>
