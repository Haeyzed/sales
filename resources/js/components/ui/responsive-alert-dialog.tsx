import * as React from "react"
import { useMediaQuery } from "@/hooks/use-media-query"
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/components/ui/alert-dialog"
import {
  Drawer,
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerFooter,
  DrawerHeader,
  DrawerTitle,
  DrawerTrigger,
} from "@/components/ui/drawer"
import { Button } from "@/components/ui/button"

interface ResponsiveAlertDialogProps {
  children: React.ReactNode
  open?: boolean
  onOpenChange?: (open: boolean) => void
}

interface ResponsiveAlertDialogTriggerProps {
  children: React.ReactNode
  asChild?: boolean
}

interface ResponsiveAlertDialogContentProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveAlertDialogHeaderProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveAlertDialogTitleProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveAlertDialogDescriptionProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveAlertDialogFooterProps {
  children: React.ReactNode
  className?: string
}

interface ResponsiveAlertDialogActionProps {
  children: React.ReactNode
  onClick?: () => void
  className?: string
}

interface ResponsiveAlertDialogCancelProps {
  children: React.ReactNode
  onClick?: () => void
  className?: string
}

const ResponsiveAlertDialogContext = React.createContext<{
  isDesktop: boolean
}>({
  isDesktop: true,
})

export function ResponsiveAlertDialog({ children, open, onOpenChange }: ResponsiveAlertDialogProps) {
  const isDesktop = useMediaQuery("(min-width: 768px)")

  if (isDesktop) {
    return (
      <AlertDialog open={open} onOpenChange={onOpenChange}>
        <ResponsiveAlertDialogContext.Provider value={{ isDesktop }}>{children}</ResponsiveAlertDialogContext.Provider>
      </AlertDialog>
    )
  }

  return (
    <Drawer open={open} onOpenChange={onOpenChange}>
      <ResponsiveAlertDialogContext.Provider value={{ isDesktop }}>{children}</ResponsiveAlertDialogContext.Provider>
    </Drawer>
  )
}

export function ResponsiveAlertDialogTrigger({ children, asChild }: ResponsiveAlertDialogTriggerProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return <AlertDialogTrigger asChild={asChild}>{children}</AlertDialogTrigger>
  }

  return <DrawerTrigger asChild={asChild}>{children}</DrawerTrigger>
}

export function ResponsiveAlertDialogContent({ children, className }: ResponsiveAlertDialogContentProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return <AlertDialogContent className={className}>{children}</AlertDialogContent>
  }

  return <DrawerContent className={className}>{children}</DrawerContent>
}

export function ResponsiveAlertDialogHeader({ children, className }: ResponsiveAlertDialogHeaderProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return <AlertDialogHeader className={className}>{children}</AlertDialogHeader>
  }

  return <DrawerHeader className={className}>{children}</DrawerHeader>
}

export function ResponsiveAlertDialogTitle({ children, className }: ResponsiveAlertDialogTitleProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return <AlertDialogTitle className={className}>{children}</AlertDialogTitle>
  }

  return <DrawerTitle className={className}>{children}</DrawerTitle>
}

export function ResponsiveAlertDialogDescription({ children, className }: ResponsiveAlertDialogDescriptionProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return <AlertDialogDescription className={className}>{children}</AlertDialogDescription>
  }

  return <DrawerDescription className={className}>{children}</DrawerDescription>
}

export function ResponsiveAlertDialogFooter({ children, className }: ResponsiveAlertDialogFooterProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return <AlertDialogFooter className={className}>{children}</AlertDialogFooter>
  }

  return <DrawerFooter className={className}>{children}</DrawerFooter>
}

export function ResponsiveAlertDialogAction({ children, onClick, className }: ResponsiveAlertDialogActionProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return (
      <AlertDialogAction onClick={onClick} className={className}>
        {children}
      </AlertDialogAction>
    )
  }

  return (
    <Button onClick={onClick} className={className}>
      {children}
    </Button>
  )
}

export function ResponsiveAlertDialogCancel({ children, onClick, className }: ResponsiveAlertDialogCancelProps) {
  const { isDesktop } = React.useContext(ResponsiveAlertDialogContext)

  if (isDesktop) {
    return (
      <AlertDialogCancel onClick={onClick} className={className}>
        {children}
      </AlertDialogCancel>
    )
  }

  return (
    <DrawerClose asChild>
      <Button variant="outline" onClick={onClick} className={className}>
        {children}
      </Button>
    </DrawerClose>
  )
}
