<template>
    <div class="user-item">
        <article class="media">
            {{ userId }}
            <figure class="media-left">
                <router-link
                    v-if="user.avatar"
                    class="image is-48x48 is-square"
                    :to="{ name: 'user-page', params: { id: user.id } }"
                >
                    <img class="is-rounded fit" :src="user.avatar">
                </router-link>

                <router-link v-else :to="{ name: 'user-page', params: { id: user.id } }">
                    <DefaultAvatar class="image is-48x48" :user="user" />
                </router-link>
            </figure>
            <div class="media-content">
                <div class="content">
                    <p>
                        <strong>
                            {{ user.firstName }} {{ user.lastName }}
                        </strong>
                    </p>
                </div>
            </div>
        </article>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import showStatusToast from '@/components/mixin/showStatusToast';
import DefaultAvatar from '../../common/DefaultAvatar.vue';

export default {
    name: 'UserItem',

    components: {
        DefaultAvatar,
    },

    mixins: [showStatusToast],

    props: {
        userId: {
            type: Number,
            require: true
        }
    },

    // data: () => ({
    //     user: {},
    // }),

    async created() {
        await this.fetchUserById(this.userId);
        // try {
        //     await this.fetchUserById(this.userId);
        // } catch (error) {
        //     this.showErrorMessage(error.message);
        // }
    },

    computed: {
        ...mapGetters('user', [
            'getUserById',
        ]),

        user() {
            return this.fetchUserById(this.userId);
        },
        // ...mapActions('user', [
        //     'fetchUserById',
        // ]),
        //
        // user() {
        //     return this.fetchUserById(this.userId);
        // },

    },

    methods: {
        ...mapActions('user', [
            'fetchUserById'
        ]),
    }

};
</script>

<style lang="scss" scoped>
nav {
    margin-left: -8px;
}

.content {
    p {
        margin-bottom: 0;
    }
}
</style>
