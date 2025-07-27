<script setup lang="ts">
import chef1 from '@/assets/img/chefs/chefs-1.jpg';
import chef2 from '@/assets/img/chefs/chefs-2.jpg';
import chef3 from '@/assets/img/chefs/chefs-3.jpg';

type Chef = {
  name: string;
  title: string;
  description: string;
  img: string;
  delay: number;
};

const chefs: Chef[] = [
  {
    name: 'Walter White',
    title: 'Master Chef',
    description: 'They say cooking is chemistry — and Walter proves it. Known for his precise methods and “secret ingredients,” he’s the one who knocks… flavor into every dish.',
    img: chef1,
    delay: 100
  },
  {
    name: 'Sarah Jonson',
    title: 'Patissier',
    description: 'Don’t let the sugar fool you — Sarah is the queen of desserts. Her pastries are so good, they should probably be illegal in at least three states.',
    img: chef2,
    delay: 200
  },
  {
    name: 'William Anderson',
    title: 'Cook',
    description: 'William keeps things cooking in the background. He’s not in it for the fame — just the satisfaction of seeing happy, full bellies leave the restaurant.',
    img: chef3,
    delay: 300
  }
];
</script>

<template>
  <section id="chefs" class="chefs section">
    <div class="container section-title" data-aos="fade-up">
      <h2>chefs</h2>
      <p><span>Our</span> <span class="description-title">Proffesional Chefs</span></p>
    </div>

    <div class="container">
      <div class="row gy-4">
        <div
          class="col-lg-4 d-flex align-items-stretch"
          v-for="chef in chefs"
          :key="chef.name"
          data-aos="fade-up"
          :data-aos-delay="chef.delay"
        >
          <div class="team-member">
            <div class="member-img">
              <img :src="chef.img" class="img-fluid" :alt="chef.name" />
              <div class="social">
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
                <a href="#"><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
            <div class="member-info">
              <h4>{{ chef.name }}</h4>
              <span>{{ chef.title }}</span>
              <p>{{ chef.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
</style>