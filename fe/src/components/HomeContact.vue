<script setup lang="ts">
type ContactInfoItem = {
  icon: string;
  title: string;
  text: string;
  delay: number;
};

const contactInfo: ContactInfoItem[] = [
  {
    icon: 'bi-geo-alt',
    title: 'Address',
    text: 'A108 Adam Street, New York, NY 535022',
    delay: 200
  },
  {
    icon: 'bi-telephone',
    title: 'Call Us',
    text: '+1 5589 55488 55',
    delay: 300
  },
  {
    icon: 'bi-envelope',
    title: 'Email Us',
    text: 'info@example.com',
    delay: 400
  },
  {
    icon: 'bi-clock',
    title: 'Opening Hours',
    text: '<strong>Mon-Sat:</strong> 11AM - 23PM; <strong>Sunday:</strong> Closed',
    delay: 500
  }
];
</script>

<template>
  <section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Contact</h2>
      <p><span>Need Help?</span> <span class="description-title">Contact Us</span></p>
    </div>

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="row gy-4 mb-5">
        <div
          v-for="(item, index) in contactInfo"
          :key="index"
          class="col-md-6"
        >
          <div
            class="info-item d-flex align-items-center"
            data-aos="fade-up"
            :data-aos-delay="item.delay"
          >
            <i class="icon bi flex-shrink-0" :class="item.icon"></i>
            <div>
              <h3 v-html="item.title" />
              <p v-html="item.text" />
            </div>
          </div>
        </div>
      </div>

      <div class="mb-5">
        <iframe
          style="width: 100%; height: 400px;"
          src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621"
          frameborder="0"
          allowfullscreen
        ></iframe>
      </div>
    </div>
  </section>
</template>

<style scoped>
</style>