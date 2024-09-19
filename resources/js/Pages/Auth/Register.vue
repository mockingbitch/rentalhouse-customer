<script setup>
import { ref } from 'vue';
import { useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/vue3';
import AuthLayout from "@/Pages/Auth/AuthLayout.vue";
import VIcon from "@/Icons/VIcon.vue";
import VErrorLabel from "@/Components/Common/VErrorLabel.vue";

const form = useForm({
    email: '',
    password: '',
    confirm_password: '',
});

const showPassword = ref(false);
const isLoading = ref(false);
const errors = ref({
    email: null,
    password: null,
})

const submit = () => {
    isLoading.value = true;
    Inertia.post('/register', form, {
        onError: (err) => {
            isLoading.value = false;
            errors.value = err;
        },
    });
};
</script>

<template>
    <AuthLayout>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card z-index-0">
                        <div class="card-header text-center pt-4">
                            <div class="p-4 bg-gray-100">
                                <h1 class="text-xl font-bold">Welcome to Rental House!</h1>
                                <p class="mt-2 text-gray-700">Please tell me who you are.</p>
                            </div>
                            <h4>Register with</h4>
                        </div>
                        <div class="row px-xl-5 px-sm-4 px-3">
                            <div class="col-3 ms-auto px-1">
                                <a class="btn btn-outline-light w-100" href="javascript:;">
                                    <v-icon name="IconFacebook" />
                                </a>
                            </div>
                            <div class="col-1 px-1">
                            </div>
                            <div class="col-3 me-auto px-1">
                                <a class="btn btn-outline-light w-100" href="javascript:;">
                                    <v-icon name="IconGoogle" />
                                </a>
                            </div>
                            <div class="mt-2 position-relative text-center">
                                <p class="text-sm font-weight-bold mb-2 text-secondary text-border d-inline z-index-2 bg-white px-3">
                                    or
                                </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <form role="form" @submit.prevent="submit">
                                <div class="mb-3">
                                    <input
                                        v-model="form.email"
                                        :class="errors?.email ? 'is-invalid' : ''"
                                        type="text"
                                        class="form-control"
                                        placeholder="Email"
                                        aria-label="Email"
                                    >
                                    <v-error-label :message="errors?.email"></v-error-label>
                                </div>
                                <div class="mb-3">
                                    <input
                                        v-model="form.password"
                                        :class="errors?.password ? 'is-invalid' : ''"
                                        type="password"
                                        class="form-control"
                                        placeholder="Password"
                                        aria-label="Password"
                                    >
                                    <v-error-label :message="errors?.password"></v-error-label>
                                </div>
                                <div class="mb-3">
                                    <input
                                        v-model="form.confirm_password"
                                        :class="errors?.confirm_password ? 'is-invalid' : ''"
                                        type="password"
                                        class="form-control"
                                        placeholder="Password"
                                        aria-label="Password"
                                    >
                                    <v-error-label :message="errors?.confirm_password"></v-error-label>
                                </div>
                                <div class="form-check form-check-info text-start">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" checked>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Remember account
                                    </label>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">
                                        <span v-if="isLoading" class="spinner-border w-[15px] h-[15px]" role="status"></span>
                                        Register
                                    </button>
                                </div>
                                <p class="text-sm mt-3 mb-0">
                                    Already have an account?
                                    <Link :href="route('login')" class="text-dark font-weight-bolder">
                                        Login
                                    </Link>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<style>
</style>
