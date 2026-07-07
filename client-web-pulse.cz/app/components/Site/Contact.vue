<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useApi } from '~/../app/composables/useApi';

const TYPES = ['Nový web', 'E‑shop', 'Úpravy stávajícího webu', 'Správa a údržba', 'Něco jiného'];
const BUDGETS = ['Do 50 000 Kč', '50–100 000 Kč', 'Nad 100 000 Kč', 'Zatím nevím'];

const api = useApi();
const toast = useToast();
const { locale } = useI18n();

const form = reactive({
  fullname: '',
  email: '',
  type: TYPES[0],
  budget: BUDGETS[0],
  message: '',
});
const sent = ref(false);
const sending = ref(false);

const inputClass =
  'rounded-[0.875rem] border-[1.5px] border-brand-soft px-4 py-[13px] text-[15px] outline-none transition-colors focus:border-brand';

const onSubmit = async () => {
  if (sending.value) return;
  sending.value = true;
  try {
    await api.global.demand(
      {
        fullname: form.fullname,
        phone: '',
        email: form.email,
        text: `Co potřebuje: ${form.type}\nOrientační rozpočet: ${form.budget}\n\n${form.message}`,
      },
      locale.value,
    );
    sent.value = true;
  } catch (error) {
    toast.error({
      title: 'Chyba',
      message: 'Poptávku se nepodařilo odeslat. Zkuste to prosím znovu.',
      position: 'topRight',
    });
    console.error('Error submitting demand form:', error);
  } finally {
    sending.value = false;
  }
};
</script>

<template>
  <section id="kontakt" class="section bg-brand-dark">
    <div class="container-x grid items-start gap-16 lg:grid-cols-[1fr_1.1fr]">
      <div class="reveal reveal-left flex flex-col gap-5">
        <span
          class="inline-block self-start rounded-full bg-brand/25 px-3.5 py-1.5 text-sm font-bold text-brand-accent"
        >
          Nezávazná poptávka
        </span>
        <h2 class="m-0 text-[clamp(30px,3vw,44px)] !text-white">Pojďme probrat váš projekt</h2>
        <p class="m-0 text-pretty text-[17px] leading-[1.7] text-white/65">
          Napište mi pár vět o tom, co potřebujete. Do 48 hodin se ozvu s návrhem dalšího postupu —
          konzultace je zdarma a k ničemu vás nezavazuje.
        </p>
        <div class="mt-2 flex flex-col gap-3.5">
          <a
            href="mailto:hanzl.martas@gmail.com"
            class="flex items-center gap-3.5 font-semibold text-white transition-colors hover:text-brand-accent"
          >
            <span class="grid size-11 place-items-center rounded-xl bg-brand/30 text-brand-accent">
              @
            </span>
            hanzl.martas@gmail.com
          </a>
          <a
            href="tel:+420773284824"
            class="flex items-center gap-3.5 font-semibold text-white transition-colors hover:text-brand-accent"
          >
            <span class="grid size-11 place-items-center rounded-xl bg-brand/30 text-brand-accent">
              ✆
            </span>
            +420 773 284 824
          </a>
        </div>
      </div>

      <div class="reveal reveal-right rounded-4xl bg-white p-6 sm:p-10">
        <div v-if="sent" class="flex flex-col items-center gap-4 px-6 py-12 text-center">
          <span
            class="grid size-16 place-items-center rounded-full bg-brand-soft text-[28px] font-extrabold text-brand"
          >
            ✓
          </span>
          <h3 class="m-0 text-2xl">Děkuji za poptávku!</h3>
          <p class="m-0 leading-relaxed text-brand-muted">
            Ozvu se vám do 48 hodin. Zatím se můžete podívat na
            <a href="#portfolio" class="text-brand hover:text-brand-dark">moje práce</a>.
          </p>
        </div>

        <form v-else class="flex flex-col gap-[18px]" @submit.prevent="onSubmit">
          <div class="grid gap-[18px] sm:grid-cols-2">
            <label class="flex flex-col gap-1.5 text-sm font-bold">
              Jméno
              <input
                v-model="form.fullname"
                required
                name="jmeno"
                type="text"
                placeholder="Vaše jméno"
                :class="inputClass"
              />
            </label>
            <label class="flex flex-col gap-1.5 text-sm font-bold">
              E‑mail
              <input
                v-model="form.email"
                required
                name="email"
                type="email"
                placeholder="vas@email.cz"
                :class="inputClass"
              />
            </label>
          </div>
          <div class="grid gap-[18px] sm:grid-cols-2">
            <label class="flex flex-col gap-1.5 text-sm font-bold">
              Co potřebujete
              <select v-model="form.type" name="typ" :class="[inputClass, 'bg-white']">
                <option v-for="type in TYPES" :key="type">{{ type }}</option>
              </select>
            </label>
            <label class="flex flex-col gap-1.5 text-sm font-bold">
              Orientační rozpočet
              <select v-model="form.budget" name="rozpocet" :class="[inputClass, 'bg-white']">
                <option v-for="budget in BUDGETS" :key="budget">{{ budget }}</option>
              </select>
            </label>
          </div>
          <label class="flex flex-col gap-1.5 text-sm font-bold">
            Zpráva
            <textarea
              v-model="form.message"
              required
              name="zprava"
              rows="5"
              placeholder="Popište v pár větách, co potřebujete…"
              :class="[inputClass, 'resize-y']"
            />
          </label>
          <button
            type="submit"
            :disabled="sending"
            class="cursor-pointer rounded-full border-none bg-brand px-8 py-4 font-extrabold text-white transition-colors hover:bg-brand-dark disabled:cursor-wait disabled:opacity-70"
          >
            {{ sending ? 'Odesílám…' : 'Odeslat poptávku' }}
          </button>
          <p class="m-0 text-center text-[13px] text-brand-muted">
            Odesláním souhlasíte se zpracováním údajů pro účely odpovědi na poptávku.
          </p>
        </form>
      </div>
    </div>
  </section>
</template>
