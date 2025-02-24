<script setup lang="ts">
const daysInMonth = (month: number, year: number) => {
	return new Date(year, month, 0).getDate();
};

const props = defineProps({
	categories: {
		type: Array,
		required: true,
		default: [],
	},
	income: {
		type: Array,
		required: true,
		default: [],
	},
	year: {
		type: Number,
		required: true,
		default: new Date().getFullYear(),
	},
	month: {
		type: Number,
		required: true,
		default: new Date().getMonth() + 1,
	},
});

const cashflowActionDialog = ref({
	show: false as boolean,
	dayRecords: [] as [],
	day: 0 as number,
	type: 'expense' as string,
	categoryId: null as number | null,
});

const cashflowBudgetDialog = ref({
	show: false as boolean,
	categoryId: 0 as number,
	budget: 0 as number,
});

const emit = defineEmits(['create-category', 'load-items', 'save-day-records', 'save-budget']);

function handleMouseOver(event: MouseEvent) {
	const target = event.currentTarget as HTMLElement;
	const verticalLine = target.querySelector('.vertical-line::after');
	if (verticalLine) {
		verticalLine.style.display = 'flex';
	}
}
function handleClick() {
	emit('create-category');
}

function summaryByDay(categoryId: number, day: number) {
	const category = props.categories.find((category: any) => category.id === categoryId);
	if (category) {
		return category.cashflows.reduce((acc: number, cashflow: any) => {
			if (new Date(cashflow.date).getDate() === day) {
				return acc + cashflow.amount;
			}
			return acc;
		}, 0);
	}
	return 0;
}

function incomeByDay(day: number) {
	return props.income.reduce((acc: number, cashflow: any) => {
		if (new Date(cashflow.date).getDate() === day) {
			return acc + cashflow.amount;
		}
		return acc;
	}, 0);
}

function monthlyCategoryBudget(categoryId: number) {
	const category = props.categories.find((category: any) => category.id === categoryId);
	if (category) {
		return category.budgets[0].amount;
	}
	return 0;
}

function totalSpent(categoryId: number) {
	const category = props.categories.find((category: any) => category.id === categoryId);
	if (category) {
		return category.cashflows.reduce((acc: number, cashflow: any) => {
			return acc + cashflow.amount;
		}, 0);
	}
	return 0;
}

function leftFromBudget(categoryId: number) {
	const category = props.categories.find((category: any) => category.id === categoryId);
	if (category) {
		const totalSpent = category.cashflows.reduce((acc: number, cashflow: any) => {
			return acc + cashflow.amount;
		}, 0);
		return category.budgets[0].amount - totalSpent;
	}
	return 0;
}

function descriptionByDay(day: number) {
	return props.categories.reduce((acc: string, category: any) => {
		return category.cashflows.reduce((acc: string, cashflow: any) => {
			if (new Date(cashflow.date).getDate() === day) {
				return acc ? `${acc}, ${cashflow.description}` : cashflow.description;
			}
			return acc;
		}, acc);
	}, '');
}

function updateCashflow(type: string, categoryId: number | null, day: number) {
	cashflowActionDialog.value.day = day;
	cashflowActionDialog.value.categoryId = categoryId;

	const category = props.categories.find((category: any) => category.id === categoryId);
	if (category) {
		const dayRecords = category.cashflows
			.filter((cashflow: any) => new Date(cashflow.date).getDate() === day)
			.map((cashflow: any) => ({
				id: cashflow.id,
				description: cashflow.description,
				amount: cashflow.amount,
			}));

		cashflowActionDialog.value.dayRecords = dayRecords;
	}
	else {
		cashflowActionDialog.value.dayRecords = [];
	}

	// Přidání prázdného záznamu mimo reaktivní změny
	cashflowActionDialog.value.dayRecords.push({
		id: null,
		description: '',
		amount: 0,
	});
	cashflowActionDialog.value.type = type;
	cashflowActionDialog.value.show = true;
}

function updateCashflowIncome(day: number) {
	cashflowActionDialog.value.day = day;
	cashflowActionDialog.value.categoryId = null;

	const dayRecords = props.income
		.filter((cashflow: any) => new Date(cashflow.date).getDate() === day)
		.map((cashflow: any) => ({
			id: cashflow.id,
			description: cashflow.description,
			amount: cashflow.amount,
		}));

	cashflowActionDialog.value.dayRecords = dayRecords;

	// Přidání prázdného záznamu mimo reaktivní změny
	cashflowActionDialog.value.dayRecords.push({
		id: null,
		description: '',
		amount: 0,
	});
	cashflowActionDialog.value.type = 'income';
	cashflowActionDialog.value.show = true;
}

