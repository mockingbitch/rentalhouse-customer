<template>
    <component :is="name" :class="classes" :color="color"></component>
</template>

<script>
import IconFacebook from "@/Icons/IconFacebook.vue";
import IconGoogle from "@/Icons/IconGoogle.vue";
import IconWarning from "@/Icons/IconWarning.vue";
import IconInfo from "@/Icons/IconInfo.vue";
import IconSuccess from "@/Icons/IconSuccess.vue";

export default {
    components: {
        IconFacebook,
        IconGoogle,
        IconWarning,
        IconInfo,
        IconSuccess,
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
