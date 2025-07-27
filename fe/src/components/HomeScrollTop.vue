<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

const isActive = ref(false);

function toggleScrollTop() {
  isActive.value = window.scrollY > 100;
}

function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth',
  });
}

onMounted(() => {
  toggleScrollTop();
  document.addEventListener('scroll', toggleScrollTop);
});

onUnmounted(() => {
  document.removeEventListener('scroll', toggleScrollTop);
});
</script>

<template>
  <a href="#"
     id="scroll-top"
     class="scroll-top d-flex align-items-center justify-content-center"
     :class="{ active: isActive }"
     @click.prevent="scrollToTop"
  >
    <i class="bi bi-arrow-up-short"></i></a>
</template>

<style scoped lang="scss">

</style>