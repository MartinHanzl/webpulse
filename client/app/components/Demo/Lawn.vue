<script setup lang="ts">
import type { DemoDefinition } from '~/../app/composables/useDemos';
import { useStockImages } from '~/../app/composables/useStockImages';

defineProps<{ demo: DemoDefinition }>();
const ph = useStockImages().get('lawn');

const iconBoxes = [
  {
    icon: 'grass',
    title: 'Údržba trávníku',
    text: 'Pravidelné sečení, hnojení a provzdušnění pro hustý a zdravý trávník po celou sezónu.',
  },
  {
    icon: 'forest',
    title: 'Návrh zahrady',
    text: 'Navrhneme zahradu na míru vašemu pozemku, stylu a rozpočtu — od skici po realizaci.',
  },
  {
    icon: 'deck',
    title: 'Terasy a dlažby',
    text: 'Realizujeme dřevěné terasy, zámkové dlažby a okrasné prvky, které vydrží roky.',
  },
];

const services = [
  {
    icon: 'mowing',
    name: 'Sečení a mulčování',
    perex: 'Pravidelná údržba travnatých ploch včetně sběru i mulčování.',
    image: ph.services[0],
  },
  {
    icon: 'water_drop',
    name: 'Automatické závlahy',
    perex: 'Návrh a instalace závlahových systémů s chytrým řízením.',
    image: ph.services[1],
  },
  {
    icon: 'compost',
    name: 'Hnojení a regenerace',
    perex: 'Programy výživy a regenerace pro sytě zelený trávník.',
    image: ph.services[2],
  },
  {
    icon: 'park',
    name: 'Výsadba zeleně',
    perex: 'Stromy, keře i trvalkové záhony s následnou péčí.',
    image: ph.services[3],
  },
  {
    icon: 'yard',
    name: 'Sezónní úklid',
    perex: 'Jarní a podzimní úklid, vertikutace a provzdušnění.',
    image: ph.services[4],
  },
  {
    icon: 'pest_control',
    name: 'Ochrana proti plevelu',
    perex: 'Šetrné ošetření proti plevelu, mechu a chorobám.',
    image: ph.services[5],
  },
];

const features = [
  {
    title: 'Spokojení zákazníci',
    text: 'Přes 1 200 udržovaných zahrad v Praze a okolí.',
    progress: 98,
  },
  {
    title: 'Kvalita zpracování',
    text: 'Profesionální technika a certifikovaní zahradníci.',
    progress: 95,
  },
  {
    title: 'Dodržení termínů',
    text: 'Domluvený harmonogram bez zbytečných odkladů.',
    progress: 92,
  },
];

const projects = [
  { title: 'Rodinná zahrada Řepy', category: 'Realizace', image: ph.work[0] },
  { title: 'Závlahový systém', category: 'Technologie', image: ph.work[1] },
  { title: 'Okrasné záhony', category: 'Výsadba', image: ph.work[2] },
  { title: 'Trávník u vily', category: 'Údržba', image: ph.work[3] },
  { title: 'Dřevěná terasa', category: 'Realizace', image: ph.work[4] },
];

const facts = [
  { value: 1240, suffix: '+', label: 'Udržovaných zahrad', icon: 'yard' },
  { value: 16, suffix: ' let', label: 'Na trhu', icon: 'workspace_premium' },
  { value: 24, label: 'Zahradníků v týmu', icon: 'groups' },
  { value: 98, suffix: ' %', label: 'Spokojených klientů', icon: 'sentiment_very_satisfied' },
];

const testimonials = [
  {
    name: 'Petra Svobodová',
    role: 'Praha 6',
    text: 'Náš trávník nikdy nevypadal líp. Kluci jsou spolehliví, dochvilní a po práci je vždy uklizeno.',
    rating: 5,
  },
  {
    name: 'Martin Dvořák',
    role: 'Říčany',
    text: 'Navrhli a zrealizovali nám celou zahradu včetně závlahy. Skvělá komunikace a férová cena.',
    rating: 5,
  },
  {
    name: 'Jana Horáková',
    role: 'Černošice',
    text: 'Sezónní údržbu řeším s nimi už třetí rok. Můžu jen doporučit, výsledek mluví sám za sebe.',
    rating: 5,
  },
];

