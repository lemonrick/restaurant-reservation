<script setup lang="ts">
type Feature = {
  icon: string;
  title: string;
  text: string;
  delay: number;
};

const features: Feature[] = [
  {
    icon: 'bi-basket',
    title: 'Fresh Ingredients',
    text: 'We carefully select the freshest produce and local ingredients to ensure top quality in every bite.',
    delay: 200
  },
  {
    icon: 'bi-star-fill',
    title: 'Exceptional Taste',
    text: 'Our chefs blend tradition with creativity to deliver dishes that are both comforting and exciting.',
    delay: 300
  },
  {
    icon: 'bi-house-heart',
    title: 'Friendly Atmosphere',
    text: 'From the first smile to the last bite, we make sure you feel at home every time you visit.',
    delay: 400
  }
];
</script>

<template>
  <section id="why-us" class="why-us section light-background">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
          <div class="why-box">
            <h3>Why Choose Yummy</h3>
            <p>
              At Yummy, we serve more than just food — we serve experiences. Our meals are crafted with love,
              using only fresh and high-quality ingredients to bring you flavor that speaks for itself.
            </p>
            <div class="text-center">
              <a href="#about" class="more-btn">
                <span>Learn More</span> <i class="bi bi-chevron-right"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-8 d-flex align-items-stretch">
          <div class="row gy-4" data-aos="fade-up">
            <div
              class="col-xl-4"
              v-for="feature in features"
              :key="feature.title"
              :data-aos-delay="feature.delay"
              data-aos="fade-up"
            >
              <div class="icon-box d-flex flex-column justify-content-center align-items-center">
                <i class="bi" :class="feature.icon"></i>
                <h4>{{ feature.title }}</h4>
                <p>{{ feature.text }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
</style>