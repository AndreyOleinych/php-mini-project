import api from '@/api/Api';
import { userMapper } from '@/services/Normalizer';
import {
    SET_USERS,
    SET_USER
} from './mutationTypes';
import { SET_LOADING } from '../../mutationTypes';

export default {
    async fetchUsers({ commit }, { page }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const users = await api.get('/users', { page });

            commit(SET_USERS, users);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async fetchUserById({ commit }, { userId }) {
        commit(SET_LOADING, true, { root: true });
        try {
            const user = await api.get(`/users/${userId}`);

            commit(SET_LOADING, false, { root: true });
            commit(SET_USER, user);

            return Promise.resolve(
                user.map(userMapper)
            );
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

};
