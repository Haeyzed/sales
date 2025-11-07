"use client"

import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    useSidebar,
} from "@/components/ui/sidebar"
import type { MainNavItem } from "@/types"
import { Link, usePage } from "@inertiajs/react"
import { ChevronRight } from "lucide-react"
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from "@/components/ui/collapsible"
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from "@/components/ui/dropdown-menu"
import { useState } from "react"

export function NavMain({ items = [] }: { items: MainNavItem[] }) {
    const page = usePage()
    const { state } = useSidebar()
    const [openDropdown, setOpenDropdown] = useState<string | null>(null)

    return (
        <SidebarGroup className="px-2 py-0">
            {/*<SidebarGroupLabel>Platform</SidebarGroupLabel>*/}
            <SidebarMenu>
                {items.map((item) => {
                    const hasSubItems = item.subItems && item.subItems.length > 0

                    if (hasSubItems) {
                        if (state === "collapsed") {
                            return (
                                <SidebarMenuItem key={item.title}>
                                    <DropdownMenu
                                        open={openDropdown === item.title}
                                        onOpenChange={(open) => setOpenDropdown(open ? item.title : null)}
                                    >
                                        <DropdownMenuTrigger asChild>
                                            <SidebarMenuButton tooltip={{ children: item.title }}>
                                                {item.icon && <item.icon />}
                                                <span>{item.title}</span>
                                            </SidebarMenuButton>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent side="right" align="start" className="min-w-48 max-h-[400px] overflow-y-auto">
                                            {item.subItems?.map((subItem) => (
                                                <DropdownMenuItem key={subItem.title} asChild>
                                                    <Link href={subItem.href} prefetch className="w-full cursor-pointer">
                                                        {subItem.title}
                                                    </Link>
                                                </DropdownMenuItem>
                                            ))}
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </SidebarMenuItem>
                            )
                        }

                        return (
                            <Collapsible key={item.title} asChild defaultOpen={false} className="group/collapsible">
                                <SidebarMenuItem>
                                    <CollapsibleTrigger asChild>
                                        <SidebarMenuButton tooltip={{ children: item.title }}>
                                            {item.icon && <item.icon />}
                                            <span>{item.title}</span>
                                            <ChevronRight className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                        </SidebarMenuButton>
                                    </CollapsibleTrigger>
                                    <CollapsibleContent>
                                        <SidebarMenuSub>
                                            {item.subItems?.map((subItem) => (
                                                <SidebarMenuSubItem key={subItem.title}>
                                                    <SidebarMenuSubButton
                                                        asChild
                                                        isActive={page.url.startsWith(
                                                            typeof subItem.href === "string" ? subItem.href : subItem.href.url,
                                                        )}
                                                    >
                                                        <Link href={subItem.href} prefetch>
                                                            <span>{subItem.title}</span>
                                                        </Link>
                                                    </SidebarMenuSubButton>
                                                </SidebarMenuSubItem>
                                            ))}
                                        </SidebarMenuSub>
                                    </CollapsibleContent>
                                </SidebarMenuItem>
                            </Collapsible>
                        )
                    }

                    return (
                        <SidebarMenuItem key={item.title}>
                            <SidebarMenuButton
                                asChild
                                isActive={page.url.startsWith(typeof item.href === "string" ? item.href : item.href.url)}
                                tooltip={{ children: item.title }}
                            >
                                <Link href={item.href} prefetch>
                                    {item.icon && <item.icon />}
                                    <span>{item.title}</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    )
                })}
            </SidebarMenu>
        </SidebarGroup>
    )
}
