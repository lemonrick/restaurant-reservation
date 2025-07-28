<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth.ts';
// import router from '@/router';
import { Form, useField, useForm } from 'vee-validate';
import * as yup from 'yup';

const auth = useAuthStore();

const validationSchema = yup.object({
  email: yup
    .string()
    .required('Email is required')
    .matches(
      /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
      'Invalid email address'
    ),
  password: yup.string().min(6, 'Minimum 6 characters').required('Password is required')
});

const { handleSubmit, meta } = useForm({
  validationSchema
});

const email = useField('email', validationSchema.email);
const password = useField('password', validationSchema.password);

const passwordShow = ref(false);
const generalError = ref('');

const onSubmit = handleSubmit(async (values) => {
  try {
    await auth.login(values.email, values.password);

    // router.push('/dashboard/reservations');
    window.location.href = '/dashboard/reservations';

  } catch (error: never) {
    generalError.value = error?.response?.data?.message || 'Login failed. Check your credentials.';
  }
});
</script>

<template>
  <Form @submit="onSubmit" class="mt-7 loginForm">
    <v-text-field
      v-model="email.value.value"
      :error-messages="email.errorMessage.value"
      label="Email Address"
      class="mt-4 mb-8"
      required
      density="comfortable"
      hide-details="auto"
      variant="outlined"
      color="primary"
    /> <!-- prepend-inner-icon="$emailOutline" -->

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
      class="pwdInput"
    /> <!-- prepend-inner-icon="$lockOutline" -->

    <div class="d-sm-flex align-center mt-2 mb-7 mb-sm-0">
      <div class="ml-auto">
        <a href="javascript:void(0)" class="text-primary text-decoration-none">Forgot password?</a>
      </div>
    </div>

    <v-btn
      color="secondary"
      :loading="auth.isLoading"
      block
      class="mt-2"
      variant="flat"
      size="large"
      :disabled="(meta.validated && !meta.valid) || auth.isLoading"
      type="submit"
    >
      Sign In
    </v-btn>

    <div v-if="generalError" class="mt-2 text-center pt-2">
      <span class="text-error">{{ generalError }}</span>
    </div>
  </Form>

  <div class="mt-5 text-right">
    <v-divider />
    <v-btn variant="plain" to="/register" class="mt-2 text-capitalize mr-n2">Don't Have an account?</v-btn>
  </div>
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
.loginForm {
  .v-text-field .v-field--active input {
    font-weight: 500;
  }
}
</style>
