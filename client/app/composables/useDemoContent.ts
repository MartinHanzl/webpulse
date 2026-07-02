/**
 * Dummy content for the demo inner pages (O nás / Služby / Reference / Blog / FAQ).
 * One dataset per demo slug. All imagery comes from useStockImages so it matches
 * the demo's theme. This is placeholder marketing copy — safe to replace later.
 */
import { useStockImages } from '~/../app/composables/useStockImages';

export interface DemoService {
  slug: string;
  icon: string;
  name: string;
  perex: string;
  description: string;
  image: string;
  features: string[];
}

export interface DemoProject {
  slug: string;
  title: string;
  category: string;
  location: string;
  year: string;
  image: string;
  excerpt: string;
  description: string;
}

export interface DemoArticle {
  slug: string;
  title: string;
  perex: string;
  date: string;
  category: string;
  image: string;
  author: string;
  body: string[];
}

export interface DemoFaq {
  question: string;
  answer: string;
}

export interface DemoAbout {
  lead: string;
  paragraphs: string[];
  values: { icon: string; title: string; text: string }[];
  counters: { value: string; label: string }[];
  team: { name: string; role: string; image: string }[];
}

export interface DemoContent {
  about: DemoAbout;
  services: DemoService[];
  projects: DemoProject[];
  articles: DemoArticle[];
  faqs: DemoFaq[];
}

