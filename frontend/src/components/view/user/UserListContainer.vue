<template>
    <div class="user-container">
        <NoContent :show="noContent" title="No likes yet :)" />

        <template v-for="userArr in usersIds">
            <UserItem :key="userArr.userId" :user-id="userArr.userId" />
        </template>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import NoContent from '@/components/common/NoContent.vue';
import showStatusToast from '@/components/mixin/showStatusToast';
import UserItem from './UserItem.vue';

export default {
    name: 'UserListContainer',

    mixins: [showStatusToast],

    components: {
        UserItem,
        NoContent
    },

    props: {
        usersIds: {
            type: Array,
            required: true
        },
    },

    data: () => ({
        page: 1,
        noContent: false,
    }),

    async created() {
        try {
            await this.fetchUsers({
                page: 1
            });

            if (!this.usersIds.length) {
                this.noContent = true;
            }
        } catch (error) {
            this.showErrorMessage(error.message);
        }
    },

    computed: {
        ...mapGetters('user', [
            'getUserById',
        ]),

        ...mapActions('user', [
            // 'fetchUsers',
            // 'fetchUserById',
        ]),
    },

    methods: {

        // ...mapGetters('user', [
        //     'getUserById',
        // ]),

        ...mapActions('user', [
            'fetchUserById',
            'fetchUsers',
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
