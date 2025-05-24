<script setup lang="ts">
import SmudgeSvg from '~/public/static/img/props/smudge_hero.svg';

const { t } = useI18n();
</script>

<template>
	<div class="flex items-center h-64 py-64">
		<div class="grid grid-cols-1 md:grid-cols-2 items-center justify-between">
			<div class="col-span-1 space-y-8">
				<BasePropsHeading type="h1">
					Web-pulse
				</BasePropsHeading>
				<BasePropsHeading type="h4">
					{{ t("home.hero.description") }}
				</BasePropsHeading>
			</div>
			<div class="relative col-span-1 text-center">
				<NuxtImg
					src="/static/img/mockup.png"
					alt="Web-pulse Logo"
					class="absolute w-[75%] h-auto"
				/>
				<SmudgeSvg class="col-span-1 w-[75%] h-[75%]" />
			</div>
		</div>
	</div>
</template>
