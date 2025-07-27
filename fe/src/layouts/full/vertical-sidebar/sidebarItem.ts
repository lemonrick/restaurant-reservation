import {
  CircleIcon,
  WindmillIcon,
  TypographyIcon,
  ShadowIcon,
  PaletteIcon,
  BugIcon,
  BrandChromeIcon,
  HelpIcon,
  CalendarCheckIcon,
  UsersIcon
} from 'vue-tabler-icons';

import type { User } from '@/models/User';

export interface menu {
  header?: string;
  title?: string;
  icon?: object;
  to?: string;
  divider?: boolean;
  chip?: string;
  chipColor?: string;
  chipVariant?: string;
  chipIcon?: string;
  children?: menu[];
  disabled?: boolean;
  type?: string;
  subCaption?: string;
}

export function getSidebarItems(user: User | null): menu[] {
  const reservationsTitle = user?.role === 'guest' ? 'My reservations' : 'Reservations';

  return [
    { header: 'Dashboard' },
    {
      title: reservationsTitle,
      icon: CalendarCheckIcon,
      to: '/dashboard/reservations'
    },
    ...(user?.role === 'admin'
      ? [{
        title: 'Users',
        icon: UsersIcon,
        to: '/dashboard/users'
      }]
      : []),
  ];
}