<script setup lang="ts">
// import LogoDark from './LogoDark.vue';
const navigateTo = (path: string) => {
  window.location.href = path;
};
</script>
<template>
  <!--<LogoDark />-->
  <span class="logo" @click="navigateTo('/')">Yummy<span>.</span></span>
</template>

<style scoped>
  .logo {
    font-size: 30px;
    margin: 0;
    font-weight: 700;
    color: #37373f;
    cursor: pointer;
  }
  .logo span {
    color: #ce1212;
    font-size: 36px;
  }
</style>
