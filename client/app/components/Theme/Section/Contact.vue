<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useApi } from '~/../app/composables/useApi';
import type { DemoDefinition } from '~/../app/composables/useDemos';

const props = defineProps<{
  demo: DemoDefinition;
  subtitle: string;
  title: string;
  text: string;
  dark?: boolean;
}>();

const api = useApi();
const { locale } = useI18n();
const toast = useToast();

const form = reactive({ fullname: '', email: '', phone: '', text: '' });
const sending = ref(false);
const done = ref(false);

async function submit() {
  if (sending.value) return;
  sending.value = true;
  try {
    await api.global.demand({ ...form }, locale.value);
    done.value = true;
    toast.success({
      title: 'Odesláno!',
      message: 'Ozveme se vám co nejdříve.',
      position: 'topRight',
    });
    form.fullname = form.email = form.phone = form.text = '';
  } catch {
    toast.error({ title: 'Chyba', message: 'Zprávu se nepodařilo odeslat.', position: 'topRight' });
  } finally {
    sending.value = false;
  }
}

const contacts = [
  {
    icon: 'call',
    label: 'Zavolejte nám',
    value: props.demo.phone,
    href: `tel:${props.demo.phone.replace(/\s/g, '')}`,
  },
  {
    icon: 'mail',
    label: 'Napište nám',
    value: props.demo.email,
    href: `mailto:${props.demo.email}`,
  },
  { icon: 'location_on', label: 'Najdete nás', value: props.demo.address, href: '#' },
];
</script>

<template>
  <section id="contact" class="section" :class="dark ? 'bg-neutral-950' : ''">
    <div class="container-x">
      <div class="grid gap-10 lg:grid-cols-5 lg:gap-14">
        <!-- Info -->
        <div class="lg:col-span-2">
          <ThemeSectionHeading
            :subtitle="subtitle"
            :title="title"
            :text="text"
            align="left"
            :light="dark"
          />
          <div class="reveal mt-8 flex flex-col gap-4">
            <a
              v-for="c in contacts"
              :key="c.label"
              :href="c.href"
              class="group flex items-center gap-4 rounded-2xl border p-5 transition-all hover:border-brand-pop/30 hover:shadow-md"
              :class="dark ? 'border-white/10 bg-white/[0.04]' : 'border-slate-100 bg-white'"
            >
              <span
                class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-brand-pop/10 text-brand-pop transition-colors group-hover:bg-brand-pop group-hover:text-white"
              >
                <span class="material-symbols-outlined">{{ c.icon }}</span>
              </span>
              <span class="flex flex-col">
                <span class="text-sm" :class="dark ? 'text-white/60' : 'text-brand-muted'">{{
                  c.label
                }}</span>
                <span class="font-bold" :class="dark ? 'text-white' : 'text-brand-ink'">{{
                  c.value
                }}</span>
              </span>
            </a>
          </div>
        </div>

        <!-- Form -->
        <div class="lg:col-span-3">
          <form
            class="reveal reveal-right rounded-3xl p-7 shadow-sm sm:p-10"
            :class="dark ? 'border border-white/10 bg-neutral-900' : 'bg-brand-cream'"
            @submit.prevent="submit"
          >
            <div class="grid gap-5 sm:grid-cols-2">
              <label class="flex flex-col gap-2">
                <span class="text-sm font-semibold" :class="dark ? 'text-white' : 'text-brand-ink'"
                  >Jméno a příjmení</span
                >
                <input
                  v-model="form.fullname"
                  required
                  type="text"
                  placeholder="Jan Novák"
                  class="rounded-xl px-4 py-3.5 focus:border-brand focus:ring-brand"
                  :class="
                    dark
                      ? 'border-white/10 bg-neutral-800 text-white placeholder:text-white/40'
                      : 'border-slate-200 bg-white'
                  "
                />
              </label>
              <label class="flex flex-col gap-2">
                <span class="text-sm font-semibold" :class="dark ? 'text-white' : 'text-brand-ink'"
                  >Telefon</span
                >
                <input
                  v-model="form.phone"
                  type="tel"
                  placeholder="+420 …"
                  class="rounded-xl px-4 py-3.5 focus:border-brand focus:ring-brand"
                  :class="
                    dark
                      ? 'border-white/10 bg-neutral-800 text-white placeholder:text-white/40'
                      : 'border-slate-200 bg-white'
                  "
                />
              </label>
              <label class="flex flex-col gap-2 sm:col-span-2">
                <span class="text-sm font-semibold" :class="dark ? 'text-white' : 'text-brand-ink'"
                  >E-mail</span
                >
                <input
                  v-model="form.email"
                  required
                  type="email"
                  placeholder="vas@email.cz"
                  class="rounded-xl px-4 py-3.5 focus:border-brand focus:ring-brand"
                  :class="
                    dark
                      ? 'border-white/10 bg-neutral-800 text-white placeholder:text-white/40'
                      : 'border-slate-200 bg-white'
                  "
                />
              </label>
              <label class="flex flex-col gap-2 sm:col-span-2">
                <span class="text-sm font-semibold" :class="dark ? 'text-white' : 'text-brand-ink'"
                  >Vaše zpráva</span
                >
                <textarea
                  v-model="form.text"
                  rows="4"
                  placeholder="Napište nám, s čím můžeme pomoci…"
                  class="resize-none rounded-xl px-4 py-3.5 focus:border-brand focus:ring-brand"
                  :class="
                    dark
                      ? 'border-white/10 bg-neutral-800 text-white placeholder:text-white/40'
                      : 'border-slate-200 bg-white'
                  "
                />
              </label>
            </div>
            <div class="mt-6 flex flex-wrap items-center gap-4">
              <button
                type="submit"
                :disabled="sending"
                class="group inline-flex items-center justify-center gap-2 rounded-full bg-brand px-8 py-4 font-semibold text-white shadow-lg shadow-brand/25 transition-all hover:-translate-y-0.5 hover:bg-brand-dark disabled:opacity-60"
              >
                {{ sending ? 'Odesílám…' : done ? 'Odesláno ✓' : 'Odeslat poptávku' }}
                <span
                  class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1"
                  >send</span
                >
              </button>
              <p class="text-sm" :class="dark ? 'text-white/60' : 'text-brand-muted'">
                Odpovídáme zpravidla do 24 hodin.
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>
