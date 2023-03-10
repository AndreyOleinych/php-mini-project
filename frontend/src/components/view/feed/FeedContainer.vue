<template>
    <div class="feed-container">
        <div class="navigation">
            <b-button
                class="btn-add-tweet"
                rounded
                size="is-medium"
                type="is-danger"
                icon-left="twitter"
                icon-pack="fab"
                @click="onAddTweetClick"
            >
                Tweet :)
            </b-button>

            <div class="sort-container">
                <strong>Sort By:</strong>
                <div v-if="currentSorting == 'likes'">
                    <a class="sort-item" @click="currentSorting = 'dates'">Dates</a>
                    <a class="sort-item active" @click="currentSorting = 'likes'">Likes</a>
                </div>
                <div v-else>
                    <a class="sort-item active" @click="currentSorting = 'dates'">Dates</a>
                    <a class="sort-item" @click="currentSorting = 'likes'">Likes</a>
                </div>
            </div>
        </div>

        <TweetPreviewList :tweets="tweets" @infinite="infiniteHandler" />

        <b-modal :active.sync="isNewTweetModalActive" has-modal-card>
            <NewTweetForm />
        </b-modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import TweetPreviewList from '@/components/common/TweetPreviewList.vue';
import { pusher } from '@/services/Pusher';
import { SET_TWEET } from '@/store/modules/tweet/mutationTypes';
import showStatusToast from '@/components/mixin/showStatusToast';
import NewTweetForm from './NewTweetForm.vue';

export default {
    name: 'FeedContainer',

    mixins: [showStatusToast],

    components: {
        TweetPreviewList,
        NewTweetForm,
    },

    data: () => ({
        currentSorting: 'tweetsSortedByCreatedDate',
        isNewTweetModalActive: false,
        page: 1,
    }),

    async created() {
        try {
            await this.fetchTweets({
                page: 1
            });
        } catch (error) {
            this.showErrorMessage(error.message);
        }

        const channel = pusher.subscribe('private-tweets');

        channel.bind('tweet.added', (data) => {
            this.$store.commit(`tweet/${SET_TWEET}`, data.tweet);
        });
    },

    beforeDestroy() {
        pusher.unsubscribe('private-tweets');
    },

    computed: {
        ...mapGetters('tweet', [
            'tweetsSortedByCreatedDate',
            'tweetsSortedByLikesCount'
        ]),

        tweets() {
            switch (this.currentSorting) {
            case 'dates':
                return this.tweetsSortedByCreatedDate;
            case 'likes':
                return this.tweetsSortedByLikesCount;
            default:
                return this.tweetsSortedByCreatedDate;
            }
        },
    },

    methods: {
        ...mapActions('tweet', [
            'fetchTweets',
        ]),

        onAddTweetClick() {
            this.showAddTweetModal();
        },

        showAddTweetModal() {
            this.isNewTweetModalActive = true;
        },

        async infiniteHandler($state) {
            try {
                const tweets = await this.fetchTweets({ page: this.page + 1 });

                if (tweets.length) {
                    this.page += 1;
                    $state.loaded();
                } else {
                    $state.complete();
                }
            } catch (error) {
                this.showErrorMessage(error.message);
                $state.complete();
            }
        },
    },
};
</script>

<style scoped lang="scss">
@import '~bulma/sass/utilities/initial-variables';

.navigation {
    padding: 10px 0;
    margin-bottom: 20px;
}

.modal-card {
    border-radius: 6px;
}

.btn-add-tweet {
    transition: 0.2s ease-out all;
    box-shadow: 1px 5px 5px 0 #00000020;

    &:hover {
        box-shadow: 1px 1px 0 0 #00000020;
    }

    @media screen and (max-width: $tablet) {
        font-size: 1rem;
    }
}

.sort-container{
    display: flex;
    justify-content: end;
}
.sort-container div{
    display: flex;
}
.sort-item{
    display: block;
    margin-left: 10px;
}

.sort-item.active{
    font-weight: bold;
    color: #ff3860;
}
</style>
