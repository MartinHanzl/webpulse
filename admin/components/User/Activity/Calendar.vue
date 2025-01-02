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

const emit = defineEmits(['update-item', 'load-items', 'add-item']);

const currentDate = ref(new Date());
const selectedDate = ref(new Date());

const startOfMonth = computed(() => {
	const date = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), 1);
	let day = date.getDay();
	if (day === 0) {
		day = 7; // Adjust Sunday to be the last day of the week
	}
	return day - 1; // Adjust to make Monday the first day of the week
});

const isSmile = ref('empty');
function checkSmile(day: Date) {
	const activityIds = [];
	const formattedDay = day.getDate().toString().padStart(2, '0');
	props.activities.forEach((activity) => {
		if (activity.formatted_day === formattedDay) {
			if ([2, 3, 4, 5, 16].includes(activity.activity.id)) {
				activityIds.push(activity.activity.id);
			}
		}
	});

	if (activityIds.length === 0) {
		return 'empty';
	}
	else if (activityIds.length === 1) {
		if (activityIds.includes(3)) return 'book';
		if (activityIds.includes(4)) return 'audio';
		if ([2, 5, 16].includes(activityIds[0])) return 'dream';
	}
	else if (activityIds.includes(3) && activityIds.includes(4)) {
		return 'audiobook';
	}
	else if (activityIds.includes(3) && [2, 5, 16].some(id => activityIds.includes(id))) {
		return 'bookdream';
	}
	else if (activityIds.includes(4) && [2, 5, 16].some(id => activityIds.includes(id))) {
		return 'audiodream';
	}
	else {
		return 'full';
	}
}

const daysInMonth = computed(() => {
	return new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 0).getDate();
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
	emit('load-items', currentDate.value.getMonth() + 1, currentDate.value.getFullYear());
}

function nextMonth() {
	currentDate.value = new Date(currentDate.value.setMonth(currentDate.value.getMonth() + 1));
	emit('load-items', currentDate.value.getMonth() + 1, currentDate.value.getFullYear());
}

function selectDate(date: Date) {
	selectedDate.value = date;
	emit('add-item', selectedDate.value);
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
				{{ currentDate.toLocaleDateString('cs-CZ', { month: 'long', year: 'numeric' })[0].toUpperCase() + currentDate.toLocaleDateString('cs-CZ', { month: 'long', year: 'numeric' }).slice(1) }}
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
			<div class="text-center py-2 font-semibold bg-grayDark rounded-l-lg">
				Po
			</div>
			<div class="text-center py-2 font-semibold bg-grayDark">
				Út
			</div>
			<div class="text-center py-2 font-semibold bg-grayDark">
				St
			</div>
			<div class="text-center py-2 font-semibold bg-grayDark">
				Čt
			</div>
			<div class="text-center py-2 font-semibold bg-grayDark">
				Pá
			</div>
			<div class="text-center py-2 font-semibold bg-grayDark">
				So
			</div>
			<div class="text-center py-2 font-semibold bg-grayDark rounded-r-lg">
				Ne
			</div>
			<div
				v-for="n in startOfMonth"
				:key="n"
				class="p-4"
			/>
			<div
				v-for="day in days"
				:key="day"
				class="min-h-[128px] h-auto p-2 flex flex-col border border-gray-100"
			>
				<div
					:class="['flex size-6 items-center justify-center text-primaryCustom rounded-full cursor-pointer font-medium p-[16px]', { 'bg-primaryCustom text-white': day.toDateString() === selectedDate.toDateString() }]"
					@click="selectDate(day)"
				>
					{{ day.getDate() }}
				</div>
				<div class="mt-4 grid grid-cols-3 gap-4">
					<div
						v-for="(activityItem, index) in activitiesByDay(day)"
						:key="index"
						:class="['col-span-1 cursor-pointer', { hidden: [2, 3, 4, 5, 16].includes(activityItem.activity.id) }]"
						@click="emit('update-item', activityItem)"
					>
						<div
							v-if="[6, 8, 11, 12].includes(activityItem.activity.id) && !activityItem.completed"
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
							v-if="[13, 14, 23].includes(activityItem.activity.id)"
							class="p-4 border-2 border-primaryLight col-span-1"
						/>
						<div
							v-if="activityItem.activity.id === 18"
							class="flex items-center justify-center col-span-1 text-success font-semibold"
						>
							K
						</div>
						<div
							v-if="activityItem.activity.id === 20"
							class="flex items-center justify-center col-span-1 text-primaryLight font-semibold"
						>
							K
						</div>
					</div>
					<div
						v-if="checkSmile(day) !== 'empty'"
						class="mt-4 flex items-center justify-center flex-col"
					>
						<div class="flex items-center">
							<div
								v-if="['book', 'audiobook', 'bookdream', 'full'].includes(checkSmile(day))"
								class="p-1 border-l-2 border-success"
							/>
							<div
								v-if="['audio', 'audiobook', 'audiodream', 'full'].includes(checkSmile(day))"
								class="p-1 border-r-2 border-success"
							/>
						</div>
						<div
							v-if="['dream', 'bookdream', 'audiodream', 'full'].includes(checkSmile(day))"
							class="px-4 py-2 border-b-2 border-success rounded-full"
						/>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
