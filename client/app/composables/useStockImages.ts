/**
 * Centralised stock photography for the demo variants.
 * The bought ThemeForest template ships only grey placeholders, so we point
 * at Unsplash (garden / lawn / landscaping themed). All URLs live here — if a
 * photo ID is wrong, fix it in this one file.
 */
const U = (id: string, w = 1200, h = 0): string =>
  `https://images.unsplash.com/photo-${id}?auto=format&fit=crop&q=80&w=${w}${h ? `&h=${h}` : ''}`;

// Curated, garden-themed photo pool (long-standing Unsplash photos).
const P = {
  grassField: '1466692476868-aef1dfb1e735',
  greenHills: '1416879595882-3373a0480b5b',
  soilHands: '1558904541-efa843a96f01',
  flowersGarden: '1523348837708-15d4a09cfac2',
  nursery: '1485955900006-10f4d324d411',
  forestPath: '1444392061186-9fc38f84f726',
  wateringPlant: '1530836369250-ef72a3f5cda8',
  landscaping: '1585320806297-9794b3e4eeae',
  greenPlants: '1591857177580-dc82b9ac4e1e',
  backyardLawn: '1601985705806-5b9a71f6004f',
  pottedPlants: '1598902108854-10e335adac99',
  parkGreen: '1519331379826-f10be5486c6f',
  hedge: '1574482620811-1aa16ffe3c82',
  stonePath: '1558618666-fcd25c85cd64',
  treeCanopy: '1441974231531-c6227db76b6e',
  pineForest: '1448375240586-882707db888b',
  // Restaurant / food (Savoria demo)
  restoInterior: '1517248135467-4c7edcad34c4',
  plating: '1504674900247-0877df9cc836',
  pasta: '1551183053-bf91a1d81141',
  steak: '1546964124-0cce460f38ef',
  dessert: '1551024506-0bccd828d307',
  wine: '1510812431401-41d2bd2722f3',
  chefCooking: '1577219491135-ce391730fb2c',
  tableSetting: '1414235077428-338989a2e8c0',
};

export interface VariantImages {
  hero: string;
  aboutMain: string;
  aboutSecondary: string;
  choose: string;
  facts: string;
  work: string[];
  services: string[];
  blog: string[];
}

export function useStockImages() {
  const images: Record<string, VariantImages> = {
    lawn: {
      hero: U(P.grassField, 1920),
      aboutMain: U(P.backyardLawn, 900),
      aboutSecondary: U(P.soilHands, 600),
      choose: U(P.wateringPlant, 900),
      facts: U(P.greenHills, 1920),
      work: [P.backyardLawn, P.flowersGarden, P.greenPlants, P.nursery, P.wateringPlant].map((p) =>
        U(p, 800),
      ),
      services: [
        P.grassField,
        P.wateringPlant,
        P.soilHands,
        P.greenPlants,
        P.flowersGarden,
        P.nursery,
      ].map((p) => U(p, 700)),
      blog: [P.grassField, P.wateringPlant, P.greenPlants].map((p) => U(p, 700)),
    },
    tree: {
      hero: U(P.treeCanopy, 1920),
      aboutMain: U(P.forestPath, 900),
      aboutSecondary: U(P.pineForest, 600),
      choose: U(P.greenHills, 900),
      facts: U(P.pineForest, 1920),
      work: [P.forestPath, P.treeCanopy, P.greenHills, P.hedge, P.parkGreen].map((p) => U(p, 800)),
      services: [P.forestPath, P.treeCanopy, P.pineForest, P.greenHills, P.parkGreen, P.hedge].map(
        (p) => U(p, 700),
      ),
      blog: [P.forestPath, P.treeCanopy, P.greenHills].map((p) => U(p, 700)),
    },
    landscaping: {
      hero: U(P.landscaping, 1920),
      aboutMain: U(P.parkGreen, 900),
      aboutSecondary: U(P.stonePath, 600),
      choose: U(P.hedge, 900),
      facts: U(P.parkGreen, 1920),
      work: [P.parkGreen, P.stonePath, P.landscaping, P.hedge, P.forestPath].map((p) => U(p, 800)),
      services: [
        P.landscaping,
        P.stonePath,
        P.grassField,
        P.forestPath,
        P.wateringPlant,
        P.hedge,
      ].map((p) => U(p, 700)),
      blog: [P.parkGreen, P.landscaping, P.hedge].map((p) => U(p, 700)),
    },
    restaurant: {
      hero: U(P.restoInterior, 1920),
      aboutMain: U(P.plating, 900),
      aboutSecondary: U(P.chefCooking, 600),
      choose: U(P.tableSetting, 900),
      facts: U(P.restoInterior, 1920),
      work: [P.plating, P.steak, P.pasta, P.dessert, P.wine].map((p) => U(p, 800)),
      services: [P.pasta, P.steak, P.dessert, P.wine, P.plating, P.tableSetting].map((p) =>
        U(p, 700),
      ),
      blog: [P.plating, P.chefCooking, P.dessert].map((p) => U(p, 700)),
    },
  };

  const get = (slug: string): VariantImages => images[slug] ?? images.lawn;
  return { get, images };
}
