/**
 * Dummy content for the Veritas (lawyer) demo — practice areas, attorneys,
 * stats, process, testimonials, journal. Czech placeholder copy; imagery from
 * Unsplash (office + professional portraits). One-file source of truth for the
 * lawyer demo components.
 */
const U = (id: string, w = 900, h = 0): string =>
  `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&q=80&w=${w}${h ? `&h=${h}` : ''}`;

const L = {
  office: '1521737604893-d14cc237f11d',
  columns: '1589829545856-d10d557cf95f',
  books: '1521587760476-6c12a4b040da',
  handshake: '1454165804606-c3d57bc86b40',
  building: '1486406146926-c627a92ad1ab',
  a1: '1560250097-0b93528c311a',
  a2: '1573496359142-b8d87734a5a2',
  a3: '1568602471122-7832951cc4c5',
  a4: '1580489944761-15a19d654956',
  a5: '1507003211169-0a1dd7228f2d',
  a6: '1472099645785-5658abf4ff4e',
  a7: '1519085360753-af0119f7cbe7',
  a8: '1556157382-97eda2d62296',
};

export interface LPracticeArea {
  slug: string;
  icon: string;
  name: string;
  perex: string;
  description: string;
  image: string;
  features: string[];
}
export interface LAttorney {
  slug: string;
  name: string;
  role: string;
  specialization: string;
  image: string;
  bio: string;
  education: string[];
  experience: string;
}
export interface LStat {
  value: string;
  label: string;
}
export interface LTestimonial {
  name: string;
  role: string;
  text: string;
  rating: number;
}
export interface LProcessStep {
  no: string;
  icon: string;
  title: string;
  text: string;
}
export interface LFaq {
  question: string;
  answer: string;
}
export interface LArticle {
  slug: string;
  title: string;
  category: string;
  date: string;
  image: string;
  author: string;
  perex: string;
  body: string[];
}

const practiceAreas: LPracticeArea[] = [
  {
    slug: 'obchodni-pravo',
    icon: 'business_center',
    name: 'Obchodní právo',
    perex: 'Smlouvy, korporátní agenda a zastupování obchodních společností.',
    description:
      'Poskytujeme komplexní právní služby v oblasti obchodního a korporátního práva — od zakládání společností přes smluvní agendu až po fúze a akvizice.',
    image: U(L.office, 700),
    features: ['Zakládání a přeměny společností', 'Obchodní smlouvy', 'Fúze a akvizice'],
  },
  {
    slug: 'trestni-pravo',
    icon: 'gavel',
    name: 'Trestní právo',
    perex: 'Obhajoba v trestním řízení a zastupování poškozených.',
    description:
      'Zajišťujeme obhajobu ve všech fázích trestního řízení a zastupujeme poškozené při uplatnění nároků na náhradu škody.',
    image: U(L.columns, 700),
    features: ['Obhajoba v trestním řízení', 'Zastupování poškozených', 'Odklony a dohody o vině'],
  },
  {
    slug: 'rodinne-pravo',
    icon: 'family_restroom',
    name: 'Rodinné právo',
    perex: 'Rozvody, péče o děti, výživné a majetkové vypořádání.',
    description:
      'S citem a diskrétností řešíme rozvody, úpravu péče o nezletilé, výživné i vypořádání společného jmění manželů.',
    image: U(L.handshake, 700),
    features: ['Rozvodové řízení', 'Péče o děti a výživné', 'Vypořádání SJM'],
  },
  {
    slug: 'pracovni-pravo',
    icon: 'work',
    name: 'Pracovní právo',
    perex: 'Pracovní smlouvy, ukončení poměru a spory ze zaměstnání.',
    description:
      'Zastupujeme zaměstnavatele i zaměstnance ve věcech pracovních smluv, ukončení pracovního poměru a pracovněprávních sporů.',
    image: U(L.building, 700),
    features: ['Pracovní smlouvy a dohody', 'Ukončení pracovního poměru', 'Pracovněprávní spory'],
  },
  {
    slug: 'nemovitosti',
    icon: 'home_work',
    name: 'Nemovitosti',
    perex: 'Převody, nájmy a development nemovitostí.',
    description:
      'Připravujeme kupní a nájemní smlouvy, zajišťujeme advokátní úschovy a poskytujeme právní podporu developerským projektům.',
    image: U(L.office, 700),
    features: ['Kupní a nájemní smlouvy', 'Advokátní úschova', 'Developerské projekty'],
  },
  {
    slug: 'obcanske-pravo',
    icon: 'balance',
    name: 'Občanské právo',
    perex: 'Smlouvy, náhrada škody a vymáhání pohledávek.',
    description:
      'Řešíme širokou agendu občanského práva — od přípravy smluv přes náhradu škody až po vymáhání pohledávek.',
    image: U(L.books, 700),
    features: ['Příprava smluv', 'Náhrada škody', 'Vymáhání pohledávek'],
  },
  {
    slug: 'spravni-pravo',
    icon: 'account_balance',
    name: 'Správní právo',
    perex: 'Zastupování před úřady a správní soudnictví.',
    description:
      'Zastupujeme klienty ve správních řízeních před úřady a v řízeních před správními soudy.',
    image: U(L.columns, 700),
    features: ['Řízení před úřady', 'Správní žaloby', 'Stavební řízení'],
  },
  {
    slug: 'dusevni-vlastnictvi',
    icon: 'copyright',
    name: 'Duševní vlastnictví',
    perex: 'Ochranné známky, autorská práva a licence.',
    description:
      'Chráníme vaše nehmotné statky — registrujeme ochranné známky, řešíme autorská práva a licenční smlouvy.',
    image: U(L.building, 700),
    features: ['Ochranné známky', 'Autorská práva', 'Licenční smlouvy'],
  },
];

