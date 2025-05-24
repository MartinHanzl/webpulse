<script setup lang="ts">
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const benefits = Array.from({ length: 5 }, (_, i) => ({
  title: t(`benefits.data.${i}.title`),
  description: t(`benefits.data.${i}.description`),
}));
</script>

<template>
  <div>
    <LayoutContainer class="space-y-24">
      <HomeHero />
      <div>
        <BasePropsHeading type="h2">{{ t("benefits.title") }}</BasePropsHeading>
        <div class="flex flex-wrap gap-6">
          <HomeBenefitCard
            v-gsap.whenVisible.from="{ autoAlpha: 0 }"
            v-for="(benefit, index) in benefits"
            :key="index"
            :title="benefit.title"
            :description="benefit.description"
            class="col-span-1"
          />
        </div>
      </div>
      <HomeTechnologies />
    </LayoutContainer>
  </div>
</template>
