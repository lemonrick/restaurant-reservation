<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination } from 'swiper/modules';

import event1 from '@/assets/img/events-1.jpg';
import event2 from '@/assets/img/events-2.jpg';
import event3 from '@/assets/img/events-3.jpg';
import event4 from '@/assets/img/events-4.jpg';

const events = [
  {
    title: 'Custom Parties',
    price: '$99',
    desc: 'Looking to host a unique event? Our restaurant offers flexible options for custom parties tailored to your needs. Whether it’s a themed dinner or a casual get-together, we’ll help you make it special.',
    img: event1
  },
  {
    title: 'Private Parties',
    price: '$289',
    desc: 'Enjoy complete privacy in our separate lounge area, perfect for corporate events, family gatherings, or special occasions. We offer dedicated service and a customizable menu to suit your group.',
    img: event2
  },
  {
    title: 'Birthday Parties',
    price: '$499',
    desc: 'Celebrate your birthday in style with delicious food, festive decor, and great atmosphere. Our lounge space offers the perfect setting for both kids and adults to have fun.',
    img: event3
  },
  {
    title: 'Wedding Parties',
    price: '$899',
    desc: 'Host your wedding reception or engagement dinner in our elegant private lounge. We provide a romantic ambiance, personalized service, and menus designed to make your big day unforgettable.',
    img: event4
  }
];
</script>

<template>
  <section id="events" class="events section">
    <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">
      <Swiper
        :modules="[Autoplay, Pagination]"
        :loop="true"
        :speed="600"
        :autoplay="{ delay: 5000 }"
        :slides-per-view="'auto'"
        :pagination="{
          el: '.swiper-pagination',
          type: 'bullets',
          clickable: true
        }"
        :breakpoints="{
          320: { slidesPerView: 1, spaceBetween: 40 },
          1200: { slidesPerView: 3, spaceBetween: 1 }
        }"
        class="init-swiper"
      >
        <SwiperSlide v-for="e in events" :key="e.title">
          <div
            class="swiper-slide event-item d-flex flex-column justify-content-end"
            :style="`background-image: url(${e.img})`"
          >
            <h3>{{ e.title }}</h3>
            <div class="price align-self-start">{{ e.price }}</div>
            <p class="description">{{ e.desc }}</p>
          </div>
        </SwiperSlide>

        <div class="swiper-pagination"></div>
      </Swiper>
    </div>
  </section>
</template>

<style scoped>
</style>
