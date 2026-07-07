/**
 * Dummy content for the Vitalmed (medical) demo — clinical departments,
 * specialist treatments, doctors, reviews, FAQ, booking data. Original Czech
 * placeholder copy; imagery from Unsplash. One-file source of truth.
 */
const U = (id: string, w = 900, h = 0): string =>
  `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&q=80&w=${w}${h ? `&h=${h}` : ''}`;

const M = {
  doctorTeam: '1582750433449-648ed127bb54',
  clinicHall: '1519494026892-80bbd2d6fd0d',
  surgeons: '1551190822-a9333d879b1f',
  doctorPatient: '1576091160399-112ba8d25d1d',
  stethoscope: '1505751172876-fa1923c5c528',
  nurse: '1559839734-2b71ea197ec2',
  lab: '1579154204601-01588f351e67',
  reception: '1629909613654-28e377c37b09',
  doctorMale: '1612349317150-e413f6a5b16d',
  doctorFemale: '1594824476967-48c8b964273f',
  consult: '1521791136064-7986c2920216',
  mri: '1516069677018-378515003435',
};

export interface MedicalDepartment {
  slug: string;
  icon: string;
  name: string;
  text: string;
}
export interface MedicalTreatment {
  slug: string;
  icon: string;
  name: string;
  text: string;
}
export interface MedicalDoctor {
  slug: string;
  name: string;
  specialty: string;
  rating: string;
  image: string;
}
export interface MedicalReview {
  name: string;
  text: string;
  stars: number;
  image: string;
}
export interface MedicalStat {
  value: string;
  suffix?: string;
  label: string;
  stars?: boolean;
}
export interface MedicalStep {
  number: string;
  title: string;
  text: string;
}
export interface MedicalFaqItem {
  question: string;
  answer: string;
}

/** Clinical departments — homepage carousel (4 unique). */
const departments: MedicalDepartment[] = [
  {
    slug: 'vseobecna-pece',
    icon: 'medical_services',
    name: 'Všeobecná péče',
    text: 'Komplexní preventivní prohlídky a péče o celou rodinu.',
  },
  {
    slug: 'kardiologie',
    icon: 'cardiology',
    name: 'Kardiologie',
    text: 'Diagnostika a léčba onemocnění srdce a cév.',
  },
  {
    slug: 'chirurgie',
    icon: 'surgical',
    name: 'Všeobecná chirurgie',
    text: 'Moderní operační sály a zkušený chirurgický tým.',
  },
  {
    slug: 'krevni-testy',
    icon: 'labs',
    name: 'Krevní testy',
    text: 'Vlastní laboratoř s výsledky do 24 hodin.',
  },
];

/** Specialist treatments — treatments page grid (8 cards). */
const treatments: MedicalTreatment[] = [
  {
    slug: 'stomatologie',
    icon: 'dentistry',
    name: 'Stomatologie',
    text: 'Moderní zubní péče bez bolesti a stresu.',
  },
  {
    slug: 'interni-medicina',
    icon: 'medication',
    name: 'Interní medicína',
    text: 'Diagnostika a léčba vnitřních onemocnění.',
  },
  {
    slug: 'genetika',
    icon: 'biotech',
    name: 'Genetika',
    text: 'Genetická vyšetření a rodinné poradenství.',
  },
  {
    slug: 'neurochirurgie',
    icon: 'neurology',
    name: 'Neurochirurgie',
    text: 'Špičkové zákroky na nervové soustavě.',
  },
  {
    slug: 'kardiologie',
    icon: 'cardiology',
    name: 'Kardiologie',
    text: 'Komplexní péče o srdce a cévní systém.',
  },
  {
    slug: 'hepatologie',
    icon: 'gastroenterology',
    name: 'Hepatologie',
    text: 'Specializovaná péče o játra a žlučové cesty.',
  },
  {
    slug: 'optometrie',
    icon: 'visibility',
    name: 'Optometrie',
    text: 'Vyšetření zraku a aplikace kontaktních čoček.',
  },
  {
    slug: 'kardiochirurgie',
    icon: 'monitor_heart',
    name: 'Kardiochirurgie',
    text: 'Operace srdce s nejmodernější technikou.',
  },
];

/** Doctors — first 4 shown on the homepage, all 8 on the doctors page. */
const doctors: MedicalDoctor[] = [
  {
    slug: 'jana-svobodova',
    name: 'MUDr. Jana Svobodová',
    specialty: 'psychiatrii',
    rating: '4,9',
    image: U(M.doctorFemale, 400, 400),
  },
  {
    slug: 'petr-novak',
    name: 'MUDr. Petr Novák',
    specialty: 'onkologii',
    rating: '4,7',
    image: U(M.doctorMale, 400, 400),
  },
  {
    slug: 'lenka-maresova',
    name: 'MUDr. Lenka Marešová',
    specialty: 'pediatrii',
    rating: '5,0',
    image: U(M.nurse, 400, 400),
  },
  {
    slug: 'tomas-dvorak',
    name: 'MUDr. Tomáš Dvořák',
    specialty: 'psychiatrii',
    rating: '5,0',
    image: U(M.doctorTeam, 400, 400),
  },
  {
    slug: 'eva-horakova',
    name: 'MUDr. Eva Horáková',
    specialty: 'kardiologii',
    rating: '4,9',
    image: U(M.doctorPatient, 400, 400),
  },
  {
    slug: 'martin-benes',
    name: 'MUDr. Martin Beneš',
    specialty: 'chirurgii',
    rating: '5,0',
    image: U(M.surgeons, 400, 400),
  },
  {
    slug: 'klara-vesela',
    name: 'MUDr. Klára Veselá',
    specialty: 'pediatrii',
    rating: '5,0',
    image: U(M.consult, 400, 400),
  },
  {
    slug: 'jakub-cerny',
    name: 'MUDr. Jakub Černý',
    specialty: 'neurologii',
    rating: '4,8',
    image: U(M.reception, 400, 400),
  },
];

