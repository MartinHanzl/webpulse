// Czech public holidays — frontend-only helper for calendar highlighting.
// Fixed-date holidays + movable Easter holidays (computed via the
// anonymous Gregorian / Gauss algorithm for Easter Sunday).

function pad(n: number): string {
  return n < 10 ? `0${n}` : `${n}`;
}

function toDateKey(date: Date): string {
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
}

function addDays(date: Date, days: number): Date {
  const d = new Date(date);
  d.setDate(d.getDate() + days);
  return d;
}

// Anonymous Gregorian algorithm (Gauss) for the date of Easter Sunday.
function getEasterSunday(year: number): Date {
  const a = year % 19;
  const b = Math.floor(year / 100);
  const c = year % 100;
  const d = Math.floor(b / 4);
  const e = b % 4;
  const f = Math.floor((b + 8) / 25);
  const g = Math.floor((b - f + 1) / 3);
  const h = (19 * a + b - d - g + 15) % 30;
  const i = Math.floor(c / 4);
  const k = c % 4;
  const l = (32 + 2 * e + 2 * i - h - k) % 7;
  const m = Math.floor((a + 11 * h + 22 * l) / 451);
  const month = Math.floor((h + l - 7 * m + 114) / 31); // 3 = March, 4 = April
  const day = ((h + l - 7 * m + 114) % 31) + 1;

  return new Date(year, month - 1, day);
}

export function getCzechHolidays(year: number): Map<string, string> {
  const holidays = new Map<string, string>();

  const fixed: Array<[number, number, string]> = [
    [1, 1, 'Nový rok'],
    [5, 1, 'Svátek práce'],
    [5, 8, 'Den vítězství'],
    [7, 5, 'Den slovanských věrozvěstů Cyrila a Metoděje'],
    [7, 6, 'Den upálení mistra Jana Husa'],
    [9, 28, 'Den české státnosti'],
    [10, 28, 'Den vzniku samostatného československého státu'],
    [11, 17, 'Den boje za svobodu a demokracii'],
    [12, 24, 'Štědrý den'],
    [12, 25, '1. svátek vánoční'],
    [12, 26, '2. svátek vánoční'],
  ];

  fixed.forEach(([month, day, name]) => {
    holidays.set(toDateKey(new Date(year, month - 1, day)), name);
  });

  const easterSunday = getEasterSunday(year);
  const goodFriday = addDays(easterSunday, -2);
  const easterMonday = addDays(easterSunday, 1);

  holidays.set(toDateKey(goodFriday), 'Velký pátek');
  holidays.set(toDateKey(easterMonday), 'Velikonoční pondělí');

  return holidays;
}
