<script setup lang="ts">
import HomeGallery from '@/components/HomeGallery.vue';
import HomeContact from '@/components/HomeContact.vue';
import HomeHero from '@/components/HomeHero.vue';
import HomeFooter from '@/components/HomeFooter.vue';
import HomeStats from '@/components/HomeStats.vue';
import HomeScrollTop from '@/components/HomeScrollTop.vue';
import HomeChefs from '@/components/HomeChefs.vue';
import HomeMenu from '@/components/HomeMenu.vue';
import HomeEvents from '@/components/HomeEvents.vue';
import HomeTestimonials from '@/components/HomeTestimonials.vue';
import HomeHeader from '@/components/HomeHeader.vue';
import HomeAbout from '@/components/HomeAbout.vue';
import HomeWhyUs from '@/components/HomeWhyUs.vue';
</script>

<template>

  <HomeHeader />

  <main class="main">

    <HomeHero />
    <HomeAbout />
    <HomeWhyUs />
    <HomeStats />
    <HomeMenu />
    <HomeTestimonials />
    <HomeEvents />
    <HomeChefs />
    <HomeGallery />
    <HomeContact />

  </main>

  <HomeFooter />
  <HomeScrollTop />
  <!-- Preloader -->
  <!--<div id="preloader"></div>-->
</template>

<style scoped>

</style>


