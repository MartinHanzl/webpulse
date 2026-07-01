/**
 * Dummy content for the Serenity (spa) demo — treatment categories, price list,
 * packages, stats, benefits, testimonials, facility. Original Czech placeholder
 * copy; imagery from Unsplash. One-file source of truth for the spa components.
 */
const U = (id: string, w = 900, h = 0): string =>
  `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&q=80&w=${w}${h ? `&h=${h}` : ''}`;

const S = {
  massage: '1544161515-4ab6ce6db874',
  scene: '1600334129128-685c5582fd35',
  facial: '1570172619644-dfd03ed5d881',
  stones: '1519823551278-64ac92734fb1',
  sauna: '1571019613454-1cb2f99b2d8b',
  towels: '1540555700478-4be289fbecef',
  oils: '1608571423902-eed4a5ad8108',
  wellness: '1512290923902-8a9f81dc236c',
  hotstone: '1600334089648-b0d9d3028eb2',
  aroma: '1596178065887-1198b6148b2b',
  relax: '1519415387722-a1c3bbef716c',
  cucumber: '1512418490979-92798cec1380',
};

export interface SpaCategory {
  slug: string;
  icon: string;
  name: string;
  text: string;
  image: string;
}
export interface SpaTreatment {
  slug: string;
  subtitle: string;
  name: string;
  desc: string;
  price: string;
  discount?: string;
  image: string;
}
export interface SpaPackageItem {
  name: string;
  desc: string;
  price: string;
  image: string;
}
export interface SpaPackage {
  name: string;
  price: string;
  period: string;
  desc: string;
  features: string[];
  featured?: boolean;
}
export interface SpaStat {
  value: string;
  label: string;
}
export interface SpaTestimonial {
  text: string;
  name: string;
  role: string;
}
export interface SpaFacility {
  name: string;
  text: string;
  image: string;
}

const categories: SpaCategory[] = [
  {
    slug: 'masaze',
    icon: 'spa',
    name: 'Masáže',
    text: 'Uvolnění svalů i mysli pod rukama zkušených terapeutů.',
    image: U(S.massage, 700),
  },
  {
    slug: 'kosmetika',
    icon: 'face',
    name: 'Kosmetika',
    text: 'Pleťové rituály pro zářivou a odpočatou pokožku.',
    image: U(S.facial, 700),
  },
  {
    slug: 'terapie',
    icon: 'self_improvement',
    name: 'Terapie',
    text: 'Celostní procedury pro tělo, mysl i duši.',
    image: U(S.hotstone, 700),
  },
];

const treatments: SpaTreatment[] = [
  {
    slug: 'celotelova-masaz',
    subtitle: 'Dotek pro tělo',
    name: 'Celotělová masáž',
    desc: 'Klasická relaxační masáž celého těla, která uvolní napětí a vrátí vám energii.',
    price: '990 Kč',
    discount: '20 %',
    image: U(S.massage, 900),
  },
  {
    slug: 'geotermalni-lazen',
    subtitle: 'Vydechněte',
    name: 'Geotermální lázeň',
    desc: 'Prohřívací procedura v minerální lázni pro dokonalé uvolnění a regeneraci.',
    price: '1 290 Kč',
    discount: '30 %',
    image: U(S.stones, 900),
  },
  {
    slug: 'telova-relaxace',
    subtitle: 'Zářivá pleť',
    name: 'Tělová relaxace',
    desc: 'Jemná procedura s aromatickými oleji, která zklidní tělo i mysl.',
    price: '1 190 Kč',
    discount: '20 %',
    image: U(S.aroma, 900),
  },
  {
    slug: 'masaz-horkymi-kameny',
    subtitle: 'Hluboké teplo',
    name: 'Masáž horkými kameny',
    desc: 'Terapie lávovými kameny, které prohřejí svaly do hloubky.',
    price: '1 390 Kč',
    image: U(S.hotstone, 900),
  },
  {
    slug: 'pletovy-ritual',
    subtitle: 'Péče o obličej',
    name: 'Pleťový rituál',
    desc: 'Kompletní ošetření pleti na míru vašemu typu pokožky.',
    price: '890 Kč',
    image: U(S.facial, 900),
  },
  {
    slug: 'finska-sauna',
    subtitle: 'Prohřátí',
    name: 'Finská sauna',
    desc: 'Tradiční saunový rituál pro detoxikaci a posílení imunity.',
    price: '450 Kč',
    image: U(S.sauna, 900),
  },
];

