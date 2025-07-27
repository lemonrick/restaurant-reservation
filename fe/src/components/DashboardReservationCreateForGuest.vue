<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { toast } from '@/plugins/toast';
import { Form, useField, useForm } from 'vee-validate';
import * as yup from 'yup';
import api from '@/plugins/axios'
import { isSunday, startOfDay, isBefore, format } from 'date-fns'

const isLoading = ref(false)
const emit = defineEmits(['reservation-added'])

const validationSchema = yup.object({
  user_id: yup.number().required('User is required'),
  date: yup
    .date()
    .typeError('Date is required')
    .required('Date is required'),
  time: yup
    .string()
    .required('Time is required'),
  guests_count: yup
    .number()
    .typeError('Guests count must be a number')
    .required('Guests count is required')
    .integer('Guests count must be an integer')
    .min(1, 'At least 1 guest is required'),
  note: yup
    .string()
    .max(1000, 'Note is too long')
    .nullable()
})

const { handleSubmit, meta, resetForm } = useForm({
  validationSchema
});

const seatOptions = ref<number[]>([])
const userOptions = ref<{ id: number, name: string }[]>([])

function allowedDates(date: Date): boolean {
  const today = startOfDay(new Date())
  return !isSunday(date) && !isBefore(date, today)
}

const datePickerMenu = ref(false)

const user_id = useField('user_id', validationSchema.user_id)
const date = useField('date', validationSchema.date)
const time = useField('time', validationSchema.time)
const guests_count = useField('guests_count')
const note = useField('note')

const generalError = ref('');

function generateTimeOptions(): string[] {
  const times: string[] = []
  for (let hour = 11; hour <= 20; hour++) {
    times.push(`${String(hour).padStart(2, '0')}:00`)
    if (hour < 20) {
      times.push(`${String(hour).padStart(2, '0')}:30`)
    }
  }
  times.push('20:30')
  return times
}

const timeOptions = ref<string[]>(generateTimeOptions())

async function resetAll() {
  resetForm()
  generalError.value = ''
}

async function fetchSeatOptions(): Promise<number[]> {
  try {
    const response = await api.get('/tables/seats')
    return response.data
  } catch (e) {
    console.error('Failed to fetch seat options:', e)
    return []
  }
}

async function fetchUserOptions(): Promise<{ id: number, name: string }[]> {
  try {
    const response = await api.get('/users/selectable')
    return response.data
  } catch (e) {
    console.error('Failed to fetch user options:', e)
    return []
  }
}

const onSubmit = handleSubmit(async (values) => {
  isLoading.value = true
  try {
    let selectedDate = values.date
    const selectedTime: string = values.time

    if (typeof selectedDate === 'string') {
      selectedDate = new Date(selectedDate)
    }

    const isoDate = format(selectedDate, 'yyyy-MM-dd')
    const starts_at = `${isoDate}T${selectedTime}` // full datetime string

    await api.post('/reservations/for-user', {
      user_id: values.user_id,
      starts_at,
      guests_count: values.guests_count,
      note: values.note || null,
    })

    emit('reservation-added')
    toast.success('Reservation was successfully created.')
    await resetAll()

  } catch (error: never) {
    generalError.value =
      error?.response?.data?.message ||
      error?.response?.data?.error ||
      'Reservation failed.'
  } finally {
    isLoading.value = false
  }
})


onMounted(async () => {
  seatOptions.value = await fetchSeatOptions()
  userOptions.value = await fetchUserOptions()
})
</script>

<template>
  <Form @submit="onSubmit" class="mt-7">
    <v-row justify="center">
      <v-col cols="12" sm="4">
        <v-select
          v-model="user_id.value.value"
          :error-messages="user_id.errorMessage.value"
          :items="userOptions"
          item-title="name"
          item-value="id"
          label="Select user"
          required
          variant="outlined"
          color="primary"
          hide-details="auto"
          :disabled="isLoading"
        />
      </v-col>
      <v-col cols="12" sm="4">
        <v-menu
          v-model="datePickerMenu"
          :close-on-content-click="false"
          transition="scale-transition"
          offset-y
        >
          <template #activator="{ props }">
            <v-text-field
              v-bind="props"
              :model-value="date.value.value ? date.value.value.toLocaleDateString() : ''"
              label="Select Date"
              readonly
              variant="outlined"
              color="primary"
              hide-details="auto"
              :error-messages="date.errorMessage.value"
              :disabled="isLoading"
            />
          </template>

          <v-date-picker
            v-model="date.value.value"
            :allowed-dates="allowedDates"
            color="primary"
            first-day-of-week="1"
            @update:modelValue="() => datePickerMenu = false"
          />
        </v-menu>
      </v-col>
      <v-col cols="12" sm="4">
        <v-select
          v-model="time.value.value"
          :items="timeOptions"
          label="Select Time"
          required
          variant="outlined"
          color="primary"
          hide-details="auto"
          :error-messages="time.errorMessage.value"
          :disabled="isLoading"
        />
      </v-col>
      <v-col cols="12" sm="4">
        <v-select
          v-model="guests_count.value.value"
          :error-messages="guests_count.errorMessage.value"
          :items="seatOptions"
          label="Guests Count"
          required
          variant="outlined"
          color="primary"
          hide-details="auto"
          :disabled="isLoading"
        />
      </v-col>
      <v-col cols="12" sm="8">
        <v-textarea
          v-model="note.value.value"
          :error-messages="note.errorMessage.value"
          label="Note (optional)"
          variant="outlined"
          color="primary"
          auto-grow
          hide-details="auto"
          rows="1"
          :disabled="isLoading"
        />
      </v-col>

      <v-col cols="12" sm="6" class="d-flex justify-center align-center">
        <v-btn
          color="secondary"
          :loading="isLoading"
          class="mt-2"
          variant="flat"
          size="large"
          :disabled="(meta.validated && !meta.valid) || isLoading"
          type="submit"
        >
          Create reservation
        </v-btn>
      </v-col>
    </v-row>

    <div v-if="generalError" class="mt-2 text-center pt-2">
      <span class="text-error">{{ generalError }}</span>
    </div>
  </Form>

</template>

<style lang="scss">
.custom-devider {
  border-color: rgba(0, 0, 0, 0.08) !important;
}
.googleBtn {
  border-color: rgba(0, 0, 0, 0.08);
  margin: 30px 0 20px 0;
}
.outlinedInput .v-field {
  border: 1px solid rgba(0, 0, 0, 0.08);
  box-shadow: none;
}
.orbtn {
  padding: 2px 40px;
  border-color: rgba(0, 0, 0, 0.08);
  margin: 20px 15px;
}
.pwdInput {
  position: relative;
  .v-input__append {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
  }
}
</style>