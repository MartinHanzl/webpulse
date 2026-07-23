/**
 * Shared Disc Golf helpers: +/- par formatting and trend metadata.
 */
export function useDiscGolf() {
  /**
   * Format a relative-to-par value as a signed string.
   * 0 → "E" (even par), positive → "+3", negative → "-1".
   */
  function formatRelative(value: number | string | null | undefined): string {
    if (value === null || value === undefined || value === '') return '—';
    const num = typeof value === 'string' ? parseFloat(value) : value;
    if (isNaN(num)) return '—';
    if (num === 0) return 'E';
    return num > 0 ? `+${num}` : `${num}`;
  }

  /**
   * Tailwind text color class for a relative-to-par value.
   * under par (negative) = green, over par (positive) = red, even = slate.
   */
  function relativeColor(value: number | null | undefined): string {
    if (value === null || value === undefined || isNaN(Number(value))) return 'text-slate-500';
    if (value < 0) return 'text-emerald-600';
    if (value > 0) return 'text-red-500';
    return 'text-slate-600';
  }

  /**
   * Metadata for a trend value: label, arrow, colors.
   */
  function trendMeta(trend: string | null | undefined) {
    switch (trend) {
      case 'improving':
        return {
          label: 'zlepšuje se',
          arrow: '↑',
          text: 'text-emerald-600',
          bg: 'bg-emerald-50',
          ring: 'ring-emerald-200',
        };
      case 'worsening':
        return {
          label: 'zhoršuje se',
          arrow: '↓',
          text: 'text-red-500',
          bg: 'bg-red-50',
          ring: 'ring-red-200',
        };
      default:
        return {
          label: 'stabilní',
          arrow: '→',
          text: 'text-slate-500',
          bg: 'bg-slate-50',
          ring: 'ring-slate-200',
        };
    }
  }

  return { formatRelative, relativeColor, trendMeta };
}
