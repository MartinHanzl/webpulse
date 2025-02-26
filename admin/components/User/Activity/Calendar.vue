<script setup lang="ts">
import { ref, computed } from 'vue';
import { PauseCircleIcon, StarIcon, HeartIcon, ChatBubbleBottomCenterIcon, PhoneIcon } from '@heroicons/vue/24/outline';
import DumbbellIcon from 'public/static/img/icon/dumbbell.svg';
import SmileFullIcon from '~/public/static/img/icon/smile-full.svg';
import SmileAudioIcon from '~/public/static/img/icon/smile-audio.svg';
import SmileAudioBookIcon from '~/public/static/img/icon/smile-audiobook.svg';
import SmileAudioDreamIcon from '~/public/static/img/icon/smile-audiodream.svg';
import SmileBookIcon from '~/public/static/img/icon/smile-book.svg';
import SmileBookDreamIcon from '~/public/static/img/icon/smile-bookdream.svg';
import SmileDreamIcon from '~/public/static/img/icon/smile-dream.svg';

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
		if (activityIds.includes(3)) {
			return 'book';
		}
		if (activityIds.includes(4)) {
			return 'audio';
		}
		if ([2, 5, 16].includes(activityIds[0])) {
			return 'dream';
		}
	}
	else if (activityIds.includes(3) && activityIds.includes(4) && [2, 5, 16].some(id => activityIds.includes(id))) {
		return 'full';
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
	else if ([2, 5, 16].some(id => activityIds.includes(id))) {
		return 'dream';
	}
	else {
		return 'empty';
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
	<div>
		<div class="flex justify-between items-center mb-4">
			<BaseButton
				variant="primary"
				size="lg"
				@click="prevMonth"
			>
				Předchozí měsíc
			</BaseButton>
			<h2 class="text-sm lg:text-lg font-semibold text-primaryCustom">
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
			<div class="text-xs lg:text-md text-center py-1.5 lg:py-2 font-semibold bg-grayDark rounded-l-lg">
				Po
			</div>
			<div class="text-xs lg:text-md text-center py-1.5 lg:py-2 font-semibold bg-grayDark">
				Út
			</div>
			<div class="text-xs lg:text-md text-center py-1.5 lg:py-2 font-semibold bg-grayDark">
				St
			</div>
			<div class="text-xs lg:text-md text-center py-1.5 lg:py-2 font-semibold bg-grayDark">
				Čt
			</div>
			<div class="text-xs lg:text-md text-center py-1.5 lg:py-2 font-semibold bg-grayDark">
				Pá
			</div>
			<div class="text-xs lg:text-md text-center py-1.5 lg:py-2 font-semibold bg-grayDark">
				So
			</div>
			<div class="text-xs lg:text-md text-center py-1.5 lg:py-2 font-semibold bg-grayDark rounded-r-lg">
				Ne
			</div>
			<div
				v-for="n in startOfMonth"
				:key="n"
				class="p-2 lg:p-4"
			/>
			<div
				v-for="day in days"
				:key="day"
				class="min-h-[128px] h-auto p-2 flex flex-col border border-gray-100"
			>
				<div
					:class="['flex size-2 lg:size-6 items-center justify-center text-primaryCustom rounded-full cursor-pointer font-medium p-[12px] lg:p-[16px] text-xs lg:text-sm', { 'bg-primaryCustom text-white': day.toDateString() === selectedDate.toDateString() }]"
					@click="selectDate(day)"
				>
					{{ day.getDate() }}
				</div>
				<div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
					<div
						v-for="(activityItem, index) in activitiesByDay(day)"
						:key="index"
						:class="['col-span-1 cursor-pointer', { hidden: [2, 3, 4, 5, 9, 16, 17, 24].includes(activityItem.activity.id) }]"
						@click="emit('update-item', activityItem)"
					>
						<div
							v-if="[6, 8, 11, 12].includes(activityItem.activity.id) && !activityItem.completed"
							class="p-2 lg:p-4 border-b-2 border-r-2 border-danger"
						/>
						<div
							v-else-if="[6, 8, 11, 12].includes(activityItem.activity.id) && activityItem.completed"
							class="p-2 lg:p-4 border-2 border-danger bg-danger"
						/>
						<PhoneIcon
							v-if="activityItem.activity.id === 1"
							class="text-amber-600 size-5 lg:size-8 col-span-1"
						/>
						<ChatBubbleBottomCenterIcon
							v-if="activityItem.activity.id === 27"
							class="text-purple-600 size-5 lg:size-8 col-span-1"
						/>
						<HeartIcon
							v-if="activityItem.activity.id === 28 && !activityItem.completed"
							class="text-pink-600 size-5 lg:size-8 col-span-1"
						/>
						<HeartIcon
							v-if="activityItem.activity.id === 28 && activityItem.completed"
							class="text-danger size-5 lg:size-8 fill-pink-600 col-span-1"
						/>
						<PauseCircleIcon
							v-if="activityItem.activity.id === 10"
							class="text-danger size-5 lg:size-8 col-span-1"
						/>
						<StarIcon
							v-if="activityItem.activity.id === 22"
							class="text-danger size-5 lg:size-8 col-span-1 fill-danger col-span-1"
						/>
						<div
							v-if="activityItem.activity.id === 21"
							class="p-2 lg:p-4 border-2 border-grayDark col-span-1"
						/>
						<div
							v-if="[13, 14, 23].includes(activityItem.activity.id)"
							class="p-2 lg:p-4 border-2 border-primaryLight col-span-1"
						/>
						<div
							v-if="activityItem.activity.id === 18"
							class="flex items-center justify-center col-span-1 text-md lg:text-lg text-success font-semibold"
						>
							K
						</div>
						<div
							v-if="activityItem.activity.id === 20"
							class="flex items-center justify-center col-span-1 text-md lg:text-lg text-primaryLight font-semibold"
						>
							K
						</div>
						<div
							v-if="activityItem.activity.id === 25"
							class="flex items-center justify-center col-span-1 text-md lg:text-lg text-primaryLight font-semibold"
						>
							<DumbbellIcon
								class="size-5 lg:size-8 fill-warning"
							/>
						</div>
					</div>
					<div
						v-if="checkSmile(day) !== 'empty'"
						class="flex justify-center flex-col col-span-1"
					>
						<SmileBookIcon
							v-if="checkSmile(day) === 'book'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
						<SmileAudioIcon
							v-else-if="checkSmile(day) === 'audio'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
						<SmileBookIcon
							v-else-if="checkSmile(day) === 'book'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
						<SmileDreamIcon
							v-else-if="checkSmile(day) === 'dream'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
						<SmileAudioBookIcon
							v-else-if="checkSmile(day) === 'audiobook'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
						<SmileBookDreamIcon
							v-else-if="checkSmile(day) === 'bookdream'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
						<SmileAudioDreamIcon
							v-else-if="checkSmile(day) === 'audiodream'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
						<SmileFullIcon
							v-else-if="checkSmile(day) === 'full'"
							class="size-5 lg:size-8 fill-success col-span-1"
						/>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
