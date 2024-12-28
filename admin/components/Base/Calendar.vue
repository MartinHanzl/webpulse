<script setup lang="ts">
import { ref, computed } from 'vue';
import { PauseCircleIcon, StarIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
	activities: {
		type: Array,
		required: true,
		default: [],
	},
});

const currentDate = ref(new Date());
const selectedDate = ref(new Date());

const startOfMonth = computed(() => {
	const date = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), 1);
	return date.getDay();
});

const daysInMonth = computed(() => {
	return new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), 0).getDate();
});

const days = computed(() => {
	const daysArray = [];
	for (let i = 1; i <= daysInMonth.value; i++) {
		daysArray.push(new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), i));
	}
	return daysArray;
});

function prevMonth() {
	currentDate.value = new Date(currentDate.value.setMonth(currentDate.value.getMonth() - 1));
}

function nextMonth() {
	currentDate.value = new Date(currentDate.value.setMonth(currentDate.value.getMonth() + 1));
}

function selectDate(date: Date) {
	selectedDate.value = date;
}

const activitiesByDay = computed(() => {
	return (day: Date) => {
		const formattedDay = day.getDate().toString().padStart(2, '0');
		return props.activities.filter((activity) => {
			return activity.formatted_day === formattedDay;
		});
	};
});
</script>

<template>
	<div class="p-4">
		<div class="flex justify-between items-center mb-4">
			<BaseButton
				variant="primary"
				size="lg"
				@click="prevMonth"
			>
				Předchozí měsíc
			</BaseButton>
			<h2 class="text-lg font-semibold text-primaryCustom">
				{{ currentDate.toLocaleDateString('cs-CZ', { month: 'long', year: 'numeric' }) }}
			</h2>
			<BaseButton
				variant="primary"
				size="lg"
				@click="nextMonth"
			>
				Následující měsíc
			</BaseButton>
		</div>
		<div class="grid grid-cols-7">
			<div class="text-center font-semibold bg-grayDark">
				Ne
			</div>
			<div class="text-center font-semibold bg-grayDark">
				Po
			</div>
			<div class="text-center font-semibold bg-grayDark">
				Út
			</div>
			<div class="text-center font-semibold bg-grayDark">
				St
			</div>
			<div class="text-center font-semibold bg-grayDark">
				Čt
			</div>
			<div class="text-center font-semibold bg-grayDark">
				Pá
			</div>
			<div class="text-center font-semibold bg-grayDark">
				So
			</div>
			<div
				v-for="n in startOfMonth"
				:key="n"
				class="p-4"
			/>
			<div
				v-for="day in days"
				:key="day"
				:class="['min-h-[128px] h-auto p-4 flex flex-col cursor-pointer border border-grayDark', { 'bg-grayLight': day.toDateString() === selectedDate.toDateString() }, 'text-primaryCustom']"
				@click="selectDate(day)"
			>
				<div>
					{{ day.getDate() }}
				</div>
				<div class="mt-4 grid grid-cols-3 gap-4">
					<div
						v-for="(activityItem, index) in activitiesByDay(day)"
						:key="index"
						class="col-span-1"
					>
						<div
							v-if="[6, 8, 11, 12].includes(activityItem.activity.id)"
							class="p-4 border-b-2 border-r-2 border-danger"
						/>
						<div
							v-else-if="[6, 8, 11, 12].includes(activityItem.activity.id) && activityItem.completed"
							class="p-4 border-2 border-danger bg-danger"
						/>
						<PauseCircleIcon
							v-if="activityItem.activity.id === 10"
							class="text-danger size-8"
						/>
						<StarIcon
							v-if="activityItem.activity.id === 22"
							class="text-danger size-8 col-span-1 fill-danger"
						/>
						<div
							v-if="activityItem.activity.id === 21"
							class="p-4 border-2 border-grayDark col-span-1"
						/>
						<div
							v-if="[13, 14].includes(activityItem.activity.id)"
							class="p-4 border-2 border-primaryLight col-span-1"
						/>
						<div
							v-if="activityItem.activity.id === 18"
							class="p-4 flex items-center justify-center col-span-1 text-success font-semibold"
						>
							K
						</div>
						<div
							v-if="activityItem.activity.id === 20"
							class="p-4 flex items-center justify-center col-span-1 text-primaryLight font-semibold"
						>
							K
						</div>
						<div
							v-if="[2, 3, 4, 5, 16].includes(activityItem.activity.id)"
							class="mt-4 flex items-center justify-center flex-col"
						>
							<div class="flex items-center">
								<div
									v-if="activityItem.activity.id === 3"
									class="p-1 border-l-2 border-success"
								/>
								<div
									v-if="activityItem.activity.id === 4"
									class="p-1 border-r-2 border-success"
								/>
							</div>
							<div
								v-if="[2, 5, 16].includes(activityItem.activity.id)"
								class="px-4 py-2 border-b-2 border-success rounded-full"
							/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
