<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useMedicalContent } from '~/../app/composables/useMedicalContent';
import { useStockImages } from '~/../app/composables/useStockImages';
import { useAutoSlider } from '~/../app/composables/useAutoSlider';

const props = defineProps<{ demo: DemoDefinition }>();

const ph = useStockImages().get('medical');
const { departments, doctors, stats, steps, healthTips, appointmentDoctors } = useMedicalContent();

const homepageDoctors = doctors.slice(0, 4);

// --- Health-tip ticker (hero bottom bar) ------------------------------------
const { index: tipIndex } = useAutoSlider(healthTips.length, 4000);

// --- Departments carousel: 3 / 2 / 1 cards per view, autoplay + dots --------
const perView = ref(1);
const depIndex = ref(0);
let depTimer: ReturnType<typeof setInterval> | null = null;
const depMax = computed(() => Math.max(departments.length - perView.value, 0));

function depGo(i: number) {
  depIndex.value = Math.min(Math.max(i, 0), depMax.value);
}
function depTick() {
  depIndex.value = depIndex.value >= depMax.value ? 0 : depIndex.value + 1;
}
function depPause() {
  if (depTimer) clearInterval(depTimer);
  depTimer = null;
}
function depResume() {
  depPause();
  depTimer = setInterval(depTick, 4000);
}

let mqHandlers: Array<() => void> = [];
onMounted(() => {
  const queries: Array<[string, number]> = [
    ['(min-width: 1200px)', 3],
    ['(min-width: 768px) and (max-width: 1199px)', 2],
    ['(max-width: 767px)', 1],
  ];
  mqHandlers = queries.map(([q, count]) => {
    const mq = window.matchMedia(q);
    const apply = () => {
      if (mq.matches) {
        perView.value = count;
        depIndex.value = Math.min(depIndex.value, depMax.value);
      }
    };
    apply();
    mq.addEventListener('change', apply);
    return () => mq.removeEventListener('change', apply);
  });
  depResume();
});
onBeforeUnmount(() => {
  depPause();
  mqHandlers.forEach((off) => off());
});

// --- Appointment form (demo only — no backend) ------------------------------
const form = ref({ name: '', email: '', date: '', time: '', doctor: '', message: '' });
const formSent = ref(false);
function submitAppointment() {
  formSent.value = true;
}
</script>