function build(slug: string): DemoContent {
  const ph = useStockImages().get(slug);

  const datasets: Record<string, DemoContent> = {
    lawn: {
      about: {
        lead: 'Pečujeme o trávníky a zahrady jako o vlastní — už od roku 2008.',
        paragraphs: [
          'Lawnly je rodinná firma, která spojuje řemeslnou preciznost s moderní technikou. Začínali jsme se sekačkou a dvěma lidmi, dnes se staráme o stovky zahrad v Praze a okolí.',
          'Věříme, že krásný trávník není náhoda, ale výsledek pravidelné a odborné péče. Proto každému klientovi navrhneme program na míru jeho zahradě, půdě i rozpočtu.',
        ],
        values: [
          {
            icon: 'eco',
            title: 'Šetrně k přírodě',
            text: 'Používáme postupy a přípravky ohleduplné k půdě i okolí.',
          },
          {
            icon: 'schedule',
            title: 'Spolehlivost',
            text: 'Dodržujeme termíny a domluvený harmonogram bez výmluv.',
          },
          {
            icon: 'workspace_premium',
            title: 'Záruka kvality',
            text: 'Na realizace dáváme záruku a po práci vždy uklidíme.',
          },
        ],
        counters: [
          { value: '1 240+', label: 'Udržovaných zahrad' },
          { value: '16', label: 'Let na trhu' },
          { value: '24', label: 'Zahradníků v týmu' },
          { value: '98 %', label: 'Spokojených klientů' },
        ],
        team: [
          { name: 'Tomáš Zelený', role: 'Zakladatel', image: ph.work[0] },
          { name: 'Petra Sadová', role: 'Vedoucí realizací', image: ph.work[1] },
          { name: 'Martin Trávníček', role: 'Hlavní zahradník', image: ph.work[2] },
        ],
      },
      services: [
        {
          slug: 'seceni-a-mulcovani',
          icon: 'mowing',
          name: 'Sečení a mulčování',
          perex: 'Pravidelná údržba travnatých ploch včetně sběru i mulčování.',
          description:
            'Postaráme se o pravidelné sečení vaší zahrady ve správné výšce i frekvenci. Posečenou hmotu odvezeme nebo mulčujeme přímo na ploše jako přírodní hnojivo.',
          image: ph.services[0],
          features: [
            'Pravidelný i jednorázový režim',
            'Sběr nebo mulčování',
            'Úklid zpevněných ploch',
          ],
        },
        {
          slug: 'automaticke-zavlahy',
          icon: 'water_drop',
          name: 'Automatické závlahy',
          perex: 'Návrh a instalace závlahových systémů s chytrým řízením.',
          description:
            'Navrhneme a nainstalujeme závlahový systém, který zalévá přesně tehdy a tam, kde je potřeba. Ušetříte vodu, čas i starosti.',
          image: ph.services[1],
          features: ['Návrh na míru pozemku', 'Chytré řízení přes aplikaci', 'Servis a zazimování'],
        },
        {
          slug: 'hnojeni-a-regenerace',
          icon: 'compost',
          name: 'Hnojení a regenerace',
          perex: 'Programy výživy a regenerace pro sytě zelený trávník.',
          description:
            'Sestavíme roční program výživy, provzdušnění a regenerace, aby váš trávník zůstal hustý a zdravý po celou sezónu.',
          image: ph.services[2],
          features: ['Rozbor a plán výživy', 'Vertikutace a aerifikace', 'Dosev prořídlých míst'],
        },
        {
          slug: 'vysadba-zelene',
          icon: 'park',
          name: 'Výsadba zeleně',
          perex: 'Stromy, keře i trvalkové záhony s následnou péčí.',
          description:
            'Navrhneme a vysadíme dřeviny, trvalky i okrasné záhony, které budou ladit s vaší zahradou a vydrží roky.',
          image: ph.services[3],
          features: ['Výběr vhodných druhů', 'Příprava půdy', 'Následná péče'],
        },
        {
          slug: 'sezonni-uklid',
          icon: 'yard',
          name: 'Sezónní úklid',
          perex: 'Jarní a podzimní úklid, vertikutace a provzdušnění.',
          description:
            'Připravíme zahradu na sezónu i na zimu — od hrabání listí po řez a ochranu rostlin.',
          image: ph.services[4],
          features: ['Jarní probuzení zahrady', 'Podzimní úklid listí', 'Zazimování'],
        },
        {
          slug: 'ochrana-proti-plevelu',
          icon: 'pest_control',
          name: 'Ochrana proti plevelu',
          perex: 'Šetrné ošetření proti plevelu, mechu a chorobám.',
          description:
            'Zbavíme trávník plevele, mechu i chorob šetrnými postupy, které neublíží okolní zeleni ani vašim mazlíčkům.',
          image: ph.services[5],
          features: ['Cílené ošetření', 'Šetrné přípravky', 'Prevence a monitoring'],
        },
      ],
      projects: [
        {
          slug: 'rodinna-zahrada-repy',
          title: 'Rodinná zahrada Řepy',
          category: 'Realizace',
          location: 'Praha 6',
          year: '2024',
          image: ph.work[0],
          excerpt: 'Kompletní obnova zanedbané zahrady včetně nového trávníku a závlahy.',
          description:
            'Zahradu jsme od základu přemodelovali — nový travní koberec, automatická závlaha, trvalkové záhony a dřevěná terasa. Realizace proběhla za tři týdny.',
        },
        {
          slug: 'zavlahovy-system-ricany',
          title: 'Závlahový systém',
          category: 'Technologie',
          location: 'Říčany',
          year: '2024',
          image: ph.work[1],
          excerpt: 'Návrh a instalace chytré závlahy pro rozsáhlou zahradu.',
          description:
            'Pro klienta s velkým pozemkem jsme navrhli zónovou závlahu s ovládáním přes mobil a čidlem srážek. Spotřeba vody klesla o třetinu.',
        },
        {
          slug: 'okrasne-zahony',
          title: 'Okrasné záhony',
          category: 'Výsadba',
          location: 'Černošice',
          year: '2023',
          image: ph.work[2],
          excerpt: 'Barevné trvalkové záhony kvetoucí od jara do podzimu.',
          description:
            'Navrhli jsme kompozici trvalek a okrasných trav, která kvete v každé fázi sezóny a vyžaduje minimální údržbu.',
        },
        {
          slug: 'travnik-u-vily',
          title: 'Trávník u vily',
          category: 'Údržba',
          location: 'Praha 5',
          year: '2023',
          image: ph.work[3],
          excerpt: 'Pravidelná údržba reprezentativního trávníku u vily.',
          description:
            'Celoroční servis trávníku — sečení, hnojení, provzdušnění a dosev — aby plocha vypadala perfektně po celý rok.',
        },
        {
          slug: 'drevena-terasa',
          title: 'Dřevěná terasa',
          category: 'Realizace',
          location: 'Jesenice',
          year: '2022',
          image: ph.work[4],
          excerpt: 'Dřevěná terasa propojená se zahradou a záhony.',
          description:
            'Realizace dřevěné terasy z modřínu navazující na trávník a okrasné záhony s nasvícením.',
        },
      ],
      articles: [
        {
          slug: 'jak-na-zdravy-travnik-na-jare',
          title: 'Jak na zdravý trávník na jaře',
          perex: 'Vertikutace, hnojení a první seč — provedeme vás kompletní jarní péčí.',
          date: '12. března 2025',
          category: 'Trávník',
          image: ph.blog[0],
          author: 'Tomáš Zelený',
          body: [
            'Jaro je pro trávník nejdůležitější období. Po zimě potřebuje provzdušnit, vyhrabat mech a dodat živiny.',
            'Začněte vertikutací, následně dosejte prořídlá místa a aplikujte startovací hnojivo. První seč proveďte, až tráva dosáhne výšky 8–10 cm.',
          ],
        },
        {
          slug: 'zavlaha-ktera-setri-vodu',
          title: 'Závlaha, která šetří vodu i čas',
          perex: 'Chytré systémy zalévají přesně tehdy, kdy je potřeba. Vyplatí se?',
          date: '2. dubna 2025',
          category: 'Technologie',
          image: ph.blog[1],
          author: 'Petra Sadová',
          body: [
            'Automatická závlaha není luxus, ale chytrá investice. Díky čidlům a aplikaci zaléváte jen tehdy, kdy je to potřeba.',
            'Návratnost se počítá v ušetřené vodě i čase. A vaše zahrada je krásná i během dovolené.',
          ],
        },
        {
          slug: '5-rostlin-do-nenarocne-zahrady',
          title: '5 rostlin do nenáročné zahrady',
          perex: 'Tipy na trvalky a keře, které zvládnou i sušší léto bez náročné péče.',
          date: '20. dubna 2025',
          category: 'Výsadba',
          image: ph.blog[2],
          author: 'Martin Trávníček',
          body: [
            'Nenáročná zahrada neznamená nudná. Vsaďte na okrasné trávy, levanduli, rozchodníky a kakosty.',
            'Tyto rostliny zvládnou sucho, lákají opylovače a vypadají skvěle celou sezónu.',
          ],
        },
      ],
      faqs: [
        {
          question: 'Jak často je potřeba sekat trávník?',
          answer:
            'V hlavní sezóně zpravidla jednou týdně, podle počasí a typu trávníku. Ideální je nikdy neposekat víc než třetinu výšky.',
        },
        {
          question: 'Postaráte se o zahradu i celoročně?',
          answer:
            'Ano, nabízíme celoroční servisní programy včetně jarního i podzimního úklidu a zazimování.',
        },
        {
          question: 'Děláte i návrh nové zahrady?',
          answer:
            'Ano. Navrhneme zahradu na míru — od skici a osazovacího plánu po kompletní realizaci.',
        },
        {
          question: 'Jak rychle dorazíte na nezávaznou prohlídku?',
          answer: 'Zpravidla do několika dnů od poptávky. Prohlídka i cenová nabídka jsou zdarma.',
        },
      ],
    },

    remeslo: {
      about: {
        lead: 'Elektřina, voda, topení i plyn — jedna firma, na kterou se spolehnete.',
        paragraphs: [
          'ProfiServis je řemeslná firma, která spojuje elektrikáře, instalatéry a topenáře pod jednou střechou. Nemusíte obvolávat tři různé party — vše vyřešíte na jednom místě.',
          'Pracujeme čistě a spolehlivě. Přijedeme v domluvený čas, práci vám předáme odzkoušenou a s platnou revizí. Působíme v Brně a okolí.',
        ],
        values: [
          {
            icon: 'fact_check',
            title: 'Revizní technik',
            text: 'Elektro i plyn předáme s platnou revizní zprávou pro pojišťovnu i kolaudaci.',
          },
          {
            icon: 'bolt',
            title: 'Havarijní servis 24/7',
            text: 'Prasklá voda nebo výpadek proudu? Přijedeme i o víkendu a svátcích.',
          },
          {
            icon: 'handshake',
            title: 'Cena předem',
            text: 'Víte dopředu, kolik zaplatíte. Doprava i cenová nabídka jsou zdarma.',
          },
        ],
        counters: [
          { value: '3 500+', label: 'Dokončených zakázek' },
          { value: '15', label: 'Let praxe' },
          { value: '24/7', label: 'Havarijní servis' },
          { value: '100 %', label: 'Práce s revizí' },
        ],
        team: [
          { name: 'Martin Vodák', role: 'Vedoucí instalatér', image: ph.work[0] },
          { name: 'Lukáš Proud', role: 'Elektrikář — revizní technik', image: ph.work[1] },
          { name: 'Petr Kotlík', role: 'Topenář a plynař', image: ph.work[2] },
        ],
      },
      services: [
        {
          slug: 'elektroinstalace',
          icon: 'electrical_services',
          name: 'Elektroinstalace',
          perex: 'Kompletní elektroinstalace novostaveb i rekonstrukce.',
          description:
            'Zajistíme kompletní elektroinstalaci — rozvaděče, zásuvky, osvětlení i datové rozvody. Novostavby, rekonstrukce i drobné opravy.',
          image: ph.services[0],
          features: ['Rozvaděče a jištění', 'Zásuvky a osvětlení', 'Datové rozvody'],
        },
        {
          slug: 'voda-a-odpady',
          icon: 'plumbing',
          name: 'Voda a odpady',
          perex: 'Rozvody vody a odpadů, výměna baterií i zařizovacích předmětů.',
          description:
            'Nataháme rozvody vody a odpadů, vyměníme baterie, umyvadla i WC a připojíme myčku nebo pračku. Vše bez zbytečného nepořádku.',
          image: ph.services[1],
          features: ['Rozvody vody a odpadů', 'Výměna baterií a WC', 'Připojení spotřebičů'],
        },
        {
          slug: 'topeni-a-kotle',
          icon: 'mode_heat',
          name: 'Topení a kotle',
          perex: 'Montáž a servis kotlů, radiátorů a podlahového vytápění.',
          description:
            'Namontujeme a zprovozníme kotel, radiátory i podlahové vytápění. Postaráme se i o pravidelný servis a přípravu na topnou sezónu.',
          image: ph.services[2],
          features: ['Montáž kotlů', 'Radiátory a rozvody', 'Podlahové vytápění'],
        },
        {
          slug: 'havarijni-servis',
          icon: 'emergency',
          name: 'Havarijní servis 24/7',
          perex: 'Prasklá voda nebo výpadek proudu? Přijedeme co nejdříve.',
          description:
            'Havárie nepočká. Při prasklé vodě, ucpaném odpadu nebo výpadku proudu vyjíždíme 24 hodin denně, i o víkendech a svátcích.',
          image: ph.services[3],
          features: ['Nonstop výjezd', 'Rychlá diagnostika', 'Dočasné i trvalé řešení'],
        },
        {
          slug: 'chytra-domacnost-fve',
          icon: 'solar_power',
          name: 'Chytrá domácnost a FVE',
          perex: 'Chytré vypínače, termostaty a fotovoltaika na klíč.',
          description:
            'Zapojíme chytré vypínače, termostaty i kamery a navrhneme fotovoltaiku na míru. Ušetříte energii a ovládáte dům z mobilu.',
          image: ph.services[4],
          features: ['Chytré ovládání', 'Fotovoltaika na klíč', 'Bateriové úložiště'],
        },
        {
          slug: 'revize-a-certifikace',
          icon: 'fact_check',
          name: 'Revize a certifikace',
          perex: 'Revize elektro i plynu s platnou zprávou.',
          description:
            'Provedeme revizi elektroinstalace i plynu a vystavíme platnou revizní zprávu pro pojišťovnu, kolaudaci i váš klidný spánek.',
          image: ph.services[5],
          features: ['Revize elektro', 'Revize plynu', 'Zpráva pro pojišťovnu'],
        },
      ],
      projects: [
        {
          slug: 'rekonstrukce-elektro-v-byte',
          title: 'Rekonstrukce elektro v bytě',
          category: 'Elektroinstalace',
          location: 'Brno',
          year: '2024',
          image: ph.work[0],
          excerpt: 'Kompletní výměna rozvodů a rozvaděče v panelovém bytě.',
          description:
            'V obydleném bytě jsme po etapách vyměnili hliníkové rozvody za měděné, osadili nový rozvaděč s proudovými chrániči a vše předali s revizí.',
        },
        {
          slug: 'nova-koupelna-na-klic',
          title: 'Nová koupelna na klíč',
          category: 'Voda a odpady',
          location: 'Blansko',
          year: '2024',
          image: ph.work[1],
          excerpt: 'Nové rozvody vody a odpadů včetně osazení zařizovacích předmětů.',
          description:
            'Připravili jsme rozvody vody a odpadů pro novou koupelnu, osadili baterie, vanu i WC a zapojili pračku. Kompletně a bez starostí pro klienta.',
        },
        {
          slug: 'vymena-plynoveho-kotle',
          title: 'Výměna plynového kotle',
          category: 'Topení',
          location: 'Vyškov',
          year: '2023',
          image: ph.work[2],
          excerpt: 'Demontáž starého a montáž nového kondenzačního kotle.',
          description:
            'Vyměnili jsme dosluhující kotel za úsporný kondenzační, zregulovali otopnou soustavu a předali s revizí plynu.',
        },
        {
          slug: 'fotovoltaika-na-rodinny-dum',
          title: 'Fotovoltaika na rodinný dům',
          category: 'FVE',
          location: 'Kuřim',
          year: '2023',
          image: ph.work[3],
          excerpt: 'Instalace fotovoltaické elektrárny s bateriovým úložištěm.',
          description:
            'Na střechu rodinného domu jsme instalovali fotovoltaiku s baterií a chytrým řízením. Klient výrazně snížil účty za energie.',
        },
        {
          slug: 'rozvadec-pro-dilnu',
          title: 'Rozvaděč pro dílnu',
          category: 'Elektroinstalace',
          location: 'Brno',
          year: '2022',
          image: ph.work[4],
          excerpt: 'Nový rozvaděč a silnoproudé zásuvky pro řemeslnou dílnu.',
          description:
            'Pro dílnu jsme navrhli a osadili rozvaděč s třífázovými okruhy, zásuvkami 400 V a odpovídajícím jištěním pro dílenské stroje.',
        },
      ],
      articles: [
        {
          slug: 'jak-poznat-ze-potrebujete-novy-rozvadec',
          title: 'Jak poznat, že potřebujete nový rozvaděč',
          perex: 'Vypadávající jističe, teplý rozvaděč a staré pojistky — kdy je čas na výměnu.',
          date: '14. ledna 2025',
          category: 'Elektro',
          image: ph.blog[0],
          author: 'Lukáš Proud',
          body: [
            'Pokud vám často vypadávají jističe, rozvaděč se zahřívá nebo v něm máte ještě staré šroubovací pojistky, je nejvyšší čas na modernizaci.',
            'Nový rozvaděč s proudovými chrániči výrazně zvyšuje bezpečnost. Výměnu vždy předáváme s revizní zprávou.',
          ],
        },
        {
          slug: 'kapajici-kohoutek-tesneni-nebo-vymena',
          title: 'Kapající kohoutek: kdy stačí těsnění a kdy výměna',
          perex: 'Kapající baterie umí prosoutit tisíce litrů ročně. Poradíme, jak na to.',
          date: '3. února 2025',
          category: 'Voda',
          image: ph.blog[1],
          author: 'Martin Vodák',
          body: [
            'U starší pákové baterie často stačí vyměnit keramickou kartuši, u ventilové bytelné těsnění. Jindy se ale víc vyplatí baterii vyměnit celou.',
            'Kapající kohoutek není jen otravný zvuk — za rok umí protéct tisíce litrů. Řešení je rychlé a levné.',
          ],
        },
        {
          slug: 'vyplati-se-fotovoltaika-v-roce-2025',
          title: 'Vyplatí se fotovoltaika v roce 2025?',
          perex: 'Návratnost, dotace a baterie — na co se ptát před pořízením FVE.',
          date: '26. února 2025',
          category: 'Úspory',
          image: ph.blog[2],
          author: 'Petr Kotlík',
          body: [
            'Návratnost fotovoltaiky se dnes díky cenám energií pohybuje kolem sedmi až deseti let. S baterií využijete víc vlastní výroby.',
            'Klíčové je správné nadimenzování podle spotřeby a orientace střechy. Rádi vám vše spočítáme na míru.',
          ],
        },
      ],
      faqs: [
        {
          question: 'Přijedete i na drobnou opravu?',
          answer:
            'Ano. Nezáleží, jestli jde o jednu zásuvku, kapající baterii, nebo celou rekonstrukci — rádi přijedeme i na malou zakázku.',
        },
        {
          question: 'Jak rychle dorazíte při havárii?',
          answer:
            'Havarijní servis máme 24/7. Při prasklé vodě nebo výpadku proudu vyjíždíme co nejdříve, i o víkendu a svátcích.',
        },
        {
          question: 'Dostanu revizní zprávu?',
          answer:
            'Ano. Elektro i plyn předáváme s platnou revizní zprávou, kterou uznají pojišťovna i stavební úřad.',
        },
        {
          question: 'Řeknete cenu předem?',
          answer:
            'Ano. Po prohlídce nebo podle zaslaných fotek dostanete cenovou nabídku zdarma a bez závazku.',
        },
      ],
    },

    landscaping: {
      about: {
        lead: 'Měníme krajinu i veřejný prostor k lepšímu — od projektu po údržbu.',
        paragraphs: [
          'TerraScape je krajinářská společnost s vlastní těžkou technikou a projekčním zázemím. Realizujeme terénní a sadové úpravy pro města, obce i developery.',
          'Zakázku zvládneme celou pod jednou střechou — od první čáry v projektu přes výstavbu až po dlouhodobou údržbu rozsáhlých ploch.',
        ],
        values: [
          {
            icon: 'engineering',
            title: 'Projekce i realizace',
            text: 'Vše pod jednou střechou — od studie po prováděcí dokumentaci a stavbu.',
          },
          {
            icon: 'agriculture',
            title: 'Vlastní technika',
            text: 'Bagry, nakladače i sklápěče zvládnou i rozsáhlé projekty.',
          },
          {
            icon: 'workspace_premium',
            title: 'Certifikace ISO',
            text: 'Reference u měst a obcí a certifikovaný systém řízení kvality.',
          },
        ],
        counters: [
          { value: '320+', label: 'Dokončených zakázek' },
          { value: '60 ha', label: 'Upravených ploch' },
          { value: '28', label: 'Let na trhu' },
          { value: 'ISO', label: 'Certifikovaná firma' },
        ],
        team: [
          { name: 'Ing. Petr Krajina', role: 'Jednatel', image: ph.work[0] },
          { name: 'Ing. Hana Polní', role: 'Hlavní projektantka', image: ph.work[1] },
          { name: 'Josef Skála', role: 'Stavbyvedoucí', image: ph.work[2] },
        ],
      },
      services: [
        {
          slug: 'terenni-upravy',
          icon: 'terrain',
          name: 'Terénní úpravy',
          perex: 'Srovnání, navážky a modelace pozemků před výstavbou i výsadbou.',
          description:
            'Vlastní těžkou technikou srovnáme a vymodelujeme terén do požadovaného tvaru — od skrývek po finální urovnání.',
          image: ph.services[0],
          features: ['Skrývky a navážky', 'Modelace svahů', 'Příprava podloží'],
        },
        {
          slug: 'zpevnene-plochy',
          icon: 'foundation',
          name: 'Zpevněné plochy',
          perex: 'Komunikace, chodníky, parkoviště a opěrné konstrukce.',
          description:
            'Realizujeme příjezdové cesty, chodníky, parkoviště i opěrné zdi s dlouhou životností a kvalitním podložím.',
          image: ph.services[1],
          features: ['Dlažby a komunikace', 'Opěrné zdi', 'Odvodnění'],
        },
        {
          slug: 'sadove-upravy',
          icon: 'forest',
          name: 'Sadové úpravy',
          perex: 'Výsadba dřevin, trvalkových záhonů a zakládání trávníků.',
          description:
            'Osázíme plochy dřevinami, trvalkami i trávníky podle projektu sadových úprav a zajistíme následnou péči.',
          image: ph.services[2],
          features: ['Výsadba dřevin', 'Trvalkové záhony', 'Zakládání trávníků'],
        },
        {
          slug: 'modelace-terenu',
          icon: 'landscape',
          name: 'Modelace terénu',
          perex: 'Tvarování svahů, valů a vodních prvků v krajině.',
          description:
            'Vytvoříme valy, svahy i vodní prvky, které zapadnou do krajiny a plní technickou i estetickou funkci.',
          image: ph.services[3],
          features: ['Protihlukové valy', 'Vodní prvky', 'Krajinné modelace'],
        },
        {
          slug: 'zakladani-ploch',
          icon: 'yard',
          name: 'Zakládání ploch',
          perex: 'Travnaté, parkové i biologicky cenné plochy na klíč.',
          description:
            'Založíme parkové i biologicky cenné plochy — od přípravy půdy po výsev a první seče.',
          image: ph.services[4],
          features: ['Parkové trávníky', 'Květnaté louky', 'Protierozní výsadby'],
        },
        {
          slug: 'udrzba-zelene',
          icon: 'grass',
          name: 'Údržba zeleně',
          perex: 'Celoroční péče, sečení, řez dřevin a kácení.',
          description:
            'Zajistíme dlouhodobou údržbu rozsáhlých ploch — sečení, řez dřevin, kácení i úklid.',
          image: ph.services[5],
          features: ['Celoroční servis', 'Řez a kácení dřevin', 'Úklid ploch'],
        },
      ],
      projects: [
        {
          slug: 'revitalizace-namesti',
          title: 'Revitalizace náměstí',
          category: 'Veřejný prostor',
          location: 'Kolín',
          year: '2024',
          image: ph.work[0],
          excerpt: 'Kompletní obnova náměstí včetně zpevněných ploch a zeleně.',
          description:
            'Pro město jsme zrevitalizovali náměstí — nové dlažby, mobiliář, stromořadí i závlaha. Realizace proběhla za jedinou sezónu.',
        },
        {
          slug: 'park-pro-developera',
          title: 'Park pro rezidenci',
          category: 'Sadové úpravy',
          location: 'Pardubice',
          year: '2024',
          image: ph.work[1],
          excerpt: 'Vnitroblokový park nové rezidenční čtvrti.',
          description:
            'Navrhli a realizovali jsme park ve vnitrobloku — modelovaný terén, trávníky, výsadby a dětské prvky.',
        },
        {
          slug: 'protihlukovy-val',
          title: 'Protihlukový val',
          category: 'Terénní úpravy',
          location: 'Hradec Králové',
          year: '2023',
          image: ph.work[2],
          excerpt: 'Modelace a osázení protihlukového valu podél silnice.',
          description:
            'Vybudovali jsme protihlukový val z navážek, vymodelovali jeho svahy a osázeli protierozní výsadbou.',
        },
        {
          slug: 'sportovni-areal',
          title: 'Sportovní areál',
          category: 'Zpevněné plochy',
          location: 'Liberec',
          year: '2023',
          image: ph.work[3],
          excerpt: 'Zpevněné plochy a zeleň nového sportovního areálu.',
          description:
            'Realizovali jsme komunikace, parkoviště a doprovodnou zeleň sportovního areálu včetně závlahy.',
        },
        {
          slug: 'krajinna-vysadba',
          title: 'Krajinná výsadba',
          category: 'Sadové úpravy',
          location: 'Jihlava',
          year: '2022',
          image: ph.work[4],
          excerpt: 'Výsadba krajinné zeleně pro obec.',
          description:
            'Pro obec jsme vysadili krajinnou zeleň — stromořadí, remízky a protierozní pásy s následnou péčí.',
        },
      ],
      articles: [
        {
          slug: 'jak-vznika-revitalizace-namesti',
          title: 'Jak vzniká revitalizace náměstí',
          perex: 'Od studie přes výběrové řízení po realizaci — průvodce procesem.',
          date: '18. ledna 2025',
          category: 'Veřejný prostor',
          image: ph.blog[0],
          author: 'Ing. Petr Krajina',
          body: [
            'Revitalizace veřejného prostoru začíná analýzou území a participací s obyvateli. Následuje studie a prováděcí dokumentace.',
            'Díky tomu, že zvládáme projekci i realizaci, je celý proces rychlejší a bez zbytečných nejasností.',
          ],
        },
        {
          slug: 'vlastni-technika-vyhoda',
          title: 'Proč je vlastní technika výhoda',
          perex: 'Bagry, nakladače a sklápěče pod jednou střechou šetří čas i peníze.',
          date: '6. února 2025',
          category: 'Technika',
          image: ph.blog[1],
          author: 'Josef Skála',
          body: [
            'Vlastní těžká technika znamená, že nejsme závislí na subdodavatelích a držíme termíny i kvalitu pod kontrolou.',
            'U rozsáhlých terénních úprav je to zásadní výhoda — práce běží plynule a bez prostojů.',
          ],
        },
        {
          slug: 'sadove-upravy-pro-obce',
          title: 'Sadové úpravy pro obce',
          perex: 'Jak na výsadbu zeleně, která vydrží a zraje do krásy.',
          date: '28. března 2025',
          category: 'Sadové úpravy',
          image: ph.blog[2],
          author: 'Ing. Hana Polní',
          body: [
            'Kvalitní sadové úpravy začínají správným výběrem druhů a přípravou stanoviště. Pak teprve přichází výsadba.',
            'Důležitá je následná péče v prvních letech — bez ní ani sebelepší projekt nepřežije.',
          ],
        },
      ],
      faqs: [
        {
          question: 'Děláte projekt i realizaci?',
          answer:
            'Ano, zvládáme vše pod jednou střechou — od studie a prováděcí dokumentace po výstavbu a údržbu.',
        },
        {
          question: 'Pracujete pro obce a developery?',
          answer: 'Ano, máme rozsáhlé reference u měst, obcí i soukromých investorů a developerů.',
        },
        {
          question: 'Máte vlastní techniku?',
          answer:
            'Ano, disponujeme vlastní těžkou technikou — bagry, nakladači i sklápěči pro projekty každého rozsahu.',
        },
        {
          question: 'Zajišťujete i následnou údržbu?',
          answer: 'Ano, nabízíme dlouhodobé servisní smlouvy na údržbu rozsáhlých ploch zeleně.',
        },
      ],
    },
  };

  return datasets[slug] ?? datasets.lawn;
}

export function useDemoContent(slug: string) {
  const content = build(slug);
  return {
    ...content,
    getService: (s: string): DemoService | undefined => content.services.find((x) => x.slug === s),
    getProject: (s: string): DemoProject | undefined => content.projects.find((x) => x.slug === s),
    getArticle: (s: string): DemoArticle | undefined => content.articles.find((x) => x.slug === s),
  };
}
