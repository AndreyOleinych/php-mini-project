<template>
    <article class="media">
        <figure class="media-left">
            <router-link
                v-if="comment.author.avatar"
                class="image is-48x48 is-square"
                :to="{ name: 'user-page', params: { id: comment.author.id } }"
            >
                <img class="is-rounded fit" :src="comment.author.avatar">
            </router-link>

            <router-link v-else :to="{ name: 'user-page', params: { id: comment.author.id } }">
                <DefaultAvatar class="image is-48x48" :user="comment.author" />
            </router-link>
        </figure>
        <div class="media-content">
            <div class="content">
                <strong>
                    {{ comment.author.firstName }} {{ comment.author.lastName }}
                </strong>
                <br>
                <p>
                    {{ comment.body }}
                </p>
                <br>
                <small class="has-text-grey">
                    {{ comment.created | createdDate }}
                </small>
                <div v-if="isCommentOwner(comment.author.id, user.id)" class="d-inline">
                    <!--                            <div class="buttons">-->
                    <b-button type="is-danger" @click="onDeleteComment">Delete</b-button>
                    <!--                            </div>-->
                </div>
                <b-tooltip label="Like" animated>
                    <a class="level-item" @click="onLikeOrDislikeComment">
                        <span
                            class="icon is-medium has-text-info"
                            :class="{
                                'has-text-danger': commentIsLikedByUser(comment.id, user.id)
                            }"
                        >
                            <font-awesome-icon icon="heart" />
                        </span>
                    </a>

                    <a class="level-item" @click="showUsersLikedCommentModal">
                        {{ comment.likesCount }}
                    </a>
                </b-tooltip>
            </div>
        </div>
    </article>

<!--    <b-modal :active.sync="areUsersLikedCommentModalActive">-->
<!--        <template>-->
<!--            <UserListContainer :users-ids="commentAreLikedByUsers(comment.id)" />-->
<!--        </template>-->
<!--    </b-modal>-->
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import DefaultAvatar from '../../common/DefaultAvatar.vue';
import showStatusToast from '../../mixin/showStatusToast';
import UserListContainer from '../user/UserListContainer.vue';

export default {
    name: 'Comment',

    components: {
        DefaultAvatar,
        UserListContainer
    },

    mixins: [showStatusToast],

    props: {
        comment: {
            type: Object,
            required: true,
        },
    },

    data: () => ({
        areUsersLikedCommentModalActive: false,
    }),

    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),

        ...mapGetters('comment', [
            'isCommentOwner',
            'commentIsLikedByUser',
            'commentAreLikedByUsers'
        ]),
    },

    methods: {
        ...mapActions('comment', [
            'deleteComment',
            'likeOrDislikeComment'
        ]),

        onDeleteComment() {
            this.$buefy.dialog.confirm({
                title: 'Deleting comment',
                message: 'Are you sure you want to <b>delete</b> your comment? This action cannot be undone.',
                confirmText: 'Delete comment',
                type: 'is-danger',

                onConfirm: async () => {
                    try {
                        await this.deleteComment(this.comment.id);

                        this.showSuccessMessage('Comment deleted!');

                        this.$router.go(this.$router.currentRoute).catch(() => {});
                    } catch {
                        this.showErrorMessage('Unable to delete comment!');
                    }
                }
            });
        },

        showUsersLikedCommentModal() {
            this.areUsersLikedCommentModalActive = true;
        },

        async onLikeOrDislikeComment() {
            await this.likeOrDislikeComment({
                id: this.comment.id,
                userId: this.user.id
            });
        }
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
