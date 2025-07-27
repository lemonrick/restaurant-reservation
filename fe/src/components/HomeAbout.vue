<script setup lang="ts">
import about1 from '@/assets/img/about.jpg';
import about2 from '@/assets/img/about-2.jpg';
</script>

<template>
  <section id="about" class="about section">
    <div class="container section-title" data-aos="fade-up">
      <h2>About Us</h2>
      <p><span>Learn More</span> <span class="description-title">About Us</span></p>
    </div>

    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
          <img :src="about1" class="img-fluid mb-4" alt="Restaurant interior" />
          <div class="book-a-table">
            <h3>Book a Table</h3>
            <p>+421 912 345 678</p>
          </div>
        </div>

        <div class="col-lg-5" data-aos="fade-up" data-aos-delay="250">
          <div class="content ps-0 ps-lg-5">
            <p class="fst-italic">
              At Yummy, we believe that great food starts with great ingredients — and a passion for cooking.
              Every dish we serve is made with care to bring joy to your table.
            </p>
            <ul>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Fresh, locally sourced ingredients prepared daily.</span>
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Friendly and cozy atmosphere for every occasion.</span>
              </li>
              <li>
                <i class="bi bi-check-circle-fill"></i>
                <span>Skilled chefs who blend traditional recipes with modern flavors to delight every palate.</span>
              </li>
            </ul>
            <p>
              Whether you're joining us for a casual lunch, a family dinner, or a special celebration,
              we’re here to make it memorable. Come taste the difference at Yummy — where every bite tells a story.
            </p>

            <div class="position-relative mt-4">
              <img :src="about2" class="img-fluid" alt="Chef preparing food" />
              <a
                href="https://www.youtube.com/watch?v=vOyRo-Yjr2Q"
                class="glightbox pulsating-play-btn"
              ></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
</style>