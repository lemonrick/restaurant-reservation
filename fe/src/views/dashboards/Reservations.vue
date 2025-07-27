<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import ReservationsAdmin from '@/components/ReservationsAdmin.vue';
import ReservationsGuest from '@/components/ReservationsGuest.vue';

const auth = useAuthStore();
</script>

<template>
  <ReservationsAdmin v-if="auth.isAdmin" />
  <ReservationsGuest v-else />
</template>
