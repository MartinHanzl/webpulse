<script setup lang="ts">
const { t } = useI18n();
defineProps({
  services: {
    type: Array,
    required: true,
  },
});
</script>
<template>
  <div>
    <BasePropsHeading type="h2">
      {{ t("services.title") }}
    </BasePropsHeading>
    <div class="space-y-4">
      <div
        v-for="(service, index) in services"
        :key="index"
        :class="[
          index % 2 === 0 ? 'text-right' : 'text-left',
          'border-b border-b-primary py-4',
        ]"
      >
        <BasePropsHeading type="h3">
          {{ service.name }}
        </BasePropsHeading>
        <BasePropsParagraph bold="bold">
          {{ service.perex }}
        </BasePropsParagraph>
        <BasePropsParagraph class="mt-2">
          Cena:
          {{ $formatPrice(service.price, service.currency, service.tax_rate) }}
          (cena je za
          {{ service.price_type === "hourly" ? "hodinu" : "projekt" }})
        </BasePropsParagraph>
      </div>
    </div>
  </div>
</template>
