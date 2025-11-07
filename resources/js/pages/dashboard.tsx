import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import {
    ResponsiveDialog,
    ResponsiveDialogContent,
    ResponsiveDialogDescription,
    ResponsiveDialogHeader,
    ResponsiveDialogTitle,
    ResponsiveDialogTrigger,
    ResponsiveDialogFooter,
    ResponsiveDialogClose,
} from "@/components/ui/responsive-dialog"
import {
    ResponsiveAlertDialog,
    ResponsiveAlertDialogAction,
    ResponsiveAlertDialogCancel,
    ResponsiveAlertDialogContent,
    ResponsiveAlertDialogDescription,
    ResponsiveAlertDialogFooter,
    ResponsiveAlertDialogHeader,
    ResponsiveAlertDialogTitle,
    ResponsiveAlertDialogTrigger,
} from "@/components/ui/responsive-alert-dialog"
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard() {
    const [dialogOpen, setDialogOpen] = useState(false)
    const [alertOpen, setAlertOpen] = useState(false)
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
                <div className="flex flex-col gap-4">
                    <h1 className="text-2xl font-bold">Responsive Dialog Demo</h1>
                    <p className="text-muted-foreground">
                        Resize your browser to see the dialog change between desktop and mobile views
                    </p>

                    <div className="flex gap-4">
                        <ResponsiveDialog open={dialogOpen} onOpenChange={setDialogOpen}>
                            <ResponsiveDialogTrigger asChild>
                                <Button variant="outline">Edit Profile</Button>
                            </ResponsiveDialogTrigger>
                            <ResponsiveDialogContent className="sm:max-w-[425px]">
                                <ResponsiveDialogHeader>
                                    <ResponsiveDialogTitle>Edit profile</ResponsiveDialogTitle>
                                    <ResponsiveDialogDescription>
                                        Make changes to your profile here. Click save when you&apos;re done.
                                    </ResponsiveDialogDescription>
                                </ResponsiveDialogHeader>
                                <div className="grid gap-4 py-4 px-4 sm:px-0">
                                    <div className="grid gap-2">
                                        <Label htmlFor="name">Name</Label>
                                        <Input id="name" defaultValue="Pedro Duarte" />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="username">Username</Label>
                                        <Input id="username" defaultValue="@peduarte" />
                                    </div>
                                </div>
                                <ResponsiveDialogFooter className="px-4 sm:px-0">
                                    <ResponsiveDialogClose asChild>
                                        <Button variant="outline">Cancel</Button>
                                    </ResponsiveDialogClose>
                                    <Button type="submit" onClick={() => setDialogOpen(false)}>
                                        Save changes
                                    </Button>
                                </ResponsiveDialogFooter>
                            </ResponsiveDialogContent>
                        </ResponsiveDialog>

                        <ResponsiveAlertDialog open={alertOpen} onOpenChange={setAlertOpen}>
                            <ResponsiveAlertDialogTrigger asChild>
                                <Button variant="destructive">Delete Account</Button>
                            </ResponsiveAlertDialogTrigger>
                            <ResponsiveAlertDialogContent>
                                <ResponsiveAlertDialogHeader>
                                    <ResponsiveAlertDialogTitle>Are you absolutely sure?</ResponsiveAlertDialogTitle>
                                    <ResponsiveAlertDialogDescription>
                                        This action cannot be undone. This will permanently delete your account and remove your data from our
                                        servers.
                                    </ResponsiveAlertDialogDescription>
                                </ResponsiveAlertDialogHeader>
                                <ResponsiveAlertDialogFooter>
                                    <ResponsiveAlertDialogCancel>Cancel</ResponsiveAlertDialogCancel>
                                    <ResponsiveAlertDialogAction onClick={() => setAlertOpen(false)}>Continue</ResponsiveAlertDialogAction>
                                </ResponsiveAlertDialogFooter>
                            </ResponsiveAlertDialogContent>
                        </ResponsiveAlertDialog>
                    </div>
                </div>
                <div className="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>
        </AppLayout>
    );
}