const attorneys: LAttorney[] = [
  {
    slug: 'jan-prochazka',
    name: 'JUDr. Jan Procházka',
    role: 'Řídící partner',
    specialization: 'Obchodní právo',
    image: U(L.a1, 600),
    bio: 'S více než 20 lety praxe vede kancelář a specializuje se na korporátní transakce a fúze a akvizice.',
    education: ['Právnická fakulta UK, Praha (2001)', 'Rigorózní řízení – JUDr. (2003)'],
    experience: '20 let',
  },
  {
    slug: 'petra-novakova',
    name: 'JUDr. Petra Nováková',
    role: 'Partnerka',
    specialization: 'Rodinné právo',
    image: U(L.a2, 600),
    bio: 'Zaměřuje se na rodinné a občanské právo, s důrazem na mimosoudní řešení sporů.',
    education: ['Právnická fakulta MU, Brno (2005)', 'Advokátní zkoušky (2008)'],
    experience: '17 let',
  },
  {
    slug: 'martin-svoboda',
    name: 'Mgr. Martin Svoboda',
    role: 'Advokát',
    specialization: 'Trestní právo',
    image: U(L.a3, 600),
    bio: 'Obhájce v trestních věcech s bohatou zkušeností se zastupováním u soudů všech stupňů.',
    education: ['Právnická fakulta UK, Praha (2010)'],
    experience: '13 let',
  },
  {
    slug: 'lucie-dvorakova',
    name: 'Mgr. Lucie Dvořáková',
    role: 'Advokátka',
    specialization: 'Pracovní právo',
    image: U(L.a4, 600),
    bio: 'Poskytuje poradenství zaměstnavatelům i zaměstnancům a zastupuje v pracovněprávních sporech.',
    education: ['Právnická fakulta ZČU, Plzeň (2012)'],
    experience: '11 let',
  },
  {
    slug: 'tomas-cerny',
    name: 'Mgr. Tomáš Černý',
    role: 'Advokát',
    specialization: 'Nemovitosti',
    image: U(L.a5, 600),
    bio: 'Specialista na převody nemovitostí, advokátní úschovy a developerské projekty.',
    education: ['Právnická fakulta UP, Olomouc (2011)'],
    experience: '12 let',
  },
  {
    slug: 'eva-kralova',
    name: 'JUDr. Eva Králová',
    role: 'Advokátka',
    specialization: 'Správní právo',
    image: U(L.a6, 600),
    bio: 'Zastupuje klienty ve správním soudnictví a v řízeních před úřady.',
    education: ['Právnická fakulta UK, Praha (2007)'],
    experience: '16 let',
  },
  {
    slug: 'jiri-marek',
    name: 'Mgr. Jiří Marek',
    role: 'Advokát',
    specialization: 'Duševní vlastnictví',
    image: U(L.a7, 600),
    bio: 'Věnuje se ochranným známkám, autorskému právu a IT smlouvám.',
    education: ['Právnická fakulta MU, Brno (2013)'],
    experience: '10 let',
  },
  {
    slug: 'katerina-benesova',
    name: 'Mgr. Kateřina Benešová',
    role: 'Koncipientka',
    specialization: 'Občanské právo',
    image: U(L.a8, 600),
    bio: 'Podílí se na přípravě smluv a zastupování v občanskoprávních věcech.',
    education: ['Právnická fakulta UK, Praha (2019)'],
    experience: '4 roky',
  },
];

const stats: LStat[] = [
  { value: '98 %', label: 'Úspěšnost případů' },
  { value: '1 200+', label: 'Vyřešených případů' },
  { value: '25', label: 'Let praxe' },
  { value: '12', label: 'Advokátů v týmu' },
];

