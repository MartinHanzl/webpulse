<script setup lang="ts">
const { t } = useI18n();
</script>

<template>
	<div
		class="flex items-center h-64 py-64 px-24 border-2 border-primary rounded-xl"
	>
		<div class="grid grid-cols-1 md:grid-cols-2">
			<div class="col-span-1 space-y-8">
				<BasePropsHeading type="h1">
					Web-pulse
				</BasePropsHeading>
				<BasePropsHeading type="h3">
					{{
						t("home.hero.description")
					}}
				</BasePropsHeading>
			</div>
		</div>
	</div>
</template>
