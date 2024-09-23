<template>
    <component :is="resolveComponent(name)" :class="classes" :color="color"></component>
</template>

<script setup>
import IconFacebook from "@/Icons/IconFacebook.vue";
import IconGoogle from "@/Icons/IconGoogle.vue";
import IconWarning from "@/Icons/IconWarning.vue";
import IconInfo from "@/Icons/IconInfo.vue";
import IconSuccess from "@/Icons/IconSuccess.vue";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    classes: {
        type: String,
        default: "icon-xs",
    },
    color: String
});

const resolveComponent = (name) => {
    const components = {
        IconFacebook,
        IconGoogle,
        IconWarning,
        IconInfo,
        IconSuccess,
    };

    return components[name] || null;
};
</script>
