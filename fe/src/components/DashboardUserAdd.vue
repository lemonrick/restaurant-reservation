<script setup lang="ts">
import { ref } from 'vue';
import type { RegisterPayload } from '@/models/RegisterPayload';
import { useAuthStore } from '@/stores/auth';
import { toast } from '@/plugins/toast';
import { Form, useField, useForm } from 'vee-validate';
import * as yup from 'yup';
import { VPhoneInput } from 'v-phone-input';

const auth = useAuthStore();
const emit = defineEmits(['user-added'])
const phoneInputRef = ref()

const validationSchema = yup.object({
  first_name: yup.string().required('First name is required'),
  last_name: yup.string().required('Last name is required'),
  email: yup
    .string()
    .required('Email is required')
    .matches(
      /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
      'Invalid email address'
    ),
  phone: yup.string().required('Phone number is required'),
  password: yup.string().min(6, 'Minimum 6 characters').required('Password is required'),
  role: yup.string().oneOf(['guest', 'admin']).required('Role is required')
})

const phoneRules = [
  (value: string, phone: never, { country, example }: never) => {
    if (!value) return 'Phone number is required'
    if (!phone?.valid) {
      const countryName = country?.name || country?.iso2 || 'this country'
      return `Enter a valid number for ${countryName}. Example: ${example}`
    }
    return true
  }
]

const { handleSubmit, meta, resetForm } = useForm({
  validationSchema
});

const first_name = useField('first_name', validationSchema.first_name);
const last_name = useField('last_name', validationSchema.last_name);
const email = useField('email', validationSchema.email);
const {
  value: phone,
  errorMessage: phoneError
} = useField<string>('phone')
const password = useField('password', validationSchema.password);
const role = useField('role', validationSchema.role, {
  initialValue: 'guest'
});

const roles = [
  { label: 'Guest', value: 'guest' },
  { label: 'Admin', value: 'admin' }
];

const passwordShow = ref(false);
const generalError = ref('');

async function resetAll() {
  resetForm()
  generalError.value = ''
  await phoneInputRef.value?.resetValidation()
}

const onSubmit = handleSubmit(async (values: RegisterPayload) => {
  try {
    await auth.register(values);

    emit('user-added')
    toast.success('Account created successfully');
    await resetAll()

  } catch (error: never) {
    generalError.value =
      error?.response?.data?.message ||
      error?.response?.data?.error ||
      'Registration failed.';  }
});
</script>

<template>
  <Form @submit="onSubmit" class="mt-7">
    <v-row justify="center">
      <v-col cols="12" sm="4">
        <v-text-field
          v-model="first_name.value.value"
          :error-messages="first_name.errorMessage.value"
          label="Firstname"
          required
          density="comfortable"
          hide-details="auto"
          variant="outlined"
          color="primary"
          :disabled="auth.isLoading"
        ></v-text-field>
      </v-col>
      <v-col cols="12" sm="4">
        <v-text-field
          v-model="last_name.value.value"
          :error-messages="last_name.errorMessage.value"
          label="Lastname"
          required
          density="comfortable"
          hide-details="auto"
          variant="outlined"
          color="primary"
          :disabled="auth.isLoading"
        ></v-text-field>
      </v-col>
      <v-col cols="12" sm="4">
        <v-phone-input
          ref="phoneInputRef"
          v-model="phone"
          :error="!!phoneError"
          :error-messages="phoneError"
          :rules="phoneRules"
          label="Phone"
          required
          density="comfortable"
          hideDetails="auto"
          variant="outlined"
          color="primary"
          :include-countries="['CZ', 'SK']"
          default-country="CZ"
          display-format="international"
          :disabled="auth.isLoading"
        />
      </v-col>
      <v-col cols="12" sm="4">
        <v-text-field
          v-model="email.value.value"
          :error-messages="email.errorMessage.value"
          label="Email Address"
          class="mt-4 mb-4"
          required
          density="comfortable"
          hide-details="auto"
          variant="outlined"
          color="primary"
          :disabled="auth.isLoading"
        ></v-text-field>
      </v-col>
      <v-col cols="12" sm="4">
        <v-text-field
          v-model="password.value.value"
          :error-messages="password.errorMessage.value"
          label="Password"
          required
          density="comfortable"
          variant="outlined"
          color="primary"
          hide-details="auto"
          :append-icon="passwordShow ? '$eye' : '$eyeOff'"
          :type="passwordShow ? 'text' : 'password'"
          @click:append="passwordShow = !passwordShow"
          class="pwdInput mt-4"
          autocomplete="new-password"
          :disabled="auth.isLoading"
        ></v-text-field>
      </v-col>
      <v-col cols="12" sm="4">
        <v-select
          v-model="role.value.value"
          :error-messages="role.errorMessage.value"
          label="Role"
          class="mt-4 mb-4"
          required
          hide-details="auto"
          density="comfortable"
          variant="outlined"
          color="primary"
          :items="roles"
          item-title="label"
          item-value="value"
          :disabled="auth.isLoading"
        ></v-select>
      </v-col>

      <v-col cols="12" sm="6" class="d-flex justify-center align-center">
        <v-btn
          color="secondary"
          :loading="auth.isLoading"
          class="mt-2"
          variant="flat"
          size="large"
          :disabled="(meta.validated && !meta.valid) || auth.isLoading"
          type="submit"
        >
          Create User
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
