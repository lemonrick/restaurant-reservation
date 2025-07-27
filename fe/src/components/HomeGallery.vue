<script setup lang="ts">
import { Autoplay, Pagination } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';

import gallery1 from '@/assets/img/gallery/gallery-1.jpg';
import gallery2 from '@/assets/img/gallery/gallery-2.jpg';
import gallery3 from '@/assets/img/gallery/gallery-3.jpg';
import gallery4 from '@/assets/img/gallery/gallery-4.jpg';
import gallery5 from '@/assets/img/gallery/gallery-5.jpg';
import gallery6 from '@/assets/img/gallery/gallery-6.jpg';
import gallery7 from '@/assets/img/gallery/gallery-7.jpg';
import gallery8 from '@/assets/img/gallery/gallery-8.jpg';

const galleryImages = [
  gallery1,
  gallery2,
  gallery3,
  gallery4,
  gallery5,
  gallery6,
  gallery7,
  gallery8
];
</script>

<template>
  <section id="gallery" class="gallery section light-background">
    <div class="container section-title" data-aos="fade-up">
      <h2>Gallery</h2>
      <p><span>Check</span> <span class="description-title">Our Gallery</span></p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <Swiper
        :modules="[Autoplay, Pagination]"
        :loop="true"
        :speed="600"
        :autoplay="{ delay: 5000 }"
        :slides-per-view="'auto'"
        :centered-slides="true"
        :pagination="{
          el: '.swiper-pagination',
          type: 'bullets',
          clickable: true
        }"
        :breakpoints="{
          320: { slidesPerView: 1, spaceBetween: 0 },
          768: { slidesPerView: 3, spaceBetween: 20 },
          1200: { slidesPerView: 5, spaceBetween: 20 }
        }"
        class="init-swiper"
      >
        <SwiperSlide
          v-for="img in galleryImages"
          :key="img"
          class="align-items-center"
        >
          <a class="glightbox" data-gallery="images-gallery" :href="img">
            <img :src="img" class="img-fluid" alt="" />
          </a>
        </SwiperSlide>

        <div class="swiper-pagination"></div>
      </Swiper>
    </div>
  </section>
</template>

<style scoped>
</style>