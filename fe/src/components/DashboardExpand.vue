<script setup lang="ts">
import { ref } from 'vue'
const props = defineProps<{
  title?: string | null
  defaultExpanded?: boolean
}>()
const expanded = ref(props.defaultExpanded ?? false)
</script>

<template>
  <v-card variant="flat" class="mb-6">
    <v-card-title
      class="d-flex justify-space-between align-center"
    >
      <span class="h5 font-weight-regular">{{ props.title }}</span>
      <v-btn icon variant="outlined" size="small" @click="expanded = !expanded">
        <v-icon>{{ expanded ? '$chevronUp' : '$chevronDown' }}</v-icon>
      </v-btn>
    </v-card-title>

    <v-expand-transition>
      <div v-show="expanded">
        <v-card-text>
          <slot />
        </v-card-text>
      </div>
    </v-expand-transition>
  </v-card>
</template>
