<script setup>
import ChildToast from "@/Components/Toastify/ChildToast.vue";

const props = defineProps({
    notifications: Object,
});
</script>

<template>
    <div id="notification-area" class="notification-area position-fixed top-[10px] padding-[10px] right-[10px] w-[350px] z-50 max-h-[80%] overflow-y-auto">
        <child-toast
            v-for="(item, key) in props.notifications"
            :key="key"
            :message="item"
        >
        </child-toast>
    </div>
</template>
<style lang="scss" scoped>
</style>