const blogFallback = [
  {
    name: 'Jak na zdravý trávník na jaře',
    perex: 'Vertikutace, hnojení a první seč — provedeme vás kompletní jarní péčí.',
    image: ph.blog[0],
    category: 'Trávník',
    date: '12. března 2025',
  },
  {
    name: 'Závlaha, která šetří vodu i čas',
    perex: 'Chytré systémy zalévají přesně tehdy, kdy je potřeba. Vyplatí se?',
    image: ph.blog[1],
    category: 'Technologie',
    date: '2. dubna 2025',
  },
  {
    name: '5 rostlin do nenáročné zahrady',
    perex: 'Tipy na trvalky a keře, které zvládnou i sušší léto bez náročné péče.',
    image: ph.blog[2],
    category: 'Výsadba',
    date: '20. dubna 2025',
  },
];
</script>

<template>
  <div>
    <ThemeSectionHero
      subtitle="Zdravé, zelené a krásné trávníky"
      title="Profesionální péče o trávník pro dokonalou zahradu"
      text="Váš trávník si zaslouží tu nejlepší péči, aby zůstal hustý, zelený a zdravý po celý rok. Postaráme se o všechno za vás."
      :image="ph.hero"
      cta-label="Proměňte svůj trávník"
      :stats="[
        { value: '16+', label: 'let zkušeností' },
        { value: '1 240', label: 'zahrad' },
        { value: '4,9★', label: 'hodnocení' },
      ]"
    />

    <ThemeSectionIconBoxes :items="iconBoxes" />

    <ThemeSectionAbout
      subtitle="O nás"
      title="Pečujeme o vaši zahradu jako o vlastní"
      text="Jsme rodinná firma s vášní pro zeleň. Od roku 2008 navrhujeme, zakládáme a udržujeme trávníky i zahrady tak, aby vám dělaly radost po celý rok."
      :image="ph.aboutMain"
      :image-secondary="ph.aboutSecondary"
      :bullets="[
        'Certifikovaní zahradníci',
        'Profesionální technika',
        'Šetrné postupy k přírodě',
        'Záruka na realizace',
      ]"
      experience-years="16"
      signature="Tomáš Zelený"
    />

    <ThemeSectionMarquee
      :words="['Údržba trávníků', 'Návrh zahrad', 'Závlahy', 'Výsadba', 'Terasy']"
    />

    <ThemeSectionServices
      subtitle="Naše služby"
      title="Komplexní péče o trávník a zahradu"
      text="Od pravidelného sečení po kompletní realizaci zahrady — postaráme se o každý detail."
      :fallback="services"
    />

    <ThemeSectionChoose
      subtitle="Proč my"
      title="Důvody, proč si vybrat právě nás"
      text="Spojujeme řemeslnou preciznost s moderní technikou. Výsledek? Zahrada, která vydrží a kterou si užijete."
      :image="ph.choose"
      :features="features"
      badge="Záruka spokojenosti na každou realizaci"
    />

    <ThemeSectionFacts :items="facts" :image="ph.facts" />

    <ThemeSectionWork
      subtitle="Reference"
      title="Vybrané realizace z naší dílny"
      text="Každá zahrada je jiná. Podívejte se na ukázku naší práce."
      :projects="projects"
    />

    <ThemeSectionTestimonials
      subtitle="Reference klientů"
      title="Co o nás říkají zákazníci"
      :items="testimonials"
    />

    <ThemeSectionBlog
      subtitle="Z našeho blogu"
      title="Tipy a novinky ze zahrady"
      :fallback="blogFallback"
    />

    <ThemeSectionContact
      :demo="demo"
      subtitle="Kontakt"
      title="Pojďme se domluvit na vaší zahradě"
      text="Napište nám nezávaznou poptávku a my se vám ozveme s návrhem řešení i cenou."
    />
  </div>
</template>
