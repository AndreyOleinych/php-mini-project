<template>
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-mobile is-centered">
                    <div class="column is-three-quarters-mobile is-two-thirds-tablet is-one-third-desktop">
                        <div class="box shadow-box">
                            <p class="subtitle">
                                I have an account?
                                <router-link class="link" to="/auth/sign-in">
                                    Sign in
                                </router-link>
                            </p>

                            <form
                                class="form"
                                @submit.prevent
                                novalidate="true"
                            >
                                <b-field label="Email">
                                    <b-input
                                        v-model="user.email"
                                        name="email"
                                        autofocus
                                    />
                                </b-field>

                                <b-field label="Password">
                                    <b-input
                                        type="password"
                                        v-model="user.password"
                                    />
                                </b-field>

                                <b-field label="Password">
                                    <b-input
                                        type="password"
                                        v-model="user.password_confirmation"
                                    />
                                </b-field>

                                <div class="has-text-centered">
                                    <button
                                        type="button"
                                        class="button is-primary is-rounded"
                                        @click="onResetPassword"
                                    >
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapActions } from 'vuex';
import showStatusToast from '../components/mixin/showStatusToast';

export default {
    name: 'ResetPasswordPage',

    mixins: [showStatusToast],

    data: () => ({
        user: {
            password: '',
            password_confirmation: ''
        },
    }),

    methods: {
        ...mapActions('auth', [
            'resetPassword',
        ]),

        onResetPassword() {
            this.resetPassword(this.user)
                .then(() => {
                    this.showSuccessMessage('Welcome!');

                    this.$router.push({ path: '/auth/sign-in' }).catch(() => {});
                })
                .catch(error => this.showErrorMessage(error.message));
        },
    },
};
</script>

<style scoped>
</style>
