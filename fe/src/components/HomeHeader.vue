<script setup lang="ts">
const navigateTo = (path: string) => {
  window.location.href = path;
};

const navItems = [
  { label: 'Home', href: '#hero' },
  { label: 'About', href: '#about' },
  { label: 'Menu', href: '#menu' },
  { label: 'Events', href: '#events' },
  { label: 'Chefs', href: '#chefs' },
  { label: 'Gallery', href: '#gallery' },
  { label: 'Contact', href: '#contact' }
];
</script>

<template>
  <header id="header" class="header sticky-top">
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">

        <a href="/" class="navbar-brand logo d-flex align-items-center">
          <!-- <img src="@/assets/img/logo.png" alt=""> -->
          <h1 class="sitename">Yummy</h1><span>.</span>
        </a>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarScroll"
          aria-controls="navbarScroll"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="icon-bar top-bar"></span>
          <span class="icon-bar middle-bar"></span>
          <span class="icon-bar bottom-bar"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarScroll">
          <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll">
            <li
              v-for="item in navItems"
              :key="item.href"
              class="nav-item"
            >
              <a
                class="nav-link"
                :class="{ active: item.href === '#hero' }"
                :href="item.href"
              >
                {{ item.label }}
              </a>
            </li>
          </ul>

          <div class="d-flex align-items-center gap-2">
            <a
              href="#"
              @click.prevent="navigateTo('/dashboard/reservations')"
              class="btn-getstarted"
            >
              Book a Table
            </a>
            <a
              href="#"
              @click.prevent="navigateTo('/dashboard')"
              class="btn btn-lg d-flex align-items-center gap-1"
            >
              <i class="bi bi-person"></i>
            </a>
          </div>
        </div>
      </div>
    </nav>
  </header>
</template>

<style scoped>
</style>