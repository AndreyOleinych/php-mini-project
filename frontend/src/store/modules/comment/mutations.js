import { commentMapper } from '@/services/Normalizer';
import {
    SET_COMMENTS, ADD_COMMENT, DELETE_COMMENT, DISLIKE_COMMENT
} from './mutationTypes';

export default {
    [SET_COMMENTS]: (state, comments) => {
        let commentsByIdMap = {};

        comments.forEach(comment => {
            commentsByIdMap = {
                ...commentsByIdMap,
                [comment.id]: commentMapper(comment)
            };
        });

        state.comments = commentsByIdMap;
    },

    [ADD_COMMENT]: (state, comment) => {
        state.comments = {
            ...state.comments,
            [comment.id]: commentMapper(comment)
        };
    },

    [DELETE_COMMENT]: (state, id) => {
        delete state.comments[id];
    },

    [DISLIKE_COMMENT]: (state, { id, userId }) => {
        state.comments[id].likesCount--;

        state.comments[id].likes = state.comments[id].likes.filter(like => like.userId !== userId);
    }
};
