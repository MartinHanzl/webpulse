import { defineStore } from 'pinia';

export const useUserGroupStore = defineStore({
	id: 'userGroupStore',
	state: () => ({
		userGroups: [],
	}),
	actions: {
		async fetchUserGroups() {
			const client = useSanctumClient();
			const response = client('/api/admin/user/group');
			const userGroups = await response.json();
			this.userGroups = userGroups;
		},
	},
	getters: {
		userGroupOptions() {
			return this.userGroups.map(userGroup => ({
				name: userGroup.name,
				label: userGroup.name,
				value: userGroup.id,
			}));
		},
	},
});
