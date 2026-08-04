import { inject, ref, type Ref } from 'vue';
import type { MenuSectionDef } from '~/components/Layout/navigation';

export interface MenuOrderRow {
  section_key: string;
  item_key: string;
  position: number;
}

interface MenuOrderResponse {
  data: MenuOrderRow[];
}

// Always returns a fresh array of fresh section objects (each with its own fresh
// `menu` array) — never the same references as `sections` — so callers can safely
// mutate the result (e.g. vuedraggable splicing) without corrupting the shared
// `menuSections` singleton or another component's copy.
export function applyMenuOrder(sections: MenuSectionDef[], rows: MenuOrderRow[]): MenuSectionDef[] {
  const sectionPosition = new Map<string, number>();
  const itemPosition = new Map<string, Map<string, number>>();

  for (const row of rows) {
    if (row.item_key === '') {
      sectionPosition.set(row.section_key, row.position);
    } else {
      if (!itemPosition.has(row.section_key)) itemPosition.set(row.section_key, new Map());
      itemPosition.get(row.section_key)!.set(row.item_key, row.position);
    }
  }

  const orderedSections = sections
    .map((section, index) => ({ section, index }))
    .sort((a, b) => {
      const posA = sectionPosition.get(a.section.key) ?? 1000 + a.index;
      const posB = sectionPosition.get(b.section.key) ?? 1000 + b.index;
      return posA - posB;
    })
    .map(({ section }) => section);

  return orderedSections.map((section) => {
    const positions = itemPosition.get(section.key);

    const menu = section.menu
      .map((item, index) => ({ item, index }))
      .sort((a, b) => {
        const posA = positions?.get(a.item.key) ?? 1000 + a.index;
        const posB = positions?.get(b.item.key) ?? 1000 + b.index;
        return posA - posB;
      })
      .map(({ item }) => item);

    return { ...section, menu };
  });
}

export function menuOrderRowsFromSections(sections: MenuSectionDef[]): MenuOrderRow[] {
  const rows: MenuOrderRow[] = [];
  sections.forEach((section, sectionIndex) => {
    rows.push({ section_key: section.key, item_key: '', position: sectionIndex });
    section.menu.forEach((item, itemIndex) => {
      rows.push({ section_key: section.key, item_key: item.key, position: itemIndex });
    });
  });
  return rows;
}

export function useMenuOrder() {
  const selectedSiteHash = inject<Ref<string>>('selectedSiteHash', ref(''));

  async function load(): Promise<MenuOrderRow[]> {
    if (!selectedSiteHash.value) return [];

    const client = useSanctumClient();
    try {
      const response = await client<MenuOrderResponse>('/api/admin/menu-order', {
        method: 'GET',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-Site-Hash': selectedSiteHash.value,
        },
      });
      return response?.data ?? [];
    } catch {
      return [];
    }
  }

  async function save(rows: MenuOrderRow[]): Promise<MenuOrderRow[]> {
    const client = useSanctumClient();
    const response = await client<MenuOrderResponse>('/api/admin/menu-order', {
      method: 'POST',
      body: JSON.stringify({ rows }),
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Site-Hash': selectedSiteHash.value,
      },
    });
    return response?.data ?? [];
  }

  async function reset(): Promise<void> {
    const client = useSanctumClient();
    await client('/api/admin/menu-order', {
      method: 'DELETE',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Site-Hash': selectedSiteHash.value,
      },
    });
  }

  return { load, save, reset };
}
