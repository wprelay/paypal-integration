import * as React from "react"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/src/lib/utils"

const badgeVariants = cva(
  "wrp-inline-flex wrp-items-center wrp-rounded-full wrp-border wrp-px-2.5 wrp-py-0.5 wrp-text-xs wrp-font-semibold wrp-transition-colors focus:wrp-outline-none focus:wrp-ring-2 focus:wrp-ring-ring focus:wrp-ring-offset-2",
  {
    variants: {
      variant: {
        default:
          "wrp-border-transparent wrp-bg-primary wrp-text-primary-foreground hover:wrp-bg-primary/80",
        secondary:
          "wrp-border-transparent wrp-bg-secondary wrp-text-secondary-foreground hover:wrp-bg-secondary/80",
        destructive:
          "wrp-border-transparent wrp-bg-destructive wrp-text-destructive-foreground hover:wrp-bg-destructive/80",
        outline: "wrp-text-foreground",
      },
    },
    defaultVariants: {
      variant: "default",
    },
  }
)

export interface BadgeProps
  extends React.HTMLAttributes<HTMLDivElement>,
    VariantProps<typeof badgeVariants> {}

function Badge({ className, variant, ...props }: BadgeProps) {
  return (
    <div className={cn(badgeVariants({ variant }), className)} {...props} />
  )
}

export { Badge, badgeVariants }
