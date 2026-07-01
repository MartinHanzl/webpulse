/**
 * Registry of demo "clients". Each entry is a landing-page variant rebuilt
 * from the ThemeForest template. Drives the demo switcher, navbar branding
 * and the `data-demo` palette attribute consumed by theme.css.
 */
export interface DemoNavLink {
  label: string;
  to: string;
}

export interface DemoDefinition {
  slug: 'lawn' | 'tree' | 'landscaping' | 'restaurant';
  /** value for data-demo (palette key in theme.css) */
  palette: 'lawn' | 'tree' | 'landscaping' | 'restaurant';
  brandName: string;
  tagline: string;
  phone: string;
  email: string;
  address: string;
  /** short label shown in the demo switcher */
  switchLabel: string;
  /** one-word industry shown as accent chip */
  industry: string;
  /** dark template → inner pages & solid navbar render on a dark surface */
  dark?: boolean;
}

export const DEMOS: DemoDefinition[] = [
  {
    slug: 'lawn',
    palette: 'lawn',
    brandName: 'Lawnly',
    tagline: 'Profesionální péče o trávník',
    phone: '+420 777 123 456',
    email: 'info@lawnly.cz',
    address: 'Zahradní 12, Praha 6',
    switchLabel: 'Lawn Care',
    industry: 'Údržba trávníků',
  },
  {
    slug: 'tree',
    palette: 'tree',
    brandName: 'ArborPro',
    tagline: 'Péče o stromy a rizikové kácení',
    phone: '+420 608 987 654',
    email: 'info@arborpro.cz',
    address: 'Lesní 22, Brno',
    switchLabel: 'Tree Service',
    industry: 'Arboristika',
  },
  {
    slug: 'landscaping',
    palette: 'landscaping',
    brandName: 'TerraScape',
    tagline: 'Krajinářské a sadové úpravy',
    phone: '+420 720 555 333',
    email: 'kontakt@terrascape.cz',
    address: 'Lipová 45, Olomouc',
    switchLabel: 'Landscaping Co.',
    industry: 'Krajinářství',
    dark: true,
  },
  {
    slug: 'restaurant',
    palette: 'restaurant',
    brandName: 'Savoria',
    tagline: 'Sezónní kuchyně & vinný sklep',
    phone: '+420 222 315 900',
    email: 'rezervace@savoria.cz',
    address: 'Michalská 6, Praha 1',
    switchLabel: 'Restaurant',
    industry: 'Restaurace',
  },
];

export function useDemos() {
  const nav = (slug: string): DemoNavLink[] => {
    // Restaurant demo has its own page set (menu, chefs, gallery, story…).
    if (slug === 'restaurant') {
      return [
        { label: 'Domů', to: `/demo/${slug}` },
        { label: 'O nás', to: `/demo/${slug}/o-nas` },
        { label: 'Menu', to: `/demo/${slug}/menu` },
        { label: 'Příběh', to: `/demo/${slug}/pribeh` },
        { label: 'Galerie', to: `/demo/${slug}/galerie` },
        { label: 'Kuchaři', to: `/demo/${slug}/kuchari` },
        { label: 'Blog', to: `/demo/${slug}/blog` },
        { label: 'Kontakt', to: `/demo/${slug}/kontakt` },
      ];
    }
    return [
      { label: 'Domů', to: `/demo/${slug}` },
      { label: 'O nás', to: `/demo/${slug}/o-nas` },
      { label: 'Služby', to: `/demo/${slug}/sluzby` },
      { label: 'Reference', to: `/demo/${slug}/reference` },
      { label: 'Blog', to: `/demo/${slug}/blog` },
      { label: 'FAQ', to: `/demo/${slug}/faq` },
      { label: 'Kontakt', to: `/demo/${slug}/kontakt` },
    ];
  };

  // Navigation for the REAL site (non-/demo). Points at the actual CMS routes,
  // not the per-demo showcase pages.
  const siteNav = (): DemoNavLink[] => [
    { label: 'Domů', to: '/' },
    { label: 'Služby', to: '/sluzby' },
    { label: 'Blog', to: '/blog' },
    { label: 'Recenze', to: '/review' },
    { label: 'FAQ', to: '/faq' },
    { label: 'Kontakt', to: '/kontakt' },
  ];

  const getDemo = (slug: string): DemoDefinition | undefined => DEMOS.find((d) => d.slug === slug);

  return { demos: DEMOS, nav, siteNav, getDemo };
}
