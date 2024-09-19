<script setup>
defineProps(['message']);
</script>

<template>
    <ul v-show="message" class="error-msg">
        <li>{{ message }}</li>
    </ul>
</template>

<style lang='scss' scoped>
.error-msg {
    color: red;
    //background-color: #ffe7eb;
    font-size: 14px;
    padding: 10px;
    border-radius: 5px;
}
</style>
