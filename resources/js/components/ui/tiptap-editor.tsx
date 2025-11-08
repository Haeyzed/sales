"use client"

import * as React from "react"
import { useEditor, EditorContent } from "@tiptap/react"
import StarterKit from "@tiptap/starter-kit"
import { cn } from "@/lib/utils"

interface TiptapEditorProps {
    value?: string
    onChange?: (value: string) => void
    placeholder?: string
    className?: string
    disabled?: boolean
}

/**
 * TiptapEditor
 *
 * A rich text editor component using Tiptap.
 * Note: Requires @tiptap/react and @tiptap/starter-kit packages to be installed.
 * Install with: npm install @tiptap/react @tiptap/starter-kit
 */
export function TiptapEditor({
    value = "",
    onChange,
    placeholder = "Start typing...",
    className,
    disabled = false,
}: TiptapEditorProps) {
    const editor = useEditor({
        extensions: [StarterKit],
        content: value,
        editable: !disabled,
        onUpdate: ({ editor }) => {
            const html = editor.getHTML()
            onChange?.(html)
        },
        editorProps: {
            attributes: {
                class: cn(
                    "prose prose-sm sm:prose-base lg:prose-lg xl:prose-2xl mx-auto focus:outline-none min-h-[150px] p-4",
                    "prose-headings:font-semibold",
                    "prose-p:text-foreground",
                    "prose-strong:text-foreground",
                    "prose-em:text-foreground",
                    "prose-code:text-foreground",
                    "prose-pre:bg-muted",
                    "prose-blockquote:border-l-4 prose-blockquote:border-primary",
                    "prose-ul:list-disc prose-ul:pl-6",
                    "prose-ol:list-decimal prose-ol:pl-6",
                    className
                ),
            },
        },
    })

    React.useEffect(() => {
        if (editor && value !== editor.getHTML()) {
            editor.commands.setContent(value)
        }
    }, [value, editor])

    React.useEffect(() => {
        if (editor) {
            editor.setEditable(!disabled)
        }
    }, [disabled, editor])

    if (!editor) {
        return (
            <div className={cn("min-h-[150px] border rounded-md p-4", className)}>
                <p className="text-muted-foreground">{placeholder}</p>
            </div>
        )
    }

    return (
        <div className="border rounded-md overflow-hidden relative">
            <EditorContent editor={editor} />
            {!value && !editor.getText() && (
                <div className="absolute top-4 left-4 pointer-events-none text-muted-foreground">
                    {placeholder}
                </div>
            )}
        </div>
    )
}

