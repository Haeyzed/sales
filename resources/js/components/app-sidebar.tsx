import { NavFooter } from "@/components/nav-footer"
import { NavMain } from "@/components/nav-main"
import { NavUser } from "@/components/nav-user"
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from "@/components/ui/sidebar"
import { dashboard, products } from "@/routes"
import type { MainNavItem, NavItem } from "@/types"
import { Link } from "@inertiajs/react"
import {
    BookOpen,
    Folder,
    LayoutGrid,
    ShoppingCart,
    Package,
    Receipt,
    ClipboardList,
    RefreshCw,
    CornerDownLeft,
    Briefcase,
    Users,
    BarChart3,
    Factory,
    Globe,
    ShoppingBag,
    Ticket,
    Settings,
} from "lucide-react"
import AppLogo from "./app-logo"

const mainNavItems: MainNavItem[] = [
    {
        title: "Dashboard",
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: "Product",
        href: "#",
        icon: Folder,
        subItems: [
            {
                title: "Category",
                href: "#",
            },
            {
                title: "Product List",
                href: products(),
            },
            {
                title: "Add Product",
                href: "#",
            },
            {
                title: "Print Barcode",
                href: "#",
            },
            {
                title: "Adjustment List",
                href: "#",
            },
            {
                title: "Add Adjustment",
                href: "#",
            },
            {
                title: "Stock Count",
                href: "#",
            },
        ],
    },
    {
        title: "Purchase",
        href: "#",
        icon: Package,
        subItems: [
            {
                title: "Purchase List",
                href: "#",
            },
            {
                title: "Add Purchase",
                href: "#",
            },
            {
                title: "Import Purchase By CSV",
                href: "#",
            },
        ],
    },
    {
        title: "Sale",
        href: "#",
        icon: ShoppingCart,
        subItems: [
            {
                title: "Sale List",
                href: "#",
            },
            {
                title: "POS",
                href: "#",
            },
            {
                title: "Add Sale",
                href: "#",
            },
            {
                title: "Import Sale By CSV",
                href: "#",
            },
            {
                title: "Packing Slip List",
                href: "#",
            },
            {
                title: "Challan List",
                href: "#",
            },
            {
                title: "Delivery List",
                href: "#",
            },
            {
                title: "Gift Card List",
                href: "#",
            },
            {
                title: "Coupon List",
                href: "#",
            },
            {
                title: "Courier List",
                href: "#",
            },
        ],
    },
    {
        title: "Expense",
        href: "#",
        icon: Receipt,
        subItems: [
            {
                title: "Expense Category",
                href: "#",
            },
            {
                title: "Expense List",
                href: "#",
            },
            {
                title: "Add Expense",
                href: "#",
            },
        ],
    },
    {
        title: "Quotation",
        href: "#",
        icon: ClipboardList,
        subItems: [
            {
                title: "Quotation List",
                href: "#",
            },
            {
                title: "Add Quotation",
                href: "#",
            },
        ],
    },
    {
        title: "Transfer",
        href: "#",
        icon: RefreshCw,
        subItems: [
            {
                title: "Transfer List",
                href: "#",
            },
            {
                title: "Add Transfer",
                href: "#",
            },
            {
                title: "Import Transfer By CSV",
                href: "#",
            },
        ],
    },
    {
        title: "Return",
        href: "#",
        icon: CornerDownLeft,
        subItems: [
            {
                title: "Sale",
                href: "#",
            },
            {
                title: "Purchase",
                href: "#",
            },
        ],
    },
    {
        title: "Accounting",
        href: "#",
        icon: Briefcase,
        subItems: [
            {
                title: "Account List",
                href: "#",
            },
            {
                title: "Add Account",
                href: "#",
            },
            {
                title: "Money Transfer",
                href: "#",
            },
            {
                title: "Balance Sheet",
                href: "#",
            },
            {
                title: "Account Statement",
                href: "#",
            },
        ],
    },
    {
        title: "HRM",
        href: "#",
        icon: Users,
        subItems: [
            {
                title: "Department",
                href: "#",
            },
            {
                title: "Employee",
                href: "#",
            },
            {
                title: "Attendance",
                href: "#",
            },
            {
                title: "Payroll",
                href: "#",
            },
            {
                title: "Holiday",
                href: "#",
            },
        ],
    },
    {
        title: "People",
        href: "#",
        icon: Users,
        subItems: [
            {
                title: "User List",
                href: "#",
            },
            {
                title: "Add User",
                href: "#",
            },
            {
                title: "Customer List",
                href: "#",
            },
            {
                title: "Add Customer",
                href: "#",
            },
            {
                title: "Biller List",
                href: "#",
            },
            {
                title: "Add Biller",
                href: "#",
            },
            {
                title: "Supplier List",
                href: "#",
            },
            {
                title: "Add Supplier",
                href: "#",
            },
        ],
    },
    {
        title: "Reports",
        href: "#",
        icon: BarChart3,
        subItems: [
            {
                title: "Summary Report",
                href: "#",
            },
            {
                title: "Best Seller",
                href: "#",
            },
            {
                title: "Product Report",
                href: "#",
            },
            {
                title: "Daily Sale",
                href: "#",
            },
            {
                title: "Monthly Sale",
                href: "#",
            },
            {
                title: "Daily Purchase",
                href: "#",
            },
            {
                title: "Monthly Purchase",
                href: "#",
            },
            {
                title: "Sale Report",
                href: "#",
            },
            {
                title: "Challan Report",
                href: "#",
            },
            {
                title: "Sale Report Chart",
                href: "#",
            },
            {
                title: "Payment Report",
                href: "#",
            },
            {
                title: "Purchase Report",
                href: "#",
            },
            {
                title: "Customer Report",
                href: "#",
            },
            {
                title: "Customer Group Report",
                href: "#",
            },
            {
                title: "Customer Due Report",
                href: "#",
            },
            {
                title: "Supplier Report",
                href: "#",
            },
            {
                title: "Supplier Due Report",
                href: "#",
            },
            {
                title: "Warehouse Report",
                href: "#",
            },
            {
                title: "Warehouse Stock Chart",
                href: "#",
            },
            {
                title: "Product Expiry Report",
                href: "#",
            },
            {
                title: "Product Quantity Alert",
                href: "#",
            },
            {
                title: "Daily Sale Objective Report",
                href: "#",
            },
            {
                title: "User Report",
                href: "#",
            },
            {
                title: "Cash Register",
                href: "#",
            },
        ],
    },
    {
        title: "Manufacturing",
        href: "#",
        icon: Factory,
        subItems: [
            {
                title: "Production List",
                href: "#",
            },
            {
                title: "Add Production",
                href: "#",
            },
            {
                title: "Recipe",
                href: "#",
            },
        ],
    },
    {
        title: "WooCommerce",
        href: "#",
        icon: Globe,
    },
    {
        title: "ECommerce",
        href: "#",
        icon: ShoppingBag,
        subItems: [
            {
                title: "Sliders",
                href: "#",
            },
            {
                title: "Menu",
                href: "#",
            },
            {
                title: "Collections",
                href: "#",
            },
            {
                title: "Pages",
                href: "#",
            },
            {
                title: "Widgets",
                href: "#",
            },
            {
                title: "Faq Category",
                href: "#",
            },
            {
                title: "Faqs",
                href: "#",
            },
            {
                title: "Social Links",
                href: "#",
            },
            {
                title: "Blog",
                href: "#",
            },
            {
                title: "Payment Gateways",
                href: "#",
            },
            {
                title: "Settings",
                href: "#",
            },
            {
                title: "Db.Product Review",
                href: "#",
            },
        ],
    },
    {
        title: "Support Tickets",
        href: "#",
        icon: Ticket,
    },
    {
        title: "Settings",
        href: "#",
        icon: Settings,
        subItems: [
            {
                title: "Receipt Printers",
                href: "#",
            },
            {
                title: "Invoice Settings",
                href: "#",
            },
            {
                title: "Role Permission",
                href: "#",
            },
            {
                title: "SMS Template",
                href: "#",
            },
            {
                title: "Custom Field List",
                href: "#",
            },
            {
                title: "Discount Plan",
                href: "#",
            },
            {
                title: "Discount",
                href: "#",
            },
            {
                title: "All Notification",
                href: "#",
            },
            {
                title: "Send Notification",
                href: "#",
            },
            {
                title: "Warehouse",
                href: "#",
            },
            {
                title: "Tables",
                href: "#",
            },
            {
                title: "Customer Group",
                href: "#",
            },
            {
                title: "Brand",
                href: "#",
            },
            {
                title: "Unit",
                href: "#",
            },
            {
                title: "Currency",
                href: "#",
            },
            {
                title: "Tax",
                href: "#",
            },
            {
                title: "User Profile",
                href: "#",
            },
            {
                title: "Create SMS",
                href: "#",
            },
            {
                title: "Backup Database",
                href: "#",
            },
            {
                title: "General Setting",
                href: "#",
            },
            {
                title: "Mail Setting",
                href: "#",
            },
            {
                title: "Reward Point Setting",
                href: "#",
            },
            {
                title: "SMS Setting",
                href: "#",
            },
            {
                title: "Payment Gateways",
                href: "#",
            },
            {
                title: "POS Settings",
                href: "#",
            },
            {
                title: "HRM Setting",
                href: "#",
            },
            {
                title: "Barcode Settings",
                href: "#",
            },
            {
                title: "Languages",
                href: "#",
            },
        ],
    },
]

const footerNavItems: NavItem[] = [
    {
        title: "Repository",
        href: "https://github.com/laravel/react-starter-kit",
        icon: Folder,
    },
    {
        title: "Documentation",
        href: "https://laravel.com/docs/starter-kits#react",
        icon: BookOpen,
    },
]

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                {/*<NavFooter items={footerNavItems} className="mt-auto" />*/}
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    )
}