const testimonials: LTestimonial[] = [
  {
    name: 'Ing. Pavel Horák',
    role: 'Jednatel, Stavko s.r.o.',
    text: 'Profesionální přístup a jasná komunikace. Obchodní spor vyřešili rychle a v náš prospěch.',
    rating: 5,
  },
  {
    name: 'Marie Veselá',
    role: 'Klientka',
    text: 'V těžké životní situaci mi advokátka velmi pomohla. Lidský přístup a odbornost na nejvyšší úrovni.',
    rating: 5,
  },
  {
    name: 'Robert Král',
    role: 'Ředitel, Nemo Group',
    text: 'Spolehlivý partner pro naši firmu. Právní podporu máme vždy včas a srozumitelně.',
    rating: 5,
  },
];

const process: LProcessStep[] = [
  {
    no: '01',
    icon: 'forum',
    title: 'Úvodní konzultace',
    text: 'Nezávazně probereme váš případ a možnosti řešení.',
  },
  {
    no: '02',
    icon: 'search',
    title: 'Analýza případu',
    text: 'Prostudujeme podklady a připravíme právní strategii.',
  },
  {
    no: '03',
    icon: 'edit_document',
    title: 'Zastupování',
    text: 'Zastoupíme vás při jednáních i před soudy.',
  },
  { no: '04', icon: 'verified', title: 'Vyřešení', text: 'Dovedeme případ k úspěšnému výsledku.' },
];

const faqs: LFaq[] = [
  {
    question: 'Kolik stojí úvodní konzultace?',
    answer:
      'První nezávazná konzultace v rozsahu 30 minut je zdarma. Další úkony vždy předem transparentně naceníme.',
  },
  {
    question: 'Jak probíhá spolupráce?',
    answer:
      'Po úvodní konzultaci připravíme strategii a smlouvu o poskytování právních služeb. Průběžně vás informujeme o vývoji.',
  },
  {
    question: 'Zastupujete i mimo Prahu?',
    answer:
      'Ano, působíme po celé ČR a v případě potřeby i v přeshraničních věcech se zahraničními partnery.',
  },
  {
    question: 'Jak účtujete odměnu?',
    answer:
      'Dle dohody — hodinovou sazbou, paušálem, nebo podílem na výsledku. Vždy podle povahy případu.',
  },
];

const articles: LArticle[] = [
  {
    slug: 'novela-zakoniku-prace',
    title: 'Novela zákoníku práce: co se mění',
    category: 'Pracovní právo',
    date: '16. června 2025',
    image: U(L.office, 700),
    author: 'Mgr. Lucie Dvořáková',
    perex: 'Přehled nejdůležitějších změn a jejich dopad na zaměstnavatele.',
    body: [
      'Novela přináší změny v oblasti home office, doručování i dohod o pracích konaných mimo pracovní poměr.',
      'Doporučujeme zaměstnavatelům včas upravit vnitřní předpisy a vzorové smlouvy.',
    ],
  },
  {
    slug: 'jak-na-vymahani-pohledavek',
    title: 'Jak efektivně vymáhat pohledávky',
    category: 'Občanské právo',
    date: '3. června 2025',
    image: U(L.books, 700),
    author: 'JUDr. Jan Procházka',
    perex: 'Od upomínky po exekuci — kroky, které zvyšují šanci na úspěch.',
    body: [
      'Klíčem je rychlost a správně nastavené smluvní podmínky už na začátku obchodního vztahu.',
      'Pokud dlužník nereaguje, následuje předžalobní výzva, žaloba a případně exekuce.',
    ],
  },
  {
    slug: 'ochranna-znamka-krok-za-krokem',
    title: 'Ochranná známka krok za krokem',
    category: 'Duševní vlastnictví',
    date: '21. května 2025',
    image: U(L.handshake, 700),
    author: 'Mgr. Jiří Marek',
    perex: 'Jak si ochránit název i logo vaší firmy.',
    body: [
      'Před podáním přihlášky je vhodné provést rešerši, zda podobná známka již není zapsaná.',
      'Registrace u ÚPV chrání vaši značku a je důležitou hodnotou firmy.',
    ],
  },
];

export function useLawyerContent() {
  return {
    practiceAreas,
    attorneys,
    stats,
    testimonials,
    process,
    faqs,
    articles,
    getArea: (s: string): LPracticeArea | undefined => practiceAreas.find((x) => x.slug === s),
    getAttorney: (s: string): LAttorney | undefined => attorneys.find((x) => x.slug === s),
    getArticle: (s: string): LArticle | undefined => articles.find((x) => x.slug === s),
  };
}