const priceList: SpaPackageItem[] = [
  {
    name: 'Relaxační masáž',
    desc: '50minutová masáž pro uvolnění.',
    price: '990 Kč',
    image: U(S.massage, 200),
  },
  {
    name: 'Tělová relaxace',
    desc: 'Progresivní uvolnění svalů.',
    price: '790 Kč',
    image: U(S.aroma, 200),
  },
  {
    name: 'Masáž hlavy',
    desc: 'Jeden z nejlepších způsobů relaxace.',
    price: '590 Kč',
    image: U(S.relax, 200),
  },
  {
    name: 'Finská sauna',
    desc: 'Tradiční saunový zážitek.',
    price: '450 Kč',
    image: U(S.sauna, 200),
  },
  {
    name: 'Geotermální lázeň',
    desc: 'Prohřívající a stimulující.',
    price: '1 290 Kč',
    image: U(S.stones, 200),
  },
  {
    name: 'Aromaterapie',
    desc: 'S koncentrovanými esenciálními oleji.',
    price: '890 Kč',
    image: U(S.oils, 200),
  },
];

const packages: SpaPackage[] = [
  {
    name: 'Odpoledne v klidu',
    price: '1 490 Kč',
    period: '90 minut',
    desc: 'Ideální únik z každodenního shonu.',
    features: ['Relaxační masáž zad', 'Vstup do sauny', 'Bylinný čaj'],
  },
  {
    name: 'Den pro sebe',
    price: '2 990 Kč',
    period: '3 hodiny',
    desc: 'Nejoblíbenější wellness balíček.',
    features: ['Celotělová masáž', 'Pleťový rituál', 'Geotermální lázeň', 'Občerstvení'],
    featured: true,
  },
  {
    name: 'Královský rituál',
    price: '4 490 Kč',
    period: '5 hodin',
    desc: 'Kompletní péče pro tělo i mysl.',
    features: ['Masáž horkými kameny', 'Aromaterapie', 'Pleťový rituál', 'Privátní sauna', 'Oběd'],
  },
];

const stats: SpaStat[] = [
  { value: '9,8', label: 'Hodnocení Google' },
  { value: '30k', label: 'Sledujících' },
  { value: '96 %', label: 'Vracejících se klientů' },
  { value: '28+', label: 'Let zkušeností' },
];

const benefits: string[] = [
  'Více energie a klidu',
  'Zářivá a zdravá pleť',
  'Lepší spánek',
  'Uvolnění svalů',
];

const testimonials: SpaTestimonial[] = [
  {
    text: 'Nádherné místo. Interiér, personál i procedury jsou naprosto dokonalé. Odcházím pokaždé jako vyměněná.',
    name: 'Lucie Kratochvílová',
    role: 'Relaxační masáž',
  },
  {
    text: 'Profesionální přístup a klidná atmosféra. Den pro sebe je ta nejlepší investice do sebe sama.',
    name: 'Martina Veselá',
    role: 'Wellness balíček',
  },
  {
    text: 'Masáž horkými kameny předčila má očekávání. Vřele doporučuji všem, kdo hledají skutečný odpočinek.',
    name: 'Petr Novák',
    role: 'Masáž horkými kameny',
  },
];

const facilities: SpaFacility[] = [
  {
    name: 'Masážní studia',
    text: 'Pět privátních místností v tlumeném světle a s aromaterapií.',
    image: U(S.massage, 700),
  },
  {
    name: 'Sauna & pára',
    text: 'Finská sauna, parní lázeň a ochlazovací bazének.',
    image: U(S.sauna, 700),
  },
  {
    name: 'Relaxační zóna',
    text: 'Tichá odpočívárna s bylinnými čaji a lehátky.',
    image: U(S.relax, 700),
  },
  {
    name: 'Kosmetický salón',
    text: 'Moderní zázemí pro pleťové rituály a péči o tělo.',
    image: U(S.facial, 700),
  },
];

export function useSpaContent() {
  return {
    categories,
    treatments,
    priceList,
    packages,
    stats,
    benefits,
    testimonials,
    facilities,
    getTreatment: (s: string): SpaTreatment | undefined => treatments.find((t) => t.slug === s),
  };
}