/** Featured doctor — doctors page profile section. */
const featuredDoctor = {
  name: 'MUDr. Jan Kovařík',
  role: 'Primář kliniky, vedoucí kardiolog',
  text: 'Přes dvacet let se věnuje kardiologii a vede tým specialistů, pro které je pacient vždy na prvním místě. Věříme, že moderní medicína začíná nasloucháním.',
  phone: '+420 233 456 789',
  office: '+420 233 456 780',
  email: 'recepce@vitalmed.cz',
  operations: 856,
  rating: '4,9',
  reviews: '7 548',
  image: U(M.doctorMale, 800),
};

/** Homepage stats strip. */
const stats: MedicalStat[] = [
  { value: '4,98', label: '2 488 hodnocení.', stars: true },
  { value: '98', suffix: '%', label: 'Skutečně pozitivní zpětná vazba.' },
  { value: '200', suffix: '+', label: 'Pacientů ošetřených denně.' },
];

/** Booking process steps. */
const steps: MedicalStep[] = [
  { number: '01', title: 'Najděte lékaře', text: 'Podle specializace' },
  { number: '02', title: 'Rezervujte termín', text: 'Online či telefonem' },
  { number: '03', title: 'Přijďte na kliniku', text: 'Bez čekání ve frontě' },
];

/** Hero ticker — health tips (original copy). */
const healthTips: string[] = [
  'Nepodceňujte prevenci — na pravidelnou prohlídku se objednáte online během minuty.',
  'Myjte si ruce často a důkladně, chráníte tím sebe i své okolí.',
  'Při kašli a kýchání si zakrývejte ústa a nos ohnutým loktem nebo kapesníkem.',
];

/** Patient reviews — about page slider (5 unique). */
const reviews: MedicalReview[] = [
  {
    name: '@Marek',
    text: 'Pan doktor je zkušený a velmi vstřícný.',
    stars: 4.5,
    image: U(M.doctorPatient, 300, 300),
  },
  {
    name: '@Helena',
    text: 'Bylo mi potěšením nechat se u něj vyšetřit.',
    stars: 5,
    image: U(M.nurse, 300, 300),
  },
  {
    name: '@Jakub',
    text: 'Skvělý chirurg a ochotný personál.',
    stars: 4.5,
    image: U(M.surgeons, 300, 300),
  },
  {
    name: '@Alexandra',
    text: 'Paní doktorka je milá a profesionální.',
    stars: 5,
    image: U(M.doctorFemale, 300, 300),
  },
  {
    name: '@Jáchym',
    text: 'Kliniku doporučuji každému, kdo hledá specialistu.',
    stars: 5,
    image: U(M.doctorTeam, 300, 300),
  },
];

/** FAQ — doctors + contact pages. */
const faq: MedicalFaqItem[] = [
  {
    question: 'Co se stane, když budu potřebovat hospitalizaci?',
    answer:
      'Naši specialisté zajistí přijetí na lůžkové oddělení partnerské nemocnice a předají veškerou dokumentaci. O všem vás předem informujeme.',
  },
  {
    question: 'Jak zjistím, co hradí moje pojišťovna?',
    answer:
      'Recepce vám na počkání ověří smluvní vztah s vaší zdravotní pojišťovnou a vysvětlí případné doplatky ještě před vyšetřením.',
  },
  {
    question: 'Nabízíte také péči o duševní zdraví?',
    answer:
      'Ano, tým psychiatrů a terapeutů přijímá nové pacienty průběžně. První konzultaci lze domluvit i formou videohovoru.',
  },
];

/** Appointment select options: specialty – doctor. */
const appointmentDoctors: string[] = [
  'Pediatrie – MUDr. Lenka Marešová',
  'Kardiologie – MUDr. Eva Horáková',
  'Neurologie – MUDr. Jakub Černý',
  'Chirurgie – MUDr. Martin Beneš',
  'Optometrie – MUDr. Jana Svobodová',
];

/** Opening hours — contact page. */
const openingHours: { label: string; value: string }[] = [
  { label: 'Pondělí – Středa:', value: '8.00 – 20.00' },
  { label: 'Čtvrtek – Pátek:', value: '8.00 – 20.00' },
  { label: 'Sobota:', value: '8.00 – 12.00' },
  { label: 'Neděle:', value: 'Zavřeno' },
];

export function useMedicalContent() {
  return {
    departments,
    treatments,
    doctors,
    featuredDoctor,
    stats,
    steps,
    healthTips,
    reviews,
    faq,
    appointmentDoctors,
    openingHours,
  };
}
