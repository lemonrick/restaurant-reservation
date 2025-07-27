<script setup lang="ts">
const navigateTo = (path: string) => {
  window.location.href = path;
};
</script>

<template>
  <section id="hero" class="hero section light-background">
    <div class="container">
      <div class="row gy-4 justify-content-center justify-content-lg-between">
        <div
          class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center"
          data-aos="fade-up"
        >
          <h1>Enjoy Your Healthy<br />Delicious Food</h1>
          <p data-aos-delay="100">
            At Yummy, we bring you fresh, tasty, and wholesome food you’ll love.
          </p>
          <div class="d-flex" data-aos-delay="200">
            <a
              href="#"
              @click.prevent="navigateTo('/dashboard/reservations')"
              class="btn-get-started"
            >
              Book a Table
            </a>
            <a
              href="https://www.youtube.com/watch?v=vOyRo-Yjr2Q"
              class="glightbox btn-watch-video d-flex align-items-center"
            >
              <i class="bi bi-play-circle"></i>
              <span>Watch Video</span>
            </a>
          </div>
        </div>

        <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
          <img
            src="@/assets/img/hero-img.png"
            class="img-fluid animated"
            alt="Healthy food on a plate"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
</style>