function updateBudget(categoryId: number, budget: number) {
	cashflowBudgetDialog.value.categoryId = categoryId;
	cashflowBudgetDialog.value.budget = budget;
	cashflowBudgetDialog.value.show = true;
}

function leftFromBudgetTextClass(categoryId: number) {
	const leftFromBudgetValue = leftFromBudget(categoryId);
	const totalBudget = monthlyCategoryBudget(categoryId);
	const percentageLeft = (leftFromBudgetValue / totalBudget) * 100;

	if (percentageLeft >= 50) {
		return 'bg-green-200 text-success';
	}
	else if (percentageLeft >= 25) {
		return 'bg-yellow-200 text-warning';
	}
	else {
		return 'bg-red-200 text-danger';
	}
}

function summaryMonthlySpent() {
	return props.categories.reduce((acc: number, category: any) => {
		return acc + category.cashflows.reduce((acc: number, cashflow: any) => {
			return acc + cashflow.amount;
		}, 0);
	}, 0);
}

function summaryMonthlyBudget() {
	return props.categories.reduce((acc: number, category: any) => {
		return acc + category.budgets[0].amount;
	}, 0);
}

function summaryMonthlyLeft() {
	return summaryMonthlyBudget() - summaryMonthlySpent();
}

function markRowColumn(event: MouseEvent) {
	const target = event.currentTarget as HTMLElement;
	const row = target.parentElement as HTMLElement;
	const table = row.parentElement?.parentElement as HTMLElement;
	const columnIndex = Array.from(row.children).indexOf(target);

	// Reset all cells
	table.querySelectorAll('td, th').forEach((cell) => {
		cell.classList.remove('highlight');
	});

	// Highlight the row
	row.querySelectorAll('td').forEach((cell) => {
		cell.classList.add('highlight');
	});

	// Highlight the column
	table.querySelectorAll(`tr`).forEach((row) => {
		const cell = row.children[columnIndex];
		if (cell) {
			cell.classList.add('highlight');
		}
	});
}

function formatAmount(amount: number) {
	return amount.toLocaleString('cs-CZ');
}
</script>