<template>
  <div class="bg-white text-brand-ink lg:px-10">
    <!-- 1. HERO -->
    <section class="relative overflow-hidden bg-brand-soft lg:rounded-2xl">
      <img
        :src="ph.hero"
        alt=""
        class="absolute inset-0 size-full object-cover opacity-30 mix-blend-luminosity"
      />
      <div
        class="absolute inset-0 bg-gradient-to-r from-brand-soft via-brand-soft/80 to-brand/20"
      />

      <div class="container-x relative grid items-end gap-10 pt-20 lg:grid-cols-12 lg:pt-24">
        <div class="self-center pb-16 text-center lg:col-span-5 lg:pb-28 lg:text-left">
          <p class="reveal text-lg font-semibold text-brand">Svěřte nám svůj problém!</p>
          <h1 class="reveal mt-4 text-4xl leading-[1.05] sm:text-5xl xl:text-6xl">
            Nejdůvěryhodnější partner pro váš zdravý život.
          </h1>
          <div
            class="reveal mt-9 flex flex-wrap items-center justify-center gap-4 lg:justify-start"
          >
            <ThemeButton :to="`/demo/${props.demo.slug}/lekari`" variant="dark" size="lg">
              Najít lékaře
            </ThemeButton>
            <ThemeButton
              :to="`/demo/${props.demo.slug}/objednani`"
              variant="outline"
              size="lg"
              :icon="false"
            >
              <span class="material-symbols-outlined text-xl">videocam</span>
              Videohovor
            </ThemeButton>
          </div>
        </div>

        <div class="relative hidden self-end lg:col-span-7 lg:block">
          <div class="relative ml-auto w-[85%] overflow-hidden rounded-t-3xl">
            <img :src="ph.aboutSecondary" alt="Lékařka kliniky" class="w-full object-cover" />
          </div>

          <!-- Pulsing heart badge -->
          <span
            class="sonar-ring absolute left-8 top-10 flex size-20 items-center justify-center rounded-full bg-[#f45959] text-[#f45959] shadow-xl"
          >
            <span class="material-symbols-outlined text-3xl !text-white">favorite</span>
          </span>

          <!-- Frosted glass card -->
          <div
            class="floaty absolute right-4 top-1/3 w-[250px] rounded-xl bg-white/70 p-6 shadow-xl backdrop-blur-md"
          >
            <span
              class="flex size-11 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">verified</span>
            </span>
            <p class="mt-3 font-bold">Ověření lékaři</p>
            <p class="text-sm text-brand-muted">Profesionální péče</p>
          </div>
        </div>
      </div>

      <!-- Health-tip ticker bar -->
      <div class="relative bg-brand py-6 text-white">
        <div class="container-x flex flex-col items-center gap-3 sm:flex-row sm:gap-6">
          <p class="flex shrink-0 items-center gap-2 font-semibold">
            <span class="material-symbols-outlined">info</span>
            Zdravotní doporučení
            <span class="ml-4 hidden h-6 w-px bg-white/30 sm:block" />
          </p>
          <transition name="fade" mode="out-in">
            <p :key="tipIndex" class="text-center text-white/90 sm:text-left">
              {{ healthTips[tipIndex] }}
            </p>
          </transition>
        </div>
      </div>
    </section>

    <!-- 2. ABOUT -->
    <section class="section bg-white">
      <div class="container-x grid items-center gap-16 lg:grid-cols-12">
        <div class="reveal-left relative lg:col-span-6">
          <div class="w-3/4 overflow-hidden rounded-xl">
            <img :src="ph.aboutMain" alt="" class="aspect-[3/4] w-full object-cover" />
          </div>
          <div
            class="absolute -bottom-10 right-0 w-[55%] overflow-hidden rounded-xl border-8 border-white shadow-2xl"
          >
            <img :src="ph.aboutSecondary" alt="" class="aspect-[4/5] w-full object-cover" />
          </div>
          <div
            class="absolute bottom-16 left-6 hidden size-36 items-center justify-center rounded-full bg-white text-brand shadow-2xl md:flex"
          >
            <ThemeCircleText text="• Klinika Vitalmed • péče o celou rodinu " :duration="18">
              <span class="material-symbols-outlined text-4xl">health_and_safety</span>
            </ThemeCircleText>
          </div>
        </div>

        <div class="reveal-right lg:col-span-5 lg:col-start-8">
          <p class="flex items-center gap-4 font-bold">
            <span
              class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
            >
              <span class="material-symbols-outlined">medical_information</span>
            </span>
            O klinice Vitalmed
          </p>
          <h2 class="mt-6 text-4xl sm:text-5xl">Vítejte na klinice Vitalmed.</h2>
          <p class="mt-6 text-lg leading-relaxed text-brand-muted">
            Vážíme si každého, kdo nám svěří své zdraví, a den co den pracujeme na tom, abychom
            překonali očekávání pacientů i jejich blízkých.
          </p>

          <div class="mt-8 flex items-center gap-5">
            <span class="text-5xl font-extrabold tracking-tight">
              <ThemeCounter :to="722" suffix="+" />
            </span>
            <span class="flex items-center gap-1 rounded-full bg-brand-accent px-4 py-2 text-white">
              <span v-for="i in 5" :key="i" class="material-symbols-outlined star-fill text-base"
                >star</span
              >
            </span>
            <p class="border-l-2 border-brand-ink pl-5 font-bold leading-snug">
              Pětihvězdičková hodnocení od spokojených pacientů.
            </p>
          </div>

          <div class="mt-10 flex flex-wrap gap-4">
            <ThemeButton :to="`/demo/${props.demo.slug}/o-nas`" variant="dark" size="md">
              O klinice
            </ThemeButton>
            <ThemeButton
              :to="`/demo/${props.demo.slug}/objednani`"
              variant="outline"
              size="md"
              :icon="false"
            >
              <span class="material-symbols-outlined text-xl">calendar_month</span>
              Objednat se
            </ThemeButton>
          </div>
        </div>
      </div>
    </section>

    <!-- 3. DEPARTMENTS + STATS -->
    <section class="relative bg-brand-soft py-24 lg:mx-0 lg:rounded-2xl">
      <svg
        class="pointer-events-none absolute -left-10 -top-10 hidden w-72 text-brand/20 lg:block"
        viewBox="0 0 200 200"
        fill="none"
        stroke="currentColor"
      >
        <template v-for="row in 5" :key="row">
          <path
            v-for="col in 5"
            :key="col"
            :transform="`translate(${col * 36 - 26} ${row * 36 - 26}) rotate(30 14 14)`"
            d="M14 1l11.3 6.5v13L14 27 2.7 20.5v-13z"
          />
        </template>
      </svg>

      <div class="container-x">
        <div class="grid items-center gap-12 lg:grid-cols-12">
          <div class="reveal-left lg:col-span-4">
            <p class="flex items-center gap-4 font-bold">
              <span
                class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
              >
                <span class="material-symbols-outlined">verified_user</span>
              </span>
              Naše moderní služby
            </p>
            <h2 class="mt-6 text-4xl sm:text-5xl">Naše klinická oddělení.</h2>
            <p class="mt-5 text-lg leading-relaxed text-brand-muted">
              Specializovaná pracoviště pod jednou střechou — od prevence po náročné zákroky.
            </p>
            <ThemeButton
              :to="`/demo/${props.demo.slug}/lecba`"
              variant="dark"
              size="md"
              class="mt-8"
            >
              Všechny obory
            </ThemeButton>
          </div>

          <div
            class="reveal relative overflow-hidden lg:col-span-8"
            @mouseenter="depPause"
            @mouseleave="depResume"
          >
            <div
              class="flex transition-transform duration-700 ease-out"
              :style="{ transform: `translateX(-${depIndex * (100 / perView)}%)` }"
            >
              <article
                v-for="dep in departments"
                :key="dep.slug"
                class="shrink-0 px-3"
                :style="{ width: `${100 / perView}%` }"
              >
                <div
                  class="group relative h-full overflow-hidden rounded-xl bg-white p-9 shadow-lg shadow-brand-ink/5 transition-colors duration-300 hover:bg-brand"
                >
                  <span
                    class="flex size-24 items-center justify-center rounded-full bg-brand-soft text-brand transition-colors duration-300 group-hover:bg-white/15 group-hover:text-white"
                  >
                    <span class="material-symbols-outlined text-5xl">{{ dep.icon }}</span>
                  </span>
                  <h3
                    class="mt-7 text-xl font-bold transition-colors duration-300 group-hover:text-white"
                  >
                    {{ dep.name }}
                  </h3>
                  <p
                    class="mt-2 leading-relaxed text-brand-muted transition-colors duration-300 group-hover:text-white/80"
                  >
                    {{ dep.text }}
                  </p>
                  <NuxtLink
                    :to="`/demo/${props.demo.slug}/lecba`"
                    class="mt-6 inline-flex items-center gap-2 font-semibold transition-colors duration-300 group-hover:text-white"
                  >
                    <span class="material-symbols-outlined text-xl">add_circle</span>
                    Zjistit více
                  </NuxtLink>
                </div>
              </article>
            </div>

            <div class="mt-8 flex justify-center gap-2">
              <button
                v-for="i in depMax + 1"
                :key="i"
                type="button"
                :aria-label="`Karta ${i}`"
                class="size-2.5 rounded-full transition-all"
                :class="depIndex === i - 1 ? 'w-7 bg-brand' : 'bg-brand-ink/20 hover:bg-brand/50'"
                @click="depGo(i - 1)"
              />
            </div>
          </div>
        </div>

        <!-- Stats -->
        <div class="mt-20 grid gap-10 border-t border-brand-ink/10 pt-14 sm:grid-cols-3">
          <div
            v-for="(s, i) in stats"
            :key="s.label"
            class="reveal flex items-center justify-center gap-5"
            :class="i > 0 ? 'sm:border-l sm:border-brand-ink/10' : ''"
          >
            <span class="text-5xl font-extrabold tracking-tight">
              {{ s.value }}<sup v-if="s.suffix" class="text-2xl text-brand">{{ s.suffix }}</sup>
            </span>
            <div>
              <span v-if="s.stars" class="flex text-brand-ink">
                <span
                  v-for="star in 5"
                  :key="star"
                  class="material-symbols-outlined star-fill text-base"
                  >star</span
                >
              </span>
              <p class="mt-1 font-semibold text-brand-muted">{{ s.label }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. VIRTUAL TOUR / TECHNOLOGIES -->
    <section class="section bg-white">
      <div class="container-x">
        <div class="grid items-center gap-16 lg:grid-cols-12">
          <div class="reveal-left group relative lg:col-span-6">
            <div
              class="overflow-hidden rounded-xl transition-transform duration-500 [transform-style:preserve-3d] group-hover:[transform:perspective(2450px)_rotateX(2deg)_rotateY(-3deg)]"
            >
              <img :src="ph.choose" alt="" class="aspect-[4/3] w-full object-cover" />
            </div>
            <a
              href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
              target="_blank"
              rel="noopener"
              class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-1/2 items-center gap-3 rounded-full bg-white px-8 py-4 font-bold shadow-2xl transition-transform hover:scale-105"
            >
              <span class="material-symbols-outlined star-fill text-2xl text-[#f45959]"
                >play_circle</span
              >
              Virtuální prohlídka
            </a>
          </div>

          <div class="reveal-right lg:col-span-5 lg:col-start-8">
            <p class="flex items-center gap-4 font-bold">
              <span
                class="flex size-14 items-center justify-center rounded-full bg-brand/15 text-brand"
              >
                <span class="material-symbols-outlined">biotech</span>
              </span>
              Moderní technologie
            </p>
            <h2 class="mt-6 text-4xl sm:text-5xl">Pomůžeme vám k rychlejšímu uzdravení!</h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">
              Investujeme do nejmodernějšího vybavení a postupů, aby léčba byla šetrnější a
              rekonvalescence kratší.
            </p>
            <a
              :href="`tel:${props.demo.phone.replace(/\s/g, '')}`"
              class="mt-9 inline-flex items-center gap-2 rounded-full bg-brand-dark px-9 py-4 font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-brand"
            >
              <span class="material-symbols-outlined text-xl">call</span>
              {{ props.demo.phone }}
            </a>
          </div>
        </div>

        <p class="reveal mt-16 flex items-center justify-center gap-3 text-xl font-semibold">
          <span class="material-symbols-outlined text-3xl text-brand">sentiment_satisfied</span>
          Vrátíme vám úsměv, po kterém jste vždy toužili.
        </p>
      </div>
    </section>

    <!-- 5. DOCTORS + PROCESS STEPS -->
    <section class="relative bg-brand-soft pb-24 lg:rounded-2xl">
      <svg
        class="pointer-events-none absolute -right-10 -top-10 hidden w-72 text-brand/20 lg:block"
        viewBox="0 0 200 200"
        fill="none"
        stroke="currentColor"
      >
        <template v-for="row in 5" :key="row">
          <path
            v-for="col in 5"
            :key="col"
            :transform="`translate(${col * 36 - 26} ${row * 36 - 26}) rotate(30 14 14)`"
            d="M14 1l11.3 6.5v13L14 27 2.7 20.5v-13z"
          />
        </template>
      </svg>

      <div class="container-x">
        <!-- Overlapping steps banner -->
        <div
          class="reveal relative -top-12 grid gap-10 rounded-xl bg-brand p-10 text-white shadow-xl shadow-brand/25 lg:grid-cols-12"
        >
          <h3 class="text-2xl !text-white lg:col-span-3">
            Najděte
            <span class="relative inline-block">
              lékaře
              <svg
                class="absolute -bottom-1 left-0 w-full"
                viewBox="0 0 100 8"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
              >
                <path d="M2 6C20 2 50 1 98 4" />
              </svg>
            </span>
            přesně pro vás.
          </h3>
          <div class="grid gap-8 sm:grid-cols-3 lg:col-span-9">
            <div v-for="step in steps" :key="step.number" class="flex items-center gap-5">
              <span
                class="flex size-16 shrink-0 items-center justify-center rounded-full border border-white/40 text-xl font-bold shadow-lg shadow-brand-ink/10"
              >
                {{ step.number }}
              </span>
              <div>
                <p class="font-bold">{{ step.title }}</p>
                <p class="text-sm text-white/70">{{ step.text }}</p>
              </div>
            </div>
          </div>
        </div>

        <h2 class="reveal text-center text-4xl sm:text-5xl">Kvalifikovaní lékaři</h2>

        <div class="mt-14 grid gap-7 sm:grid-cols-2 xl:grid-cols-4">
          <article
            v-for="doc in homepageDoctors"
            :key="doc.slug"
            class="reveal rounded-xl bg-white p-9 text-center shadow-xl shadow-brand-ink/5 transition-transform duration-300 hover:-translate-y-1"
          >
            <div class="relative mx-auto size-36">
              <img :src="doc.image" :alt="doc.name" class="size-full rounded-full object-cover" />
              <span
                class="absolute -right-2 top-0 flex items-center gap-1 rounded-full bg-[#ffea23] px-3 py-1 text-sm font-bold text-brand-ink shadow"
              >
                <span class="material-symbols-outlined star-fill text-sm">star</span>
                {{ doc.rating }}
              </span>
            </div>
            <h3 class="mt-6 text-lg font-bold">{{ doc.name }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-brand-muted">
              Specializace na
              <span class="font-semibold text-brand-ink underline decoration-brand/40">{{
                doc.specialty
              }}</span>
              na klinice Vitalmed.
            </p>
            <div
              class="mt-6 flex items-center justify-center gap-5 border-t border-brand-ink/10 pt-5 text-brand-muted"
            >
              <a
                v-for="social in ['public', 'photo_camera', 'alternate_email', 'thumb_up']"
                :key="social"
                href="#"
                class="transition-colors hover:text-brand"
                @click.prevent
              >
                <span class="material-symbols-outlined text-xl">{{ social }}</span>
              </a>
            </div>
          </article>
        </div>

        <p class="reveal mt-14 flex items-center justify-center gap-4 text-xl font-semibold">
          <span
            class="rounded-full bg-brand px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white"
            >Důvěra</span
          >
          Klinice Vitalmed důvěřuje přes 10 000 pacientů.
        </p>
      </div>
    </section>

    <!-- 6. APPOINTMENT -->
    <section id="appointment" class="section bg-white">
      <div class="container-x grid gap-16 lg:grid-cols-12">
        <div class="reveal-left lg:col-span-4">
          <h2 class="text-4xl sm:text-5xl">Objednejte se k nám.</h2>
          <p class="mt-6 text-lg leading-relaxed text-brand-muted">
            Vaše údaje předáme koordinátorce péče, která se vám ozve s návrhem termínu.
          </p>
          <div class="mt-10 flex items-center gap-5">
            <span
              class="flex size-14 items-center justify-center rounded-full bg-[#f45959]/10 text-[#f45959]"
            >
              <span class="material-symbols-outlined text-3xl">e911_emergency</span>
            </span>
            <div>
              <p class="text-sm font-semibold text-brand-muted">Akutní případy</p>
              <a
                :href="`tel:${props.demo.phone.replace(/\s/g, '')}`"
                class="text-2xl font-extrabold tracking-tight transition-colors hover:text-brand"
              >
                {{ props.demo.phone }}
              </a>
            </div>
          </div>
        </div>

        <div class="reveal-right lg:col-span-8">
          <div
            v-if="formSent"
            class="flex h-full flex-col items-center justify-center rounded-xl bg-brand-soft p-12 text-center"
          >
            <span class="material-symbols-outlined text-6xl text-brand">event_available</span>
            <h3 class="mt-4 text-2xl">Děkujeme za objednávku!</h3>
            <p class="mt-2 text-brand-muted">Ozveme se vám co nejdříve s potvrzením termínu.</p>
          </div>

          <form v-else class="grid gap-5 md:grid-cols-2" @submit.prevent="submitAppointment">
            <div class="flex flex-col gap-5">
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="Jméno a příjmení pacienta*"
                class="w-full rounded-lg border border-brand-ink/10 bg-white px-5 py-4 shadow-sm outline-none transition-colors focus:border-brand"
              />
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="E-mail pacienta*"
                class="w-full rounded-lg border border-brand-ink/10 bg-white px-5 py-4 shadow-sm outline-none transition-colors focus:border-brand"
              />
              <div class="grid grid-cols-2 gap-5">
                <input
                  v-model="form.date"
                  type="date"
                  class="w-full rounded-lg border border-brand-ink/10 bg-white px-5 py-4 shadow-sm outline-none transition-colors focus:border-brand"
                />
                <input
                  v-model="form.time"
                  type="time"
                  min="08:00"
                  max="20:00"
                  class="w-full rounded-lg border border-brand-ink/10 bg-white px-5 py-4 shadow-sm outline-none transition-colors focus:border-brand"
                />
              </div>
            </div>

            <div class="flex flex-col gap-5">
              <select
                v-model="form.doctor"
                class="w-full rounded-lg border border-brand-ink/10 bg-white px-5 py-4 shadow-sm outline-none transition-colors focus:border-brand"
                :class="form.doctor === '' ? 'text-brand-muted' : ''"
              >
                <option value="" disabled selected>Vyberte lékaře</option>
                <option v-for="d in appointmentDoctors" :key="d" :value="d">{{ d }}</option>
              </select>
              <textarea
                v-model="form.message"
                rows="4"
                placeholder="Vaše zpráva"
                class="w-full flex-1 resize-none rounded-lg border border-brand-ink/10 bg-white px-5 py-4 shadow-sm outline-none transition-colors focus:border-brand"
              />
            </div>

            <p class="text-sm text-brand-muted md:self-center">
              Ochranu vašich osobních údajů bereme vážně. Bez vašeho souhlasu je nikdy nikomu
              nepředáme.
            </p>
            <div class="md:justify-self-end">
              <ThemeButton variant="solid" size="lg" :icon="false" type="submit">
                <span class="material-symbols-outlined text-xl">event_available</span>
                Objednat se
              </ThemeButton>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
.star-fill {
  font-variation-settings: 'FILL' 1;
}
</style>
