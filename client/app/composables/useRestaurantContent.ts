/**
 * Dummy content for the Savoria (restaurant) demo — menu, dishes, chefs,
 * gallery, story timeline, awards, blog. Czech placeholder copy; imagery from
 * Unsplash (food + portraits). Replace freely later. One-file source of truth
 * for the restaurant demo components.
 */
const U = (id: string, w = 900, h = 0): string =>
  `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&q=80&w=${w}${h ? `&h=${h}` : ''}`;

// Food + portrait photo pool.
const F = {
  plating: '1504674900247-0877df9cc836',
  pasta: '1551183053-bf91a1d81141',
  steak: '1546964124-0cce460f38ef',
  dessert: '1551024506-0bccd828d307',
  wine: '1510812431401-41d2bd2722f3',
  salad: '1512621776951-a57141f2eefd',
  pizza: '1513104890138-7c749659a591',
  burger: '1568901346375-23c9450c58cd',
  soup: '1547592180-85f173990554',
  seafood: '1559737558-2f5a35f4523b',
  breakfast: '1533089860892-a7c6f0a88666',
  coffee: '1495474472287-4d71bcdd2085',
  interior: '1517248135467-4c7edcad34c4',
  cooking: '1577219491135-ce391730fb2c',
  tableSetting: '1414235077428-338989a2e8c0',
  // portraits (chefs)
  chef1: '1577219491135-ce391730fb2c',
  chef2: '1583394293214-28ded15ee548',
  chef3: '1607631568010-a87245c0daf8',
  chef4: '1595475207225-428b62bda831',
  chef5: '1566554273541-37a9ca77b91f',
  chef6: '1544005313-94ddf0286df2',
};

export interface RMenuItem {
  name: string;
  desc: string;
  price: string;
  image: string;
}
export interface RMenuCategory {
  key: string;
  label: string;
  icon: string;
  items: RMenuItem[];
}
export interface RDish {
  name: string;
  ingredients: string;
  price: string;
  image: string;
  rating?: number;
  oldPrice?: string;
}
export interface RChef {
  name: string;
  role: string;
  cuisine: string;
  image: string;
}
export interface RGalleryItem {
  image: string;
  category: string;
  title: string;
}
export interface RStoryStep {
  year: string;
  title: string;
  text: string;
  image: string;
}
export interface RAward {
  year: string;
  title: string;
}
export interface RArticle {
  slug: string;
  title: string;
  category: string;
  date: string;
  image: string;
  perex: string;
  body: string[];
}
export interface RFeature {
  icon: string;
  title: string;
  text: string;
}

const features: RFeature[] = [
  {
    icon: 'local_shipping',
    title: 'Rozvoz do 30 minut',
    text: 'Rozvážíme čerstvé pokrmy po celé Praze.',
  },
  { icon: 'workspace_premium', title: 'Oceněná kuchyně', text: 'Michelin Bib Gourmand 2023.' },
  {
    icon: 'restaurant_menu',
    title: 'Sezónní suroviny',
    text: 'Vaříme z lokálních farmářských produktů.',
  },
];

