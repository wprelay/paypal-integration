import * as React from "react"
import { Slot } from "@radix-ui/react-slot"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/src/lib/utils"

const buttonVariants = cva(
  "wrp-inline-flex wrp-items-center wrp-justify-center wrp-whitespace-nowrap wrp-rounded-md wrp-text-sm wrp-font-medium wrp-ring-offset-background wrp-transition-colors focus-visible:wrp-outline-none focus-visible:wrp-ring-2 focus-visible:wrp-ring-ring focus-visible:wrp-ring-offset-2 disabled:wrp-pointer-events-none disabled:wrp-opacity-50",
  {
    variants: {
      variant: {
        default: "wrp-bg-primary wrp-text-primary-foreground hover:wrp-bg-primary/90",
        destructive:
          "wrp-bg-destructive wrp-text-destructive-foreground hover:wrp-bg-destructive/90",
        outline:
          "wrp-border wrp-border-input wrp-bg-background hover:wrp-bg-accent hover:wrp-text-accent-foreground",
        secondary:
          "wrp-bg-secondary wrp-text-secondary-foreground hover:wrp-bg-secondary/80",
        ghost: "hover:wrp-bg-accent hover:wrp-text-accent-foreground",
        link: "wrp-text-primary wrp-underline-offset-4 hover:wrp-underline",
      },
      size: {
        default: "wrp-h-10 wrp-px-4 wrp-py-2",
        sm: "wrp-h-9 wrp-rounded-md wrp-px-3",
        lg: "wrp-h-11 wrp-rounded-md wrp-px-8",
        icon: "wrp-h-10 wrp-w-10",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
)

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  asChild?: boolean
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, asChild = false, ...props }, ref) => {
    const Comp = asChild ? Slot : "button"
    return (
      <Comp
        className={cn(buttonVariants({ variant, size, className }))}
        ref={ref}
        {...props}
      />
    )
  }
)
Button.displayName = "Button"

export { Button, buttonVariants }
