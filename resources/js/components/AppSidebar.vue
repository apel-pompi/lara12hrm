<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import { type SidebarProps } from '@/components/ui/sidebar';
import { Link, usePage } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import {
    BadgeCent,
    Book,
    BookOpenCheck,
    Briefcase,
    CalendarCog,
    CalendarX2,
    ClipboardCheck,
    ClipboardPlus,
    Columns2,
    Cpu,
    CreditCard,
    FileCog,
    Fingerprint,
    Flag,
    Handshake,
    LayoutDashboard,
    MessageCircleOff,
    MonitorCog,
    PanelRightClose,
    RadioReceiver,
    Receipt,
    School,
    Settings,
    SquareStar,
    Upload,
    User,
    UserCog,
    Wallet,
    WalletMinimal,
} from 'lucide-vue-next';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

interface Company {
    companyname: string;
}

interface Auth {
    company: Company[];
}

const year = new Date().getFullYear();
const page = usePage<{ auth: Auth }>();
const company = page.props.auth.company?.[0];

/* ---------------- OVERVIEW ---------------- */
const overview = [
    {
        route: 'dashboard',
        title: 'Overview',
        icon: LayoutDashboard,
    },
];

/* ---------------- SYSTEM ADMIN ---------------- */
const systemAdmin = [
    {
        title: 'System Administration',
        icon: Settings,
        items: [
            { title: 'User Management', href: '/roles', icon: UserCog },
            { title: 'General Settings', href: '/settings/profile', icon: FileCog },
            { title: 'CRM Configuration', href: '/general', icon: MonitorCog },
            { title: 'HR Configuration', href: '/companyinfo', icon: CalendarCog },
            { title: 'Accounting Configuration', href: '/accountssetting', icon: BadgeCent },
        ],
    },
];

/* ---------------- HR ---------------- */
const hrMenu = [
    {
        title: 'Human Resources',
        icon: ClipboardPlus,
        items: [
            { title: 'Holiday Calendar', href: '/holidayHd', icon: CalendarX2 },
            { title: 'Employee Directory', href: '/personalinfo', icon: User },
            { title: 'Payroll Processing', href: '/attendanceStatus', icon: Flag },
        ],
    },
];

/* ---------------- Biometric ---------------- */
const biometricMenu = [
    {
        title: 'Biometric Managemets',
        icon: ClipboardPlus,
        items: [
            { title: 'Device Management', href: '/zkteco', icon: Cpu },
            { title: 'Biometric Attendance', href: '/attendance', icon: Fingerprint },
        ],
    },
];

/* ---------------- LEAVE ---------------- */
const leaveMenu = [
    {
        title: 'Leave Management',
        icon: MessageCircleOff,
        items: [{ href: '/leave', title: 'Leave Statistics', icon: MessageCircleOff }],
    },
];

/* ---------------- BUSINESS & PARTNERS ---------------- */
const businessMenu = [
    {
        title: 'Business & Partners',
        icon: Handshake,
        items: [
            { href: '/partner', title: 'Partners Statistics', icon: School },
            { href: '/partner/create', title: 'Add New Partners', icon: Handshake },
        ],
    },
];
/* ---------------- STUDENTS ---------------- */
const studentMenu = [
    {
        title: 'Student Management',
        icon: Book,
        items: [
            { href: '/student', title: 'Student Statistics', icon: Book },
            { href: '/student/create', title: 'Add New Student', icon: User },
            { href: '/imports', title: 'Import Students', icon: Upload },
        ],
    },
];
/* ---------------- INVOICE ---------------- */
const invoiceMenu = [
    {
        title: 'Billing & Invoicing',
        icon: Briefcase,
        items: [
            { route: 'invoicelist.AllInvoiceList', title: 'All Invoices', icon: Wallet },
            { route: 'invoicelist.DueInvoiceList', title: 'Outstanding Invoices', icon: WalletMinimal },
            { route: 'invoicelist.MRList', title: 'Payment Receipts', icon: Receipt },
        ],
    },
];

/* ---------------- SUPPLIER ---------------- */
const supplierMenu = [
    {
        title: 'Supplier Management',
        icon: Handshake,
        items: [
            { route: 'suppliers.index', title: 'Supplier Directory', icon: SquareStar },
            { route: 'suppliersInvoice.index', title: 'Supplier Invoices', icon: WalletMinimal },
            { route: 'suppliersPayble.index', title: 'Accounts Payable', icon: Receipt },
        ],
    },
];
/* ---------------- ACCOUNTS ---------------- */
const accountsMenu = [
    {
        title: 'Financial Transactions',
        icon: ClipboardPlus,
        items: [
            { route: 'voucherheader.opening', title: 'Opening Balance', icon: Columns2 },
            { route: 'voucherheader.jurnal', title: 'Journal Voucher', icon: CreditCard },
            { route: 'voucherheader.payment', title: 'Payment Voucher', icon: PanelRightClose },
            { route: 'voucherheader.receipt', title: 'Receipt Voucher', icon: RadioReceiver },
            { route: 'voucherheader.reverse', title: 'Reverse Entry', icon: Columns2 },
            { route: 'voucherheader.allvoucher', title: 'All Vouchers', icon: Columns2 },
        ],
    },
];
/* ---------------- REPORTS ---------------- */
const reportMenu = [
    {
        title: 'Reports & Analytics',
        icon: ClipboardCheck,
        items: [
            { title: 'Sales Reports', href: '/leadreports', icon: BookOpenCheck },
            { title: 'HR Analytics', href: '/hrreports', icon: ClipboardCheck },
            { title: 'Financial Reports', href: '/accountsreport', icon: BadgeCent },
        ],
    },
];
</script>

<template>
    <Sidebar variant="inset" v-bind="props">
        <!-- HEADER -->
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <!-- CONTENT -->
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupContent>
                    <!-- Overview -->
                    <SidebarMenu>
                        <SidebarMenuItem v-for="item in overview" :key="item.title">
                            <SidebarMenuButton as-child>
                                <Link :href="route(item.route)" class="ps-4">
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>

                    <NavMain :items="systemAdmin" />
                    <NavMain :items="hrMenu" />
                    <NavMain :items="biometricMenu" />
                    <NavMain :items="leaveMenu" />
                    <NavMain :items="businessMenu" />
                    <NavMain :items="studentMenu" />
                    <NavMain :items="invoiceMenu" />
                    <NavMain :items="supplierMenu" />
                    <NavMain :items="accountsMenu" />
                    <NavMain :items="reportMenu" />
                </SidebarGroupContent>
            </SidebarGroup>
        </SidebarContent>
        <!-- FOOTER -->
        <SidebarFooter class="border-t p-3 text-center text-xs text-gray-500"> © {{ year }} {{ company?.companyname }} </SidebarFooter>
    </Sidebar>
    <slot />
</template>