const menu: RMenuCategory[] = [
  {
    key: 'predkrmy',
    label: 'Předkrmy',
    icon: 'ramen_dining',
    items: [
      {
        name: 'Hovězí tatarák',
        desc: 'Vyzrálé hovězí, křepelčí žloutek, topinky',
        price: '245 Kč',
        image: U(F.plating, 300),
      },
      {
        name: 'Burrata',
        desc: 'Rajčata, bazalkové pesto, olivový olej',
        price: '215 Kč',
        image: U(F.salad, 300),
      },
      {
        name: 'Carpaccio z lososa',
        desc: 'Citrusy, kapary, kopr, olivový olej',
        price: '235 Kč',
        image: U(F.seafood, 300),
      },
      {
        name: 'Krémová polévka dýně',
        desc: 'Pražená semínka, smetana, chilli olej',
        price: '145 Kč',
        image: U(F.soup, 300),
      },
    ],
  },
  {
    key: 'hlavni',
    label: 'Hlavní chody',
    icon: 'lunch_dining',
    items: [
      {
        name: 'Rib-eye steak',
        desc: 'Grilované rib-eye, demi-glace, restované brambory',
        price: '595 Kč',
        image: U(F.steak, 300),
      },
      {
        name: 'Domácí tagliatelle',
        desc: 'Hříbky, parmezán, lanýžový olej',
        price: '325 Kč',
        image: U(F.pasta, 300),
      },
      {
        name: 'Confit z kachny',
        desc: 'Bramborové pyré, červené zelí, portské',
        price: '385 Kč',
        image: U(F.plating, 300),
      },
      {
        name: 'Grilovaný candát',
        desc: 'Máslová omáčka, chřest, nové brambory',
        price: '425 Kč',
        image: U(F.seafood, 300),
      },
    ],
  },
  {
    key: 'vege',
    label: 'Vegetariánské',
    icon: 'eco',
    items: [
      {
        name: 'Rizoto s lanýžem',
        desc: 'Arborio, parmezán, sezónní houby',
        price: '295 Kč',
        image: U(F.pasta, 300),
      },
      {
        name: 'Pečená květáková steak',
        desc: 'Tahini, granátové jablko, bylinky',
        price: '265 Kč',
        image: U(F.salad, 300),
      },
      {
        name: 'Gnocchi s dýní',
        desc: 'Šalvěj, máslo, pražené ořechy',
        price: '275 Kč',
        image: U(F.plating, 300),
      },
    ],
  },
  {
    key: 'dezerty',
    label: 'Dezerty',
    icon: 'cake',
    items: [
      {
        name: 'Créme brûlée',
        desc: 'Vanilkový krém, karamelová krusta',
        price: '155 Kč',
        image: U(F.dessert, 300),
      },
      {
        name: 'Čokoládový fondant',
        desc: 'Tekuté nitro, zmrzlina slaný karamel',
        price: '175 Kč',
        image: U(F.dessert, 300),
      },
      {
        name: 'Cheesecake',
        desc: 'Lesní ovoce, drobenka',
        price: '145 Kč',
        image: U(F.breakfast, 300),
      },
    ],
  },
  {
    key: 'napoje',
    label: 'Nápoje',
    icon: 'wine_bar',
    items: [
      {
        name: 'Sklenka Barolo',
        desc: 'Piemont, DOCG, 0,15 l',
        price: '185 Kč',
        image: U(F.wine, 300),
      },
      {
        name: 'Aperol Spritz',
        desc: 'Aperol, prosecco, soda',
        price: '135 Kč',
        image: U(F.wine, 300),
      },
      {
        name: 'Espresso',
        desc: 'Výběrová káva, single origin',
        price: '65 Kč',
        image: U(F.coffee, 300),
      },
    ],
  },
];

const specials: RDish[] = [
  {
    name: 'Grilovaný T-bone',
    ingredients: 'Rozmarýn • Máslo • Tymián',
    price: '650 Kč',
    oldPrice: '790 Kč',
    image: U(F.steak, 500),
    rating: 5,
  },
  {
    name: 'Mořské plody na másle',
    ingredients: 'Krevety • Chilli • Citron',
    price: '520 Kč',
    oldPrice: '620 Kč',
    image: U(F.seafood, 500),
    rating: 5,
  },
  {
    name: 'Degustační menu',
    ingredients: '5 chodů • Párování vín',
    price: '1 290 Kč',
    oldPrice: '1 490 Kč',
    image: U(F.plating, 500),
    rating: 5,
  },
];

const dishes: RDish[] = [
  {
    name: 'Rib-eye steak',
    ingredients: 'Hovězí • Demi-glace • Brambory',
    price: '595 Kč',
    image: U(F.steak, 600),
  },
  {
    name: 'Tagliatelle s hříbky',
    ingredients: 'Těstoviny • Parmezán • Lanýž',
    price: '325 Kč',
    image: U(F.pasta, 600),
  },
  {
    name: 'Grilovaný candát',
    ingredients: 'Ryba • Chřest • Máslo',
    price: '425 Kč',
    image: U(F.seafood, 600),
  },
  {
    name: 'Burrata',
    ingredients: 'Sýr • Rajčata • Bazalka',
    price: '215 Kč',
    image: U(F.salad, 600),
  },
  {
    name: 'Čokoládový fondant',
    ingredients: 'Čokoláda • Karamel • Zmrzlina',
    price: '175 Kč',
    image: U(F.dessert, 600),
  },
];