<template>
	<div class="px-4 sm:px-0">
		<div class="mt-8 flow-root">
			<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
					<table class="min-w-full divide-y divide-gray-300">
						<thead>
							<tr class="divide-x divide-gray-200">
								<th
									scope="col"
									class="text-center px-2 lg:px-4 w-[80px] py-2 lg:py-3.5 text-xs lg:text-sm font-semibold text-gray-300 bg-gray-900"
								>
									Datum
								</th>
								<th
									scope="col"
									class="w-[110px] py-2 lg:py-3.5 pr-2 lg:pr-4 pl-2 lg:pl-4 text-left text-xs lg:text-sm font-semibold text-gray-300 sm:pr-0 bg-gray-900"
								>
									Příjem
								</th>
								<th
									v-for="(category, index) in categories"
									:key="index"
									scope="col"
									class="w-[110px] px-2 lg:px-4 py-2 lg:py-3.5 text-left text-xs lg:text-sm font-semibold text-gray-300 bg-gray-900"
									:class="{ 'vertical-line': index === categories.length - 1 }"
									@mouseover="handleMouseOver"
									@click="handleClick"
								>
									{{ category.name }}
								</th>
								<th
									scope="col"
									class="py-2 lg:py-3.5 pr-2 lg:pr-4 pl-2 lg:pl-4 text-left text-xs lg:text-sm font-semibold text-gray-300 sm:pr-0 bg-gray-900"
								>
									Poznámky
								</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-200 bg-white">
							<tr
								v-for="(day, index) in daysInMonth(month, year)"
								:key="index"
								class="divide-x divide-gray-200"
							>
								<td class="p-1 lg:p-2 text-sm lg:text-sm whitespace-nowrap text-gray-500 font-semibold text-center">
									{{ day }}
								</td>
								<td
									class="p-1 lg:p-2 text-xs whitespace-nowrap text-gray-500 text-end hover:bg-gray-100 cursor-pointer"
									@click="updateCashflowIncome(day)"
								>
									<span v-if="incomeByDay(day) > 0">{{ formatAmount(incomeByDay(day)) }} Kč</span>
								</td>
								<td
									v-for="(category, index) in categories"
									:key="index"
									class="p-1 lg:p-2 text-xs whitespace-nowrap text-gray-500 text-end hover:bg-gray-100 cursor-pointer"
									@click="updateCashflow('expense', category.id, day)"
									@mouseover="markRowColumn"
								>
									<span v-if="summaryByDay(category.id, day) > 0">{{ formatAmount(summaryByDay(category.id, day)) }} Kč</span>
								</td>
								<td class="p-1 lg:p-2 text-xs whitespace-nowrap text-gray-500">
									{{ descriptionByDay(day) }}
								</td>
							</tr>
							<tr
								class="divide-x divide-gray-200"
							>
								<td
									class="p-1 lg:p-2 text-xs lg:text-sm whitespace-nowrap text-gray-500 font-semibold text-center"
									colspan="2"
								>
									Celkem utraceno
								</td>
								<td
									v-for="(category, index) in categories"
									:key="index"
									class="p-1 lg:p-2 text-xs lg:text-sm font-semibold whitespace-nowrap text-gray-500 text-end"
								>
									{{ formatAmount(totalSpent(category.id)) + ' Kč' }}
								</td>
								<td class="p-1 lg:p-2 text-xs lg:text-sm text-center font-semibold whitespace-nowrap text-sky-500">
									{{ formatAmount(summaryMonthlySpent()) }} Kč
								</td>
							</tr>
							<tr
								class="divide-x divide-gray-200"
							>
								<td
									class="p-1 lg:p-2 text-xs lg:text-sm whitespace-nowrap text-gray-500 font-semibold text-center"
									colspan="2"
								>
									Měsíční budget
								</td>
								<td
									v-for="(category, index) in categories"
									:key="index"
									class="p-1 lg:p-2 text-xs lg:text-sm font-semibold whitespace-nowrap text-gray-500 text-end cursor-pointer hover:bg-gray-100"
									@click="updateBudget(category.id, monthlyCategoryBudget(category.id))"
								>
									{{ formatAmount(monthlyCategoryBudget(category.id)) + ' Kč' }}
								</td>
								<td class="p-1 lg:p-2 text-xs lg:text-sm text-center font-semibold whitespace-nowrap text-sky-500">
									{{ formatAmount(summaryMonthlyBudget()) }} Kč
								</td>
							</tr>
							<tr class="divide-x divide-gray-200">
								<td
									class="p-1 lg:p-2 text-xs lg:text-sm whitespace-nowrap text-gray-500 font-semibold text-center"
									colspan="2"
								>
									Zůstává z budgetu
								</td>
								<td
									v-for="(category, index) in categories"
									:key="index"
									:class="leftFromBudgetTextClass(category.id) + ' p-1 lg:p-2 text-xs lg:text-sm font-semibold whitespace-nowrap text-end'"
								>
									{{ formatAmount(leftFromBudget(category.id)) + ' Kč' }}
								</td>
								<td class="p-1 lg:p-2 text-xs lg:text-sm text-center font-semibold whitespace-nowrap text-sky-500">
									{{ formatAmount(summaryMonthlyLeft()) }} Kč
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<CashflowDialogAction
			v-model:show="cashflowActionDialog.show"
			:day="cashflowActionDialog.day"
			:day-records="cashflowActionDialog.dayRecords"
			:type="cashflowActionDialog.type"
			@save-day-records="emit('save-day-records', cashflowActionDialog.categoryId, cashflowActionDialog.day, cashflowActionDialog.type, cashflowActionDialog.dayRecords)"
		/>
		<CashflowDialogBudget
			:id="cashflowBudgetDialog.categoryId"
			v-model:show="cashflowBudgetDialog.show"
			v-model:budget="cashflowBudgetDialog.budget"
			@save-budget="emit('save-budget', cashflowBudgetDialog.categoryId, cashflowBudgetDialog.budget)"
		/>
	</div>
</template>

<style scoped>
.vertical-line {
  position: relative;
}

.vertical-line::after {
  content: '+';
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 32px;
  background-color: blue;
  display: none;
  cursor: pointer;
  align-items: center;
  justify-content: center;
  color: #fff;
}

.vertical-line:hover::after {
  display: flex;
}

.highlight {
  background-color: #f9fafb;
}

th {
  background-color: #111827 !important;
}
</style>
