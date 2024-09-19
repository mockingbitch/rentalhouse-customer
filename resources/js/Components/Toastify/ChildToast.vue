<script setup>
import { ref, watch, onMounted } from 'vue';
import VIcon from "@/Icons/VIcon.vue";

const props = defineProps({
    message: String,
    onRemove: Function,
});

const visible = ref(false);

const showNotification = () => {
    visible.value = true;
};

const handleAfterLeave = () => {
    // Notify parent component to remove this notification
    props.onRemove();
};

onMounted(() => {
    showNotification();
    setTimeout(() => {
        visible.value = false;
    }, 3000); // Hide notification after 3 seconds
});
</script>

<template>
    <transition name="fade" @after-leave="handleAfterLeave">
        <div
            class="notification-item"
            data-type="alert"
        >
            <v-icon name="IconFacebook" />
            <div v-if="visible" class="mx-4">
                <div class="notification-app-name">{{ props.message }}</div>
                <div class="notification-title">Task Due</div>
                <div class="notification-body">Your task "Write Report" is due tomorrow.</div>
            </div>
            <div class="notification-options">X</div>
        </div>
        <!--            <div class="notification-close iconoir-xmark"></div>-->
    </transition>
</template>

<style scoped>

.notification-item {
    backdrop-filter: blur(7px);
    background-color: rgb(66 66 69/70%);
    box-shadow: inset 0 0 1px rgb(232 232 237/11%);
    color: rgb(245, 245, 247);
    display: flex;
    will-change: transform;
    z-index: 1;
    transition: linear 250ms;
    border-radius: 7px;
    padding: 15px;
    margin-bottom: 10px;
    font-family: "Work Sans", sans-serif !important;
    align-items: center;
}

.notification-item[data-type="banner"]:hover {
    opacity: 0.9; /* Slight interactive effect on hover for banners */
}

.notification-title {
    font-size: 14px;
    font-weight: bold;
    margin-right: 8px;
    margin-bottom: 4px;
}

.notification-body {
    font-size: 12px;
    opacity: 0.8;
}

.notification-options {
    cursor: pointer;
    user-select: none;
    font-size: 12px;
    color: rgb(245, 245, 247);
    margin-left: auto;
}

.notification-options:hover {
    text-decoration: underline;
}

/* Custom scrollbar for the notification area */
.notification-area::-webkit-scrollbar {
    width: 6px;
}

.notification-app-name {
    font-size: 10px;
    font-weight: bold;
    text-transform: uppercase; /* Ensures the app name is in small caps */
    color: #aaa; /* Lighter text color for less emphasis */
    margin-bottom: 4px; /* Spacing between app name and title */
}

.notification-area::-webkit-scrollbar-thumb {
    background-color: #c1c1c1;
    border-radius: 4px;
}

.notification-area::-webkit-scrollbar-track {
    background: transparent;
}

.notification-item:hover .notification-close {
    visibility: visible;
}

.notification-close {
    position: absolute;
    top: -7.5px;
    left: -7.5px;
    cursor: pointer;
    background: rgb(118 120 124);
    visibility: hidden;
    border-radius: 50%;
    font-size: 14px;
    padding: 3px;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

.notification-icon {
    display: flex;
    align-items: center;
    font-size: 20px;
    margin-right: 12px;
}

.text-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
}
</style>
