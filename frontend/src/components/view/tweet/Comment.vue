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
                    {{ comment.id }}
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
            </div>
        </div>
    </article>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import DefaultAvatar from '../../common/DefaultAvatar.vue';
import showStatusToast from '../../mixin/showStatusToast';

export default {
    name: 'Comment',

    components: {
        DefaultAvatar,
    },

    mixins: [showStatusToast],

    props: {
        comment: {
            type: Object,
            required: true,
        },
    },

    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),

        ...mapGetters('comment', [
            'isCommentOwner',
        ]),
    },

    methods: {
        ...mapActions('comment', [
            'deleteComment',
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
