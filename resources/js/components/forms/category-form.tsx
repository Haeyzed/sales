"use client"

import * as React from "react"
import { useForm } from "@inertiajs/react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Combobox } from "@/components/ui/combobox"
import { Spinner } from "@/components/ui/spinner"
import InputError from "@/components/input-error"

interface CategoryFormProps {
    initialData?: {
        id?: number
        name?: string
        parent_id?: number | string | null
        image?: string
    }
    categories: Array<{ id: number; name: string }>
    onSubmit: (data: Record<string, unknown>) => void
    onCancel?: () => void
    submitLabel?: string
    cancelLabel?: string
    processing?: boolean
    errors?: Record<string, string>
    showActions?: boolean
    className?: string
}

export function CategoryForm({
    initialData,
    categories,
    onSubmit,
    onCancel,
    submitLabel = "Save Category",
    cancelLabel = "Cancel",
    processing = false,
    errors = {},
    showActions = true,
    className,
}: CategoryFormProps) {
    const { data, setData } = useForm({
        name: initialData?.name || "",
        parent_id: initialData?.parent_id?.toString() || "",
        image: null as File | null,
    })

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        onSubmit(data)
    }

    return (
        <form onSubmit={handleSubmit} className={className}>
            <div className="space-y-4">
                <div className="space-y-2">
                    <Label htmlFor="name">Category Name *</Label>
                    <Input
                        id="name"
                        value={data.name}
                        onChange={(e) => setData("name", e.target.value)}
                        required
                        placeholder="Enter category name"
                    />
                    <InputError message={errors.name} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="parent_id">Parent Category</Label>
                    <Combobox
                        options={[
                            { value: "", label: "No parent" },
                            ...categories
                                .filter((cat) => cat.id !== initialData?.id)
                                .map((category) => ({
                                    value: category.id.toString(),
                                    label: category.name,
                                })),
                        ]}
                        value={data.parent_id}
                        onValueChange={(value) => setData("parent_id", value)}
                        placeholder="Select parent category"
                    />
                    <InputError message={errors.parent_id} />
                </div>

                <div className="space-y-2">
                    <Label htmlFor="image">Category Image</Label>
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

