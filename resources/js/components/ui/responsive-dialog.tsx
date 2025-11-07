import * as React from "react"
import { useMediaQuery } from "@/hooks/use-media-query"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog"
import {
  Drawer,
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerHeader,
  DrawerTitle,
  DrawerTrigger,
} from "@/components/ui/drawer"

interface ResponsiveDialogProps {
  children: React.ReactNode
  open?: boolean
  onOpenChange?: (open: boolean) => void
}

interface ResponsiveDialogTriggerProps {
  children: React.ReactNode
  asChild?: boolean
}

interface ResponsiveDialogContentProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveDialogHeaderProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveDialogTitleProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveDialogDescriptionProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveDialogFooterProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveDialogCloseProps {
  children: React.ReactNode
  asChild?: boolean
}

const ResponsiveDialogContext = React.createContext<{
  isDesktop: boolean
}>({
  isDesktop: true,
})

export function ResponsiveDialog({ children, open, onOpenChange }: ResponsiveDialogProps) {
  const isDesktop = useMediaQuery("(min-width: 768px)")

  if (isDesktop) {
    return (
      <Dialog open={open} onOpenChange={onOpenChange}>
        <ResponsiveDialogContext.Provider value={{ isDesktop }}>{children}</ResponsiveDialogContext.Provider>
      </Dialog>
    )
  }

  return (
    <Drawer open={open} onOpenChange={onOpenChange}>
      <ResponsiveDialogContext.Provider value={{ isDesktop }}>{children}</ResponsiveDialogContext.Provider>
    </Drawer>
  )
}

export function ResponsiveDialogTrigger({ children, asChild }: ResponsiveDialogTriggerProps) {
  const { isDesktop } = React.useContext(ResponsiveDialogContext)

  if (isDesktop) {
    return <DialogTrigger asChild={asChild}>{children}</DialogTrigger>
  }

  return <DrawerTrigger asChild={asChild}>{children}</DrawerTrigger>
}

export function ResponsiveDialogContent({ children, className }: ResponsiveDialogContentProps) {
  const { isDesktop } = React.useContext(ResponsiveDialogContext)

  if (isDesktop) {
    return <DialogContent className={className}>{children}</DialogContent>
  }

  return <DrawerContent className={className}>{children}</DrawerContent>
}

export function ResponsiveDialogHeader({ children, className }: ResponsiveDialogHeaderProps) {
  const { isDesktop } = React.useContext(ResponsiveDialogContext)

  if (isDesktop) {
    return <DialogHeader className={className}>{children}</DialogHeader>
  }

  return <DrawerHeader className={className}>{children}</DrawerHeader>
}

export function ResponsiveDialogTitle({ children, className }: ResponsiveDialogTitleProps) {
  const { isDesktop } = React.useContext(ResponsiveDialogContext)

  if (isDesktop) {
    return <DialogTitle className={className}>{children}</DialogTitle>
  }

  return <DrawerTitle className={className}>{children}</DrawerTitle>
}

export function ResponsiveDialogDescription({ children, className }: ResponsiveDialogDescriptionProps) {
  const { isDesktop } = React.useContext(ResponsiveDialogContext)

  if (isDesktop) {
    return <DialogDescription className={className}>{children}</DialogDescription>
  }

  return <DrawerDescription className={className}>{children}</DrawerDescription>
}

export function ResponsiveDialogFooter({ children, className }: ResponsiveDialogFooterProps) {
  const { isDesktop } = React.useContext(ResponsiveDialogContext)

  if (isDesktop) {
    return <div className={className}>{children}</div>
  }

  return <div className={className}>{children}</div>
}

export function ResponsiveDialogClose({ children, asChild }: ResponsiveDialogCloseProps) {
  const { isDesktop } = React.useContext(ResponsiveDialogContext)

  if (isDesktop) {
    return <div onClick={(e) => e.stopPropagation()}>{children}</div>
  }

  return <DrawerClose asChild={asChild}>{children}</DrawerClose>
}
