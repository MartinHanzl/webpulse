import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useUserGroupStore = defineStore({
	id: 'userGroupStore',
	state: () => ({
		userGroups: [],
	}),
	actions: {
		async fetchUserGroups() {
			const client = useSanctumClient();
			await client<{}>('/api/admin/user/group', {
				method: 'GET',
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json',
				},
			}).then((response) => {
				this.userGroups = response;
			});
		},
	},
    getters: {
        userGroupsOptions() {
            return this.userGroups.map((userGroup: any) => ({
                label: userGroup.name,
                name: userGroup.name,
                value: userGroup.id,
            }));
        }
    }
});
