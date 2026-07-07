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
  // Trades / electrician + plumber (ProfiServis "remeslo" demo)
  crewSky: '1516216628859-9bccecab13ca', // crew in blue overalls climbing scaffold
  electricianPanel: '1621905251189-08b45d6a269e', // electrician testing an electrical box
  electricianPortrait: '1621905252507-b35492cc74b4', // tradesman by electrical box
  electricianBox: '1621905251918-48416bd8575a', // electrician wiring a box
  circuitRepair: '1581092918056-0c4c3acd3789', // hands repairing electronics
  blueprintConsult: '1581092446327-9b52bd1570c2', // hi-vis worker over blueprints
  toolsRack: '1530124566582-a618bc2615dc', // rack of pliers / hand tools
  welding: '1504328345606-18bbc8c9d7d1', // welder with sparks
  pipesBrick: '1607472586893-edb57bdc0e39', // industrial pipes on brick wall
  faucetChrome: '1542013936693-884638332954', // chrome kitchen tap + water
  faucetBlack: '1521207418485-99c705420785', // black tap running water
  faucetDrip: '1517646287270-a5a9ca602e5c', // dripping faucet (b&w)
  insulation: '1607400201889-565b1ee75f8e', // worker installing insulation
  solarPanels: '1558449028-b53a39d100fc', // solar panel array
  constructionSite: '1591955506264-3f5a6834570a', // construction site with crane (b&w)
  // Restaurant / food (Savoria demo)
  restoInterior: '1517248135467-4c7edcad34c4',
  plating: '1504674900247-0877df9cc836',
  pasta: '1551183053-bf91a1d81141',
  steak: '1546964124-0cce460f38ef',
  dessert: '1551024506-0bccd828d307',
  wine: '1510812431401-41d2bd2722f3',
  chefCooking: '1577219491135-ce391730fb2c',
  tableSetting: '1414235077428-338989a2e8c0',
  // Lawyer / office (Veritas demo)
  lawOffice: '1521737604893-d14cc237f11d',
  lawColumns: '1589829545856-d10d557cf95f',
  lawBooks: '1521587760476-6c12a4b040da',
  lawyerPortrait: '1560250097-0b93528c311a',
  lawHandshake: '1454165804606-c3d57bc86b40',
  lawBuilding: '1486406146926-c627a92ad1ab',
  // Freelancer / creative (Adam Kovář demo)
  fWorkspace: '1498050108023-c5249f4df085',
  fDesk: '1497366754035-f200968a6e72',
  fDesigner: '1507003211169-0a1dd7228f2d',
  fAbstract: '1550745165-9bc0b252726f',
  fLaptop: '1517245386807-bb43f82c33c4',
  fMockup: '1559028012-481c04fa702d',
  fCreative: '1561070791-2526d30994b5',
  fBranding: '1600880292203-757bb62b4baf',
  fApp: '1512941937669-90a1b58e7e9c',
  fUi: '1545235617-9465d2a55698',
  fPortrait: '1500648767791-00dcc994a43e',
  // Spa / wellness (Serenity demo)
  spaMassage: '1544161515-4ab6ce6db874',
  spaScene: '1600334129128-685c5582fd35',
  spaFacial: '1570172619644-dfd03ed5d881',
  spaStones: '1519823551278-64ac92734fb1',
  spaSauna: '1571019613454-1cb2f99b2d8b',
  spaTowels: '1540555700478-4be289fbecef',
  spaOils: '1608571423902-eed4a5ad8108',
  spaWellness: '1512290923902-8a9f81dc236c',
  spaHotstone: '1600334089648-b0d9d3028eb2',
  spaAroma: '1596178065887-1198b6148b2b',
  spaRelax: '1519415387722-a1c3bbef716c',
  // Medical / clinic (Vitalmed demo)
  mDoctorTeam: '1582750433449-648ed127bb54', // doctor portrait, warm light
  mClinicHall: '1519494026892-80bbd2d6fd0d', // modern clinic corridor
  mSurgeons: '1551190822-a9333d879b1f', // surgeons at work
  mDoctorPatient: '1576091160399-112ba8d25d1d', // doctor with tablet
  mStethoscope: '1505751172876-fa1923c5c528', // stethoscope on desk
  mNurse: '1559839734-2b71ea197ec2', // smiling female doctor
  mLab: '1579154204601-01588f351e67', // laboratory samples
  mDentist: '1588776814546-1ffcf47267a5', // dental care
  mPediatric: '1632053002928-1919605ee6f7', // pediatrician with child
  mCardio: '1628348068343-c6a848d2b6dd', // heart model
  mMri: '1516069677018-378515003435', // MRI scanner (b&w)
  mReception: '1629909613654-28e377c37b09', // clinic reception
  mDoctorMale: '1612349317150-e413f6a5b16d', // male doctor portrait
  mDoctorFemale: '1594824476967-48c8b964273f', // female doctor portrait
  mConsult: '1521791136064-7986c2920216', // consultation handshake
  fSkincare: '1571781926291-c477ebfd024b',
  fCosmetic: '1523293182086-7651a899d37f',
  fLeaf: '1512207736890-6ffed8a84e8d',
  fCamera: '1526170375885-4d8ecf77b99f',
  fFashion: '1503602642458-232111445657',
  fPerfume: '1541643600914-78b084683601',
  fShoe: '1542291026-7eec264c27ff',
  fWatch: '1523275335684-37898b6baf30',
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
    remeslo: {
      hero: U(P.crewSky, 1920),
      aboutMain: U(P.electricianPanel, 900),
      aboutSecondary: U(P.toolsRack, 600),
      choose: U(P.faucetChrome, 900),
      facts: U(P.constructionSite, 1920),
      work: [
        P.electricianPortrait,
        P.electricianBox,
        P.blueprintConsult,
        P.pipesBrick,
        P.solarPanels,
      ].map((p) => U(p, 800)),
      services: [
        P.electricianPanel,
        P.pipesBrick,
        P.faucetChrome,
        P.welding,
        P.solarPanels,
        P.insulation,
      ].map((p) => U(p, 700)),
      blog: [P.circuitRepair, P.faucetBlack, P.solarPanels].map((p) => U(p, 700)),
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
    lawyer: {
      hero: U(P.lawColumns, 1920),
      aboutMain: U(P.lawOffice, 900),
      aboutSecondary: U(P.lawHandshake, 600),
      choose: U(P.lawBooks, 900),
      facts: U(P.lawBuilding, 1920),
      work: [P.lawOffice, P.lawColumns, P.lawBooks, P.lawHandshake, P.lawBuilding].map((p) =>
        U(p, 800),
      ),
      services: [
        P.lawBooks,
        P.lawHandshake,
        P.lawOffice,
        P.lawColumns,
        P.lawBuilding,
        P.lawyerPortrait,
      ].map((p) => U(p, 700)),
      blog: [P.lawOffice, P.lawBooks, P.lawHandshake].map((p) => U(p, 700)),
    },
    freelancer: {
      hero: U(P.fPortrait, 1200),
      aboutMain: U(P.fDesk, 900),
      aboutSecondary: U(P.fPortrait, 900),
      choose: U(P.fCreative, 900),
      facts: U(P.fAbstract, 1920),
      work: [P.fSkincare, P.fCosmetic, P.fLeaf, P.fFashion, P.fPerfume].map((p) => U(p, 800)),
      services: [P.fCamera, P.fWatch, P.fShoe, P.fCosmetic, P.fSkincare, P.fFashion].map((p) =>
        U(p, 700),
      ),
      blog: [P.fCreative, P.fDesk, P.fAbstract].map((p) => U(p, 700)),
    },
    medical: {
      hero: U(P.mClinicHall, 1920),
      aboutMain: U(P.mDoctorPatient, 900),
      aboutSecondary: U(P.mNurse, 600),
      choose: U(P.mSurgeons, 900),
      facts: U(P.mMri, 1920),
      work: [P.mDoctorTeam, P.mSurgeons, P.mLab, P.mPediatric, P.mReception].map((p) => U(p, 800)),
      services: [P.mCardio, P.mDentist, P.mPediatric, P.mLab, P.mMri, P.mDoctorPatient].map((p) =>
        U(p, 700),
      ),
      blog: [P.mStethoscope, P.mConsult, P.mLab].map((p) => U(p, 700)),
    },
    spa: {
      hero: U(P.spaMassage, 1920),
      aboutMain: U(P.spaFacial, 900),
      aboutSecondary: U(P.spaStones, 600),
      choose: U(P.spaTowels, 900),
      facts: U(P.spaScene, 1920),
      work: [P.spaMassage, P.spaFacial, P.spaStones, P.spaHotstone, P.spaAroma].map((p) =>
        U(p, 800),
      ),
      services: [P.spaMassage, P.spaFacial, P.spaStones, P.spaSauna, P.spaAroma, P.spaHotstone].map(
        (p) => U(p, 700),
      ),
      blog: [P.spaWellness, P.spaOils, P.spaRelax].map((p) => U(p, 700)),
    },
  };

  const get = (slug: string): VariantImages => images[slug] ?? images.lawn;
  return { get, images };
}
