<script setup lang="ts">
import { onMounted, ref } from 'vue'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'aos/dist/aos.css'
import 'glightbox/dist/css/glightbox.css'
import 'swiper/css'
import 'swiper/css/pagination'
import '@/assets/css/main.css'

import 'bootstrap'
import AOS from 'aos'
import GLightbox from 'glightbox'
import PureCounter from '@srexi/purecounterjs'
import {Swiper, SwiperSlide} from 'swiper/vue'
import {Autoplay, Pagination} from 'swiper/modules'
// import '@/assets/js/main.js'

const isMobileMenuOpen = ref(false)

onMounted(() => {
  AOS.init({
    duration: 600,
    easing: 'ease-in-out',
    once: true,
    mirror: false
  });
  const glightbox = GLightbox({
    selector: '.glightbox'
  });
  new PureCounter();
})
</script>

<template>
  <router-view />
</template>

<style>
  @import "@/assets/css/fonts.css";
</style>
