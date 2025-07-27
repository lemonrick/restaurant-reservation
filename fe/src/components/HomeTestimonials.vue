<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination } from 'swiper/modules';

import testimonial1 from '@/assets/img/testimonials/testimonials-1.jpg';
import testimonial2 from '@/assets/img/testimonials/testimonials-2.jpg';
import testimonial3 from '@/assets/img/testimonials/testimonials-3.jpg';
import testimonial4 from '@/assets/img/testimonials/testimonials-4.jpg';

const testimonials = [
  {
    name: 'Saul Goodman',
    role: 'Food Critic',
    text: 'Came for lunch, stayed for dessert, left in a food coma. Yummy is dangerously good. Send help. And maybe one more slice.',
    img: testimonial1
  },
  {
    name: 'Sara Wilsson',
    role: 'Designer',
    text: 'The food slaps. The place looks like Pinterest. I came to eat, not redecorate my apartment… but now I want both.',
    img: testimonial2
  },
  {
    name: 'Jena Karlis',
    role: 'Store Owner',
    text: 'After a long day, Yummy hits the spot. Great food, chill vibe, and I swear the staff can read minds. Love this place!',
    img: testimonial3
  },
  {
    name: 'John Larson',
    role: 'Entrepreneur',
    text: 'Best way to mix business with pleasure. Deals get signed faster when there’s lasagna involved. Trust me, I’ve tested this.',
    img: testimonial4
  }
];
</script>

<template>
  <section id="testimonials" class="testimonials section light-background">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <h2>TESTIMONIALS</h2>
      <p>
        What Are They <span class="description-title">Saying About Us</span>
      </p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <Swiper
        :modules="[Autoplay, Pagination]"
        :loop="true"
        :speed="600"
        :autoplay="{ delay: 7000 }"
        :slides-per-view="'auto'"
        :pagination="{
          el: '.swiper-pagination',
          type: 'bullets',
          clickable: true
        }"
        class="init-swiper"
      >
        <SwiperSlide v-for="t in testimonials" :key="t.name">
          <div class="testimonial-item">
            <div class="row gy-4 justify-content-center">
              <div class="col-lg-6">
                <div class="testimonial-content">
                  <p>
                    <i class="bi bi-quote quote-icon-left"></i>
                    <span>{{ t.text }}</span>
                    <i class="bi bi-quote quote-icon-right"></i>
                  </p>
                  <h3>{{ t.name }}</h3>
                  <h4>{{ t.role }}</h4>
                  <div class="stars">
                    <i class="bi bi-star-fill" v-for="i in 5" :key="i"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 text-center">
                <img
                  :src="t.img"
                  class="img-fluid testimonial-img"
                  :alt="t.name"
                />
              </div>
            </div>
          </div>
        </SwiperSlide>

        <div class="swiper-pagination"></div>
      </Swiper>
    </div>
  </section>
</template>

<style scoped>
</style>