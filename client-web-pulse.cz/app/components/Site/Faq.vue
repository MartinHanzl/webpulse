<script setup lang="ts">
const FAQS = [
  {
    question: 'Jak dlouho trvá vytvoření webu?',
    answer:
      'Jednoduchý web mám hotový do 2 týdnů od dodání podkladů, větší projekty typicky 2–3 měsíce. Přesný termín dostanete v nabídce a platí.',
  },
  {
    question: 'Co všechno potřebujete ode mě?',
    answer:
      'Hlavně představu o tom, co má web dělat, a texty či fotky, pokud je máte. S obsahem umím pomoct — copywriting i fotobanku vyřešíme společně.',
  },
  {
    question: 'Kolik stojí provoz webu?',
    answer:
      'Hosting a doména vyjdou řádově na stokoruny měsíčně a platíte je napřímo — web je váš. Volitelná správa začíná na 3 500 Kč měsíčně.',
  },
  {
    question: 'Můžu si pak web upravovat sám?',
    answer:
      'Ano. Weby stavím s redakčním systémem a při předání vás zaškolím, takže texty, fotky i novinky zvládnete měnit sami.',
  },
  {
    question: 'Převezmete i web po jiném dodavateli?',
    answer:
      'Běžně. Nejdřív udělám bezplatný audit, ať víte, v jakém stavu web je — a pak se domluvíme, jestli dává smysl ho převzít, nebo postavit znovu.',
  },
];
</script>

<template>
  <section id="faq" class="section bg-brand-cream">
    <div class="container-x max-w-[880px]">
      <div class="reveal mb-12 text-center">
        <span
          class="mb-4 inline-block rounded-full bg-brand-soft px-3.5 py-1.5 text-sm font-bold text-brand"
        >
          Časté otázky
        </span>
        <h2 class="m-0 text-[clamp(30px,3vw,44px)]">Na co se ptáte nejčastěji</h2>
      </div>
      <div class="reveal flex flex-col gap-3">
        <details
          v-for="faq in FAQS"
          :key="faq.question"
          class="faq-item rounded-[1.25rem] bg-white px-7 py-[22px]"
        >
          <summary
            class="flex cursor-pointer list-none items-center justify-between gap-4 text-[17px] font-bold"
          >
            {{ faq.question }}
            <span class="faq-chevron text-[22px] font-normal text-brand transition-transform">
              +
            </span>
          </summary>
          <p class="mb-0 mt-3.5 text-[15px] leading-[1.7] text-brand-muted">{{ faq.answer }}</p>
        </details>
      </div>
    </div>
  </section>
</template>

<style scoped>
.faq-item > summary::-webkit-details-marker {
  display: none;
}
.faq-item[open] .faq-chevron {
  transform: rotate(45deg);
}
</style>
