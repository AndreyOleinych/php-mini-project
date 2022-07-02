import { userMapper } from '@/services/Normalizer';
import {
    SET_USERS,
    SET_USER,
} from './mutationTypes';

export default {
    [SET_USERS]: (state, users) => {
        state.users = {
            ...state.users,
            ...users.reduce(
                (prev, user) => ({ ...prev, [user.id]: userMapper(user) }),
                {}
            ),
        };
    },

    [SET_USER]: (state, user) => {
        state.users = {
            ...state.users,
            [user.id]: userMapper(user)
        };
    },

};
