"use client"

import * as React from "react"
import { useForm } from "@inertiajs/react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Spinner } from "@/components/ui/spinner"
import InputError from "@/components/input-error"

interface BrandFormProps {
    initialData?: {
        id?: number
        title?: string
        image?: string
        page_title?: string
        short_description?: string
    }
    onSubmit: (data: Record<string, unknown>) => void
    onCancel?: () => void
    submitLabel?: string
    cancelLabel?: string
    processing?: boolean
    errors?: Record<string, string>
    showActions?: boolean
    className?: string
}

export function BrandForm({
    initialData,
    onSubmit,
    onCancel,
    submitLabel = "Save Brand",
    cancelLabel = "Cancel",
    processing = false,
    errors = {},
    showActions = true,
    className,
}: BrandFormProps) {
    const { data, setData } = useForm({
        title: initialData?.title || "",
        image: null as File | null,
        page_title: initialData?.page_title || "",
        short_description: initialData?.short_description || "",
    })

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        onSubmit(data)
    }

    return (
        <form onSubmit={handleSubmit} className={className}>
            <div className="space-y-4">
                <div className="space-y-2">
                    <Label htmlFor="title">Brand Title *</Label>
                    <Input
                        id="title"
                        value={data.title}
                        onChange={(e) => setData("title", e.target.value)}
                        required
                        placeholder="Enter brand title"
                    />
                    <InputError message={errors.title} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="image">Brand Image</Label>
                    <Input
                        id="image"
                        type="file"
                        accept="image/*"
                        onChange={(e) => {
                            const file = e.target.files?.[0]
                            if (file) setData("image", file)
                        }}
                    />
                    <InputError message={errors.image} />
                </div>

                {showActions && (
                    <div className="flex justify-end gap-4 pt-4">
                        {onCancel && (
                            <Button type="button" variant="outline" onClick={onCancel}>
                                {cancelLabel}
                            </Button>
                        )}
                        <Button type="submit" disabled={processing}>
                            {processing && <Spinner className="mr-2" />}
                            {submitLabel}
                        </Button>
                    </div>
                )}
                {!showActions && (
                    // Hidden submit button for dialog usage
                    <Button type="submit" className="hidden" />
                )}
            </div>
        </form>
    )
}

