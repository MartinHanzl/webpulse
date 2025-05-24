import { defineNuxtPlugin } from "#app";

export default defineNuxtPlugin((nuxtApp) => {
  const formatPrice = (
    price: number,
    currency: object,
    taxRate: object,
  ): string => {
    const rate = 1 + taxRate.rate / 100;
    const finalPrice = price * rate;

    return `${finalPrice},- Kč`;

    /*const priceWithTax = price * (1 + taxRate);
    return new Intl.NumberFormat("cs-CZ", {
      style: "currency",
      currency: currency,
    }).format(priceWithTax);*/
  };

  nuxtApp.vueApp.config.globalProperties.$formatPrice = formatPrice;
});