const chefs: RChef[] = [
  {
    name: 'Herman Miller',
    role: 'Šéfkuchař & majitel',
    cuisine: 'Moderní evropská',
    image: U(F.chef1, 500),
  },
  { name: 'Jan Richter', role: 'Sous-chef', cuisine: 'Francouzská', image: U(F.chef2, 500) },
  { name: 'Klára Ferrari', role: 'Cukrářka', cuisine: 'Dezerty', image: U(F.chef3, 500) },
  { name: 'Marta Warner', role: 'Kuchařka', cuisine: 'Italská', image: U(F.chef4, 500) },
  { name: 'Lukáš Green', role: 'Grilmaster', cuisine: 'Steaky', image: U(F.chef5, 500) },
  {
    name: 'Antonín Taylor',
    role: 'Kuchař',
    cuisine: 'Ryby & mořské plody',
    image: U(F.chef6, 500),
  },
];

const gallery: RGalleryItem[] = [
  { image: U(F.steak, 700), category: 'Hlavní chody', title: 'Rib-eye steak' },
  { image: U(F.pasta, 700), category: 'Hlavní chody', title: 'Domácí těstoviny' },
  { image: U(F.dessert, 700), category: 'Dezerty', title: 'Créme brûlée' },
  { image: U(F.wine, 700), category: 'Nápoje', title: 'Vinný sklep' },
  { image: U(F.salad, 700), category: 'Vegetariánské', title: 'Sezónní salát' },
  { image: U(F.seafood, 700), category: 'Hlavní chody', title: 'Mořské plody' },
  { image: U(F.interior, 700), category: 'Interiér', title: 'Hlavní sál' },
  { image: U(F.plating, 700), category: 'Předkrmy', title: 'Degustační talíř' },
];

const story: RStoryStep[] = [
  {
    year: '1988',
    title: 'Založení',
    text: 'Otevřeli jsme malou trattorii v centru Prahy.',
    image: U(F.interior, 500),
  },
  {
    year: '2001',
    title: 'Nový šéfkuchař',
    text: 'Herman Miller přinesl moderní evropskou kuchyni.',
    image: U(F.cooking, 500),
  },
  {
    year: '2015',
    title: 'Vinný sklep',
    text: 'Otevřeli jsme sklep s více než 300 etiketami.',
    image: U(F.wine, 500),
  },
  {
    year: '2023',
    title: 'Michelin',
    text: 'Získali jsme ocenění Bib Gourmand.',
    image: U(F.plating, 500),
  },
];

const awards: RAward[] = [
  { year: '2017', title: 'Restaurace roku' },
  { year: '2019', title: 'Cena za gastronomii' },
  { year: '2021', title: 'Nejlepší vinný lístek' },
  { year: '2023', title: 'Michelin Bib Gourmand' },
];

const articles: RArticle[] = [
  {
    slug: 'umeni-prostirani',
    title: 'Umění prostírání a servisu',
    category: 'Zážitek',
    date: '18. června 2025',
    image: U(F.tableSetting, 700),
    perex: 'Jak vytváříme dokonalý zážitek od prvního usednutí ke stolu.',
    body: [
      'Detail dělá zážitek. Od výběru porcelánu po tření sklenic — každý prvek servisu má svůj smysl.',
      'Náš tým prochází pravidelnými školeními, aby byl servis nenápadný, a přitom vždy po ruce.',
    ],
  },
  {
    slug: 'sezonni-suroviny',
    title: 'Proč vaříme sezónně',
    category: 'Kuchyně',
    date: '2. června 2025',
    image: U(F.salad, 700),
    perex: 'Sezónní suroviny mají nejlepší chuť i nejmenší uhlíkovou stopu.',
    body: [
      'Spolupracujeme s lokálními farmáři a rybáři. Menu proto měníme podle toho, co právě dozrálo.',
      'Díky tomu máte na talíři vždy to nejlepší, co daná roční doba nabízí.',
    ],
  },
  {
    slug: 'parovani-vin',
    title: 'Základy párování vín',
    category: 'Víno',
    date: '20. května 2025',
    image: U(F.wine, 700),
    perex: 'Několik tipů, jak k jídlu vybrat správné víno.',
    body: [
      'Nemusíte být sommelier. Stačí pár pravidel — a chuť pokrmu i vína se navzájem povýší.',
      'Náš sommelier vám rád poradí přímo u stolu s výběrem z více než 300 etiket.',
    ],
  },
];

export function useRestaurantContent() {
  return {
    features,
    menu,
    specials,
    dishes,
    chefs,
    gallery,
    story,
    awards,
    articles,
    getArticle: (s: string): RArticle | undefined => articles.find((a) => a.slug === s),
  };
}
