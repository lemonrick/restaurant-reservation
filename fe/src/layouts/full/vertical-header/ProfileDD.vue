<script setup lang="ts">
import { computed } from 'vue';
import { LogoutIcon } from 'vue-tabler-icons';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

const greeting = computed(() => {
  const user = authStore.user;
  return user ? `Hello, ${user.first_name} ${user.last_name}` : 'Hello';
});
</script>

<template>
  <!-- ---------------------------------------------- -->
  <!-- profile DD -->
  <!-- ---------------------------------------------- -->
  <div class="pa-4">
    <span class="font-weight-regular">{{ greeting }}</span>

    <v-divider></v-divider>

    <v-list class="mt-3">
      <v-list-item @click="authStore.logout()" color="secondary" rounded="md">
        <template v-slot:prepend>
          <LogoutIcon size="20" class="mr-2" />
        </template>

        <v-list-item-title class="text-subtitle-2">Logout</v-list-item-title>
      </v-list-item>
    </v-list>

  </div>
</template>
