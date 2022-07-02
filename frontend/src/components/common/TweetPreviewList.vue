<template>
    <div class="tweets-container">
        <div class="views-container">
            <strong>View:</strong>
            <div v-if="currentDisplay == 'cards'">
                <a class="display-item active" @click="currentDisplay = 'cards'">Cards</a>
                <a class="display-item " @click="currentDisplay = 'media'">Media</a>
            </div>
            <div v-else>
                <a class="display-item " @click="currentDisplay = 'cards'">Cards</a>
                <a class="display-item active" @click="currentDisplay = 'media'">Media</a>
            </div>
        </div>
        <transition-group v-if="currentDisplay == 'cards'" name="slide-prev" tag="div">
            <template v-for="tweet in tweets">
                <TweetCard
                    :key="tweet.id"
                    :tweet="tweet"
                    @click="onTweetClick"
                />
            </template>
        </transition-group>
        <transition-group v-else name="slide-prev" tag="div">
            <template v-for="tweet in tweets">
                <TweetPreview
                    :key="tweet.id"
                    :tweet="tweet"
                    @click="onTweetClick"
                />
            </template>
        </transition-group>
        <infinite-loading @infinite="infiniteHandler">
            <div slot="no-more" />
            <div slot="no-results" />
            <div slot="spinner" />
        </infinite-loading>
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';
import TweetPreview from './TweetPreview.vue';
import TweetCard from './TweetCard.vue';

export default {
    name: 'TweetPreviewList',

    props: {
        tweets: {
            type: Array,
            required: true
        },
    },

    data: () => ({
        currentDisplay: 'media',
    }),

    components: {
        TweetPreview,
        InfiniteLoading,
        TweetCard,
    },

    methods: {
        onTweetClick(tweet) {
            this.$router.push({ name: 'tweet-page', params: { id: tweet.id } }).catch(() => {});
        },

        infiniteHandler($state) {
            this.$emit('infinite', $state);
        },
    },
};
</script>

<style scoped lang="scss">
.tweets-container {
    padding-bottom: 20px;
}
.views-container{
    display: flex;
    justify-content: end;
    width: 100%;
}
.views-container div{
    display: flex;
}
.display-item div{
    display: flex;
}
.display-item{
    display: block;
    color: #000;
    margin-left: 10px;
}

.display-item.active{
    font-weight: bold;
    color: #ff3860;
}
</style>
